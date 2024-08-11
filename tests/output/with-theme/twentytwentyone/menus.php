<?php

namespace WPJsonSchemas;

// Create a nav menu, add some items to it, and give it a location:
$menu_id = wp_create_nav_menu( 'Test Menu' );

wp_update_nav_menu_item( $menu_id, 0, [
	'menu-item-title' => 'Home',
	'menu-item-url' => home_url( '/' ),
	'menu-item-status' => 'publish',
] );
wp_update_nav_menu_item( $menu_id, 0, [
	'menu-item-title' => 'About',
	'menu-item-url' => home_url( '/about/' ),
	'menu-item-status' => 'publish',
] );
wp_update_nav_menu_item( $menu_id, 0, [
	'menu-item-title' => 'Contact',
	'menu-item-url' => home_url( '/contact/' ),
	'menu-item-status' => 'publish',
] );

$locations = get_theme_mod( 'nav_menu_locations' ) ?: [];
$locations['primary'] = $menu_id;
set_theme_mod( 'nav_menu_locations', $locations );

foreach ( [ 'menu-locations', 'menu-items', 'menus' ] as $type ) {
	$view_data = get_rest_response( 'GET', "/wp/v2/{$type}", [
		'context' => 'view',
		'per_page' => 100,
	] );
	$edit_data = get_rest_response( 'GET', "/wp/v2/{$type}", [
		'context' => 'edit',
		'per_page' => 100,
	] );

	save_rest_array( [
		$view_data,
		$edit_data,
	], $type );
}
