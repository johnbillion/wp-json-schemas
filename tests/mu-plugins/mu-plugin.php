<?php

namespace WPJsonSchemas;

use Ergebnis\Json\Printer;

use WP_CLI;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
	return;
}

require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';

set_error_handler( function( int $errno, string $errstr, string $errfile = '', int $errline = 0 ) : bool {
	// This is an @-suppressed error:
	if ( ! ( error_reporting() & $errno ) ) {
		return false;
	}

	// Throw all other errors as an exception:
	throw new \Exception( sprintf(
		'%s in %s:%d',
		$errstr,
		$errfile,
		$errline
	) );
} );

$composer = json_decode( file_get_contents( dirname( __DIR__, 2 ) . '/composer.json' ), true );
define( 'WP_VERSION', $composer['require-dev']['roots/wordpress-full'] );

function use_requested_theme( string $theme ) : string {
	foreach ( $_SERVER['argv'] as $arg ) {
		if ( str_starts_with( $arg, '--theme=' ) ) {
			return substr( $arg, 8 );
		}
	}

	return $theme;
}

add_filter( 'option_template', __NAMESPACE__ . '\use_requested_theme', 1 );
add_filter( 'option_stylesheet', __NAMESPACE__ . '\use_requested_theme', 1 );

add_action( 'init', function() : void {
	// Ensure we're authenticated as an admin during test data generation.
	grant_super_admin( 1 );
	wp_set_current_user( 1 );

	register_post_type( 'book', [
		'public' => true,
		'label' => 'Books',
		'show_in_rest' => true,
		'template' => [
			[
				'core/paragraph',
				[
					'placeholder' => 'Add a description of the book',
				],
			],
			[
				'core/paragraph',
			],
		],
	] );
} );

/**
 * Saves an array of test data as JSON in files ready for validating against a schema.
 *
 * @param mixed[] $data Array of test data objects.
 * @param string  $dir  The directory to save the files.
 */
function save_object_array( array $data, string $dir ) : void {
	if ( empty( $data ) ) {
		throw new \Exception( "No object data to save for {$dir}." );
	}

	$dir = dirname( ABSPATH ) . '/data/' . $dir;

	if ( ! file_exists( $dir ) ) {
		mkdir( $dir, 0777, true );
	}

	foreach ( $data as $i => $item ) {
		if ( is_object( $item ) ) {
			$item = get_object_vars( $item );
		}

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
function save_rest_array( array $data, string $dir, bool $single = false ) : void {
	if ( empty( $data ) ) {
		throw new \Exception( "No REST API data to save for {$dir}." );
	}

	if ( $single ) {
		$dir = dirname( ABSPATH ) . '/data/rest-api/' . $dir;
	} else {
		$dir = dirname( ABSPATH ) . '/data/rest-api/collections/' . $dir;
	}

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

function save_external_schema( string $url, string $name, array $path = [] ) : void {
	$target = dirname( ABSPATH ) . "/external-schemas/{$name}.json";
	$schema = download_url( $url );

	if ( is_wp_error( $schema ) ) {
		throw new \Exception( "Failed to download external {$url} schema." );
	}

	$file = file_get_contents( $schema );

	if ( ! $file ) {
		throw new \Exception( "Failed to open {$name} schema file." );
	}

	$data = json_decode( $file, true );

	if ( ! $data ) {
		throw new \Exception( "Failed to parse external {$name} schema." );
	}

	foreach ( $path as $key ) {
		if ( isset( $data[ $key ] ) ) {
			$data = $data[ $key ];
		} else {
			$full_path = implode( '.', $path );
			throw new \Exception( "Failed to find path `{$full_path}` in {$url} schema." );
		}
	}

	$json = json_encode( $data, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES );

	$printer = new Printer\Printer();

	$json = $printer->print(
		$json,
		"\t",
	);

	$result = file_put_contents( $target, $json );

	if ( ! $result ) {
		throw new \Exception( "Failed to save external {$name} schema." );
	}
}

/**
 * @param array<string, mixed> $values
 */
function set_schema_fields( string $filename, array $values ) : void {
	$target = dirname( ABSPATH, 2 ) . "/" . $filename;
	$file = file_get_contents( $target );

	if ( ! $file ) {
		throw new \Exception( "Failed to open {$filename} schema file." );
	}

	$data = json_decode( $file, true );

	if ( ! $data ) {
		throw new \Exception( "Failed to parse {$filename} schema." );
	}

	foreach ( $values as $key => $value ) {
		$data[ $key ] = $value;
	}

	$json = json_encode( $data, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES );

	$printer = new Printer\Printer();

	$json = $printer->print(
		$json,
		"\t",
	);

	$result = file_put_contents( $target, $json . "\n" );

	if ( ! $result ) {
		throw new \Exception( "Failed to save {$filename} schema." );
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
	if ( isset( $assoc_args['theme'] ) ) {
		$theme = $assoc_args['theme'];
		$dir = dirname( __DIR__ ) . "/output/with-theme/{$theme}";

		if ( file_exists( $dir ) ) {
			foreach ( glob( "{$dir}/*.php" ) as $file ) {
				require_once $file;
			}
		}
	} else {
		foreach ( glob( dirname( __DIR__ ) . '/output/*.php' ) as $file ) {
			require_once $file;
		}
	}

	WP_CLI::success( 'Dumped test data');
} );
