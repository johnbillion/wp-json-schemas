<?php

namespace WPJsonSchemas;

$parent = wp_insert_post(
	[
		'post_title' => 'I am a parent',
		'post_type'  => 'post',
	],
	true
);

if ( is_wp_error( $parent ) ) {
	throw new \Exception( 'Failed to create parent post: ' . $parent->get_error_message() );
}

$attachment_with_parent = wp_insert_attachment(
	[
		'post_title' => 'I am an attachment with a parent',
	],
	false,
	$parent,
	true
);

if ( is_wp_error( $attachment_with_parent ) ) {
	throw new \Exception( 'Failed to create attachment with parent: ' . $attachment_with_parent->get_error_message() );
}

$attachment_without_parent = wp_insert_attachment(
	[
		'post_title' => 'I am an attachment without a parent',
	],
	false,
	0,
	true
);

if ( is_wp_error( $attachment_without_parent ) ) {
	throw new \Exception( 'Failed to create attachment without parent: ' . $attachment_without_parent->get_error_message() );
}

$attachments = get_posts( [
	'post_type'      => 'attachment',
	'posts_per_page' => -1,
	'post_status'    => 'any',
	'orderby'        => 'ID',
	'order'          => 'ASC',
] );

save_object_array( $attachments, 'attachment' );

$view_data = get_rest_response( 'GET', '/wp/v2/media', [
	'context' => 'view',
	'per_page' => 100,
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/media', [
	'context' => 'edit',
	'per_page' => 100,
] );

$empty_response = get_rest_response( 'GET', '/wp/v2/media', [
	'search' => '1234567890',
] );

save_rest_array( [
	$view_data,
	$edit_data,
	$empty_response,
], 'media' );
