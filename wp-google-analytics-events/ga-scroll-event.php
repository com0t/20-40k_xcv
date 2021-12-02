<?php

/*
  Plugin Name: WP Google Analytics Events
  Plugin URI: http://wpflow.com
  Description: Adds the Google Analytics code to your website and enables you to send events on scroll or click.
  Version: 2.6.3
  Author: PineWise
  Author URI: http://wpflow.com
  License: GPLv2
 */

defined('ABSPATH') or die('Direct access not allowed!');

/* ------------------------------------------------------------------------ *
 * Setup
 * ------------------------------------------------------------------------ */

// Script debugging
define('GAE_SCRIPT_DEBUG',
false);

// Paths
define('GAE_PLUGIN_PATH',
plugin_dir_path(__FILE__));
define('GAE_PLUGIN_URL',
plugin_dir_url(__FILE__));

// Current version number
if (!defined('GA_EVENTS_VERSION'))
  define('GA_EVENTS_VERSION',
  '2.6.3');

// Dependencies

require_once(GAE_PLUGIN_PATH . '/include/Activation.php');

// Activation hook
register_activation_hook(__FILE__,
  array('GAEActivation', 'start_activation'));


/* ------------------------------------------------------------------------ *
 * Main plugin class
 * ------------------------------------------------------------------------ */
if (!class_exists('GaEvents')) {

  class GAEvents
  {

    function __construct()
    {
      if (is_admin()) {
        $this->init_hooks();
        $this->do_settings();
        $this->load_admin_scripts();
        $this->load_admin_styles();
        include( dirname( __FILE__ ) . '/include/notice.php' );


      }
      require_once(GAE_PLUGIN_PATH . '/include/Snippets.php');
      $this->include_snippet();
      $this->load_front_end_scripts();
    }

    function init_hooks()
    {
      require_once(GAE_PLUGIN_PATH . '/include/EventClasses.php');
      require_once(GAE_PLUGIN_PATH . '/include/Settings.php');

      add_action('init', 'WPGAEGClickEvent::wpgae_register_click_event_post_type');
      add_action('init', 'WPGAEGScrollEvent::wpgae_register_scroll_event_post_type');

      add_action('wp_ajax_wpflow_add_event', 'wpflow_event_ajax_handler');
      add_action('wp_ajax_wpflow_edit_event', 'wpflow_event_ajax_handler');
      add_action('wp_ajax_wpflow_delete_event', 'wpflow_event_ajax_handler');
      add_action('wp_ajax_wpflow_delete_event', 'wpflow_event_ajax_handler');
      add_action('wp_ajax_wpflow_deactivate_event', array($this, 'wpflow_plugin_deactivation_handler'));
      function wpflow_event_ajax_handler()
      {
        /**
         * The Ajax Handler for the plugin.
         * Note that all metadata updates through the save_post hook and not showing here.
         */
        // Handle the ajax request

        check_ajax_referer('wpflow_add_event', "ajax_nonce", true);

        if (isset($_POST["action"]) && $_POST["action"] == "wpflow_add_event") {
          if (!isset($_POST['wpgae_type'])) {
            wp_die("wpgae_type cannot be empty");
          }

          $post_type = sanitize_text_field($_POST['wpgae_type']);
          $postarr = array(
            'post_type' => $post_type,
            'post_status' => 'publish'
          );

          $post_id = wp_insert_post($postarr);
        } else if (isset($_POST["action"]) && $_POST["action"] == "wpflow_delete_event") {
          $post = wp_delete_post(intval($_POST["event_id"]), true);
          WPGAEGeneralEvent::update_cache_by_post_type($post->post_type);
        } else if (isset($_POST["action"]) && $_POST["action"] == "wpflow_edit_event") {
          if (isset($_POST["event_id"]) && $_POST["event_id"]) {
            $post_id = get_post(intval($_POST["event_id"]));
            wp_update_post($post_id);
          }
        }

        wp_die(); // All ajax handlers die when finished
      }

      function validateClickAndScrollForm($post_data)
      {
        /**
         * Event Tracking rules -
         * 1. Selector and Type cannot be empty - plugin restriction
         * 2. Event Category and action cannot be empty - Google Analytics restrictions
         * 3. Value can either be set to a number or not set at all
         * 4. If the selector is not of type advanced, no spaces or . or # are allowed
         */
        $form_issues = array();

        if (array_key_exists("wpgae_event_selector", $post_data) && $post_data["wpgae_event_selector"] != "") {
          if (!array_key_exists("wpgae_event_type", $post_data) || $post_data["wpgae_event_type"] != "advanced") {
            if ($post_data["wpgae_event_selector"] != sanitize_html_class($post_data["wpgae_event_selector"])) {
              array_push($form_issues, 'Cannot use non alphabetical or numerical values other than "_" and "-" when 
						Selector is set to Class or Id');
            }
          }
        } else {
          array_push($form_issues, "Selector cannot be empty");
        }
        if (sizeof($form_issues) > 0) {
          wp_send_json_error($form_issues);
          wp_die();
        }
      }

      add_action('wp_ajax_wpflow_get_event_json', 'wpflow_get_event_json_callback');

      function wpflow_get_event_json_callback()
      {

        // retrieve post_id, and sanitize it to enhance security
        $post_id = intval($_POST['post_id']);

        // Check if the input was a valid integer
        if ($post_id == 0) {

          $response['error'] = 'true';
          $response['result'] = 'Invalid Input';

        } else {

          // get the post
          $thispost = get_post($post_id);
          // check if post exists
          if (!is_object($thispost)) {

            $response['error'] = 'true';
            $response['result'] = 'There is no post with the ID ' . $post_id;

          } else {

            $post_meta = get_post_meta($thispost->ID);
            $response["meta"] = $post_meta;
            $response['error'] = 'false';
            $response['result'] = wpautop($thispost->post_content);

          }

        }

        wp_send_json($response);

      }

    }


    function do_settings()
    {
      $settings_instance = new GAESettings();

      // Handle import options button from general settings
      add_action('admin_init',
        array($settings_instance, 'upgrade_settings'));

      // Handle import options button from general settings
      add_action('admin_init',
        array($settings_instance, 'ga_events_import_settings'));

      // Handle export options button from general settings
      add_action('admin_init',
        array($settings_instance, 'ga_events_download_settings'));

      // add plugin menu
      add_action('admin_menu',
        array($settings_instance, 'ga_events_menu'));

      // Initialise plugin options
      add_action('admin_init',
        array($settings_instance, 'ga_events_initialize_plugin_options'));

      add_filter("admin_footer", array($settings_instance, 'ga_events_feedback_form_html'));
      
      // Display settings link next to plugin listing on plugins page
      $plugin = plugin_basename(__FILE__);
      add_filter("plugin_action_links_$plugin",
        array($settings_instance, 'plugin_add_settings_link'));

      // Add Ajax functionality for the admin UI
      add_action('wp_ajax_wpflow_update', 'wpflow_ajax_update_handler');

      function wpflow_ajax_update_handler()
      {
        // Handle the ajax request
        check_ajax_referer('wpflow-nounce');
        wp_die(); // All ajax handlers die when finished
      }
    }

    function  load_admin_scripts()
    {

      $ga_options = get_option('ga_events_options');
      $debug_mode = isset($ga_options['script_debug_mode']) ? $ga_options['script_debug_mode'] : '0';


      // This condition need to be checked in localize_placeholder function too!
      if (GAE_SCRIPT_DEBUG || $debug_mode) {
        add_action('admin_enqueue_scripts',
          array($this, 'enqueue_admin_scripts_debug'));
      } else {
        add_action('admin_enqueue_scripts',
          array($this, 'enqueue_admin_scripts'));
      }
    }

    function load_front_end_scripts()
    {

      $ga_options = get_option('ga_events_options');
      $debug_mode = isset($ga_options['script_debug_mode']) ? $ga_options['script_debug_mode'] : '0';

      if (GAE_SCRIPT_DEBUG || $debug_mode == '1') {
        add_action('wp_enqueue_scripts',
          array($this, 'enqueue_front_end_scripts_debug'));
      } else {
        add_action('wp_enqueue_scripts',
          array($this, 'enqueue_front_end_scripts'));
      }
    }

    function load_admin_styles()
    {

      add_action('admin_enqueue_scripts',
        array($this, 'enqueue_admin_styles'));
    }

    /* --------------------------------------------------------------------- *
     * Custom Post Types
     * --------------------------------------------------------------------- */

    /* --------------------------------------------------------------------- *
     * Register and enqueue admin scripts
     * --------------------------------------------------------------------- */

    function enqueue_admin_scripts($hook)
    {
      $screen =  get_current_screen();

      if (strpos($hook, "wp-google-analytics-events") == false  && $screen->id !== "plugins") {
        return;
      }

      wp_register_script('admin_bundle',
        GAE_PLUGIN_URL . 'js/dist/admin-scripts-min.js',
        array('jquery', 'jquery-ui-tooltip'),
        true);

      // Localize plugin options in database for use in main JS script
      wp_localize_script('admin_bundle',
        'ga_options',
        get_option('ga_events_options'));


      wp_enqueue_script('admin_bundle');

      $wpflow_nonce = wp_create_nonce('wpflow-nounce');
      wp_localize_script('admin_bundle', 'wpflow_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => $wpflow_nonce, // It is common practice to comma after
      ));

    }

    /* --------------------------------------------------------------------- *
     * Register and enqueue admin scripts - DEBUGGING!
     * --------------------------------------------------------------------- */

    function enqueue_admin_scripts_debug($hook)
    {
      $screen =  get_current_screen();

      if (strpos($hook, "wp-google-analytics-events" ) == false && $screen->id != "plugins") {
        return;
      }
      // Localize plugin options in database for use in main JS script
      wp_localize_script('gae_mapper',
        'ga_options',
        get_option('ga_events_options'));

      // Needed for the admin UI
      wp_enqueue_script('jquery_modal',
        GAE_PLUGIN_URL . 'js/third-party/jquery.modal.min.js',
        array('jquery'),
        false);

//      wp_enqueue_script('jquery_form_validator',
//        GAE_PLUGIN_URL . 'js/third-party/jquery.form-validator.min.js',
//        array('jquery'),
//        false);

      // Admin JS
      wp_enqueue_script('admin_js',
        GAE_PLUGIN_URL . 'js/admin.js',
        array('jquery', 'jquery-ui-tooltip'),
        '1.0',
        true);

      // Ajax for settings
      wp_enqueue_script('ajax_settings_js',
        GAE_PLUGIN_URL . 'js/ajax-settings.js',
        array('jquery'),
        '1.0',
        false);

      $wpflow_nonce = wp_create_nonce('wpflow-nounce');

      wp_localize_script('ajax_settings_js', 'wpflow_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => $wpflow_nonce, // It is common practice to comma after
      ));

    }

    function enqueue_front_end_scripts()
    {

      // Get plugin options from the database. Needed for conditional enqueueing
      $ga_options = get_option('ga_events_options');

      /* --------------------------------------------------------------- *
       * Register frontend scripts
       * ---------------------------------------------------------------- */

      wp_register_script('ga_events_frontend_bundle',
        GAE_PLUGIN_URL . 'js/dist/frontend-scripts-min.js',
        array('jquery'),
        '1.0',
        true);

      // Localize plugin options in database for use in main JS script
      wp_localize_script('ga_events_frontend_bundle',
        'ga_options',
        prepare_ga_options_for_frontend());


      $this->localize_placeholders();
      /* ------------------------------------------------------------- *
       * Enqueue frontend scripts
       * -------------------------------------------------------------- */

      wp_enqueue_script('ga_events_frontend_bundle');
    }

    function enqueue_front_end_scripts_debug()
    {

      // Get plugin options from the database. Needed for conditional enqueueing
      $ga_options = get_option('ga_events_options');

      /* --------------------------------------------------------------------- *
       * Register frontend scripts
       * --------------------------------------------------------------------- */

      wp_register_script('ga_events_main_script',
        GAE_PLUGIN_URL . 'js/main.js',
        array('jquery'),
        '1.0',
        false);

      // Localize plugin options in database for use in main JS script
      wp_localize_script('ga_events_main_script',
        'ga_options',
        prepare_ga_options_for_frontend());

      $this->localize_placeholders();

      /* --------------------------------------------------------------------- *
       * Enqueue frontend scripts
       * --------------------------------------------------------------------- */

      // Tracking is disabled for selected roles so we load tracking
      wp_enqueue_script('ga_events_main_script');
    }

    function enqueue_admin_styles($hook)
    {
      if (strpos($hook, "wp-google-analytics-events") == false) {
        return;
      }
      wp_enqueue_style('wpgae_font_awesome',
        '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css');

      wp_enqueue_style('wpgae_admin_styles',
        GAE_PLUGIN_URL . 'css/style.css',
        array('wpgae_font_awesome'));

      wp_enqueue_style('wpgae_jquery_modal_styles',
        GAE_PLUGIN_URL . 'css/third-party/jquery.modal.min.css');
    }

    function include_snippet()
    {
      add_action('wp_head',
        array('GAESnippets', 'add_snippet_to_header'),
        0);
    }

    function localize_placeholders() {

      global $wp_query;

      $page_title	 = ! empty( $wp_query->post->post_title ) ? $wp_query->post->post_title : '';

      $translation_array	 = array(
        // home page is home page not page title
        'is_front_page'		 => is_front_page(),
        'page_title'		 => $page_title,
      );

      $ga_options	 = get_option( 'ga_events_options' );
      $debug_mode	 = isset($ga_options[ 'script_debug_mode' ]) ? $ga_options[ 'script_debug_mode' ] : '0';

      // These need to be conditional on GAE_SCRIPT_DEBUG
      if ( GAE_SCRIPT_DEBUG || $debug_mode ) {
        // gae_mapper is bundled with admin and frontend scripts
        wp_localize_script( 'ga_events_main_script',
          'gaePlaceholders',
          $translation_array );
      } else {
        wp_localize_script( 'admin_bundle',
          'gaePlaceholders',
          $translation_array );

        wp_localize_script( 'ga_events_frontend_bundle',
          'gaePlaceholders',
          $translation_array );
      }
    }

    function isAdvancedModeOn()
    {
      $wpgae_options = get_option('ga_events_options');
      if (isset($wpgae_options['advanced']) && $wpgae_options["advanced"] == 1) {
        return true;
      }
      return false;

    }

    function wpflow_plugin_deactivation_handler() {
      print (var_dump($_POST));
      $current_plugin_version = GA_EVENTS_VERSION;
      $current_user = wp_get_current_user();
      $user_diaplay_name = $current_user->display_name;
      $user_email = isset($current_user->user_email) && $current_user->user_email != "" ? $current_user->user_email : "" ;
      $from = isset($user_diaplay_name) ? $user_diaplay_name." <".$user_email.">" : $user_email;

      $headers[] = "Content-Type: text/html; charset=UTF-8";
      $headers[] = "From: ".$from;
      $headers[] = "Reply-To: ".$from;

      $feedback = isset($_POST["feedback"]) ? sanitize_text_field($_POST["feedback"]) : "";
      $helptext = isset($_POST["helpme-text"]) ? sanitize_text_field($_POST["helpme-text"]): "";
      $site_info = home_url();

      // Include version information
      // Include wordpress version information

      $text = "Feedback From for version: ".$current_plugin_version."<br>";
      $text .= "Reason for deactivation: ".$feedback."<br>";
      $text .= "Help me with: ".$helptext."<br>";
      $text .= "website: ".$site_info."<br>";
      $subject = $current_plugin_version." - Deactivation Feedback";
      wp_mail( 'feedback@wpflow.com', $subject, $text, $headers );
      error_log($text);
      
      wp_die(); // All ajax handlers die when finished

    }
  }

  /*
 * Remove sensitive data from the frontend
 */
  function prepare_ga_options_for_frontend()
  {
    $sensitive_options = array("script_debug_mode");
    $ga_options = get_option('ga_events_options');
    foreach ($sensitive_options as $key_to_remove) {
      if (array_key_exists($key_to_remove, $ga_options)) {
        unset($ga_options[$key_to_remove]);
      }
    }
    if (! array_key_exists("force_snippet", $ga_options)) {
        $ga_options['force_snippet'] = "none";
    }
    return $ga_options;
  }

}




$wpgae = new GaEvents();

