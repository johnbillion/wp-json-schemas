<?php

namespace WPJsonSchemas;

// Create a custom template part by modifying an existing one
// This ensures we have a wp_id (database record) for revisions to work
$template_parts_data = get_rest_response( 'GET', '/wp/v2/template-parts', [
	'context' => 'edit',
] );

$template_parts_list = $template_parts_data->get_data();

if ( empty( $template_parts_list ) ) {
	throw new \Exception( 'Failed to fetch template parts list for revision test' );
}

// Find a template-part with area header or footer
$test_template_part = null;
foreach ( $template_parts_list as $tp ) {
	if ( isset( $tp['area'] ) && in_array( $tp['area'], ['header', 'footer'], true ) ) {
		$test_template_part = $tp;
		break;
	}
}

if ( ! $test_template_part ) {
	$test_template_part = $template_parts_list[0];
}

$template_part_id = $test_template_part['id'];

// First update to create a custom version (this may not create a revision if wp_id is 0)
$first_update = get_rest_response( 'POST', "/wp/v2/template-parts/{$template_part_id}", [
	'content' => $test_template_part['content']['raw'] . "\n<!-- First update -->",
] );

if ( $first_update->is_error() ) {
	$error = $first_update->as_error();
	throw new \Exception( 'Failed to create custom template part: ' . $error->get_error_message() );
}

$updated_data = $first_update->get_data();

// Second update to create a revision
$second_update = get_rest_response( 'POST', "/wp/v2/template-parts/{$template_part_id}", [
	'content' => $updated_data['content']['raw'] . "\n<!-- Second update for revision -->",
] );

if ( $second_update->is_error() ) {
	$error = $second_update->as_error();
	throw new \Exception( 'Failed to update template part: ' . $error->get_error_message() );
}

// Fetch the revisions
$data = [];
$data[] = get_rest_response( 'GET', "/wp/v2/template-parts/{$template_part_id}/revisions", ['context' => 'view'] );
$data[] = get_rest_response( 'GET', "/wp/v2/template-parts/{$template_part_id}/revisions", ['context' => 'edit'] );

save_rest_array( $data, 'template-part-revisions' );
