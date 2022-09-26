<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// json log
$args = array(
	'post_type' => 'submission',
	'post_status' => array('publish', 'pending', 'draft'),
	'posts_per_page' => -1,
);
$query = new WP_Query( $args );
$posts = array();
while( $query->have_posts() ) : $query->the_post();


$posts[] = array(
	'name' => get_post_meta( get_the_ID(), 'name_sub' )[0],
	'email' => get_post_meta( get_the_ID(), 'email_sub' )[0],
);
endwhile;
wp_reset_query();
$data = json_encode($posts);
$upload_dir = wp_upload_dir();
$folder = $upload_dir['basedir'];
$file_name = '/raviol-data.json';
file_put_contents($folder.$file_name, $data);