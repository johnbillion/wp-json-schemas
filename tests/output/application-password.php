<?php

namespace WPJsonSchemas;

$user = wp_get_current_user();
$args = [
	'name' => 'Hello',
];
$created = \WP_Application_Passwords::create_new_application_password( $user->ID, wp_slash( $args ) );

$view_data = get_rest_response( 'GET', '/wp/v2/users/me/application-passwords', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/users/me/application-passwords', [
	'context' => 'edit',
] );
save_rest_array( [
	$view_data,
	$edit_data,
], 'application-passwords' );
