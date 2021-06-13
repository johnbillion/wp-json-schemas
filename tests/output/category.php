<?php

namespace WPJsonSchemas;

$categories = get_terms( [
	'taxonomy' => 'category',
	'number'   => 0,
	'orderby'  => 'term_id',
	'order'    => 'ASC',
] );

save_object_array( $categories, 'category' );

$view_data = get_rest_response( 'GET', '/wp/v2/categories', [
	'context' => 'view',
	'per_page' => 100,
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/categories', [
	'context' => 'edit',
	'per_page' => 100,
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'categories' );
