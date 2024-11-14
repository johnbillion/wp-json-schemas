<?php

namespace WPJsonSchemas;

$data = get_rest_response( 'GET', '/wp/v2/font-collections' );

save_rest_array( [
	$data,
], 'font-collections' );

$url = sprintf(
	'https://raw.githubusercontent.com/WordPress/gutenberg/wp/%s/schemas/json/font-collection.json',
	WP_VERSION,
);

save_external_schema(
	$url,
	'font-collection'
);
