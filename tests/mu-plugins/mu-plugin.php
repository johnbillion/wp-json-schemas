<?php

namespace WPJsonSchemas;

use WP_CLI;
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
	grant_super_admin( 1 );
	wp_set_current_user( 1 );
} );

/**
 * Saves an array of test data as JSON in files ready for validating against a schema.
 *
 * @param mixed[] $data Array of test data objects.
 * @param string  $dir  The directory to save the files.
 */
function save_object_array( array $data, string $dir ) : void {
	$schema = sprintf(
		'../../../schemas/%s.json',
		$dir
	);
	$dir = dirname( ABSPATH ) . '/data/' . $dir;

	if ( ! file_exists( $dir ) ) {
		mkdir( $dir, 0777, true );
	}

	foreach ( $data as $i => $item ) {
		if ( is_object( $item ) ) {
			$item = get_object_vars( $item );
		}

		$item = array_merge(
			[
				'$schema' => $schema,
			],
			$item
		);

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

// Register the WP-CLI command for outputting test data:
WP_CLI::add_command( 'json-dump', function( array $args, array $assoc_args ) : void {
	$filename = $args[0];
	$file = dirname( __DIR__ ) . "/output/{$filename}.php";

	if ( ! file_exists( $file ) ) {
		WP_CLI::error( "File tests/output/{$filename}.php does not exist." );
	}

	require_once $file;

	WP_CLI::success(
		sprintf(
			'Dumped %s',
			$filename
		)
	);
} );
