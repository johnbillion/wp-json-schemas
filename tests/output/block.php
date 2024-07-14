<?php

namespace WPJsonSchemas;

$content = file_get_contents( __DIR__ . '/includes/blocks.html' );
$parsed = array_filter( parse_blocks( $content ), function( $block ) {
	return ! empty( $block['blockName'] );
} );
$wp_blocks = [];

foreach ( $parsed as $block ) {
	$wp_blocks[] = new \WP_Block( $block );
}

save_object_array( array_values( $wp_blocks ), 'block' );
