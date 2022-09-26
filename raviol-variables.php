<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// get custom settings
$ip_address_setting = '';
$anchor_setting = 'yes';
$admin_hubspot_access_token_key = get_option('raviol-setting-22-2');

// get custom labels
$name_label = '';
$second_name_label = '';
$email_label = '';
$subject_label = '';
$message_label = '';
$privacy_label = '';
$submit_label = 'Submit a request';
$error_name_label = '';
$error_second_name_label = '';
$error_email_label = '';
$error_subject_label = '';
$error_sum_label = '';
$error_message_label = '';
$error_links_label = '';

// get custom messages
$server_error_message = '';
$thank_you_message = '';
$auto_reply_message = '';

// first name label
$value = $name_label;
if (empty($raviol_atts['label_name'])) {
	if (empty($value)) {
		$name_label = __( 'First Name', 'raviol-form' );
	} else {
		$name_label = $value;
	}
} else {
	$name_label = $raviol_atts['label_name'];
}

// Second name label
$value = $second_name_label;
if (empty($raviol_atts['label_second_name'])) {
	if (empty($value)) {
		$second_name_label = __( 'Last Name', 'raviol-form' );
	} else {
		$second_name_label = $value;
	}
} else {
	$second_name_label = $raviol_atts['label_second_name'];
}

// email label
$value = $email_label;
if (empty($raviol_atts['label_email'])) {
	if (empty($value)) {
		$email_label = __( 'Email', 'raviol-form' );
	} else {
		$email_label = $value;
	}
} else {
	$email_label = $raviol_atts['label_email'];
}

// subject label
$value = $subject_label;
if (empty($raviol_atts['label_subject'])) {
	if (empty($value)) {
		$subject_label = __( 'Subject', 'raviol-form' );
	} else {
		$subject_label = $value;
	}
} else {
	$subject_label = $raviol_atts['label_subject'];
}

// message label
$value = $message_label;
if (empty($raviol_atts['label_message'])) {
	if (empty($value)) {
		$message_label = __( 'Message', 'raviol-form' );
	} else {
		$message_label = $value;
	}
} else {
	$message_label = $raviol_atts['label_message'];
}

// privacy label
$value = $privacy_label;
if (empty($raviol_atts['label_privacy'])) {
	if (empty($value)) {
		$privacy_label = __( 'By clicking "Submit Request" you agree to receive marketing communications', 'raviol-form' );
	} else {
		$privacy_label = $value;
	}
} else {
	$privacy_label = $raviol_atts['label_privacy'];
}

// submit label
$value = $submit_label;
if (empty($raviol_atts['label_submit'])) {
	if (empty($value)) {
		$submit_label = __( 'Submit', 'raviol-form' );
	} else {
		$submit_label = $value;
	}
} else {
	$submit_label = $raviol_atts['label_submit'];
}

// error name label
$value = $error_name_label;
if (empty($raviol_atts['error_name'])) {
	if (empty($value)) {
		$error_name_label = __( 'Please enter at least 2 characters', 'raviol-form' );
	} else {
		$error_name_label = $value;
	}
} else {
	$error_name_label = $raviol_atts['error_name'];
}

// error second name label
$value = $error_second_name_label;
if (empty($raviol_atts['error_second_name'])) {
	if (empty($value)) {
		$error_second_name_label = __( 'Please enter at least 2 characters', 'raviol-form' );
	} else {
		$error_second_name_label = $value;
	}
} else {
	$error_second_name_label = $raviol_atts['error_second_name'];
}

// error email label
$value = $error_email_label;
if (empty($raviol_atts['error_email'])) {
	if (empty($value)) {
		$error_email_label = __( 'Please enter a valid email', 'raviol-form' );
	} else {
		$error_email_label = $value;
	}
} else {
	$error_email_label = $raviol_atts['error_email'];
}

// error subject label
/* $value = $error_subject_label;
if (empty($raviol_atts['error_subject'])) {
	if (empty($value)) {
		$error_subject_label = __( 'Please enter at least 2 characters', 'raviol-form' );
	} else {
		$error_subject_label = $value;
	}
} else {
	$error_subject_label = $raviol_atts['error_subject'];
} */

// error sum label
$value = $error_sum_label;
if (empty($raviol_atts['error_sum'])) {
	if (empty($value)) {
		$error_sum_label = __( 'Please enter the correct result', 'raviol-form' );
	} else {
		$error_sum_label = $value;
	}
} else {
	$error_sum_label = $raviol_atts['error_sum'];
}

// error message label
$value = $error_message_label;
if (empty($raviol_atts['error_message'])) {
	if (empty($value)) {
		$error_message_label = __( 'Please enter at least 10 characters', 'raviol-form' );
	} else {
		$error_message_label = $value;
	}
} else {
	$error_message_label = $raviol_atts['error_message'];
}

// error links label
$value = $error_links_label;
if (empty($raviol_atts['error_links'])) {
	if (empty($value)) {
		$error_links_label = __( 'Please reduce number of links', 'raviol-form' );
	} else {
		$error_links_label = $value;
	}
} else {
	$error_links_label = $raviol_atts['error_links'];
}

// server error message
$value = $server_error_message;
if (empty($raviol_atts['message_error'])) {
	if (empty($value)) {
		$server_error_message= __( 'Error! Could not send form. This might be a server issue.', 'raviol-form' );
	} else {
		$server_error_message = $value;
	}
} else {
	$server_error_message = $raviol_atts['message_error'];
}

// thank you message
$value = $thank_you_message;
if (empty($raviol_atts['message_success'])) {
	if (empty($value)) {
		$thank_you_message = __( 'Thank you! You will receive a response as soon as possible.', 'raviol-form' );
	} else {
		$thank_you_message = $value;
	}
} else {
	$thank_you_message = $raviol_atts['message_success'];
}

// auto reply message
$value = $auto_reply_message;
if (empty($raviol_atts['auto_reply_message'])) {
	if (empty($value)) {
		$auto_reply_message = __( 'Thank you! You will receive a response as soon as possible.', 'raviol-form' );
	} else {
		$auto_reply_message = $value;
	}
} else {
	$auto_reply_message = $raviol_atts['auto_reply_message'];
}

// form anchor
if ($anchor_setting == 'yes') {
	$anchor_begin = '<div id="raviol-anchor">';
	$anchor_end = '</div>';
} else {
	$anchor_begin = '';
	$anchor_end = '';
}
