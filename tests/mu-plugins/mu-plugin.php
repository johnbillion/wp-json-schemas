<?php

namespace WPJsonSchemas;

use WP_CLI;
use WP_Error;
use WP_REST_Request;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

function save( array $data, string $dir ) : void {
	$dir = dirname( ABSPATH ) . '/data/' . $dir;

	if ( ! file_exists( $dir ) ) {
		mkdir( $dir, 0777, true );
	}

	foreach ( $data as $i => $item ) {
		$json = json_encode( $item, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES );

		file_put_contents( $dir . '/' . $i . '.json', $json );
	}
}

function get_rest_response( string $method, string $path, array $args = [] ) {
	$request = new WP_REST_Request( $method, $path );
	$request->set_query_params( $args );

	return rest_get_server()->response_to_data( rest_do_request( $request ), false );
}

/**
 * Test data for posts.
 */
WP_CLI::add_command( 'json-dump post', function() : void {
	wp_insert_post( [
		'post_type'   => 'post',
		'post_title'  => 'Title',
		'post_status' => 'publish',
	] );

	$posts = get_posts( [
		'posts_per_page' => -1,
		'orderby'        => 'ID',
		'order'          => 'ASC',
	] );

	save( $posts, 'post' );

	$data = get_rest_response( 'GET', '/wp/v2/posts', [
		'per_page' => 100,
	] );

	save( $data, 'rest-api/post' );
} );

/**
 * Test data for users.
 */
WP_CLI::add_command( 'json-dump user', function() : void {
	$users = get_users( [
		'number'  => -1,
		'orderby' => 'ID',
		'order'   => 'ASC',
	] );

	save( $users, 'user' );
} );

/**
 * Test data for tags.
 */
WP_CLI::add_command( 'json-dump tag', function() : void {
	$tags = get_terms( [
		'taxonomy' => 'post_tag',
		'number'   => 0,
		'orderby'  => 'term_id',
		'order'    => 'ASC',
	] );

	save( $tags, 'tag' );
} );

/**
 * Test data for categories.
 */
WP_CLI::add_command( 'json-dump category', function() : void {
	$categories = get_terms( [
		'taxonomy' => 'category',
		'number'   => 0,
		'orderby'  => 'term_id',
		'order'    => 'ASC',
	] );

	save( $categories, 'category' );
} );

/**
 * Test data for comments.
 */
WP_CLI::add_command( 'json-dump comment', function() : void {
	$comment = get_comments( [
		'number'  => 0,
		'orderby' => 'comment_ID',
		'order'   => 'ASC',
	] );

	save( $comment, 'comment' );
} );

/**
 * Test data for WP_Error objects.
 */
WP_CLI::add_command( 'json-dump error', function() : void {
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

	save( $errors, 'error' );
} );

/**
 * Test data for REST API search results.
 */
WP_CLI::add_command( 'json-dump search-result', function() : void {
	$data = get_rest_response( 'GET', '/wp/v2/search', [
		'per_page' => 100,
	] );

	save( $data, 'rest-api/search-result' );
} );
