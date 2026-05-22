<?php

namespace WPJsonSchemas;

$view_data = get_rest_response( 'GET', '/wp/v2/icons', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/icons', [
	'context' => 'edit',
] );

$empty_response = get_rest_response( 'GET', '/wp/v2/icons', [
	'search' => '1234567890',
] );

save_rest_array( [
	$view_data,
	$edit_data,
	$empty_response,
], 'icons' );
