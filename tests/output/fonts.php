<?php

namespace WPJsonSchemas;

// Create a font family:
$family_payload = [
	'name' => 'Chakra Petch',
	'fontFamily' => '"Chakra Petch", sans-serif',
	'slug' => 'chakra-petch',
	'preview' => 'https://s.w.org/images/fonts/17.7/previews/chakra-petch/chakra-petch.svg',
];
$family_response = get_rest_response(
	'POST',
	'/wp/v2/font-families',
	[
		'font_family_settings' => json_encode( $family_payload ),
	]
);

if ( $family_response->is_error() ) {
	throw new \Exception( 'Failed to create font family: ' . $family_response->as_error()->get_error_message() );
}

$family_id = $family_response->get_data()['id'];

// Add a font face to the font family:
$face_payload = [
	'src' => content_url( 'uploads/fonts/example.woff2' ),
	'fontWeight' => '600',
	'fontStyle' => 'normal',
	'fontFamily' => 'Chakra Petch',
	'preview' => 'https://s.w.org/images/fonts/17.7/previews/chakra-petch/chakra-petch-600-normal.svg',
];
$face_response = get_rest_response(
	'POST',
	"/wp/v2/font-families/{$family_id}/font-faces",
	[
		'font_face_settings' => json_encode( $face_payload ),
	]
);
$face_id = $face_response->data['id'];

// Get the font families:
$data = get_rest_response( 'GET', '/wp/v2/font-families' );

save_rest_array( [
	$data,
], 'font-families' );

// Get the faces for the font family:
$data = get_rest_response( 'GET', "/wp/v2/font-families/{$family_id}/font-faces" );

save_rest_array( [
	$data,
], 'font-faces' );

$url = sprintf(
	'https://raw.githubusercontent.com/WordPress/gutenberg/wp/%s/schemas/json/theme.json',
	( 'dev-main' === WP_VERSION ) ? 'next' : WP_VERSION,
);

save_external_schema(
	$url,
	'font-face',
	[
		'definitions',
		'settingsTypographyProperties',
		'properties',
		'typography',
		'properties',
		'fontFamilies',
		'items',
		'properties',
		'fontFace',
		'items',
	]
);
