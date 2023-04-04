<?php

namespace WPJsonSchemas;

use WP_Error;

$errors = [];

$errors[] = new WP_Error(
	'foo',
	'Foo'
);

$errors[] = new WP_Error(
	'foo',
	'Foo',
	'Hello'
);

$errors[] = new WP_Error(
	'foo',
	'Foo',
	[
		'key' => 'value',
	]
);

$errors[] = new WP_Error(
	'foo',
	'Foo',
	null
);

$multi_error = new WP_Error(
	'foo',
	'Foo'
);
$multi_error->add(
	'bar',
	'Bar'
);
$errors[] = $multi_error;

$multi_code_1 = new WP_Error(
	'foo',
	'Foo'
);
$multi_code_2 = new WP_Error(
	'foo',
	'Bar'
);
$multi_code_1->merge_from( $multi_code_2 );
$errors[] = $multi_code_1;

$errors_without_error = [];

$errors_without_error[] = new WP_Error();

$all_errors = array_merge( $errors, $errors_without_error );

save_object_array( $all_errors, 'error' );
save_object_array( $errors, 'error-with-error' );
save_object_array( $errors_without_error, 'error-without-error' );

$post_id = get_posts( [
	'posts_per_page' => 1,
	'post_status' => 'publish',
] )[0]->ID;

$data_route_404 = get_rest_response( 'GET', '/wp/v2/bananas' );
$data_object_404 = get_rest_response( 'GET', '/wp/v2/posts/99999' );
$data_save_400 = get_rest_response( 'POST', "/wp/v2/posts/{$post_id}", [
	'slug' => false,
] );

save_rest_array( [
	$data_route_404,
	$data_object_404,
	$data_save_400,
], 'error' );
