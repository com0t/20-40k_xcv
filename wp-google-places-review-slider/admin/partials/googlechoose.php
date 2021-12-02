<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ljapps.com
 * @since      1.0.0
 *
 * @package    WP_Google_Reviews
 * @subpackage WP_Google_Reviews/admin/partials
 */

    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('wpfbr_messages', 'wpfbr_message', __('Settings Saved', 'wp-google-reviews'), 'updated');
    }
    // show error/update messages
    settings_errors('wpfbr_messages');
?>

<div class="">
<h1></h1>
<div class="wrap" id="wp_rev_maindiv">
<img class="wprev_headerimg" src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>">
<?php 
include("tabmenu.php");
?>	
<div class="wpfbr_margin10">

<div class="w3-col wpfbr_mb15 welcomediv w3-container w3-white w3-border w3-border-light-gray2 w3-round-small">
	<div class="w3-container w3-padding-16">
	<h4 class="">Choose one or both of these options to download reviews...</h3>
	</div>
<div class="w3-row-padding wppro_choose wpfbr_mb25">
<div class="w3-col l6">
<div class="w3-card-4 w3-white">
<header class="w3-container w3-light-grey">
  <h4><i class="fa fa-cogs" aria-hidden="true"></i> Crawl Google Review Page</h4>
</header>
<div class="w3-container">
<h5>Pros:</h5>
  <p>- Easiest and simplest method.</p>
  <p>- Will download your latest 10 or most helpful 10 reviews.</p>
  <p>- No API Key required.</p>
  <p>- Can also work for service area businesses.</p>
  <hr>
  <h5>Cons:</h5>
  <p>- Can not automatically check for new reviews.</p>
</div>
<a class="w3-button w3-block w3-dark-grey" href="<?php echo $urlgooglegooglecrawl; ?>">+ Select</a>
</div>
</div>

<div class="w3-col l6 ">
<div class="w3-card-4 w3-white">
<header class="w3-container w3-light-grey">
  <h4><i class="fa fa-map-o" aria-hidden="true"></i> Google Places API</h4>
</header>
<div class="w3-container">
<h5>Pros:</h5>
  <p>- Will download your Most Helpful 5 reviews.</p>
  <p>- Can automatically check for reviews daily.</p>
  <hr>
  <h5>Cons:</h5>
  <p>- Must have a physical address on Google Maps.</p>
  <p>- Requires you to obtain Google Places API Key from Google.</p>
  <p>- Can not get all new reviews, only most helpful.</p>
</div>
<a class="w3-button w3-block w3-dark-grey" href="<?php echo $urlgoogleapi; ?>">+ Select</a>
</div>
</div>

</div>
<div class="w3-container w3-padding-16"><span class="small_message">
	The <a href="https://ljapps.com/wp-review-slider-pro/">Pro Version</a> of this plugin can download all of your Google reviews from multiple locations and keep them updated automatically!</span></div>
</div>



</div>



	<div id="popup" class="popup-wrapper wpfbr_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popup_titletext"></h3>
		</div>
		<div class="popup-body">
		  <div id="popup_bobytext1"></div>
		  <div id="popup_bobytext2"></div>
		</div>
	  </div>
	</div>
</div>
</div>