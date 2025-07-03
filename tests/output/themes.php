<?php

namespace WPJsonSchemas;

// Generate REST API responses for themes collection
$view_data = get_rest_response( 'GET', '/wp/v2/themes', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/themes', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'themes' );

// Generate REST API responses for individual themes
$theme_data = [];

// Get theme stylesheets from the collection response
$view_response_data = $view_data->get_data();
if ( is_array( $view_response_data ) ) {
	foreach ( $view_response_data as $theme ) {
		if ( isset( $theme['stylesheet'] ) ) {
			$stylesheet = $theme['stylesheet'];
			
			// Generate REST API responses for individual theme
			$theme_data[] = get_rest_response( 'GET', "/wp/v2/themes/{$stylesheet}", [
				'context' => 'view',
			] );
			$theme_data[] = get_rest_response( 'GET', "/wp/v2/themes/{$stylesheet}", [
				'context' => 'edit',
			] );
		}
	}
}

if ( ! empty( $theme_data ) ) {
	save_rest_array( $theme_data, 'theme', true );
}