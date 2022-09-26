<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// shortcode for page
function raviol_shortcode($raviol_atts) {
	// attributes
	$raviol_atts = shortcode_atts(array(
		'class' => '',
		'email_to' => '',
		'from_header' => '',
		'prefix_subject' => '',
		'subject' => '',
		'label_name' => '',
		'label_second_name' => '',
		'label_email' => '',
		'label_subject' => '',
		'label_message' => '',
		'label_privacy' => '',
		'label_submit' => '',
		'error_name' => '',
		'error_second_name' => '',
		'error_email' => '',
		'error_subject' => '',
		'error_sum' => '',
		'error_message' => '',
		'error_links' => '',
		'message_success' => '',
		'message_error' => '',
		'auto_reply_message' => ''
	), $raviol_atts);

	// initialize variables
	$form_data = array(
		'form_name' => '',
		'form_second_name' => '',
		'form_email' => '',
		'form_subject' => '',
		//'form_sum' => '',
		//'form_sum_hidden' => '',
		'form_message' => '',
		'form_privacy' => '',
		//'form_first_name' => '',
		//'form_last_name' => '',
		//'form_token' => ''
	);
	$error = false;
	$sent = false;
	$fail = false;

	// include variables
	include 'raviol-variables.php';

	// set nonce field
	$raviol_nonce_field = wp_nonce_field( 'raviol_nonce_action', 'raviol_nonce', true, false );

	// set time token field
	$raviol_token_field = base64_encode( time() );

	// set name and id of submit button
	$submit_name_id = 'raviol_send';

	// set form class
	if ( empty($raviol_atts['class']) ) {
		$custom_class = '';
	} else {
		$custom_class = ' '.sanitize_key($raviol_atts['class']);
	}
	$form_class = 'raviol-shortcode'.$custom_class.'';

	// processing form
	if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['raviol_send']) && isset( $_POST['raviol_nonce'] ) && wp_verify_nonce( $_POST['raviol_nonce'], 'raviol_nonce_action' ) ) {
		// sanitize input
		$subject_value = $_POST['raviol_subject'];

		$post_data = array(
			'form_name' => sanitize_text_field($_POST['raviol_name']),
			'form_second_name' => sanitize_text_field($_POST['raviol_second_name']),
			'form_email' => sanitize_email($_POST['raviol_email']),
			'form_subject' => sanitize_text_field($subject_value),
			//'form_sum' => sanitize_text_field($sum_value),
			//'form_sum_hidden' => sanitize_text_field($sum_value_hidden),
			'form_message' => wp_kses_post($_POST['raviol_message']),
			//'form_first_name' => sanitize_text_field($_POST['raviol_first_name']),
			//'form_last_name' => sanitize_text_field($_POST['raviol_last_name']),
			//'form_token' => sanitize_text_field($_POST['raviol_token']),
			//'form_link' => get_the_permalink()
		);
		
		$hubspot_token_key = get_option('raviol-setting-22-2');

		// include validation
		include 'raviol-validate.php';

		// include sending and saving form submission
		include 'raviol-submission.php';
	}

	// include form
	include 'raviol-form.php';

	// after form validation
	if ($sent == true) {
		return '<script type="text/javascript">window.location="'.raviol_redirect_success().'"</script>';
	} elseif ($fail == true) {
		return '<script type="text/javascript">window.location="'.raviol_redirect_error().'"</script>';
	}

	// display form or the result of submission
	if ( isset( $_GET['raviol-sh'] ) ) {
		if ( sanitize_key($_GET['raviol-sh']) == 'success' ) {
			return $anchor_begin . '<p class="raviol-info-sender" data-update="'.esc_attr__('Update form', 'raviol-form').'">'.esc_attr($thank_you_message).'</p>' . $anchor_end;
		} elseif ( sanitize_key($_GET['raviol-sh']) == 'fail' ) {
			return $anchor_begin . '<p class="raviol-info-sender" data-update="'.esc_attr__('Update form', 'raviol-form').'">'.esc_attr($server_error_message).'</p>' . $anchor_end;
		}	
	} else {
		if ($error == true) {
			return $anchor_begin .$email_form. $anchor_end;
		} else {
			return $email_form;
		}
	}	   		
} 
add_shortcode('raviol-contact', 'raviol_shortcode');