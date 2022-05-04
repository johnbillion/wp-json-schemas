<?php

namespace WPJsonSchemas;

$roles = $roles = wp_roles()->role_objects;

array_map(
	function( \WP_Role $role ) {
		$args = new \Args\wp_insert_user;
		$args->role = $role->name;
		$args->user_login = sprintf(
			'user-%s',
			$role->name
		);
		$args->user_email = sprintf(
			'user-%s@example.net',
			$role->name
		);
		$args->user_pass = '123';

		$user = wp_insert_user( $args->toArray() );

		if ( is_wp_error( $user ) ) {
			\WP_CLI::error( $user );
		}
	},
	$roles
);

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
