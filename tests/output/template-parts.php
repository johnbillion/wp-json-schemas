<?php

namespace WPJsonSchemas;

// First, update a template-part to ensure we have test coverage for modified date strings
$initial_data = get_rest_response( 'GET', '/wp/v2/template-parts', [
	'context' => 'edit',
] );

$initial_list = $initial_data->get_data();

if ( ! empty( $initial_list ) ) {
	$first_template_part = $initial_list[0];
	$template_part_id = $first_template_part['id'];

	// Update the first template-part to trigger a modified date
	$update_response = get_rest_response( 'POST', "/wp/v2/template-parts/{$template_part_id}", [
		'content' => $first_template_part['content']['raw'] . "\n<!-- Test update -->",
	] );

	if ( $update_response->is_error() ) {
		$error = $update_response->as_error();
		throw new \Exception( 'Failed to update template-part: ' . $error->get_error_message() );
	}
}

// Generate REST API responses for template-parts collection
$view_data = get_rest_response( 'GET', '/wp/v2/template-parts', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/template-parts', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'template-parts' );

// Generate REST API responses for individual template parts
$template_part_data = [];

// Get template part IDs and slugs from the collection response
$view_response_data = $view_data->get_data();

foreach ( $view_response_data as $template_part ) {
	$id = $template_part['id'];
	$slug = $template_part['slug'];

	// Generate REST API responses for individual template part
	$template_part_data[] = get_rest_response( 'GET', "/wp/v2/template-parts/{$id}", [
		'context' => 'view',
	] );
	$template_part_data[] = get_rest_response( 'GET', "/wp/v2/template-parts/{$id}", [
		'context' => 'edit',
	] );

	// Generate REST API response for template part lookup (fallback by slug)
	$template_part_data[] = get_rest_response( 'GET', '/wp/v2/template-parts/lookup', [
		'slug' => $slug,
	] );
}

save_rest_array( $template_part_data, 'template-part', true );
