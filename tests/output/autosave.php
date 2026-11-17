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
	// Must be published, otherwise an autosave by the author updates the draft instead of creating an autosave.
	$id = wp_insert_post( [
		'post_type'    => $post_type,
		'post_title'   => 'Title',
		'post_content' => '<!-- wp:paragraph --><p>Content</p><!-- /wp:paragraph -->',
		'post_status'  => 'publish',
	], true );

	if ( is_wp_error( $id ) ) {
		throw new \Exception( "Failed to create {$post_type} post for autosaves: " . $id->get_error_message() );
	}

	$autosave = get_rest_response( 'POST', "/wp/v2/{$slug}/{$id}/autosaves", [
		'title'   => 'Autosaved title',
		'content' => '<!-- wp:paragraph --><p>Autosaved content</p><!-- /wp:paragraph -->',
		'excerpt' => 'Autosaved excerpt',
	] );

	if ( $autosave->is_error() ) {
		throw new \Exception( "Failed to create {$post_type} autosave: " . $autosave->as_error()->get_error_message() );
	}

	foreach ( [ 'view', 'edit' ] as $context ) {
		$response = get_rest_response( 'GET', "/wp/v2/{$slug}/{$id}/autosaves", [
			'context' => $context,
		] );

		if ( empty( $response->get_data() ) ) {
			throw new \Exception( "No {$post_type} autosaves found in {$context} context." );
		}

		$data[] = $response;
	}
}

save_rest_array( $data, 'autosaves' );
