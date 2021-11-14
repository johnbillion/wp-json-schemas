<?php

namespace WPJsonSchemas;

$data = get_rest_response( 'GET', '/wp/v2', [] );

$d = $data->get_data();

$routes = array_map( function( array $data, string $route ) : array {
	$data['route'] = $route;
	return $data;
}, $d['routes'], array_keys( $d['routes'] ) );

$dir = 'routes';
$dir = dirname( ABSPATH ) . '/data/rest-api/' . $dir;

if ( ! file_exists( $dir ) ) {
	mkdir( $dir, 0777, true );
}

foreach ( $routes as $item ) {
	$i = preg_replace( '#[^a-z0-9]+#', '-', $item['route'] );
	$i = preg_replace( '#\b[dw]\b#', '', $i );
	$i = str_replace( 'a-z', '', $i );
	$i = str_replace( '0-9', '', $i );
	$i = preg_replace( '#\-+#', '-', $i );
	$i = trim( $i, '-' );

	$json = json_encode( $item, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES );

	file_put_contents( $dir . '/' . $i . '.json', $json );
}

$all_routes = array_column( $routes, 'route' );

sort( $all_routes );

$json = json_encode( $all_routes, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES );

file_put_contents( $dir . '/routes.json', $json );
