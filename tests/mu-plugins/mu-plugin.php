<?php

add_action( 'init', function() {
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

WP_CLI::add_command( 'json-dump', function() {
	$posts = get_posts( [
		'posts_per_page' => -1,
		'orderby'        => 'ID',
		'order'          => 'ASC',
	] );

	$dir = dirname( ABSPATH ) . '/data/post/';

	if ( ! file_exists( $dir ) ) {
		mkdir( $dir, 0777, true );
	}

	foreach ( $posts as $i => $post ) {
		$json = json_encode( $post, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES );

		file_put_contents( $dir . '/' . $i . '.json', $json );
	}
} );
