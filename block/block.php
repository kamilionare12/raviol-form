<?php
/**
 * Display a plugin form.
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PTQBLOCK_VERSION', '1.0.3' );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';

	$ptqblock_dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
	$ptqblock_dotenv->safeLoad();
	$ptqblock_current_env = isset( $_ENV['WP_BLOCK_ENV'] ) ? $_ENV['WP_BLOCK_ENV'] : '';
}

/**
 * Enqueue LiveReload for development purpose.
 *
 * @since 0.1.0
 */
function ptqblock_enqueue_livereload() {
	wp_enqueue_script( 'livereload', 'https://localhost:35729/livereload.js', array(), PTQBLOCK_VERSION, true );
}

if ( isset( $ptqblock_current_env ) && 'development' === $ptqblock_current_env ) {
	add_action( 'wp_enqueue_scripts', 'ptqblock_enqueue_livereload' );
	add_action( 'admin_enqueue_scripts', 'ptqblock_enqueue_livereload' );
}

/**
 *
 * @since 0.1.0
 *
 * @param array $attributes The block attributes.
 * @return string Returns the post content with block added.
 */
function ptqblock_render_post_types_block( $attributes ) {
    ob_start();
    echo do_shortcode('[raviol-contact]');
    return ob_get_clean();	
}
/**
 * Register the block using the metadata loaded from `block.json` file.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/
 * @since 0.1.0
 */
function ptqblock_block_init() {
	$asset_file = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

	wp_register_script(
		'ptqblock-i18n',
		plugins_url( 'build/index.js', __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	register_block_type_from_metadata(
		__DIR__,
		array(
			'title'           => __( 'Raviol form', 'PTQBlock' ),
			'description'     => __( 'Display a plugin form.', 'PTQBlock' ),
			'editor_script'   => 'ptqblock-i18n',
			'render_callback' => 'ptqblock_render_post_types_block',
		)
	);
}
add_action( 'init', 'ptqblock_block_init' );

/**
 * Load text domain files
 *
 * @since 0.1.0
 */
function ptqblock_load_textdomain() {
	load_plugin_textdomain( 'PTQBlock', false, plugin_dir_path( __FILE__ ) . 'languages' );
}
add_action( 'plugins_loaded', 'ptqblock_load_textdomain' );

/**
 * Enqueue admin translation scripts
 *
 * @since 0.1.0
 */
function ptqblock_enqueue_admin_scripts() {
	wp_set_script_translations(
		'ptqblock-i18n',
		'PTQBlock',
		plugin_dir_path( __FILE__ ) . 'languages'
	);
}
add_action( 'admin_enqueue_scripts', 'ptqblock_enqueue_admin_scripts' );
