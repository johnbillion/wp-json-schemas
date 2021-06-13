<?php

namespace WPJsonSchemas;

use WP_CLI;
use WP_Error;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
	return;
}

add_action( 'init', function() : void {
	// Ensure we're authenticated as an admin during test data generation.
	wp_set_current_user( 1 );
} );

/**
 * Saves an array of test data as JSON in files ready for validating against a schema.
 *
 * @param mixed[] $data Array of test data objects.
 * @param string  $dir  The directory to save the files.
 */
function save_object_array( array $data, string $dir ) : void {
	$dir = dirname( ABSPATH ) . '/data/' . $dir;

	if ( ! file_exists( $dir ) ) {
		mkdir( $dir, 0777, true );
	}

	foreach ( $data as $i => $item ) {
		$json = json_encode( $item, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES );

		file_put_contents( $dir . '/' . $i . '.json', $json );
	}
}

/**
 * Saves an array of REST API responses as JSON ready for validating against a schema.
 *
 * @param WP_REST_Response[] $data Array of responses to a REST API request.
 * @param string             $dir  The directory to save the files.
 */
function save_rest_array( array $data, string $dir ) : void {
	$dir = dirname( ABSPATH ) . '/data/rest-api/' . $dir;

	if ( ! file_exists( $dir ) ) {
		mkdir( $dir, 0777, true );
	}

	$server = rest_get_server();

	foreach ( $data as $i => $item ) {
		$save = $server->response_to_data( $item, false );
		$json = json_encode( $save, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES );

		file_put_contents( $dir . '/' . $i . '.json', $json );

		$save = $server->response_to_data( $item, true );
		$json = json_encode( $save, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES );

		file_put_contents( $dir . '/' . $i . '-embedded.json', $json );
	}
}

/**
 * Helper function for performing an internal REST API request and returning its response data.
 *
 * @param string $method The HTTP method such as `GET` or `POST`.
 * @param string $path   The REST API endpoint such as `wp/v2/posts`.
 * @param array $params  Array of query parameters for the request.
 * @return WP_Rest_Response The response data.
 */
function get_rest_response( string $method, string $path, array $params = [] ) {
	$request = new WP_REST_Request( $method, $path );
	$request->set_query_params( $params );

	return rest_do_request( $request );
}

// Register the WP-CLI commands for outputting test data:
foreach ( glob( dirname( __DIR__ ) . '/output/*.php' ) as $file ) {
	$type = basename( $file, '.php' );

	WP_CLI::add_command( "json-dump {$type}", function() use ( $file ) : void {
		require_once $file;
	} );
}

/**
 * Test data for comments.
 */
WP_CLI::add_command( 'json-dump comment', function() : void {
	$comment = get_comments( [
		'number'  => 0,
		'orderby' => 'comment_ID',
		'order'   => 'ASC',
	] );

	save_object_array( $comment, 'comment' );

	$view_data = get_rest_response( 'GET', '/wp/v2/comments', [
		'context' => 'view',
		'per_page' => 100,
	] );
	$edit_data = get_rest_response( 'GET', '/wp/v2/comments', [
		'context' => 'edit',
		'per_page' => 100,
	] );

	save_rest_array( [
		$view_data,
		$edit_data,
	], 'comments' );
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

	save_object_array( $errors, 'error' );

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
} );

/**
 * Test data for REST API search results.
 */
WP_CLI::add_command( 'json-dump search-results', function() : void {
	$data = get_rest_response( 'GET', '/wp/v2/search', [
		'context' => 'view',
		'per_page' => 100,
	] );

	save_rest_array( [
		$data
	], 'search-results' );
} );

/**
 * Test data for taxonomies.
 */
WP_CLI::add_command( 'json-dump taxonomies', function() : void {
	$taxos = get_taxonomies( [], 'objects' );

	save_object_array( $taxos, 'taxonomy' );

	$view_data = get_rest_response( 'GET', '/wp/v2/taxonomies', [
		'context' => 'view',
	] );
	$edit_data = get_rest_response( 'GET', '/wp/v2/taxonomies', [
		'context' => 'edit',
	] );

	save_rest_array( [
		$view_data,
		$edit_data,
	], 'taxonomies' );
} );

/**
 * Test data for post types.
 */
WP_CLI::add_command( 'json-dump types', function() : void {
	$types = get_post_types( [], 'objects' );

	save_object_array( $types, 'post-type' );

	$data = get_rest_response( 'GET', '/wp/v2/types', [
		'context' => 'view',
	] );

	save_rest_array( [
		$data
	], 'types' );
} );

/**
 * Test data for sites on a Multisite installation.
 */
WP_CLI::add_command( 'json-dump sites', function() : void {
	$sites = get_sites();

	save_object_array( $sites, 'site' );
} );

/**
 * Test data for networks on a Multisite or Multi-network installation.
 */
WP_CLI::add_command( 'json-dump networks', function() : void {
	$networks = get_networks();

	save_object_array( $networks, 'network' );
} );

/**
 * Test data for user roles.
 */
WP_CLI::add_command( 'json-dump roles', function() : void {
	$roles = wp_roles()->role_objects;

	save_object_array( $roles, 'role' );
} );

/**
 * Test data for `WP_Query`.
 */
WP_CLI::add_command( 'json-dump query', function() : void {
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
	$cat_query->get_queried_object();

	$queries[] = $cat_query;

	save_object_array( $queries, 'query' );
} );

/**
 * Test data for `WP_Locale`.
 */
WP_CLI::add_command( 'json-dump locale', function() : void {
	$locales = [
		'en_US' => $GLOBALS['wp_locale'],
	];

	$translations = array_keys( wp_get_installed_translations( 'core' )['default'] );

	foreach ( $translations as $locale ) {
		switch_to_locale( $locale );
		$locales[ $locale ] = $GLOBALS['wp_locale'];
	}

	save_object_array( $locales, 'locale' );
} );
