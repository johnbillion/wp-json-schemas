<?php

namespace WPJsonSchemas;

use WP_Query;

$queries = [];
$queries[] = new WP_Query;
$queries[] = new WP_Query( [
	'post_type' => 'post',
	'posts_per_page' => 1,
] );
$queries[] = new WP_Query( [
	'post_type' => 'post',
	's' => 'Hello',
] );
$queries[] = new WP_Query( [
	'post_type' => 'page',
	'posts_per_page' => -1,
] );
$queries[] = new WP_Query( [
	'post_type' => 'does_not_exist',
	'paged' => 7,
] );

$cats = get_terms( [
	'taxonomy' => 'category',
	'number' => 1,
] );

$cat_query = new WP_Query( [
	'cat' => $cats[0]->term_id,
] );

$queries[] = $cat_query;

save_object_array( $queries, 'query' );
