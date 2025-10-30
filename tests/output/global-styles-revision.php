<?php

namespace WPJsonSchemas;

// Create or get a custom global styles post
$theme = wp_get_theme();
$post_id = wp_insert_post( [
	'post_title'   => sprintf( 'Custom Styles (%s)', $theme->get_stylesheet() ),
	'post_content' => wp_json_encode( [
		'version'                      => 2,
		'isGlobalStylesUserThemeJSON'  => true,
		'settings'                     => [],
		'styles'                       => [],
	] ),
	'post_status'  => 'publish',
	'post_type'    => 'wp_global_styles',
	'post_name'    => sprintf( 'wp-global-styles-%s', $theme->get_stylesheet() ),
] );

if ( is_wp_error( $post_id ) ) {
	throw new \Exception( 'Failed to create global styles post: ' . $post_id->get_error_message() );
}

// Update the post to create a revision
$update_result = wp_update_post( [
	'ID'           => $post_id,
	'post_content' => wp_json_encode( [
		'version'                      => 2,
		'isGlobalStylesUserThemeJSON'  => true,
		'settings'                     => [
			'color' => [
				'palette' => [],
			],
		],
		'styles'                       => [],
	] ),
], true );

if ( is_wp_error( $update_result ) ) {
	throw new \Exception( 'Failed to update global styles post: ' . $update_result->get_error_message() );
}

// Fetch the revisions collection via REST API
$data = [];
$data[] = get_rest_response( 'GET', "/wp/v2/global-styles/{$post_id}/revisions", [
	'context' => 'view',
] );
$data[] = get_rest_response( 'GET', "/wp/v2/global-styles/{$post_id}/revisions", [
	'context' => 'edit',
] );

save_rest_array( $data, 'global-styles-revisions' );

// Fetch individual revision
$revisions_list = $data[0]->get_data();

$first_revision = $revisions_list[0];
$revision_id = $first_revision['id'];

$single_data = [];
$single_data[] = get_rest_response( 'GET', "/wp/v2/global-styles/{$post_id}/revisions/{$revision_id}", [
	'context' => 'view',
] );
$single_data[] = get_rest_response( 'GET', "/wp/v2/global-styles/{$post_id}/revisions/{$revision_id}", [
	'context' => 'edit',
] );

save_rest_array( $single_data, 'global-styles-revision', true );
