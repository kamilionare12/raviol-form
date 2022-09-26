<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// This styles is only called when our plugin's page loads!
function raviol_load_admin_js(){
	// Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
	add_action( 'admin_enqueue_scripts', 'raviol_admin_style' );
}
	
// add admin options page
function raviol_menu_page() {
    $my_page = add_menu_page( esc_attr__( 'Raviol Form', 'raviol-form' ), esc_attr__( 'Raviol Form', 'raviol-form' ), 'manage_options', 'raviol', 'raviol_options_page', 'dashicons-email', 7);
	add_action( 'load-' . $my_page, 'raviol_load_admin_js' );
}
add_action( 'admin_menu', 'raviol_menu_page' );

// add admin settings and such
function raviol_admin_init() {
	// general section
	add_settings_section( 'raviol-general-section', esc_attr__( 'General', 'raviol-form' ), '', 'raviol-general' );

	add_settings_field( 'raviol-field-22', esc_attr__( 'Administrator Email', 'raviol-form' ), 'raviol_field_callback_22', 'raviol-general', 'raviol-general-section' );
	register_setting( 'raviol-general-options', 'raviol-setting-22', array('sanitize_callback' => 'sanitize_email') );

	add_settings_field( 'raviol-field-1', esc_attr__( 'Uninstall plugin', 'raviol-form' ), 'raviol_field_callback_1', 'raviol-general', 'raviol-general-section' );
	register_setting( 'raviol-general-options', 'raviol-setting', array('sanitize_callback' => 'sanitize_key') );
	
	add_settings_field( 'raviol-field-27', esc_attr__( 'Download email logs', 'raviol-form' ), 'raviol_field_callback_27', 'raviol-general', 'raviol-general-section' );
	register_setting( 'raviol-general-options', 'raviol-setting-27', array('sanitize_callback' => 'sanitize_key') );
	
	// Hubspot integration (general-section)
	add_settings_section( 'raviol-general-section2', esc_attr__( 'Hubspot integration', 'raviol-form' ), '', 'raviol-general' );
	
	add_settings_field( 'raviol-field-22-2', esc_attr__( 'Access token', 'raviol-form' ), 'raviol_field_callback_22_2', 'raviol-general', 'raviol-general-section2' );
	register_setting( 'raviol-general-options', 'raviol-setting-22-2', array('sanitize_callback' => 'sanitize_text_field') );

	// label section
	add_settings_section( 'raviol-label-section', esc_attr__( 'Labels', 'raviol-form' ), '', 'raviol-label' );

	// message section
	add_settings_section( 'raviol-docs-section', esc_attr__( '', 'raviol-form' ), '', 'raviol-docs' );
	
	add_settings_field( 'raviol-field-15', esc_attr__( '', 'raviol-form' ), 'raviol_field_callback_15', 'raviol-docs', 'raviol-docs-section' );
	register_setting( 'raviol-general-options', 'raviol-setting-15', array('sanitize_callback' => 'sanitize_text_field') );
	
}
add_action( 'admin_init', 'raviol_admin_init' );

// general field callbacks
function raviol_field_callback_22() {
	$placeholder = get_option( 'admin_email' );
	$value = get_option( 'raviol-setting-22' );
	?>
	<input type='text' size='40' name='raviol-setting-22' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php esc_attr_e( 'Default value is the an admin settings "Administration Email Address". Access in console: ', 'raviol-form' ); ?><a href="<?php echo admin_url('options-general.php'); ?>"><?php esc_attr_e( 'Settings -> General', 'raviol-form' ); ?></a><?php esc_attr_e( ' or use custom.', 'raviol-form' ); ?></i></p>
	<?php
}
// general field Access token
function raviol_field_callback_22_2() {
	$placeholder = get_option( 'admin_hubspot_access_token_key' );
	$value = get_option( 'raviol-setting-22-2' );
	?>
	<input type='password' size='40' name='raviol-setting-22-2' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php esc_attr_e( 'If the key is specified, the forms subscribers contacts will be automatically added to your Hubspot account in the "Contacts" section. Get your access token at ', 'raviol-form' ); ?><a target="_blank" href="<?php echo esc_attr('https://hubspot.com/'); ?>"><?php echo esc_html('Hubspot.com.'); ?></a><?php esc_attr_e( ' Check instructions ', 'raviol-form' ); ?><a href="<?php echo admin_url('admin.php?page=raviol&tab=docs_options'); ?>"><?php echo esc_html('here.'); ?></a></i></p>
	<?php
}

function raviol_field_callback_1() {
	$value = get_option( 'raviol-setting' );
	?>
	
<div class="form-check form-switch d-flex align-items-center">
  <input class="form-check-input" name='raviol-setting' type="checkbox" role="switch" id="raviol-setting" <?php checked( esc_attr($value), 'yes' ); ?> value='yes'>
  <label class="form-check-label mb-1" name='raviol-setting' for="raviol-setting"><?php esc_attr_e( 'Keep form submissions and settings.', 'raviol-form' ); ?></label>
</div>
<p class="mb-2"><i><?php esc_attr_e( 'If it is checked, after uninstalling the plugin all the data will remain in the database, including email logs and settings. Otherwise all plugin traces would be erased.', 'raviol-form' ); ?></i></p>
	<?php
}

function raviol_field_callback_27() {
	$upload_dir = wp_upload_dir();
	$placeholder = get_option( 'raviol-setting-27' );
	$value = get_option( 'raviol-setting-27' );
	?>
	<a download="<?php echo esc_html('raviol-data'); ?>" target="_blank" href="<?php echo esc_html($upload_dir['baseurl'] . '/raviol-data.json'); ?>"><?php echo esc_html('raviol-data.json'); ?></a>
	<p><i><?php esc_attr_e( 'JSON file that collects subscribers data. Contain Name and Email.', 'raviol-form' ); ?></i></p>
	<?php
}

// message field callbacks
function raviol_field_callback_15() {
	$placeholder = __( 'Error! Could not send form. This might be a server issue.', 'raviol-form' );
	$value = get_option( 'raviol-setting-15' );
	?>
	<h2><?php echo esc_html__('Documentation', 'raviol-form'); ?></h2>
	<p><i><?php esc_attr_e( 'Check this video on how to connect Hubspot account, for automaticaly adding contacts.', 'raviol-form' ); ?></i></p>
	<?php
}

// display admin options page
function raviol_options_page() {
?>
<div class="wrap">
	<h1><strong><?php esc_attr_e( 'Raviol Form', 'raviol-form' ); ?></strong></h1>
	<?php
	$link_label = __( 'contact me', 'raviol-form' );
	$link_wp = '<a href="https://t.me/kamilionare" target="_blank">'.$link_label.'</a>';
	$short = '<strong>[raviol-contact]</strong>';
	?>
	<p><?php printf( esc_attr__( 'In order to show the form just use the shortcode %s anywhere on the page. For more customizations or support %s.', 'raviol-form' ), $short, $link_wp ); ?></p>
	<?php $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general_options'; ?>
	<h2 class="nav-tab-wrapper">
		<a href="?page=raviol&tab=general_options" class="nav-tab <?php echo esc_attr($active_tab) == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'General', 'raviol-form' ); ?></a>
		<a style="display:none" href="?page=raviol&tab=label_options" class="nav-tab <?php echo esc_attr($active_tab) == 'label_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Labels', 'raviol-form' ); ?></a>
		<a href="?page=raviol&tab=docs_options" class="nav-tab <?php echo esc_attr($active_tab) == 'docs_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Documentation', 'raviol-form' ); ?></a>
	</h2>
	<form action="options.php" method="POST">
		<?php if( $active_tab == 'general_options' ) {
			settings_fields( 'raviol-general-options' );
			do_settings_sections( 'raviol-general' );
			raviol_log_json();
			submit_button();
		} elseif( $active_tab == 'label_options' ) {
			settings_fields( 'raviol-label-options' );
			do_settings_sections( 'raviol-label' );
		} else {
			settings_fields( 'raviol-docs-options' );
			do_settings_sections( 'raviol-docs' );
		} ?>
	</form>
</div>
<?php
}
