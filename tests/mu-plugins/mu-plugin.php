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
