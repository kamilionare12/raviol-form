<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// json log
function raviol_on_submit_log_json () {
	include 'raviol-logs.php';
}

// sending and saving form submission
if ($error == false) {
	// hook to support plugin Contact Form DB
	//do_action( 'raviol_before_send_mail', $form_data );
	//raviol_on_submit_log_json();
	
	// site name
	$blog_name = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES);
	// email variables
	$email_admin = get_option('admin_email');
	$admin_hubspot_access_token_key = get_option('admin_hubspot_access_token_key');
	$email_settingspage = get_option('raviol-setting-22');
	$email_to_attribute = $raviol_atts['email_to'];
	$from_header_attribute = $raviol_atts['from_header'];
	$from_header = raviol_from_header();
	$to = '';
	// admin email address
	if ( !empty($email_to_attribute) ) {
		if (strpos($email_to_attribute, ',') !== false) {
			$email_list_clean = array();
			$email_list = explode(',', $email_to_attribute);
			foreach ( $email_list as $email_single ) {
				$email_clean = sanitize_email( $email_single );
				if ( is_email( $email_clean ) ) {
					$email_list_clean[] = $email_clean;
				}
			}
			if ( count($email_list_clean) < 6 ) {
				$to = implode(',', $email_list_clean);
			}
		} else {
			$email_clean = sanitize_email( $email_to_attribute );
			if ( is_email( $email_clean ) ) {
				$to = $email_clean;
			}
		}
	}
	if ( empty($to) ) {
		if ( is_email($email_settingspage) ) {
			$to = $email_settingspage;
		} else {
			$to = $email_admin;
		}
	}
	// from email header
	if ( is_email($from_header_attribute) ) {
		$from = $from_header_attribute;
	} elseif ( is_email($from_header) ) {
		$from = $from_header;
	} elseif ( is_email($email_settingspage) ) {
		$from = $email_settingspage;
	} else {
		$from = $email_admin;
	}
	// reply to email address
	if ( is_email($email_settingspage) ) {
		$reply_to = $email_settingspage;
	} else {
		$reply_to = $email_admin;
	}
	// subject
	if (!empty($raviol_atts['prefix_subject'])) {
		$prefix = $raviol_atts['prefix_subject'];
	} else {
		$prefix = $blog_name;
	}
	if (!empty($raviol_atts['subject'])) {
		$subject = $raviol_atts['subject'];
	} else {
		$subject = $prefix;
	}
	if ((!empty($raviol_atts['subject'])) ) {
		$subject_in_content = $form_data['form_subject']."\r\n\r\n";
	} else {
		$subject_in_content = '';
	}
	// auto reply message
	$reply_message = htmlspecialchars_decode($auto_reply_message, ENT_QUOTES);

	// show or hide ip address
	if ($ip_address_setting == 'yes') {
		$ip_address = '';
	} else {
		$ip_address = "\r\n\r\n".sprintf( __( 'IP: %s', 'raviol-form' ), raviol_get_the_ip() );
	}
	// save form submission in database
	$raviol_post_information = array(
		'post_title' => wp_strip_all_tags($subject),
		'post_content' => $form_data['form_name']."\r\n\r\n".$form_data['form_second_name']."\r\n\r\n".$form_data['form_email']."\r\n\r\n".$subject_in_content.$form_data['form_message'].$ip_address,
		'post_type' => 'submission',
		'post_status' => 'pending',
		'meta_input' => array( 
			"name_sub" => $form_data['form_name']. ' ' . $form_data['form_second_name'], 
			"email_sub" => $form_data['form_email'] )
	);
	$post_id = wp_insert_post($raviol_post_information);

	// mail
	$content = $form_data['form_name']."\r\n\r\n".$form_data['form_second_name']."\r\n\r\n".$form_data['form_email']."\r\n\r\n".$subject_in_content.$form_data['form_message'].$ip_address;
	$headers = "Content-Type: text/html; charset=UTF-8" . "\r\n";
	$headers .= "From: ".$form_data['form_email']." <".$from.">" . "\r\n";$headers .= "From: ".$form_data['form_email']." <".$from.">" . "\r\n";
	$headers .= "Reply-To: <".$form_data['form_email'].">" . "\r\n";
	$auto_reply_content = $reply_message."\r\n\r\n".$form_data['form_name']."\r\n\r\n".$form_data['form_second_name']."\r\n\r\n".$form_data['form_email']."\r\n\r\n".$subject_in_content.$form_data['form_message'];
	$auto_reply_headers = "Content-Type: text/html; charset=UTF-8" . "\r\n";
	$auto_reply_headers .= "From: ".$blog_name." <".$from.">" . "\r\n";
	$auto_reply_headers .= "Reply-To: <".$reply_to.">" . "\r\n";

	if( wp_mail($to, $form_data['form_subject'], $content, $headers) ) {
	//wp_mail($form_data['form_email'], wp_strip_all_tags($subject), $auto_reply_content, $auto_reply_headers);
		$sent = true;
	} else {
		$fail = true;
	}
	
	if ( $sent = true ) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.hubapi.com/crm/v3/objects/contacts',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
			"submittedAt": "' . time() . '",
			"fields": [
				{
					"name": "email",
					"value": "' . $post_data['form_email'] . '"
				},
				{
					"name": "firstname",
					"value": "' . $post_data['form_name'] . '"
				}
			],
			"context": {
				"hutk": "",
				"pageUri": "",
				"pageName": ""
			},
		"legalConsentOptions": {
			"consent": {
				"consentToProcess": true,
				"text": "I agree to allow Example Company to store and process my personal data.",
				"communications": [
					{
						"value": true,
						"subscriptionTypeId": 999,
						"text": "I agree to receive marketing communications from Example Company."
					}
				]
			}
		},
			"properties": {
				"email": "' . $post_data['form_email'] . '",
				"firstname": "' . $post_data['form_name'] . '",
				"lastname": "' . $post_data['form_second_name'] . '",
				"phone": "",
				"website": ""
			}
		}',
		  CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer ' .esc_html( $hubspot_token_key ),
			'Content-Type: application/json'
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
	}
	
	raviol_on_submit_log_json();
	
} else {
	raviol_on_submit_log_json();
}
