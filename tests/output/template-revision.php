<?php

namespace WPJsonSchemas;

// Get templates and create a revision by updating one
$templates_data = get_rest_response( 'GET', '/wp/v2/templates', [
	'context' => 'edit',
] );

$templates_list = $templates_data->get_data();

if ( empty( $templates_list ) ) {
	throw new \Exception( 'Failed to fetch templates list for revision test' );
}

$first_template = $templates_list[0];
$template_id = $first_template['id'];

// Update the template to create a revision
$update_response = get_rest_response( 'POST', "/wp/v2/templates/{$template_id}", [
	'content' => $first_template['content']['raw'] . "\n<!-- Test update -->",
] );

if ( $update_response->is_error() ) {
	$error = $update_response->as_error();
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
