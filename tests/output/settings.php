<?php

namespace WPJsonSchemas;

$view_data = get_rest_response( 'GET', '/wp/v2/settings', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/settings', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'settings' );
