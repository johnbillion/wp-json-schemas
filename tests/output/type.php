<?php

namespace WPJsonSchemas;

$types = get_post_types( [], 'objects' );

save_object_array( $types, 'post-type' );

$data = get_rest_response( 'GET', '/wp/v2/types', [
	'context' => 'view',
] );

save_rest_array( [
	$data
], 'types' );
