<?php

namespace WPJsonSchemas;

$data = get_rest_response( 'GET', '/wp/v2/search', [
	'context' => 'view',
	'per_page' => 100,
] );

save_rest_array( [
	$data
], 'search-results' );
