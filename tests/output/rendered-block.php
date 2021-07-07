<?php

namespace WPJsonSchemas;

register_block_type(
	'foo/bar',
	[
		'render_callback' => function( array $attributes, string $content ) : string {
			return '<div>Hello</div>';
		},
	]
);

$edit_data = get_rest_response( 'GET', '/wp/v2/block-renderer/foo/bar', [
	'context' => 'edit',
] );

save_rest_array( [
	$edit_data,
], 'rendered-block' );
