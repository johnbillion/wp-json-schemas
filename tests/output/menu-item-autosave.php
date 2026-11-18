<?php

namespace WPJsonSchemas;

$menu_id = wp_create_nav_menu( 'Autosave Test Menu' );

if ( is_wp_error( $menu_id ) ) {
	throw new \Exception( 'Failed to create menu for autosaves: ' . $menu_id->get_error_message() );
}

$item_id = wp_update_nav_menu_item( $menu_id, 0, [
	'menu-item-title'  => 'Title',
	'menu-item-url'    => 'https://example.org/',
	'menu-item-status' => 'publish',
] );

if ( is_wp_error( $item_id ) ) {
	throw new \Exception( 'Failed to create menu item for autosaves: ' . $item_id->get_error_message() );
}

$autosave = get_rest_response( 'POST', "/wp/v2/menu-items/{$item_id}/autosaves", [
	'title' => 'Autosaved title',
	'menus' => $menu_id,
] );

if ( $autosave->is_error() ) {
	throw new \Exception( 'Failed to create menu item autosave: ' . $autosave->as_error()->get_error_message() );
}

$data = [];

foreach ( [ 'view', 'edit' ] as $context ) {
	$response = get_rest_response( 'GET', "/wp/v2/menu-items/{$item_id}/autosaves", [
		'context' => $context,
	] );

	if ( empty( $response->get_data() ) ) {
		throw new \Exception( "No menu item autosaves found in {$context} context." );
	}

	$data[] = $response;
}

save_rest_array( $data, 'menu-item-autosaves' );
