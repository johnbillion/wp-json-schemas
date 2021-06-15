<?php

namespace WPJsonSchemas;

$id = wp_insert_post( [
	'post_type'   => 'post',
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

$view_data = get_rest_response( 'GET', "/wp/v2/posts/{$id}/revisions", [
	'context' => 'view',
	'per_page' => 100,
] );
$edit_data = get_rest_response( 'GET', "/wp/v2/posts/{$id}/revisions", [
	'context' => 'edit',
	'per_page' => 100,
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'revisions' );
