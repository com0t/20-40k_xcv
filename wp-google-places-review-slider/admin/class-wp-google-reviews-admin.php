<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Google_Reviews
 * @subpackage WP_Google_Reviews/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Google_Reviews
 * @subpackage WP_Google_Reviews/admin
 * @author     Your Name <email@example.com>
 */
class WP_Google_Reviews_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugintoken    The ID of this plugin.
	 */
	private $plugintoken;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugintoken       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugintoken, $version ) {

		$this->_token = $plugintoken;
		$this->_default_api_token = "";
		//$this->version = $version;
		//for testing==============
		$this->version = time();
		//===================
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Google_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Google_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		//only load for this plugin
		if(isset($_GET['page'])){
			if( strpos( $_GET['page'], "wp_google-" ) !== false || $_GET['page']=="wp_fb-welcome"){
				//wp_enqueue_style( $this->_token."_wprev_fonta", plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), '4.7.0', 'all' );
				
				wp_register_style( 'Font_Awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
			wp_enqueue_style('Font_Awesome');
				
				wp_enqueue_style( $this->_token."_wprev_w3", plugin_dir_url( __FILE__ ) . 'css/wprev_w3.css', array(), $this->version, 'all' );
				
				wp_enqueue_style( $this->_token, plugin_dir_url( __FILE__ ) . 'css/wprev_admin.css', array(), $this->version, 'all' );
			}
			
			//load template styles for wp_pro-templates_posts page
			if($_GET['page']=="wp_google-templates_posts" || $_GET['page']=="wp_google-get_pro"){
				//enque template styles for preview
				wp_enqueue_style( $this->_token."_style1", plugin_dir_url(dirname(__FILE__)) . 'public/css/wprev-public_template1.css', array(), $this->version, 'all' );

			}
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Google_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Google_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		

		//scripts for all pages in this plugin
		if(isset($_GET['page'])){
			if( strpos( $_GET['page'], "wp_google-" ) !== false ){
				//pop-up script
				wp_register_script( 'simple-popup-js',  plugin_dir_url( __FILE__ ) . 'js/wprev_simple-popup.min.js' , '', $this->version, false );
				wp_enqueue_script( 'simple-popup-js' );
			}
		}

		//scripts for get google reviews page
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_google-googlesettings" || $_GET['page']=="wp_google-googleplacesapi"){
				$options = get_option('wpfbr_google_options');
				$default_key = "";
				
				
				if(empty( $options['google_api_key'] )){
					$google_api_key = $default_key;
				} 
				if ($options['select_google_api']=="mine"){
					$google_api_key =$options['google_api_key'];
				} else {
					$google_api_key = $default_key;
				}
				
				
				if( ! empty($google_api_key ) )
				{
				wp_register_script( 'wpfbr_google_places_gmaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . esc_attr( $google_api_key ), array( 'jquery' ) );
				wp_enqueue_script( 'wpfbr_google_places_gmaps' );
				}

				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprev_getgoogle.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars', 
					array(
						'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'i18n'     => array( 'google_auth_error' => 
						sprintf( 
						__( '%1$sGoogle API Error:%2$s Wrong Key or Maps API not added. Due to recent changes by Google you must now add the Maps API to your existing API key in order to use the Location Lookup feature of the Google Places Widget. %3$sView documentation here%4$s', 'wp_fb-settings' ), 
						'<strong>', 
						'</strong>', 
						'<br><a href="https://ljapps.com/google-places-api-key/" target="_blank" class="new-window">', 
						'</a>' 
						) ) 
					)
				);
				
			}
		}
		
		//scripts for get fb reviews page
		/*
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_fb-settings"){
				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprev_admin.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring')
					)
				);
			}
		}
		*/
		
		//scripts for review list page
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_google-reviews"){
				//admin js
				wp_enqueue_script('review_list_page-js', plugin_dir_url( __FILE__ ) . 'js/review_list_page.js', array( 'jquery' ), $this->version, false );
				
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
		 
				wp_enqueue_script('media-upload');
				wp_enqueue_script('wptuts-upload');
			}
			
			//script for crawl page
			if($_GET['page']=="wp_google-googlecrawl"){
				wp_enqueue_script( $this->_token.'getgoogle_crawl-js', plugin_dir_url( __FILE__ ) . 'js/wprev_getgoogle_crawl.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token.'getgoogle_crawl-js', 'adminjs_script_vars', 
					array(
						'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
						'ajax_url' => admin_url( 'admin-ajax.php' )
					)
				);

			}
			
			//scripts for templates posts page
			if($_GET['page']=="wp_google-templates_posts"){
				//admin js
				wp_enqueue_script('templates_posts_page-js', plugin_dir_url( __FILE__ ) . 'js/templates_posts_page.js', array( 'jquery' ), $this->version, false );
				wp_localize_script('templates_posts_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => wpgooglerev_plugin_url
					)
				);
				//add color picker here
				wp_enqueue_style( 'wp-color-picker' );
				//enque alpha color add-on wprevpro-wp-color-picker-alpha.js
				wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ) . 'js/wprevpro-wp-color-picker-alpha.js', array( 'wp-color-picker' ), '2.1.2', false );
				
			}
		}
		
	}
	
	public function add_menu_pages() {

		/**
		 * adds the menu pages to wordpress
		 */

		$page_title = 'WP Google Reviews: Welcome';
		$menu_title = 'WP Google Reviews';
		$capability = 'manage_options';
		$menu_slug = 'wp_google-welcome';
		
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this,'wp_fb_welcome'),'dashicons-star-half');
		
		// We add this submenu page with the same slug as the parent to ensure we don't get duplicates
		$sub_menu_title = 'Welcome';
		add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, array($this,'wp_fb_welcome'));		
		
		// Now add the submenu page
		$submenu_page_title = 'WP Google Reviews: Get Google Reviews';
		$submenu_title = 'Get Google Reviews';
		$submenu_slug = 'wp_google-googlesettings';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_googlesettings'));
		
		// Now add the submenu page
		$submenu_page_title = 'WP Google Reviews: Google Places API';
		$submenu_title = 'Google API';
		$submenu_slug = 'wp_google-googleplacesapi';
		add_submenu_page(null, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_googleplacesapi'));
		
		// Now add the submenu page
		$submenu_page_title = 'WP Google Reviews: Crawl Google';
		$submenu_title = 'Google Crawl';
		$submenu_slug = 'wp_google-googlecrawl';
		add_submenu_page(null, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_googlecrawl'));
		
		
		// Now add the submenu page for the actual reviews list
		$submenu_page_title = 'WP Google Reviews: Reviews List';
		$submenu_title = 'Reviews List';
		$submenu_slug = 'wp_google-reviews';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_reviews'));
		
		// Now add the submenu page for the reviews templates
		$submenu_page_title = 'WP Google Reviews: Templates';
		$submenu_title = 'Templates';
		$submenu_slug = 'wp_google-templates_posts';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_templates_posts'));
		
		// Now add the submenu page for the reviews templates
		//$submenu_page_title = 'WP Google Reviews: Upgrade';
		//$submenu_title = 'Get Pro';
		//$submenu_slug = 'wp_google-get_pro';
		//add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_getpro'));
	}
	
	public function wp_fb_settings() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/settings.php';
	}

	public function wp_fb_reviews() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/review_list.php';
	}
	public function wp_fb_googlesettings() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/googlechoose.php';
	}
	public function wp_fb_googleplacesapi() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/googleapi.php';
	}
	public function wp_fb_googlecrawl() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/googlecrawl.php';
	}
	public function wp_fb_welcome() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/welcome.php';
	}

	public function wp_fb_templates_posts() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/templates_posts.php';
	}
	public function wp_fb_getpro() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/get_pro.php';
	}
	
	
	public function wprev_google_add_external_link_admin_submenu() {
		global $submenu;

		$menu_slug = 'wp_google-welcome'; // used as "key" in menus
		$menu_pos = 1; // whatever position you want your menu to appear

		// add the external links to the slug you used when adding the top level menu
		$submenu[$menu_slug][] = array('<div id="wprev-66022">Go Pro!</div>', 'manage_options', 'https://ljapps.com/wp-review-slider-pro/');
	}
	public function wpse_66022_add_jquery() 
	{
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($) {   
				$('#wprev-66022').parent().attr('target','_blank');  
			});
		</script>
		<?php
	}
	
	/**
	 * custom option and settings on settings page
	 */
	public function wpfbr_settings_init()
	{

		//--======================= GOOGLE =======================--//

		// register a new setting for "wp_fb-google_settings" page
		register_setting('wp_fb-google_settings', 'wpfbr_google_options', array( &$this, 'wpfbr_schedule_cron' ) );

		// register a new section in the "wp_fb-google_settings" page
		add_settings_section(
			'wpfbr_section_developers_google',
			'',
			array($this,'wpfbr_section_developers_google_cb'),
			'wp_fb-google_settings'
		);
	 
		//register Google API key input field
		add_settings_field(
			'google_api_key', 
			'Google API Key',
			array($this,'wpfbr_field_google_api_key_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_api_key',
				'class'             => 'wpfbr_row'
			)
		);
		//register location type
		/*
		add_settings_field(
			'google_location_type', 
			'Location Type',
			array($this,'wpfbr_location_type_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_location_type',
				'class'             => 'wpfbr_row wpfbr_hide2'
			)
		);
		*/
		//register location search field
		add_settings_field(
			'google_location_txt', 
			'Location Search',
			array($this,'wpfbr_location_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_location_txt',
				'class'             => 'wpfbr_row wpfbr_hide2'
			)
		);
		//register location field after autocomplete
		
		add_settings_field(
			'google_location_set', 
			'Name & Place ID',
			array($this,'wpfbr_location_set_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_location_set', 
				'class'             => 'wpfbr_row wpfbr_hide2',
				'tarray'			=> array( 'google_location_set' => array( 'location'=>'', 'place_id'=>'' ) ),
			)
		);
		
		//fetch google reviews with a minimum of X rating
		add_settings_field(
			'google_location_minrating', 
			'Minimum Rating',
			array($this,'wpfbr_location_minrating_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_location_minrating',
				'class'             => 'wpfbr_row wpfbr_hide2'
			)
		);
		//run cron everyday to get 5 more reviews; google places API only gives 5 reviews;
		add_settings_field(
			'google_review_cron',
			'Auto Fetch Reviews', 
			array($this,'wpfbr_location_review_cron_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_review_cron',
				'class'             => 'wpfbr_row wpfbr_hide2'
			)
		);
		
		//register language field
		add_settings_field(
			'google_language_option', // as of WP 4.6 this value is used only internally
			'Language Code',
			array($this,'wpfbr_location_language'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			[
				'label_for'         => 'google_language_option',
				'class'             => 'wpfbr_hide2'
			]
		);
		//--======================= /GOOGLE =======================--//
	}
	/**
	 * custom option and settings:
	 * callback functions
	 */
	 
	//==== developers section cb ====
	// section callbacks can accept an $args parameter, which is an array.
	// $args have the following keys defined: title, id, callback.
	// the values are defined at the add_settings_section() function.
	public function wpfbr_section_developers_cb($args)
	{
		//echos out at top of section
	}


	//--======================= GOOGLE =======================--//
	//callback function for register_settings::line 295; 
	public function wpfbr_schedule_cron( $input )
	{
		if(isset($input['google_review_cron'])){
			if( $input['google_review_cron'] == 1 )
			{
				if( ! wp_next_scheduled( 'wpfbr_cron_google_review' )) 
				{
					wp_schedule_event(time(), 'daily', 'wpfbr_cron_google_review');
				}
			}
			else
				wp_clear_scheduled_hook( 'wpfbr_cron_google_review' );
		}
		return $input;
	}
	public function wpfbr_cron_googlereviews()
	{
		$options = get_option('wpfbr_google_options');
		$ret = $this->get_google_reviews( $options );
		return $ret;
	}

	// the values are defined at the add_settings_section() function.
	public function wpfbr_section_developers_google_cb($args)
	{
		//echos out at top of section
	}
	
	//==== field cb =====
	// field callbacks can accept an $args parameter, which is an array.
	// $args is defined at the add_settings_field() function.
	// wordpress has magic interaction with the following keys: label_for, class.
	// the "label_for" key value is used for the "for" attribute of the <label>.
	// the "class" key value is used for the "class" attribute of the <tr> containing the field.
	// you can add custom key value pairs to be used inside your callbacks.
	public function wpfbr_field_google_api_key_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wpfbr_google_options');
		//print_r($options);
		if(!isset($options['select_google_api'])){
			$options['select_google_api']="mine";
		}
		if(!isset($options['google_api_key'])){
			$options['google_api_key']="";
		}
		

		// output the field
		?>
		<select class="select_google_api" style="display:none;" id="select_google_api" name="wpfbr_google_options[select_google_api]">
			<option value="mine" <?php if(esc_attr($options['select_google_api'])=='mine' || esc_attr($options['select_google_api'])==''){echo 'selected="selected"';} ?>>Use My API Key</option>
			<option value="default" <?php if(esc_attr($options['select_google_api'])=='default'){echo 'selected="selected"';} ?>>Use Default API Key</option>
			
		</select> 
		<p class="usedefaultkey description" style="display:none;"><?php _e('You can either use the default community API Key or create your own. It is more reliable to create and use your own key since the default key may exceed the daily call limit.', 'wp-google-reviews'); ?></p>
		<div class="showapikey">
			<input class="regular-text showapikey" id="<?php echo esc_attr($args['label_for']); ?>" type="text" name="wpfbr_google_options[<?php echo esc_attr($args['label_for']); ?>]" placeholder="<?php _e('Enter Your Google API Key', 'wp-google-reviews'); ?>" value="<?php echo esc_attr( $options[$args['label_for']] ); ?>"> <button id="wpfbr_testgooglekey" type="button" class="button">Test API Key</button>
			<p class="showapikey description"><?php _e('Once you have your Google API Key paste it in the box and click the "Save Settings" button.', 'wp-google-reviews'); ?> </p>
			<div class="showapikey" id="createbtns">
				<a href="http://ljapps.com/google-places-api-key/" target="_blank" id="fb_create_google_app_help" type="button" class=""><?php _e('How To Get API Key', 'wp-google-reviews'); ?></a>
			</div>
			<p class="showapikey description"><?php _e('Note: Google now requires an active billing account associated with your API Key.', 'wp-google-reviews'); ?> </p>
			
		</div>
		<?php
	}
	public function wpfbr_location_cb($args)
	{
		$options = get_option('wpfbr_google_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]="";
		}
		?>
		<input class="regular-text" id="<?php echo esc_attr($args['label_for']); ?>" type="text" name="wpfbr_google_options[<?php echo esc_attr($args['label_for']); ?>]" 
			placeholder="Enter a location" autocomplete="off" value="<?php echo esc_attr( $options[$args['label_for']] ); ?>">
			<span id="autocomplete_error"></span>
		<p class="description">
			<?php echo  esc_html__('Start typing to search for your Location. If your API Key is setup correctly, then you should get a drop down list of places to click when you start typing.', 'wp_fb-settings'); ?> <?php echo  esc_html__('If you can\'t find your location with the search box then manually input the values below. Look them up and copy them from this ', 'wp_fb-settings'); ?>
			<a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder"  target="_blank"><?php echo  esc_html__('page', 'wp_fb-settings'); ?></a>.
		</p>
		<div id="wpfbr_result"></div>
		<?php
	}
	public function wpfbr_location_type_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wpfbr_google_options');

		$location_type = array( "all" => __('All','wp_fb-settings'), "address"=>__('Address','wp_fb-settings'), "establishment" => __('Establishment','wp_fb-settings'), "(regions)" => __('Regions','wp_fb-settings') );
		// output the field
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wpfbr_google_options[<?php echo  esc_attr($args['label_for']); ?>]">
			<?php foreach( $location_type as $location_i => $location_v ) { ?>
			<option value="<?php echo esc_attr( $location_i ); ?>" <?php selected( $options[$args['label_for']], $location_i ); ?>><?php echo esc_attr( $location_v ); ?></option>
			<?php } ?>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Enter Location Type.', 'wp_fb-settings'); ?>
		</p>
		<?php
	}

	public function wpfbr_location_set_cb( $args )
	{
		
		$options = get_option('wpfbr_google_options');

		//foreach( $args['label_for'] as $label=>$val ){
		//	foreach( $val as $labeli=>$valv ){
				
		$x=0;
		foreach( $args['tarray'] as $label=>$val ){
			foreach( $val as $labeli=>$valv ){
			if(!isset($options[$label][$labeli])){
				$options[$label][$labeli]="";
			}	
			$tempplaceholder = __('Enter Location Name', 'wp_fb-settings');
			if($x>0){
				$tempplaceholder = __('Enter Place ID', 'wp_fb-settings');
			}
		?>
		<label>
		<span class="locationlabel"><?php echo ucfirst( $labeli ); ?>:</span> <input class="regular-text" id="wpfbr_<?php echo esc_attr($labeli); ?>" type="text"  
				name="wpfbr_google_options[<?php echo esc_attr($label); ?>][<?php echo esc_attr($labeli); ?>]" 
				placeholder="<?php echo $tempplaceholder;?>" value="<?php echo esc_attr($options[$label][$labeli]); ?>"> </label><br/>
		<?php
		$x++;
		}
		
		}
		?>
		<?php
		
	}
	public function wpfbr_location_minrating_cb($args)
	{
		$options = get_option('wpfbr_google_options');
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wpfbr_google_options[<?php echo  esc_attr($args['label_for']); ?>]">
			<?php foreach( range( 1, 5 ) as $minr ) { ?>
			<option value="<?php echo esc_attr( $minr ); ?>" <?php selected( $options[$args['label_for']], $minr ); ?>><?php echo esc_attr( $minr ); ?></option>
			<?php } ?>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Only import reviews with a minimum rating of X.', 'wp_fb-settings'); ?>
		</p>
		<?php
	}

	public function wpfbr_location_review_cron_cb($args)
	{
		$options = get_option('wpfbr_google_options');
		$temparg = esc_attr($args['label_for']);
		if(!isset($options[$temparg])){
			$options[$temparg]='';
		}
		?>
		<input type="checkbox" id="<?php echo  esc_attr($args['label_for']); ?>" name="wpfbr_google_options[<?php echo esc_attr($args['label_for']); ?>]" value="1" <?php checked( $options[$args['label_for']], "1" ); ?>/>
		<p class="description">
		<?php echo  esc_html__('Run a wp-cron to import your 5 most helpful reviews everyday. Google limits the amount of reviews you can download using their Google Places API to the 5 most helpful, this option allows you to get new reviews as they are added. The Pro version allows you to download all your reviews', 'wp_fb-settings'); ?>
		</p>
		<?php
	}
	
	public function wpfbr_location_language($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wpfbr_google_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		//print_r($options);
		// output the field
		?>
		<input class="regular-text" id="<?php echo esc_attr($args['label_for']); ?>" type="text" name="wpfbr_google_options[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo esc_attr( $options[$args['label_for']] ); ?>">
				<p class="description">
			<?php echo  esc_html__('Optional: The language code, indicating in which language the results should be returned, if possible. Click ', 'wp_pro-settings'); ?><a href="https://developers.google.com/maps/faq#languagesupport" target='_blank'><?php echo  esc_html__('here', 'wp_pro-settings'); ?></a>
			<?php echo  esc_html__(' for the language codes.', 'wp_pro-settings'); ?>
		</p>
		<?php
	}

	public function wpfbr_ajax_google_reviews()
	{
		global $wpdb, $current_user;
		get_currentuserinfo();

		if(!is_user_logged_in())  
		{
			$out = __('User not logged in','wpcvt_lang');
			//header( "Content-Type: application/json" );
			echo $out;
			die();
		}
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		if(!defined('DOING_AJAX')) define('DOING_AJAX', 1);
		
		if (strpos(@ini_get('disable_functions'), 'set_time_limit') === false) {
			@set_time_limit(3600);
		}

		$options = get_option('wpfbr_google_options');

		//if( empty( $options['google_api_key'] ) && empty( $options['google_api_key_default'] )){
		//	_e('Google Places API Key not found.');
		//	die();
		//}

		if( empty( $options['google_location_set']['place_id'] )){
			_e('There is no location set. Please search and select location to get reviews.');
			die();
		}

		echo $this->get_google_reviews( $options );
		die();
	}

	public function get_google_reviews( $options = array() )
	{
		global $wpdb;

		if( empty( $options ) )
			$options = get_option('wpfbr_google_options');

		
		if( empty( $options['google_api_key'] ) ){
			$google_api_key = $this->_default_api_token;
		} else {
			$google_api_key = $options['google_api_key'];
		}
		if(isset($options['select_google_api'])){
			if($options['select_google_api']=='default'){
				$google_api_key = $this->_default_api_token;
			} else if ($options['select_google_api']=='mine'){
				$google_api_key = $options['google_api_key'];
			}
		}
		
		$google_places_url = add_query_arg(
			array(
				'placeid' => trim($options['google_location_set']['place_id']),
				'key'     => trim($google_api_key),
				'language' => trim($options['google_language_option']),
			),
			'https://maps.googleapis.com/maps/api/place/details/json'
		);
		$response = $this->get_reviews( $google_places_url );

		//Error message from google;
		//if ( ! empty( $response->error_message ) ) 
		//{
		//	return '<strong>'.$response->status.'</strong>: '.$response->error_message;
		//} 
		//Error message from google;
		

		if ( ! empty( $response['error_message'] ) ) 
		{
			//print_r($response);
			//return '<strong>'.$response->status.'</strong>: '.$response->error_message;
			$error= sprintf( __('Google Error: %s, %s, <a href="%s" target="_blank">more info</a> </br>', 'wp_fb-settings' ), $response['status'], $response['error_message'], $google_places_url );
			return $error;
		} 
				
		//Error essage from wordpress//
		elseif ( isset( $response['error_message'] ) && ! empty( $response['error_message'] ) ) 
		{
			return '<strong>' . $response['status'] . '</strong>: ' . $response['error_message'];
		}
		//-----------------------------------------------

		$stats = array();
		$table_name = $wpdb->prefix . 'wpfb_reviews';

		$numreturned = count($response['result']['reviews']);
		
		//foreach element in $arr
		foreach( $response['result']['reviews'] as $item) 
		{ 
			//only enter reviews with ratings more than X;
			if( ! empty( $options['google_location_minrating'] )&& ! empty($item['rating'])&& (int)$options['google_location_minrating'] > (int)$item['rating'])
				continue;

			//get reviewer id from author url so we can display on front end
			$intreviewer_id = filter_var($item['author_url'], FILTER_SANITIZE_NUMBER_INT);
			
			if (extension_loaded('mbstring')) {
				$reviewlength = mb_substr_count($item['text'], ' ');
			} else {
				$reviewlength = substr_count($item['text'], ' ');
			}
			//fix for one word reviews
			if($reviewlength==0 && strlen($item['text'])>0){
				$reviewlength=1;
			}
						
			//check to see if row is in db already
			$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$item['author_name']."' AND  review_length = '".$reviewlength."'" );
			
			$slashedusername = addslashes($item['author_name']);
			
			$checkrow2 = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$slashedusername."' AND  review_length = '".$reviewlength."'" );
						
			if( empty( $checkrow ) && empty( $checkrow2 ) )
			{
				$stats[] =array( 
					'pageid' 			=> $response['result']['place_id'], 
					'pagename' 			=> $response['result']['name'], 
					'created_time' 		=> date( "Y-m-d H:i:s", $item['time'] ),
					'created_time_stamp' 	=> $item['time'],
					'reviewer_name' 		=> $item['author_name'],
					'reviewer_id' 		=> $intreviewer_id,
					'rating' 			=> $item['rating'],
					'review_text' 		=> $item['text'],
					'hide' 			=> '',
					'review_length' 		=> $reviewlength,
					'type' 			=> 'Google',
					'userpic'			=> $item['profile_photo_url'],
					'from_url' =>$response['result']['url']
				);
			}
		}
		$i = 0;
		$insertnum = 0;
		foreach ( $stats as $stat ){
			$insertnum = $wpdb->insert( $table_name, $stat );
			$i=$i + 1;
		}
		echo sprintf( __('%d Reviews returned from API. %d New Reviews downloaded.', 'wp_fb-settings' ), $numreturned,$i );
		
		echo "<br><a href='".$google_places_url."' target='_blank'>Google Result</a> ";
		
		return;
	}

	/**
	 * cURL (wp_remote_get) the Google Places API
	 *
	 * @description: CURLs the Google Places API with our url parameters and returns JSON response
	 *
	 * @param $url
	 *
	 * @return array|mixed
	 */
	function get_reviews( $url ) 
	{
		//Sanitize the URL
		$url = esc_url_raw( $url );

		// Send API Call using WP's HTTP API
		$data = wp_remote_get( $url );

		if ( is_wp_error( $data ) ) 
		{
			$response['error_message'] 	= $data->get_error_message();
			$reponse['status'] 		= $data->get_error_code();
			return $response;
		}
		$response = json_decode( $data['body'], true );

		if( ! ( isset( $response['result']['reviews'] ) && ! empty( $response['result']['reviews'] ) ) )
		{
			$response['error_message'] 	= __('No Google Reviews Found.','wp_fb-settings');
			$reponse['status'] 		= __LINE__;
			return $response;
		}

		//Get Reviewers Avatars
		$response = $this->get_reviewers_avatars( $response );

		//Google response data
		return $response;
	}

	/**
	 * Get Reviewers Avatars
	 *
	 * Get avatar from Places API response or provide placeholder.
	 *
	 * @return array
	 */
	function get_reviewers_avatars( $response ) 
	{
		// Includes Avatar image from user.
		if ( isset( $response['result']['reviews'] ) && ! empty( $response['result']['reviews'] ) ) 
		{
			// Loop Google Places reviews.
			foreach( $response['result']['reviews'] as $i => $review ) {
				// Check to see if image is empty (no broken images).
				if ( ! empty( $review['profile_photo_url'] ) ) {
					$avatar_img = $review['profile_photo_url'] . '?sz=100';
				} else {
					$avatar_img = wpgooglerev_plugin_url . '/public/css/imgs/mystery-man.png';
				}
				$response['result']['reviews'][$i]['profile_photo_url'] = $avatar_img;
			}
		}
		return $response;
	}

	//ajax for crawling to check placeid.
	public function wpfbr_ajax_crawl_placeid(){
		//echo "here";
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$gplaceid = trim($_POST['gplaceid']);
		//save placeid in the options table_name
		update_option('wprev_google_crawl_placeid',$gplaceid);
		
		if(!isset($gplaceid) || $gplaceid==''){
				$results['ack'] = 'error';
				$results['ackmsg'] = 'Please a enter a Place ID or Search Terms.';
				$results = json_encode($results);
				delete_option('wprev_google_crawl_check');
				delete_option('wprev_google_crawl_placeid');
				echo $results;
				die();
		}
		
		//$gplaceid can be a placeid or search terms, if there is a space then assume it is search terms.
		if (strpos($gplaceid, ' ') !== false) {
			//has spaces
			$idorsearch = 'search';
			$urlvalue = 'https://www.google.com/search?q='.$gplaceid;
			$foundplaceid = '';
		} else {
			$idorsearch = 'placeid';
			$urlvalue = "https://search.google.com/local/reviews?placeid=".$gplaceid;
			$foundplaceid = $gplaceid;
			//https://search.google.com/local/reviews?placeid=ChIJp7GJd3pvK4cRHgdgZR1PLow
			//https://search.google.com/local/reviews?placeid=ChIJVVVVVWVvK4cROjqGiaAyLZE
			//even pops open reviews
		}
		$urlvalue = urlencode($urlvalue);
		
		//echo $idorsearch;
		//echo $urlvalue;
		//die();
				
		$tempurlvalue = 'https://phantomjscloud.com/api/browser/v2/ak-2cme5-eqftq-dv73x-nr41t-gkbvs/?request={url:%22'.$urlvalue.'%22,renderType:%22html%22}';
		
		//echo $tempurlvalue;
		//die();
		
	
		if (ini_get('allow_url_fopen') == true) {
			$fileurlcontents=file_get_contents($tempurlvalue);
		} else if (function_exists('curl_init')) {
			$fileurlcontents=$this->file_get_contents_curl($tempurlvalue);
		} else {
			$fileurlcontents='<html><body>'.esc_html__('fopen is not allowed on this host.', 'wp-review-slider-pro').'</body></html>';
			$errormsg = $errormsg . '<p style="color: #A00;">'.esc_html__('fopen is not allowed on this host and cURL did not work either. Ask your web host to turn fopen on or fix cURL.', 'wp-review-slider-pro').'</p>';
			$this->errormsg = $errormsg;
			$results['ack'] ='error';
			$results['ackmsg'] =$errormsg;
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		
		//$tempurlvalue = plugin_dir_path( __FILE__ ).'googlecrawl.html';
		//$savefile = file_put_contents($tempurlvalue,$fileurlcontents );
		
		//echo $fileurlcontents;
	
		//die();
		
		$html = wpprogoogle_str_get_html($fileurlcontents);
		
		$results['ack'] ='success';
		$results['ackmsg'] ='';
		$results['businessname'] ='';
		$results['phone'] ='';
		$results['website'] ='';
		$results['rating'] ='';
		$results['totalreviews'] ='';
		$results['img'] ='';
		$results['googleurl'] ='';
		$results['foundplaceid'] =$foundplaceid;
		$results['tempurlvalue'] =$tempurlvalue;
		
		
		//look for review panel on side class  liYKde g VjDLd
		if($html->find("div[class=liYKde g VjDLd]", 0)){
			$reviewpanel = $html->find("div[class=liYKde g VjDLd]", 0);
		} else if($html->find("div[class=BARtsb]", 0)){
			//this could be hotel which is handled on seperate pages. check for average in div class BARtsb
			$reviewpanel = $html;
		}else {
			//echo esc_html__('Error 100: Unable to read business name.', 'wp-google-reviews');
			$results['ack'] = 'error';
			$results['ackmsg'] = 'Error 100: Unable to find business. Please try more specific search terms or use the Place ID. Feel free to contact me for help if you still have trouble.';
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		//look for business name class qrShPb kno-ecr-pt PZPZlf mfMhoc
		if($reviewpanel->find("h2[class=qrShPb kno-ecr-pt PZPZlf mfMhoc]", 0)){
			$results['businessname'] = $reviewpanel->find("h2[class=qrShPb kno-ecr-pt PZPZlf mfMhoc]", 0)->plaintext;
		} else if($reviewpanel->find("h2[class=qrShPb kno-ecr-pt PZPZlf mfMhoc EaHP9c]", 0)){
			$results['businessname'] = $reviewpanel->find("h2[class=qrShPb kno-ecr-pt PZPZlf mfMhoc EaHP9c]", 0)->plaintext;
		}  else if($reviewpanel->find("h2[class=qrShPb kno-ecr-pt PZPZlf mfMhoc hNKfZe]", 0)){
			$results['businessname'] = $reviewpanel->find("h2[class=qrShPb kno-ecr-pt PZPZlf mfMhoc hNKfZe]", 0)->plaintext;
		}   else if($reviewpanel->find("a[class=CQYfx hAP9Pd gEBR9d]", 0)){
			//for hotels on different page
			$results['businessname'] = $reviewpanel->find("a[class=CQYfx hAP9Pd gEBR9d]", 0)->plaintext;
		}   else if($reviewpanel->find("div[class=nCqM5e]", 0)){
			//for hotels on different page
			$results['businessname'] = $reviewpanel->find("div[class=nCqM5e]", 0)->plaintext;
		} else {
			$results['ack'] = 'error';
			$results['ackmsg'] = 'Error 101: Unable to find business name. Please try more specific search terms or use the Place ID.';
		}
		
		//look for business phone
		if($reviewpanel->find("span[class=LrzXr zdqRlf kno-fv]",0)){
			$results['phone'] = $reviewpanel->find("span[class=LrzXr zdqRlf kno-fv]", 0)->plaintext;
		}
		//look for business website
		if($reviewpanel->find("div[class=QqG1Sd]",0)){
			if($reviewpanel->find("div[class=QqG1Sd]",0)->find("a[class=ab_button]",0)){
				$results['website'] = $reviewpanel->find("div[class=QqG1Sd]",0)->find("a[class=ab_button]",0)->href;
			}
		}
		
		//look for business rating
		if($reviewpanel->find("span[class=Aq14fc]",0)){
			$results['rating'] = $reviewpanel->find("span[class=Aq14fc]",0)->plaintext;
		} else if($reviewpanel->find("div[class=BARtsb]", 0)){
			$results['rating'] = $reviewpanel->find("div[class=BARtsb]",0)->plaintext;
		}
		
		//die();
		//look for business totalreviews YRxmGc
		if($reviewpanel->find("div[class=Ob2kfd]",0)){
			if($reviewpanel->find("div[class=Ob2kfd]",0)->find("a")){
				$results['totalreviews'] = $reviewpanel->find("div[class=Ob2kfd]",0)->find("a",0)->plaintext;
			}
		}  else if($reviewpanel->find("div[class=YRxmGc]", 0)){
			$results['totalreviews'] = $reviewpanel->find("div[class=YRxmGc]",0)->plaintext;
			//$results['totalreviews'] =intval($results['rating']);
			
		} else {
			$results['ack'] = 'error';
			$results['ackmsg'] = 'Error 104: Unable to find any reviews for this Place ID or Search Terms. Please contact support.';
		}
		
		//find lrd value so we can use it when we actually make the call to crawl. Ob2kfd
		/*
		if($reviewpanel->find("div[class=Ob2kfd]",0)){
			if($reviewpanel->find("div[class=Ob2kfd]",0)->find("a")){
				$results['lrd'] = $reviewpanel->find("div[class=Ob2kfd]",0)->find("a",0)->getAttribute('data-fid');
			} else {
				$results['ack'] = 'error';
				$results['ackmsg'] = 'Error 102: Unable to lrd value. Please contact support.';
			}
		} else {
			$results['ack'] = 'error';
			$results['ackmsg'] = 'Error 102: Unable to lrd value. Please contact support.';
		}
		*/
		
		//look for image dimg_91
		if($reviewpanel->find('img[class=DU330c]', 0)){
			$results['img'] = $reviewpanel->find('img[class=DU330c]', 0)->src;
		} else if($reviewpanel->find('img[class=rISBZc M4dUYb]', 0)){
			$results['img'] = $reviewpanel->find('img[class=rISBZc M4dUYb]', 0)->src;
		}
		//if this is a search then find place id
		if($foundplaceid==''){
			if($reviewpanel->find("a[class=DvzRrc ab_button]",0)){
			$results['foundplaceid'] = $reviewpanel->find("a[class=DvzRrc ab_button]",0)->getAttribute('data-pid');
			} else {
				$results['ack'] = 'error';
				$results['ackmsg'] = 'Error 103: Unable to find this location. Please try a different Place ID or Search Terms.';
			}
		}
		$results['googleurl'] ="https://search.google.com/local/reviews?placeid=".$results['foundplaceid'];
			
		$results = json_encode($results);
		
		update_option('wprev_google_crawl_check',$results );
		
		echo $results;
	
		die();
				
	}
	
	//ajax for crawling and downloading reviews
	public function wpfbr_ajax_crawl_placeid_go(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		global $wpdb;
		$results['ack'] ='success';
		$results['ackmsg'] ='';
		$nhful = trim($_POST['nhful']);	//newest or relevant
		
		$checkdetails = json_decode(get_option('wprev_google_crawl_check'),true);
		
		if(isset($checkdetails['foundplaceid']) && $checkdetails['foundplaceid']!=''){
			$gplaceid = $checkdetails['foundplaceid'];
		} else {
			//error no place id.
			$results['ack'] = 'error';
			$results['ackmsg'] ='Error 103: Please enter your search terms or place id above and click the Save & Test button.';
		}

		$urlvalue = "https://search.google.com/local/reviews?placeid=".$gplaceid;

		//$urlvalue = urlencode($urlvalue);
		
		$results['url']=$urlvalue;
		
		$requestdetails = '{url:"'.$urlvalue.'",renderType:"html"}';
		
		//testing
		$requestdetails = '{url:"'.$urlvalue.'",renderType:"html",overseerScript:\'await page.waitForNavigation({waitUntil:"domcontentloaded"});\',}';
		
		$request = urlencode($requestdetails);
		
		$tempurlvalue = 'https://phantomjscloud.com/api/browser/v2/ak-2cme5-eqftq-dv73x-nr41t-gkbvs/?request='.$request;
		
		$results['tempurlvalue']=$tempurlvalue;
		
				//--------------phantomjscloud-------------------------
			$scriptpayload = "";
			if($nhful=='newest'){
				$scriptpayload = "const sortbuttons = document.querySelectorAll('div[data-sort-id]');sortbuttons[1].click();";
			} else if($nhful=='relevant'){
				$scriptpayload = "";
			}
			$fileurlcontents = $this->phantomjscloudcall($urlvalue,$scriptpayload);

		
		$html = wpprogoogle_str_get_html($fileurlcontents);
		if (!is_object($html)) {
			$results['ack'] = 'error';
			$results['ackmsg'] ='Error 103: Please enter your search terms or place id above and click the Save & Test button.';
			$results = json_encode($results);
			echo $results;
			die();
		}
					
		
		$reviewcontainerdiv = Array();
		
		
		
			$reviewcontainerdiv = Array();
			//get the array of review container class: WMbnJf vY6njf gws-localreviews__google-review
			if($html->find('div[class=WMbnJf vY6njf gws-localreviews__google-review]', 0)){
				$reviewcontainerdiv = $html->find('div[class=WMbnJf vY6njf gws-localreviews__google-review]');
				$herespot = 1;
				
			} else if($html->find('div[class=Svr5cf bKhjM]', 0)){
				//must be a hotel on separate page
				$reviewcontainerdiv = $html->find('div[class=Svr5cf bKhjM]');
				$herespot = 2;
			} else if($html->find('div[class=WMbnJf gws-localreviews__unified-review Uh9Ipe vY6njf gws-localreviews__google-review]', 0)){
				$reviewcontainerdiv = $html->find('div[class=WMbnJf gws-localreviews__unified-review Uh9Ipe vY6njf gws-localreviews__google-review]');
				$herespot = 3;
			} else {
				//try to search gain based on business name from above check.
				$previouscheck = json_decode(get_option('wprev_google_crawl_check'),true);
				$tempkey = sanitize_text_field($listedurl);
				$tempbusinessname=$previouscheck[$tempkey]['businessname'];
				$herespot = 4;
				if($tempbusinessname!=''){
					//try to search again based on 
					$urlvalue = 'https://www.google.com/search?q='.$tempbusinessname;
					$scriptpayload ="const link1 = document.querySelectorAll('a[class=hqzQac]');if(link1[0]){link1[0].click();}const link2 = document.querySelectorAll('a[data-sort_by=qualityScore]');if(link2[0]){link2[0].click();}";
					sleep(2);
					$result['callurl'] =$urlvalue;
					$fileurlcontents = $this->phantomjscloudcall($urlvalue,$scriptpayload);
					$html = wppro_str_get_html($fileurlcontents);
					if (!is_object($html)) {
						$result['ack'] = 'error';
						$result['ackmsg'] =esc_html__('Error 103: Cant find page. Please enter your search terms or place id above and click the Save & Test button.', 'wp-review-slider-pro');
						$result = json_encode($result);
						echo $result;
						die();
					}
					$herespot = 5;
					if($html->find('div[class=WMbnJf vY6njf gws-localreviews__google-review]', 0)){
						$reviewcontainerdiv = $html->find('div[class=WMbnJf vY6njf gws-localreviews__google-review]');
					} else if($html->find('div[class=WMbnJf gws-localreviews__unified-review Uh9Ipe vY6njf gws-localreviews__google-review]', 0)){
						$reviewcontainerdiv = $html->find('div[class=WMbnJf gws-localreviews__unified-review Uh9Ipe vY6njf gws-localreviews__google-review]');
					} else if($html->find('div[class=Svr5cf bKhjM]', 0)){
						//must be a hotel on separate page
						$reviewcontainerdiv = $html->find('div[class=Svr5cf bKhjM]');
					}
				}
			}
			$result['ackmsg']=$herespot;
			if(count($reviewcontainerdiv)<1){
				//echo $html;
					//die();
				//return error
				$results['ack'] = 'error';
				$results['callurl'] = $urlvalue;
				$results['html'] = $html;
				$results['ackmsg'] = esc_html__('Error 1055: Something went wrong. Unable to find reviews. Please try again or contact us Error Code: ', 'wp-review-slider-pro').$herespot;
				$results = json_encode($results);
				echo $results;
				die();
			}
			
		
		$stats = array();
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
		$x=0;
		$numreturned = count($reviewcontainerdiv);
		
		foreach ($reviewcontainerdiv as $review) {
			// Find user_name
			$results[$x]['user_name']='';
			if($review->find('div.TSUbDb', 0)){
				$results[$x]['user_name']= $review->find('div.TSUbDb', 0)->plaintext;
			}
			//fix for hotel
			if($results[$x]['user_name']==''){
				if($review->find('a.DHIhE', 0)){
					$results[$x]['user_name']= $review->find('a.DHIhE', 0)->plaintext;
				}
			}
			
			//created time
			$results[$x]['created_time']='';
			$results[$x]['created_time_stamp']='';
			$timetext='';
			if($review->find('span[class=dehysf lTi8oc]', 0)){
				$timetext = $review->find('span[class=dehysf lTi8oc]', 0)->plaintext;
			}
			if($timetext==''){
				if($review->find('span[class=iUtr1]', 0)){
					$timetextall = $review->find('span[class=iUtr1]', 0)->plaintext;
					if($review->find('span[class=iUtr1]', 0)->find('span[class=YhR3n]', 0)){
					$timetextchildren = $review->find('span[class=iUtr1]', 0)->find('span[class=YhR3n]', 0)->plaintext;
					$timetext = str_replace($timetextchildren,"",$timetextall);
					} else {
						$timetext =$timetextall;
					}
					
				}
			}
			if($timetext!=''){
				$results[$x]['created_time'] = $timetext;
				$timetext = str_replace("a month ago","1 month ago",$timetext);
				$timetext = str_replace("a day ago","1 day ago",$timetext);
				$timetext = str_replace("a week ago","1 week ago",$timetext);
				$timetext = str_replace("a year ago","1 year ago",$timetext);
				$timetext = str_replace(" on ","",$timetext);
				//echo $timetext;
				$timetext = strtotime($timetext);
				//echo "---".$timetext;
				$results[$x]['created_time_stamp'] = $timetext;
			}
			
			
			//find reviewer_id from maps link		//https://www.google.com/maps/contrib/117800412986895302631?hl=en-US&sa=X&ved=2ahUKEwit9_W6s-PyAhWFTTABHZaUBeIQvvQBegQIARAh
			$results[$x]['user_link']='';
			$results[$x]['reviewer_id']='';
			if($review->find('div.TSUbDb', 0)){
				if($review->find('div.TSUbDb', 0)->find('a', 0)){
					$results[$x]['user_link']= $review->find('div.TSUbDb', 0)->find('a', 0)->href;
					$results[$x]['reviewer_id'] = $this->get_string_between($results[$x]['user_link'], 'contrib/', '?');
				}
			}
			if($results[$x]['reviewer_id']==''){
				//AMrStc
				if($review->find('a.AMrStc', 0)){
					$results[$x]['user_link']= $review->find('a.AMrStc', 0)->href;
					$results[$x]['reviewer_id'] = $this->get_string_between($results[$x]['user_link'], 'contrib/', '?');
				}
			}
			
			
			//find review rating span Fam1ne EBe2gf
			$results[$x]['rating']='';
			if($review->find('span[class=Fam1ne EBe2gf]', 0)){
				$ratingtext = $review->find('span[class=Fam1ne EBe2gf]', 0)->attr['aria-label'];
				$ratingtext = str_replace("Rated ","",$ratingtext);
				$ratingtext = str_replace(" out of 5","",$ratingtext);
				$results[$x]['rating'] = intval($ratingtext);
			}
			if($results[$x]['rating']==''){
				if($review->find('div[class=MfbzKb]', 0)){
				$ratingtext = $review->find('div[class=MfbzKb]', 0)->plaintext;
				$ratingtext = str_replace("/5","",$ratingtext);
				$results[$x]['rating'] = intval($ratingtext);
				}
			}
			
			//find review text
			$results[$x]['review_text']='';
			$rtext='';
			if($review->find('div.Jtu6Td', 0)){
				//look for review-full-text if not then default 
				if($review->find('div.Jtu6Td', 0)->find('span.review-full-text', 0)){
				$rtext= $review->find('div.Jtu6Td', 0)->find('span.review-full-text', 0)->plaintext;
				} else {
				$rtext= $review->find('div.Jtu6Td', 0)->plaintext;
				}
			}
			//check for hotel
			if($rtext==''){
				//look for fuul text first
				if($review->find('div[class=K7oBsc]', 1)){
					$rtext= $review->find('div[class=K7oBsc]', 1)->plaintext;
				} else if($review->find('div[class=K7oBsc]', 0)){
					$rtext= $review->find('div[class=K7oBsc]', 0)->plaintext;
				}
			}
			
			//need to remove translated text here.
			//(Original)
			if (strpos($rtext, '(Original)') !== false) {
				$rtext = substr($rtext, strpos($rtext, '(Original)') + 10);
			}
			$results[$x]['review_text']=$rtext;
			
			$results[$x]['review_text']=trim($results[$x]['review_text']);
			
			$reviewlength='';
			if (extension_loaded('mbstring')) {
				$reviewlength = mb_substr_count($results[$x]['review_text'], ' ');
			} else {
				$reviewlength = substr_count($results[$x]['review_text'], ' ');
			}
			//fix for one word reviews
			if($reviewlength==0 && strlen($results[$x]['review_text'])>0){
				$reviewlength=1;
			}
			$results[$x]['reviewlength']=$reviewlength;
			
			//find user image
			$results['userpic'] ='';
			if($review->find('img[class=lDY1rd]', 0)){
				$results['userpic'] = $review->find('img[class=lDY1rd]', 0)->src;
			}
			if($results['userpic']==''){
				if($review->find('img[class=ZCWdM]', 0)){
					$results['userpic'] = $review->find('img[class=ZCWdM]', 0)->src;
				}
			}

			//check to see if row is in db already
			$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$results[$x]['user_name']."' AND  review_length = '".$results[$x]['reviewlength']."'" );
			
			$slashedusername = addslashes($results[$x]['user_name']);
			
			$checkrow2 = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$slashedusername."' AND  review_length = '".$results[$x]['reviewlength']."'" );
						
			if( empty( $checkrow ) && empty( $checkrow2 ) )
			{
				$stats[] =array( 
					'pageid' 			=> $gplaceid, 
					'pagename' 			=> $checkdetails['businessname'], 
					'created_time' 		=> date( "Y-m-d H:i:s", $timetext ),
					'created_time_stamp' 	=> $timetext,
					'reviewer_name' 		=> $results[$x]['user_name'],
					'reviewer_id' 		=> $results[$x]['reviewer_id'],
					'rating' 			=> $results[$x]['rating'],
					'review_text' 		=> $results[$x]['review_text'],
					'hide' 				=> '',
					'review_length' 	=> $results[$x]['reviewlength'],
					'type' 				=> 'Google',
					'userpic'			=> $results['userpic'],
					'from_url' 			=> $checkdetails['googleurl']
				);
			}
			
			$x++;
		}
		
		
				$i = 0;
		$insertnum = 0;
		foreach ( $stats as $stat ){
			$insertnum = $wpdb->insert( $table_name, $stat );
			$i=$i + 1;
		}
	
		$results['ackmsg'] =sprintf( __('Success! <b>%d</b> Reviews found. <b>%d</b> New Reviews downloaded. Check Review List page for downloaded reviews. The Pro version can download all your reviews and keep them updated.', 'wp_fb-settings' ), $numreturned,$i );
		
		$results = json_encode($results);
		echo $results;
		die();
		
	}
	
	public function phantomjscloudcall($callurl,$scriptpayload='',$apikey='ak-2cme5-eqftq-dv73x-nr41t-gkbvs' ){
			
		//testing--
		$requestdetails = '{url:"'.$callurl.'",renderType:"html"}';
		$request = urlencode($requestdetails);
		$tempurlvalue = 'https://phantomjscloud.com/api/browser/v2/ak-2cme5-eqftq-dv73x-nr41t-gkbvs/?request='.$request;
		$results['tempurlvalue']=$tempurlvalue;
		//-----
		
			$url = 'http://PhantomJScloud.com/api/browser/v2/'.$apikey.'/';
			$payload = '{
					"url":"'.$callurl.'",
					"renderType":"html",
					"outputAsJson":false,
					scripts:{
							domReady:[
								"",
								],
							"loadFinished": [
								"'.$scriptpayload.'",
								]
								}
				}';
				$options = array(
					'http' => array(
						'header'  => "Content-type: application/json\r\n",
						'method'  => 'POST',
						'content' => $payload
					)
				);
				
				$response = wp_remote_post( $url, array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array('Content-type: application/json\r\n'),
					'body'        => $payload
					)
				);
				 
				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					$results['ack'] = 'error';
					$results['ackmsg'] =esc_html__('Something went wrong: ', 'wp-review-slider-pro'). $error_message;
					echo esc_html__('Something went wrong: ', 'wp-review-slider-pro'). $error_message;
					die();
				}
				
				$fileurlcontents = $response['body'];

			return $fileurlcontents;

		}
	
	public function get_string_between($string, $start, $end, $end2=''){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		$len2 =500000;
		if($end2!=''){
			$len2 = strpos($string, $end2, $ini) - $ini;
		}
		if($len2<$len){
			$len=$len2;
		}
		return substr($string, $ini, $len);
	}
	
		//for using curl instead of fopen
	private function file_get_contents_curl($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}
	
	/**
	 * ajax for testing the api key
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wpfbr_ajax_testing_api(){
		//echo "here";
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$apikey = $_POST['apikey'];
		
		$goodkey = false;
		
		//remote get the autocomplete first
		//https://maps.googleapis.com/maps/api/place/autocomplete/json?input=1600+Amphitheatre&key=<API_KEY>		
		$url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=1600+Amphitheatre&key=".$apikey;
		$data = wp_remote_get( $url );

		if ( is_wp_error( $data ) ) 
		{
			$response['error_message'] 	= $data->get_error_message();
			$reponse['status'] 		= $data->get_error_code();
			print_r($response);
		}
		$response = json_decode( $data['body'], true );
		
		if(isset($response['predictions'][0]['place_id'])){
			//autocomplete is working
			echo "- Autocomplete is working.<br>";
			$goodkey = true;
		} else {
			//key not good
			echo "- Something is wrong with this Google API Key. Error from Google...<br><br>";
			print_r($response);
		}
		
		if($goodkey){
				//remote get place if passed outcomplete
				$url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJC8DB3J5sYogRV8b_lTk20U4&key=".$apikey;
				$data = wp_remote_get( $url );

				if ( is_wp_error( $data ) ) 
				{
					$response['error_message'] 	= $data->get_error_message();
					$reponse['status'] 		= $data->get_error_code();
					print_r($response);
				}
				$response = json_decode( $data['body'], true );
				
				if(isset($response['result']['name'])){
					//place lookup is working
					echo "- Place Look-up is working.<br><br>";
					echo "- This key should be good to go. Make sure to click Save Settings at the bottom.<br><br>";
				} else {
					echo "- Something is wrong with this Google API Key. Error from Google...<br><br>";
					print_r($response);
				}
		}
		die();
				
	}
	
		/**
	 * displays message in admin if it's been longer than 30 days.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wprp_admin_notice__success () {

		$activatedtime = get_option('wprev_activated_time_google');
		//if this is an old install then use 23 days ago
		if($activatedtime==''){
			$activatedtime= time() - (86400*23);
			update_option( 'wprev_activated_time_google', $activatedtime );
		}
		$thirtydaysago = time() - (86400*30);
		
		//check if an option was clicked on
		if (isset($_GET['wprevpronotice'])) {
		  $wprevpronotice = $_GET['wprevpronotice'];
		} else {
		  //Handle the case where there is no parameter
		   $wprevpronotice = '';
		}
		if($wprevpronotice=='mlater_google'){		//hide the notice for another 30 days
			update_option( 'wprev_notice_hide_google', 'later' );
			$newtime = time() - (86400*21);
			update_option( 'wprev_activated_time_google', $newtime );
			$activatedtime = $newtime;
			
		} else if($wprevpronotice=='notagain_google'){		//hide the notice forever
			update_option( 'wprev_notice_hide_google', 'never' );
		}
		
		$wprev_notice_hide = get_option('wprev_notice_hide_google');

		if($activatedtime<$thirtydaysago && $wprev_notice_hide!='never'){
		
			$urltrimmedtab = remove_query_arg( array('taction', 'tid', 'sortby', 'sortdir', 'opt') );
			$urlmayberlater = esc_url( add_query_arg( 'wprevpronotice', 'mlater_google',$urltrimmedtab ) );
			$urlnotagain = esc_url( add_query_arg( 'wprevpronotice', 'notagain_google',$urltrimmedtab ) );
			
			$temphtml = '<p>Hey, I noticed you\'ve been using my <b>WP Google Review Slider</b> plugin for a while now – that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? <br>
			Thanks!<br>
			~ Josh W.<br></p>
			<ul>
			<li><a href="https://wordpress.org/support/plugin/wp-google-places-review-slider/reviews/#new-post" target="_blank">Ok, you deserve it</a></li>
			<li><a href="'.$urlmayberlater.'">Not right now, maybe later</a></li>
			<li><a href="'.$urlnotagain.'">Don\'t remind me again</a></li>
			</ul>
			<p>P.S. If you\'ve been thinking about upgrading to the <a href="https://ljapps.com/wp-review-slider-pro/" target="_blank">Pro</a> version, here\'s a 10% off coupon code you can use! ->  <b>wprevpro10off</b></p>';
			
			?>
			<div class="notice notice-info">
				<div class="wprevpro_admin_notice" style="color: #007500;">
				<?php _e( $temphtml, $this->_token ); ?>
				</div>
			</div>
			<?php
		}

	}
	
		/**
	 * add dashboard widget to wordpress admin
	 * @access  public
	 * @since   7.3
	 * @return  void
	 */
	public function wprevpro_dashboard_widget() {
		global $wp_meta_boxes;
		//wp_add_dashboard_widget('custom_help_widget', 'Theme Support', 'custom_dashboard_help');
		add_meta_box( 'googlerevsid', 'WP Google Review Slider Recent Reviews', array($this,'custom_dashboard_help'), 'dashboard', 'side', 'high' );
	}
	 
	public function custom_dashboard_help() {
		global $wpdb;
		$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
		$tempquery = "select * from ".$reviews_table_name." ORDER by created_time_stamp Desc limit 4";
		$reviewrows = $wpdb->get_results($tempquery);
		$now = time(); // or your date as well
		
		echo '<style>
			img.wprev_dash_avatar {float: left;margin-right: 8px;border-radius: 20px;}
			.wprev_dash_stars {float: right;}
			p.wprev_dash_text {margin-top: -6px;}
			span.wprev_dash_timeago {font-size: 12px;font-style: italic;}
			</style>';
			
		echo '<style>
			img.wprev_dash_avatar {float: left;margin-right: 8px;border-radius: 20px;}
			.wprev_dash_stars {float: right;}
			p.wprev_dash_text {margin-top: -6px;}
			span.wprev_dash_timeago {font-size: 12px;font-style: italic;}
			.wprev_dash_revdiv {min-height: 50px;}
			</style>';
		echo '<ul>';
		foreach ( $reviewrows as $review ) 
		{
			$timesince = '';
			if(strlen($review->review_text)>130){
				$reviewtext = substr($review->review_text,0,130).'...';
			} else {
				$reviewtext = $review->review_text;
			}
			
			$your_date = $review->created_time_stamp;
			$datediff = $now - $your_date;
			$daysago = round($datediff / (60 * 60 * 24));
			if($daysago==1){
				$daysagohtml = $daysago.' day ago';
			} else {
				$daysagohtml = $daysago.' days ago';
			}
			if($review->rating<1){
				if($review->recommendation_type=='positive'){
					$review->rating=5;
				} else {
					$review->rating=2;
				}
			}

			$imgs_url = plugin_dir_url(__DIR__).'/public/partials/imgs/';
			$starfile = 'stars_'.$review->rating.'_yellow.png';
			$starhtml='<img src="'.$imgs_url."".$starfile.'" alt="'.$review->rating.' star rating" class="wprev_dash_stars">';
			
			$avatarhtml = '';
			if(isset($review->userpic) && $review->userpic!=''){
				$avatarhtml = '<img alt="" src="'.$review->userpic.'" class="wprev_dash_avatar" height="40" width="40">';
			}
			
			echo '<li><div class="wprev_dash_revdiv">'.$avatarhtml.'<div class="wprev_dash_stars">'.$starhtml.'</div><h4 class="wprev_dash_name">'.$review->reviewer_name.' - <span class="wprev_dash_timeago">'.$daysagohtml.'</span></h4><p class="wprev_dash_text">'.$reviewtext.'</p></div></li>';
			
		}
		echo '</ul>';
		
		echo '<div><a href="admin.php?page=wp_google-reviews">All Reviews</a> - <a href="https://ljapps.com/wp-review-slider-pro/" target="_blank">Go Pro For More Cool Features!</a></div>';
	}
	
	

}
