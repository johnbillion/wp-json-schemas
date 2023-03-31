<?php

namespace WPJsonSchemas;

wp_insert_post( [
	'post_type'   => 'post',
	'post_title'  => 'Title',
	'post_status' => 'publish',
] );

wp_insert_post( [
	'post_type'   => 'page',
	'post_title'  => 'Title',
	'post_status' => 'draft',
] );

wp_insert_post( [
	'post_type'    => 'wp_block',
	'post_title'   => 'Title',
	'post_content' => '<!-- wp:paragraph --><p>Hello</p><!-- /wp:paragraph -->',
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

foreach ( [ 'posts', 'pages', 'blocks' ] as $type ) {
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
