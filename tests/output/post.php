<?php

namespace WPJsonSchemas;

$parent_post = wp_insert_post( [
	'post_type'   => 'post',
	'post_title'  => 'Post Title',
	'post_status' => 'publish',
], true );

if ( is_wp_error( $parent_post ) ) {
	throw new \Exception( $parent_post->get_error_message() );
}

$attachment_id = wp_insert_attachment(
	[
		'post_title' => 'I am an attachment with a parent',
	],
	false,
	$parent_post,
	true,
);

if ( is_wp_error( $attachment_id ) ) {
	throw new \Exception( $attachment_id->get_error_message() );
}

$thumb = update_post_meta( $parent_post, '_thumbnail_id', $attachment_id );

if ( ! $thumb ) {
	throw new \Exception( "Failed to set thumbnail for post {$parent_post}" );
}

$parent_page = wp_insert_post( [
	'post_type'   => 'page',
	'post_title'  => 'Parent Title',
	'post_status' => 'draft',
] );

wp_insert_post( [
	'post_type'   => 'page',
	'post_title'  => 'Child Title',
	'post_status' => 'publish',
	'post_parent' => $parent_page,
] );

wp_insert_post( [
	'post_type'    => 'wp_block',
	'post_title'   => 'Block Title',
	'post_content' => '<!-- wp:paragraph --><p>Hello</p><!-- /wp:paragraph -->',
	'post_status'  => 'publish',
] );

wp_insert_post( [
	'post_type'    => 'wp_navigation',
	'post_title'   => 'Navigation Title',
	'post_content' => '<!-- wp:navigation-link {"label":"Title","type":"page","id":123,"url":"/title/","kind":"post-type"} /-->',
	'post_status'  => 'publish',
] );

$posts = get_posts( [
	'posts_per_page' => -1,
	'post_status'    => 'any',
	'post_type'      => \get_post_types(),
	'orderby'        => 'ID',
	'order'          => 'ASC',
] );

save_object_array( $posts, 'post' );

foreach ( [ 'posts', 'pages', 'blocks', 'navigation' ] as $type ) {
	$view_data = get_rest_response( 'GET', "/wp/v2/{$type}", [
		'context' => 'view',
		'per_page' => 100,
	] );
	$edit_data = get_rest_response( 'GET', "/wp/v2/{$type}", [
		'context' => 'edit',
		'per_page' => 100,
	] );

	$empty_response = get_rest_response( 'GET', "/wp/v2/{$type}", [
		'search' => '1234567890',
	] );

	save_rest_array( [
		$view_data,
		$edit_data,
		$empty_response,
	], $type );
}
