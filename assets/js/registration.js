/**
 * Demo Registration Form JavaScript
 *
 * @package ChurchTools_Suite_Demo
 * @since   1.0.0
 */

(function($) {
	'use strict';
	
	$(document).ready(function() {
		const $form = $('#cts-demo-registration-form');
		const $submitBtn = $form.find('.cts-submit-btn');
		const $btnText = $submitBtn.find('.btn-text');
		const $btnSpinner = $submitBtn.find('.btn-spinner');
		const $message = $form.find('.cts-form-message');
		
		$form.on('submit', function(e) {
			e.preventDefault();
			
			// Clear previous message
			$message.hide().removeClass('success error');
			
			// Validate privacy checkbox
			if (!$('#cts-demo-privacy').is(':checked')) {
				showMessage('error', 'Bitte akzeptieren Sie die Datenschutzerklärung.');
				return;
			}
			
			// Disable submit button
			$submitBtn.prop('disabled', true);
			$btnText.hide();
			$btnSpinner.show();
			
			// Prepare data
			const formData = {
				action: 'cts_demo_register',
				nonce: ctsDemo.nonce,
				email: $('#cts-demo-email').val(),
				name: $('#cts-demo-name').val(),
				company: $('#cts-demo-company').val(),
				purpose: $('#cts-demo-purpose').val(),
				privacy_accepted: $('#cts-demo-privacy').is(':checked') ? 1 : 0
			};
			
			// Submit via AJAX
			$.ajax({
				url: ctsDemo.ajaxUrl,
				method: 'POST',
				data: formData,
				success: function(response) {
					if (response.success) {
						showMessage('success', response.data.message);
						$form[0].reset();
						
						// Scroll to message
						$('html, body').animate({
							scrollTop: $message.offset().top - 100
						}, 500);
					} else {
						showMessage('error', response.data.message);
					}
				},
				error: function(xhr, status, error) {
					showMessage('error', 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
					console.error('AJAX Error:', error);
				},
				complete: function() {
					// Re-enable submit button
					$submitBtn.prop('disabled', false);
					$btnText.show();
					$btnSpinner.hide();
				}
			});
		});
		
		/**
		 * Show message
		 *
		 * @param {string} type - 'success' or 'error'
		 * @param {string} text - Message text
		 */
		function showMessage(type, text) {
			$message
				.removeClass('success error')
				.addClass(type)
				.html(text)
				.fadeIn(300);
		}
	});
	
})(jQuery);
