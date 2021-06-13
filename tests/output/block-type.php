<?php

namespace WPJsonSchemas;

$view_data = get_rest_response( 'GET', '/wp/v2/block-types', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/block-types', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'block-types' );
