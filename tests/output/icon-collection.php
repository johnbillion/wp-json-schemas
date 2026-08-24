<?php

namespace WPJsonSchemas;

$view_data = get_rest_response( 'GET', '/wp/v2/icon-collections', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/icon-collections', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'icon-collections' );

$collection_data = [];

foreach ( $view_data->get_data() as $collection ) {
	$slug = $collection['slug'];

	$collection_data[] = get_rest_response( 'GET', "/wp/v2/icon-collections/{$slug}", [
		'context' => 'view',
	] );
	$collection_data[] = get_rest_response( 'GET', "/wp/v2/icon-collections/{$slug}", [
		'context' => 'edit',
	] );
}

save_rest_array( $collection_data, 'icon-collection', true );
