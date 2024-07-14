<?php

namespace WPJsonSchemas;

$data = get_rest_response( 'GET', '/wp/v2/block-patterns/patterns' );

save_rest_array( [
	$data,
], 'block-patterns' );
