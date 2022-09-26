<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// validate name field
$value_name = stripslashes($post_data['form_name']);
if ( strlen($value_name)<2 ) {
	$error_class['form_name'] = true;
	$error = true;
}
$form_data['form_name'] = $value_name;

// validate second name field
$value_second_name = stripslashes($post_data['form_second_name']);
if ( strlen($value_second_name)<2 ) {
	$error_class['form_second_name'] = true;
	$error = true;
}
$form_data['form_second_name'] = $value_second_name;

// validate email field
$value_email = $post_data['form_email'];
if ( empty($value_email) ) {
	$error_class['form_email'] = true;
	$error = true;
}
$form_data['form_email'] = $value_email;

// validate subject field
$value_subject = stripslashes($post_data['form_subject']);
if ( strlen($value_subject)<2 ) {
	$error_class['form_subject'] = true;
	$error = true;
}
$form_data['form_subject'] = $value_subject;

// validate message field
$value_message = stripslashes($post_data['form_message']);

if ( strlen($value_message)<2 ) {
	$error_class['form_message'] = true;
	$error = true;
}
$form_data['form_message'] = $value_message;