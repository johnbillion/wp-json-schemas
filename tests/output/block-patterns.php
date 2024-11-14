<?php

namespace WPJsonSchemas;

$data = get_rest_response( 'GET', '/wp/v2/block-patterns/categories' );

save_rest_array( [
	$data,
], 'block-pattern-categories' );

$data = get_rest_response( 'GET', '/wp/v2/block-patterns/patterns' );

save_rest_array( [
	$data,
], 'block-patterns' );

$data = get_rest_response( 'GET', '/wp/v2/pattern-directory/patterns' );

save_rest_array( [
	$data,
], 'pattern-directory-patterns' );
