<?php

namespace WPJsonSchemas;

// Register a plugin template to test the plugin field in revisions
register_block_template(
	'wp-json-schemas//revision-test',
	[
		'title'       => 'Revision Test Template',
		'description' => 'A template for testing revisions with plugin field',
		'content'     => '<!-- wp:paragraph --><p>Initial content.</p><!-- /wp:paragraph -->',
	]
);

// Get templates and find the plugin-registered one
$templates_data = get_rest_response( 'GET', '/wp/v2/templates', [
	'context' => 'edit',
] );

$templates_list = $templates_data->get_data();

if ( empty( $templates_list ) ) {
	throw new \Exception( 'Failed to fetch templates list for revision test' );
}

// Find the plugin-registered template
$plugin_template = null;
foreach ( $templates_list as $template ) {
	if ( isset( $template['plugin'] ) && $template['plugin'] === 'wp-json-schemas' ) {
		$plugin_template = $template;
		break;
	}
}

// Fallback to first template if no plugin template found
$test_template = $plugin_template ?? $templates_list[0];
$template_id = $test_template['id'];

// First update to ensure wp_id exists
$first_update = get_rest_response( 'POST', "/wp/v2/templates/{$template_id}", [
	'content' => $test_template['content']['raw'] . "\n<!-- First update -->",
] );

if ( $first_update->is_error() ) {
	$error = $first_update->as_error();
	throw new \Exception( 'Failed to create custom template: ' . $error->get_error_message() );
}

$updated_data = $first_update->get_data();

// Second update to create a revision
$second_update = get_rest_response( 'POST', "/wp/v2/templates/{$template_id}", [
	'content' => $updated_data['content']['raw'] . "\n<!-- Second update for revision -->",
] );

if ( $second_update->is_error() ) {
	$error = $second_update->as_error();
	throw new \Exception( 'Failed to update template: ' . $error->get_error_message() );
}

// Fetch the revisions
$data = [];
$data[] = get_rest_response( 'GET', "/wp/v2/templates/{$template_id}/revisions", [
	'context' => 'view',
] );
$data[] = get_rest_response( 'GET', "/wp/v2/templates/{$template_id}/revisions", [
	'context' => 'edit',
] );

save_rest_array( $data, 'template-revisions' );
