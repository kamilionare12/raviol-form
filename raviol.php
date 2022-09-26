<?php
/*
 * Plugin Name: Raviol Form
 * Description: Lightweight form with fields: First Name, Last Name, Subject, Message, E-mail and automated hubspot.com integration with "Private app" API. In order to show the form just use the shortcode [raviol-contact] anywhere on the page
 * Version: 1.0
 * Author: Kamilionare
 * Author URI: https://t.me/kamilionare
 * License: GNU General Public License v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: raviol-form
 * Domain Path: /translation
 */

// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// load plugin text domain
function raviol_init() {
	load_plugin_textdomain( 'raviol-form', false, dirname( plugin_basename( __FILE__ ) ) . '/translation' );
}
add_action( 'plugins_loaded', 'raviol_init' );

register_activation_hook( __FILE__, 'activateLogs' );
register_deactivation_hook( __FILE__, 'deactivatePlugin' );

// enqueue plugin scripts
function raviol_scripts() {
	wp_enqueue_style('raviol_style', plugins_url('/assets/css/raviol-style.css',__FILE__));
	wp_enqueue_script('raviol_anchor_script', plugins_url( '/assets/js/raviol-script.js' , __FILE__ ), '', '', true);
	//wp_enqueue_script('raviol_validation_script', plugins_url( '/assets/js/jquery.validate.min.js' , __FILE__ ), '', '', true);
	
	// enqueue editor script (formated message field)
    wp_enqueue_editor();
    $theme_version = wp_get_theme()->get('Version');
    wp_register_script(
        'variations-editor',
        plugins_url( 'assets/js/raviol-editor.js', __FILE__ ),
        array('jquery', 'quicktags'),
        $theme_version,
        true
    );
    wp_enqueue_script('variations-editor');
}
add_action( 'wp_enqueue_scripts', 'raviol_scripts' );

// admin styles
function raviol_admin_style() {
    wp_enqueue_style( 'raviol-admin-style', plugins_url('assets/css/raviol-admin-style.css',__FILE__), array(), '1.0' );
}

// save submissions in custom post type
function raviol_custom_postype() {
	$raviol_args = array(
		//'labels' => array('name' => __( 'Mail logs', 'raviol-form' )),
		'menu_icon' => 'dashicons-email',
		'public' => false,
		'can_export' => true,
		'show_in_nav_menus' => false,
		'show_ui' => true,
		'show_in_rest' => true,
		'capability_type' => 'post',
		'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap' => true,
		'supports' => array( 'title', 'editor' )
	);
	register_post_type( 'submission', $raviol_args );
}
add_action( 'init', 'raviol_custom_postype' );

// dashboard submission columns (will show later)
function raviol_custom_columns( $columns ) {
	$columns['name_column'] = __( 'Name', 'raviol-form' );
	$columns['email_column'] = __( 'Email', 'raviol-form' );
	$custom_order = array('cb', 'title', 'name_column', 'email_column', 'date');
	foreach ($custom_order as $colname) {
		$new[$colname] = $columns[$colname];
	}
	return $new;
}
add_filter( 'manage_submission_posts_columns', 'raviol_custom_columns', 10 );

function raviol_custom_columns_content( $column_name, $post_id ) {
	if ( 'name_column' == $column_name ) {
		$name = get_post_meta( $post_id, 'name_sub', true );
		echo esc_attr($name);
	}
	if ( 'email_column' == $column_name ) {
		$email = get_post_meta( $post_id, 'email_sub', true );
		echo esc_attr($email);
	}
}
add_action( 'manage_submission_posts_custom_column', 'raviol_custom_columns_content', 10, 2 );

// make name and email column sortable
function raviol_column_register_sortable( $columns ) {
	$columns['name_column'] = 'name_sub';
	$columns['email_column'] = 'email_sub';
	return $columns;
}
add_filter( 'manage_edit-submission_sortable_columns', 'raviol_column_register_sortable' );

function raviol_name_column_orderby( $vars ) {
	if(is_admin()) {
		if ( isset( $vars['orderby'] ) && 'name_sub' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'name_sub',
				'orderby' => 'meta_value'
			) );
		}
	}
	return $vars;
}
add_filter( 'request', 'raviol_name_column_orderby' );

function raviol_email_column_orderby( $vars ) {
	if(is_admin()) {
		if ( isset( $vars['orderby'] ) && 'email_sub' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'email_sub',
				'orderby' => 'meta_value'
			) );
		}
	}
	return $vars;
}
add_filter( 'request', 'raviol_email_column_orderby' );

// add settings link
function raviol_action_links( $links ) {
	$settingslink = array( '<a href="'. admin_url( 'options-general.php?page=raviol' ) .'">'.__('Settings', 'raviol-form').'</a>' );
	return array_merge( $links, $settingslink );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'raviol_action_links' );

// get ip of user
function raviol_get_the_ip() {
	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
		$ip_address = $_SERVER["HTTP_CLIENT_IP"];
	} else {
		$ip_address = $_SERVER["REMOTE_ADDR"];
	}
	return esc_attr($ip_address);
}

// create from email header
function raviol_from_header() {
	$server = esc_url_raw($_SERVER['SERVER_NAME']);
	if ( (substr($server, 0, 4) == "http") || (substr($server, 0, 3) == "www") ) {
		$replace = array("http://" => "", "https://" => "", "www." => "");
		$domain = strtr($server, $replace);
		return esc_attr('wordpress@'.$domain);
	}
}

// redirect when sending succeeds
function raviol_redirect_success() {
	$current_url = esc_url_raw($_SERVER['REQUEST_URI']);
	if (strpos($current_url, '?') == true) {
		$url_with_param = $current_url."&raviol-sh=success";
	} else {
		if (substr($current_url, -1) == '/') {
			$url_with_param = $current_url."?raviol-sh=success";
		} else {
			$url_with_param = $current_url."/?raviol-sh=success";
		}
	}
	return esc_url_raw($url_with_param);
}

// redirect when sending fails
function raviol_redirect_error() {
	$current_url = esc_url_raw($_SERVER['REQUEST_URI']);
	if (strpos($current_url, '?') == true) {
		$url_with_param = $current_url."&raviol-sh=fail";
	} else {
		if (substr($current_url, -1) == '/') {
			$url_with_param = $current_url."?raviol-sh=fail";
		} else {
			$url_with_param = $current_url."/?raviol-sh=fail";
		}
	}
	return esc_url_raw($url_with_param);
}

// json log
function raviol_log_json () {
	include 'raviol-logs.php';
}
function activateLogs() {
	raviol_log_json ();
	flush_rewrite_rules(); 
}

// clean data
function raviol_clean_data () {
	include 'uninstall.php';
}
function deactivatePlugin() {
	$keep = get_option( 'raviol-setting' );
	if ( $keep != 'yes' ) {
		global $wpdb;

		// delete options
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'raviol-setting%'" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name = 'widget_raviol%'" );

		// delete submissions
		$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'submission'" );
		//flush_rewrite_rules(); 
	}
}

// include files
include( plugin_dir_path( __FILE__ ) . 'raviol-shortcodes.php');
include( plugin_dir_path( __FILE__ ) . 'raviol-options.php');