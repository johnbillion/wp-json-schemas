<?php

namespace WPJsonSchemas;

$endpoints = [
	'page' => 'pages',
	'post' => 'posts',
	'wp_block' => 'blocks',
	'wp_navigation' => 'navigation',
];
$data = [];

foreach ( $endpoints as $post_type => $slug ) {
	$id = wp_insert_post( [
		'post_type'   => $post_type,
		'post_title'  => 'Title',
		'post_content' => '<!-- wp:paragraph --><p>Content</p><!-- /wp:paragraph -->',
		'post_status' => 'publish',
	], true );

	if ( is_wp_error( $id ) ) {
		throw new \Exception( "Failed to create {$post_type} post for revisions: " . $id->get_error_message() );
	}

	$update_result = wp_update_post( [
		'ID' => $id,
		'post_title' => 'Hello 2',
	], true );

	if ( is_wp_error( $update_result ) ) {
		throw new \Exception( "Failed to update {$post_type} post (revision 2): " . $update_result->get_error_message() );
	}

	$update_result = wp_update_post( [
		'ID' => $id,
		'post_title' => 'Hello 3',
	], true );

	if ( is_wp_error( $update_result ) ) {
		throw new \Exception( "Failed to update {$post_type} post (revision 3): " . $update_result->get_error_message() );
	}

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
