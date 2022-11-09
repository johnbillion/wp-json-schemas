<?php

namespace WPJsonSchemas;

use WP_Block_Type_Registry;

$registry = WP_Block_Type_Registry::get_instance();
$block_types = $registry->get_all_registered();

// https://github.com/WordPress/gutenberg/issues/45677
unset( $block_types['core/post-comments'] );

save_object_array( array_values( $block_types ), 'block-type' );

$view_data = get_rest_response( 'GET', '/wp/v2/block-types', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/block-types', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'block-types' );
