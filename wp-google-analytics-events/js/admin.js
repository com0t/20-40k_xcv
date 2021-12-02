/**
 * Created with IntelliJ IDEA.
 * User: yuval
 * Date: 2/25/15
 * Time: 1:08 PM
 * To change this template use File | Settings | File Templates.
 */

jQuery(document).ready(function($){

	var snippet_type = $('#snippet_type');
	var anonymizeip = $('#anonymizeip');
	var anonymizeip_checkbox = $('#anonymizeip')[0];
	var gtm_id = $('#gtm_id');
	var script_debug_mode_input = $('#script_debug_mode');

	// Helpers to avoid repetition
	function disable_element(el){
		el.attr("disabled", true);
	}

	function enable_element(el){
		el.removeAttr("disabled");
	}

	function set_checked_value(el, bool){
		el.checked = bool;
	}

	// set up tooltips
	$.widget.bridge('gaetooltip', $.ui.tooltip);

	$('.ga-tooltip').gaetooltip({position: {
		my: "left bottom-10",
		at: "right top",
		collision: "none"
	}
	})

	$('#advanced:checkbox').on('change', function (){
		var checked = $(this).is(':checked');
		if (checked){
			$('#forcesnopperwrap').show();
		} else {
			$('#forcesnopperwrap').hide();
		}
	});

	$('.btn_upload').on('click', function (e){
		$('.settings_content').slideDown();
		e.preventDefault();
	});

	$('.btn_close').on('click', function (e){
		$('.settings_content').slideUp();
		e.preventDefault();
	});

	$('.popup').on('click', function (e){
		$('.popup').slideUp();
		e.preventDefault();
	});


	/*
	 * The following section deals with the snippet type options in the admin UI
	 */

	// If page loads and snippet type is 'none' or 'gtm', disable anonymize IP checkbox
	if (snippet_type.val()=== 'none' || snippet_type.val()=== 'gtm'){
		set_checked_value(anonymizeip_checkbox, false);
		disable_element(anonymizeip);
	}

	// If page loads and snippet_type is 'gtm', enable gtm_id
	if (snippet_type.val()=== 'gtm'){
		enable_element(gtm_id);
	} else {
		disable_element(gtm_id);
	}

	// When the snippet type option is changed
	snippet_type.on('change', function (){
		// Store current value
		var val = $(this).val();

		// If 'none' is selected, disable anonymize zip checkbox and ?
		if (val === 'none' || val === 'gtm'){
			set_checked_value(anonymizeip_checkbox, false);
			disable_element(anonymizeip);
		} else {
			enable_element(anonymizeip);
		}

		// If gtm snippet is selected
		if (val === 'gtm'){
			enable_element(gtm_id);
		} else {
			disable_element(gtm_id);
		}
	});

	// Snippet section ends

	// The following section deals with the import settings functinality in
	// general settings
	$('.btn_upload').on('click', function (e){
		$('.settings_content').slideDown();
		e.preventDefault();
	});

	$('.btn_close').on('click', function (e){
		$('.settings_content').slideUp();
		e.preventDefault();
	});

	$('.popup').on('click', function (e){
		$('.popup').slideUp();
		e.preventDefault();
	});

	// import section ends

	// Disable checkbox for admin options management permission
	$('input[name="ga_events_options[permitted_roles][]"][value="administrator"]').css({'pointer-events': 'none',
		'opacity': 0.5}).prop('checked', true);

	$('.divs-istracktime:checkbox').on('change', function (){
		var checkbox = $(this);
		var checked = checkbox.is(':checked');
		var index = checkbox.data('track');
		var trackValue = "#track-elem" + index;
		var eventValue = "#eventValue" + index;
		if (checked){
			$(trackValue).show();
			$(eventValue).attr("disabled", true);
		} else {
			$(trackValue).hide();
			$(eventValue).removeAttr("disabled");
		}

	});

	$('#empty-istracktime:checkbox').on('change', function (){
		var checkbox = $(this);
		var checked = checkbox.is(':checked');
		var trackValue = "#empty-trackelem";
		var eventValue = "#empty-eventValue";
		if (checked){
			$(trackValue).show();
			$(eventValue).attr("disabled", true);
		} else {
			$(trackValue).hide();
			$(eventValue).removeAttr("disabled");
		}

	});

	$('.divs-istracktime').trigger('change');

	function isUrlValid(url){
		return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
	}

	
	jQuery('body').on('click','a[href="admin.php?page=wp-google-analytics-events-upgrade"]', function (e) {
		e.preventDefault();
		window.open('https://wpflow.com/upgrade/?utm_source=wpadmin&utm_medium=banner&utm_campaign=nav', '_blank');
	});
});
