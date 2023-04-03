<?php

namespace WPJsonSchemas;

$post_types = get_post_types( [], 'objects' );

save_object_array( $post_types, 'post-type' );

$view_data = get_rest_response( 'GET', '/wp/v2/types', [
	'context' => 'view',
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/types', [
	'context' => 'edit',
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'types' );
