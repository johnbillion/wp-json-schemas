<?php

namespace WPJsonSchemas;

$data = get_rest_response( 'GET', '/wp/v2/block-directory/search', [
	'term' => 'block',
] );

save_rest_array( [
	$data,
], 'block-directory-items' );
