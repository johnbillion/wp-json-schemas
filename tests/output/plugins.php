<?php

namespace WPJsonSchemas;

$edit_data = get_rest_response( 'GET', '/wp/v2/plugins', [
	'context' => 'edit',
] );
$view_data = get_rest_response( 'GET', '/wp/v2/plugins', [
	'context' => 'view',
] );

save_rest_array( [
	$edit_data,
	$view_data,
], 'plugins' );
