<?php

namespace WPJsonSchemas;

$categories = get_terms( [
	'taxonomy' => [
		'category',
		'post_tag',
	],
	'number'   => 0,
	'orderby'  => 'term_id',
	'order'    => 'ASC',
] );

save_object_array( $categories, 'term' );

$category_view_data = get_rest_response( 'GET', '/wp/v2/categories', [
	'context' => 'view',
	'per_page' => 100,
] );
$category_edit_data = get_rest_response( 'GET', '/wp/v2/categories', [
	'context' => 'edit',
	'per_page' => 100,
] );

$tag_view_data = get_rest_response( 'GET', '/wp/v2/tags', [
	'context' => 'view',
	'per_page' => 100,
] );
$tag_edit_data = get_rest_response( 'GET', '/wp/v2/tags', [
	'context' => 'edit',
	'per_page' => 100,
] );

save_rest_array( [
	$category_view_data,
	$category_edit_data,
], 'tags' );

save_rest_array( [
	$category_view_data,
	$category_edit_data,
], 'categories' );
