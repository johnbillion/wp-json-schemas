<?php

namespace WPJsonSchemas;

$tags = get_terms( [
	'taxonomy' => 'post_tag',
	'number'   => 0,
	'orderby'  => 'term_id',
	'order'    => 'ASC',
] );

save_object_array( $tags, 'tag' );

$view_data = get_rest_response( 'GET', '/wp/v2/tags', [
	'context' => 'view',
	'per_page' => 100,
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/tags', [
	'context' => 'edit',
	'per_page' => 100,
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'tags' );
