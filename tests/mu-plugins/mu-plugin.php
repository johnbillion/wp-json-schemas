<?php

namespace WPJsonSchemas;

use WP_CLI;

add_action( 'init', function() : void {
	if ( defined( 'WP_INSTALLING' ) ) {
		return;
	}

	wp_insert_post( [
		'post_type'   => 'post',
		'post_title'  => 'Title',
		'post_status' => 'publish',
	] );
} );

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

WP_CLI::add_command( 'json-dump post', function() : void {
	$posts = get_posts( [
		'posts_per_page' => -1,
		'orderby'        => 'ID',
		'order'          => 'ASC',
	] );

	save( $posts, 'post' );
} );
} );
