<?php

namespace WPJsonSchemas;

use \WP_Screen;

$tools_with_parent = WP_Screen::get( 'tools.php' );
$tools_with_parent->set_parentage( 'tools.php?page=my_plugin_page' );

$post_with_parent = WP_Screen::get( 'edit.php' );
$post_with_parent->set_parentage( 'edit.php?post_type=page' );

$screens = [
	WP_Screen::get( 'ajax' ),
	WP_Screen::get( 'customize' ),
	WP_Screen::get( 'dashboard-network' ),
	WP_Screen::get( 'dashboard-user' ),
	WP_Screen::get( 'dashboard' ),
	WP_Screen::get( 'edit-page' ),
	WP_Screen::get( 'edit-post' ),
	WP_Screen::get( 'edit.php' ),
	WP_Screen::get( 'edit' ),
	WP_Screen::get( 'front' ),
	WP_Screen::get( 'nav-menu.php' ),
	WP_Screen::get( 'tools.php' ),
	WP_Screen::get( 'widgets.php' ),
	$tools_with_parent,
	$post_with_parent,
];

save_object_array( $screens, 'screen' );
