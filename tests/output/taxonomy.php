<?php

namespace WPJsonSchemas;

$taxos = get_taxonomies( [], 'objects' );

save_object_array( $taxos, 'taxonomy' );

$view_data = get_rest_response( 'GET', '/wp/v2/taxonomies', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/taxonomies', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'taxonomies' );
