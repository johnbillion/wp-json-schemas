<?php

namespace WPJsonSchemas;

$view_data = get_rest_response( 'GET', '/wp/v2/settings', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/settings', [
	'context' => 'edit',
] );

update_option( 'site_icon', 123 );

$with_site_icon = get_rest_response( 'GET', '/wp/v2/settings', [
	'context' => 'view',
] );

delete_option( 'site_icon' );

$without_site_icon = get_rest_response( 'GET', '/wp/v2/settings', [
	'context' => 'view',
] );

save_rest_array( [
	$view_data,
	$edit_data,
	$with_site_icon,
	$without_site_icon,
], 'settings' );
