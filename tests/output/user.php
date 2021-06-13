<?php

namespace WPJsonSchemas;

$users = get_users( [
	'number'  => -1,
	'orderby' => 'ID',
	'order'   => 'ASC',
] );

save_object_array( $users, 'user' );

$view_data = get_rest_response( 'GET', '/wp/v2/users', [
	'context' => 'view',
	'per_page' => 100,
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/users', [
	'context' => 'edit',
	'per_page' => 100,
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'users' );
