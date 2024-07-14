<?php

namespace WPJsonSchemas;

$data = get_rest_response( 'GET', '/wp/v2/font-collections' );

save_rest_array( [
	$data,
], 'font-collections' );

save_external_schema(
	'https://schemas.wp.org/trunk/font-collection.json',
	'font-collection'
);
