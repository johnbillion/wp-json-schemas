<?php

namespace WPJsonSchemas;

// Register additional test sidebars to exercise the API more thoroughly
register_sidebar([
	'id'            => 'test-sidebar-1',
	'name'          => 'Test Sidebar 1',
	'description'   => 'A test sidebar for comprehensive schema validation',
	'class'         => 'test-sidebar-class',
	'before_widget' => '<div class="widget %1$s %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h3 class="widget-title">',
	'after_title'   => '</h3>',
	'show_in_rest'  => true,
]);

register_sidebar([
	'id'            => 'test-sidebar-2',
	'name'          => 'Test Sidebar 2',
	'description'   => 'Another test sidebar with different HTML wrapper elements',
	'class'         => 'sidebar-secondary',
	'before_widget' => '<section id="%1$s" class="widget %2$s">',
	'after_widget'  => '</section>',
	'before_title'  => '<h4 class="sidebar-title">',
	'after_title'   => '</h4>',
	'show_in_rest'  => true,
]);

register_sidebar([
	'id'            => 'test-sidebar-3',
	'name'          => 'Test Sidebar 3',
	'description'   => 'A sidebar with minimal configuration',
	'show_in_rest'  => true,
]);

// Create some test widgets to populate the sidebars
$test_widgets = [
	'text-1' => [
		'widget_id' => 'text-1',
		'id_base'   => 'text',
		'sidebar'   => 'test-sidebar-1',
	],
	'text-2' => [
		'widget_id' => 'text-2',
		'id_base'   => 'text',
		'sidebar'   => 'test-sidebar-1',
	],
	'categories-1' => [
		'widget_id' => 'categories-1',
		'id_base'   => 'categories',
		'sidebar'   => 'test-sidebar-2',
	],
	'search-1' => [
		'widget_id' => 'search-1',
		'id_base'   => 'search',
		'sidebar'   => 'test-sidebar-2',
	],
	'archives-1' => [
		'widget_id' => 'archives-1',
		'id_base'   => 'archives',
		'sidebar'   => 'test-sidebar-3',
	],
];

// Set up sidebar widgets
$sidebars_widgets = wp_get_sidebars_widgets();
$sidebars_widgets['test-sidebar-1'] = ['text-1', 'text-2'];
$sidebars_widgets['test-sidebar-2'] = ['categories-1', 'search-1'];
$sidebars_widgets['test-sidebar-3'] = ['archives-1'];

// Add some widgets to the inactive widgets area as well
if ( ! isset( $sidebars_widgets['wp_inactive_widgets'] ) ) {
	$sidebars_widgets['wp_inactive_widgets'] = [];
}
$sidebars_widgets['wp_inactive_widgets'] = array_merge(
	$sidebars_widgets['wp_inactive_widgets'],
	['text-3', 'meta-1']
);

wp_set_sidebars_widgets( $sidebars_widgets );

// Generate REST API responses for sidebars collection
$view_data = get_rest_response( 'GET', '/wp/v2/sidebars', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/sidebars', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'sidebars' );

// Generate REST API responses for individual sidebars
$sidebar_data = [];

// Get sidebar IDs from the collection response
$view_response_data = $view_data->get_data();

foreach ( $view_response_data as $sidebar ) {
	$sidebar_id = $sidebar['id'];

	// Generate REST API responses for individual sidebar
	$sidebar_data[] = get_rest_response( 'GET', "/wp/v2/sidebars/{$sidebar_id}", [
		'context' => 'view',
	] );
	$sidebar_data[] = get_rest_response( 'GET', "/wp/v2/sidebars/{$sidebar_id}", [
		'context' => 'edit',
	] );
}

save_rest_array( $sidebar_data, 'sidebar', true );
