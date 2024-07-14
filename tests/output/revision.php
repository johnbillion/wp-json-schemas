<?php

namespace WPJsonSchemas;

$endpoints = [
	'page' => 'pages',
	'post' => 'posts',
	'wp_block' => 'blocks',
];
$data = [];

foreach ( $endpoints as $post_type => $slug ) {
	$id = wp_insert_post( [
		'post_type'   => $post_type,
		'post_title'  => 'Title',
		'post_status' => 'publish',
	] );

	wp_update_post( [
		'ID' => $id,
		'post_title' => 'Hello 2',
	] );
	wp_update_post( [
		'ID' => $id,
		'post_title' => 'Hello 3',
	] );

	$data[] = get_rest_response( 'GET', "/wp/v2/{$slug}/{$id}/revisions", [
		'context' => 'view',
		'per_page' => 100,
	] );
	$data[] = get_rest_response( 'GET', "/wp/v2/{$slug}/{$id}/revisions", [
		'context' => 'edit',
		'per_page' => 100,
	] );
}

save_rest_array( $data, 'revisions' );
