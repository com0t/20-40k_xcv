/*
 A simple jQuery modal (http://github.com/kylefox/jquery-modal)
 Version 0.9.1
 */
!function(o){"object"==typeof module&&"object"==typeof module.exports?o(require("jquery"),window,document):o(jQuery,window,document)}(function(o,t,i,e){var s=[],l=function(){return s.length?s[s.length-1]:null},n=function(){var o,t=!1;for(o=s.length-1;o>=0;o--)s[o].$blocker&&(s[o].$blocker.toggleClass("current",!t).toggleClass("behind",t),t=!0)};o.modal=function(t,i){var e,n;if(this.$body=o("body"),this.options=o.extend({},o.modal.defaults,i),this.options.doFade=!isNaN(parseInt(this.options.fadeDuration,10)),this.$blocker=null,this.options.closeExisting)for(;o.modal.isActive();)o.modal.close();if(s.push(this),t.is("a"))if(n=t.attr("href"),this.anchor=t,/^#/.test(n)){if(this.$elm=o(n),1!==this.$elm.length)return null;this.$body.append(this.$elm),this.open()}else this.$elm=o("<div>"),this.$body.append(this.$elm),e=function(o,t){t.elm.remove()},this.showSpinner(),t.trigger(o.modal.AJAX_SEND),o.get(n).done(function(i){if(o.modal.isActive()){t.trigger(o.modal.AJAX_SUCCESS);var s=l();s.$elm.empty().append(i).on(o.modal.CLOSE,e),s.hideSpinner(),s.open(),t.trigger(o.modal.AJAX_COMPLETE)}}).fail(function(){t.trigger(o.modal.AJAX_FAIL);var i=l();i.hideSpinner(),s.pop(),t.trigger(o.modal.AJAX_COMPLETE)});else this.$elm=t,this.anchor=t,this.$body.append(this.$elm),this.open()},o.modal.prototype={constructor:o.modal,open:function(){var t=this;this.block(),this.anchor.blur(),this.options.doFade?setTimeout(function(){t.show()},this.options.fadeDuration*this.options.fadeDelay):this.show(),o(i).off("keydown.modal").on("keydown.modal",function(o){var t=l();27===o.which&&t.options.escapeClose&&t.close()}),this.options.clickClose&&this.$blocker.click(function(t){t.target===this&&o.modal.close()})},close:function(){s.pop(),this.unblock(),this.hide(),o.modal.isActive()||o(i).off("keydown.modal")},block:function(){this.$elm.trigger(o.modal.BEFORE_BLOCK,[this._ctx()]),this.$body.css("overflow","hidden"),this.$blocker=o('<div class="'+this.options.blockerClass+' blocker current"></div>').appendTo(this.$body),n(),this.options.doFade&&this.$blocker.css("opacity",0).animate({opacity:1},this.options.fadeDuration),this.$elm.trigger(o.modal.BLOCK,[this._ctx()])},unblock:function(t){!t&&this.options.doFade?this.$blocker.fadeOut(this.options.fadeDuration,this.unblock.bind(this,!0)):(this.$blocker.children().appendTo(this.$body),this.$blocker.remove(),this.$blocker=null,n(),o.modal.isActive()||this.$body.css("overflow",""))},show:function(){this.$elm.trigger(o.modal.BEFORE_OPEN,[this._ctx()]),this.options.showClose&&(this.closeButton=o('<a href="#close-modal" rel="modal:close" class="close-modal '+this.options.closeClass+'">'+this.options.closeText+"</a>"),this.$elm.append(this.closeButton)),this.$elm.addClass(this.options.modalClass).appendTo(this.$blocker),this.options.doFade?this.$elm.css({opacity:0,display:"inline-block"}).animate({opacity:1},this.options.fadeDuration):this.$elm.css("display","inline-block"),this.$elm.trigger(o.modal.OPEN,[this._ctx()])},hide:function(){this.$elm.trigger(o.modal.BEFORE_CLOSE,[this._ctx()]),this.closeButton&&this.closeButton.remove();var t=this;this.options.doFade?this.$elm.fadeOut(this.options.fadeDuration,function(){t.$elm.trigger(o.modal.AFTER_CLOSE,[t._ctx()])}):this.$elm.hide(0,function(){t.$elm.trigger(o.modal.AFTER_CLOSE,[t._ctx()])}),this.$elm.trigger(o.modal.CLOSE,[this._ctx()])},showSpinner:function(){this.options.showSpinner&&(this.spinner=this.spinner||o('<div class="'+this.options.modalClass+'-spinner"></div>').append(this.options.spinnerHtml),this.$body.append(this.spinner),this.spinner.show())},hideSpinner:function(){this.spinner&&this.spinner.remove()},_ctx:function(){return{elm:this.$elm,$elm:this.$elm,$blocker:this.$blocker,options:this.options}}},o.modal.close=function(t){if(o.modal.isActive()){t&&t.preventDefault();var i=l();return i.close(),i.$elm}},o.modal.isActive=function(){return s.length>0},o.modal.getCurrent=l,o.modal.defaults={closeExisting:!0,escapeClose:!0,clickClose:!0,closeText:"Close",closeClass:"",modalClass:"modal",blockerClass:"jquery-modal",spinnerHtml:'<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',showSpinner:!0,showClose:!0,fadeDuration:null,fadeDelay:1},o.modal.BEFORE_BLOCK="modal:before-block",o.modal.BLOCK="modal:block",o.modal.BEFORE_OPEN="modal:before-open",o.modal.OPEN="modal:open",o.modal.BEFORE_CLOSE="modal:before-close",o.modal.CLOSE="modal:close",o.modal.AFTER_CLOSE="modal:after-close",o.modal.AJAX_SEND="modal:ajax:send",o.modal.AJAX_SUCCESS="modal:ajax:success",o.modal.AJAX_FAIL="modal:ajax:fail",o.modal.AJAX_COMPLETE="modal:ajax:complete",o.fn.modal=function(t){return 1===this.length&&new o.modal(this,t),this},o(i).on("click.modal",'a[rel~="modal:close"]',o.modal.close),o(i).on("click.modal",'a[rel~="modal:open"]',function(t){t.preventDefault(),o(this).modal()})});

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

/*global
 $, wpflow_ajax, jslint, alert
 */
var gaeAjax = ( function ( $ ) {

  $(document).ready( function ( $ ) {

    // Handle twitter bootstrap modals
    if (typeof $.fn.modal.noConflict !== "undefined") {
      var bootstrapModal = $.fn.modal.noConflict();
    }

    // Form Submit
      $(".wpgae-event-form").on('submit', submitEventForm);
    // Populate and Show the edit event modal
    $(".ga_main .edit a").on('click', openAndPoplulateEventModal);
    // Populate and Show the Delete event modal
    $(".ga_main .delete a").on('click', openAndPoplulateEventModal);


    $('.deactivate a[href*="wp-google-analytics-events"], #wpgae-modal-cancel a').on('click', function(e) {
      e.preventDefault();
      $("#wpgae-modal-content, #wpgae-modal-background").toggleClass("active");
      $("#wpgae-just-deactivate").attr("href", this.href);
    });

    $('#wpgae-feedback-form').on('submit', function (e) {
      e.preventDefault(); // avoid to execute the actual submit of the form.
      var form = $(this);

      $.ajax({
        type: "POST",
        url: wpflow_ajax.ajax_url,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
          window.location = $("#wpgae-just-deactivate").attr("href");
        }
      });

    });

  });


  function openAndPoplulateEventModal(e) {
    e.preventDefault();
    var id_post = $(this).attr('id');
    var modalId = "#" + $(this).data("action");
    $.ajax({
      type: 'POST',
      url: wpflow_ajax.ajax_url,
      data: {
        'post_id': id_post,
        'action': 'wpflow_get_event_json'
      },
      success: function (result) {
        $(modalId).modal();
        populateMetaEditForm(modalId, result.meta);
        $(modalId + " #event_id").val(id_post);
      },
      error: function () {
        alert("Error updating event");
      }
    });
  }

  function submitEventForm(e) {
    e.preventDefault();
    var form = $(this);

    $.ajax({
      type: "post",
      url: wpflow_ajax.ajax_url,
      data: form.serialize(),
      success: function (data) {
        window.location.reload();
      }
    });
  }
  
  function populateMetaEditForm(modal, meta) {
    if (typeof meta !== "undefined") {
      for (var input in meta) {
        if (meta.hasOwnProperty(input)) {
          if ($(modal + " #" + input).is(":checkbox")) {
            if (meta[input][0] === "true") {
              $(modal + " #" + input).attr("checked", true);
            } else {
              $(modal + " #" + input).removeAttr("checked", false);
            }
          } else {
            $(modal + " #" + input).val(meta[input][0]);
          }
        }
      }
    }
  }

} )( jQuery );






 