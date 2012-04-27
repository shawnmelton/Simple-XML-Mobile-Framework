/**
 * @desc This class will add pagination to a container's items.
 * @version $Id: $
 */
(function($){
	var FormsPlugin = function(element, options) {
		var form = $(element);		// List wrapper element (container)
		var obj = this;
		var placeholder = "Required!";
		var formElements = false;
		var emailRE = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		var phoneRE = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
		var settings = $.extend({
			ajax: false,	// Submit form using ajax.
			ajaxCallback: false // Pass in the function to call after ajax submission.
		}, options || {});
		
		
		/**************** Private methods ******************/
		
		// Remove errors highlighted on fields.
		var removePlaceholder = (function(){
			if( $(this).val() == placeholder ) {
				$(this).val("");
			}
			
			$(this).removeClass("error");
		});
		
		
		
		
		/*************** Public methods *******************/
		
		/**
		 * @desc Initialize form validation.
		 */
		this.init = function() {
			formElements = form.find("input, select, textarea");
		
			form.submit(function(event){
				formElements.each(removePlaceholder);
				formElements.each(function(){
					if( $(this).attr("required") && $(this).val() == "" ) {
						$(this).addClass("error");
						$(this).val(placeholder);
					} else if( $(this).is("input") && ($(this).attr("name") == "email_address" || $(this).attr("type") == "email") && $(this).val() != "" && !$(this).val().match(emailRE) ) {
						$(this).addClass("error");
					} else if( $(this).is("input") && ($(this).attr("name") == "phone_number" || $(this).attr("type") == "telephone") && $(this).val() != "" && !$(this).val().match(phoneRE) ) {
						$(this).addClass("error");
					}
				});
				
				if( settings["ajax"] && form.find(".error").length == 0 ) { // Should this script post using ajax?
					$.post(form.attr("action"), form.serialize(), function(response) {
						if( settings["ajaxCallback"] !== false ) {
							window[settings["ajaxCallback"]]();
						}
					});
					
					return false;
				}
				
				return (form.find(".error").length == 0); // There aren't any errors on site.
			});
			
			
			formElements.focus(removePlaceholder);
		};
	};
	
	// Define paginate function call.
	$.fn.validate = function(options) {
		return this.each(function() {
			var plugin = new FormsPlugin(this, options);
			plugin.init();
		});
	};
})(jQuery);
