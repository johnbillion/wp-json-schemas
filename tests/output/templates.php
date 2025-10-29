<?php

namespace WPJsonSchemas;

// Register a plugin template to test the plugin field
register_block_template(
	'wp-json-schemas//test-template',
	[
		'title'       => 'Test Plugin Template',
		'description' => 'A test template registered by a plugin',
		'content'     => '<!-- wp:paragraph --><p>This is a plugin-registered template.</p><!-- /wp:paragraph -->',
	]
);

// Generate REST API responses for templates collection
$view_data = get_rest_response( 'GET', '/wp/v2/templates', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/templates', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'templates' );

// Generate REST API responses for individual templates
$template_data = [];

// Get template IDs from the collection response
$view_response_data = $view_data->get_data();

foreach ( $view_response_data as $template ) {
	$id = $template['id'];

	// Generate REST API responses for individual template
	$template_data[] = get_rest_response( 'GET', "/wp/v2/templates/{$id}", [
		'context' => 'view',
	] );
	$template_data[] = get_rest_response( 'GET', "/wp/v2/templates/{$id}", [
		'context' => 'edit',
	] );
}

// Add lookup endpoint test
$template_data[] = get_rest_response( 'GET', '/wp/v2/templates/lookup', [
	'slug' => 'single',
] );

save_rest_array( $template_data, 'template', true );
