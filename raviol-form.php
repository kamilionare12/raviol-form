<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// contact form
$email_form = '<form id="raviol" class="'.$form_class.'" method="post">
	<div class="rvl-row rvl-row-cols-1 rvl-row-cols-sm-2">
		<div class="rvl-col">
			<div class="rvl-form-floating rvl-mb-3 rvl-mb-sm-4">		
				<input type="text" name="raviol_name" placeholder="'.esc_attr($name_label).'" id="raviol_name"'.(isset($error_class['form_name']) ? ' class="rvl-form-control rvl-is-invalid"' : ' class="rvl-form-control"').' value="'.esc_attr($form_data['form_name']).'" aria-required="true"/>
				<label for="raviol_name">'.esc_attr($name_label).':	</label>		
			</div>
		</div>
		<div class="rvl-col">	
			<div class="rvl-form-floating rvl-mb-3 rvl-mb-sm-4">		
			  <input type="text" class="'.(isset($error_class['form_second_name']) ? 'rvl-form-control rvl-is-invalid' : 'rvl-form-control').'" id="raviol_second_name" 			  name="raviol_second_name" placeholder="'.esc_attr($second_name_label).'" value="'.esc_attr($form_data['form_second_name']).'" >
			<label for="raviol_second_name" >'.esc_attr($second_name_label).'</label>
			</div>
		</div>
	</div>
	<div class="rvl-row rvl-row-cols-1 rvl-row-cols-sm-2">
		<div class="rvl-col">
			<div class="rvl-form-floating rvl-mb-3 rvl-mb-sm-4">
				  <input type="text" class="'.(isset($error_class['form_subject']) ? 'rvl-form-control rvl-is-invalid' : 'rvl-form-control').'" id="raviol_subject" name="raviol_subject" placeholder="'.esc_attr($second_name_label).'" value="'.esc_attr($form_data['form_subject']).'">
				  <label for="raviol_subject">'.esc_attr($subject_label).'</label>
			</div>
		</div>
		<div class="rvl-col">	
			<div class="rvl-form-floating rvl-mb-3 rvl-mb-sm-4">
				<input type="email" name="raviol_email" placeholder="'.esc_attr($email_label).'" id="raviol_email" '.(isset($error_class['form_email']) ? ' class="rvl-form-control rvl-is-invalid"' : ' class="rvl-form-control"').' value="'.esc_attr($form_data['form_email']).'" aria-required="true" />
				<label for="raviol_email">'.esc_attr($email_label).'</label>
			</div>
		</div>
	</div>
	<div class="form-group raviol-docs-group rvl-position-relative rvl-mb-3 rvl-mb-sm-4">
		<label class="message-label" for="raviol_message">'.esc_attr($message_label).' <span class="'.( ( isset($error_class['form_message']) || isset($error_class['form_links']) ) ? "raviol-error" : "raviol-hide").'" >'.(isset($error_class['form_links']) ? esc_attr($error_links_label) : esc_attr($error_message_label)).'</span></label>
		<textarea name="raviol_message" id="raviol_message" rows="7" '.( ( isset($error_class['form_message']) || isset($error_class['form_links']) ) ? ' class="rvl-form-control raviol-error editor-init"' : ' class="rvl-form-control editor-init"').' aria-required="true">'.esc_textarea($form_data['form_message']).'</textarea>
	</div>
	<div class="form-group raviol-hide">
		<input type="hidden" name="raviol_token" id="raviol_token" class="rvl-form-control" value="'.esc_attr($raviol_token_field).'" />
	</div>
	<div class="form-group raviol-hide">
		'.$raviol_nonce_field.'
	</div>
	<div class="rvl-row">
		<div class="rvl-col-12">	
			<button type="submit" name="'.$submit_name_id.'" id="'.$submit_name_id.'" class="rvl-btn rvl-btn-lg rvl-rounded-0 rvl-btn-secondary rvl-fs-6 rvl-p-3 rvl-px-4">'.esc_attr($submit_label).'</button>
		</div>
		<small class="rvl-col-12 rvl-small">
			'.esc_attr($privacy_label).'
		</small>
	</div>
	</div>
</form>';