<?php

namespace WPJsonSchemas;

$data = [];

$data[] = get_rest_response( 'GET', '/', [
	'context' => 'view',
] );
$data[] = get_rest_response( 'GET', '/', [
	'context' => 'help',
] );

$image = wp_insert_attachment(
	[
		'post_title'     => 'I am a site logo and icon',
		'post_mime_type' => 'image/png',
	],
	false,
	0,
	true
);

if ( is_wp_error( $image ) ) {
	throw new \Exception( 'Failed to create attachment: ' . $image->get_error_message() );
}

$page = wp_insert_post(
	[
		'post_title'  => 'I am the front page',
		'post_type'   => 'page',
		'post_status' => 'publish',
	],
	true
);

if ( is_wp_error( $page ) ) {
	throw new \Exception( 'Failed to create page: ' . $page->get_error_message() );
}

$options = [
	'site_icon'       => $image,
	'page_on_front'   => $page,
	'page_for_posts'  => $page,
	'show_on_front'   => 'page',
	'timezone_string' => 'Europe/London',
];
$original_options = [];

foreach ( $options as $name => $value ) {
	$original_options[ $name ] = get_option( $name );
	update_option( $name, $value );
}

set_theme_mod( 'custom_logo', $image );
add_filter( 'wp_client_side_media_processing_enabled', '__return_true' );

$data[] = get_rest_response( 'GET', '/', [
	'context' => 'view',
] );

remove_filter( 'wp_client_side_media_processing_enabled', '__return_true' );
remove_theme_mod( 'custom_logo' );
// Core syncs the custom logo theme mod to this option and reads it back from there.
delete_option( 'site_logo' );

foreach ( $original_options as $name => $value ) {
	update_option( $name, $value );
}

add_filter( 'wp_is_application_passwords_available', '__return_false' );
wp_set_current_user( 0 );

$data[] = get_rest_response( 'GET', '/', [
	'context' => 'view',
] );

wp_set_current_user( 1 );
remove_filter( 'wp_is_application_passwords_available', '__return_false' );

save_rest_array( $data, 'index', true );
