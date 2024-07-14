<?php

namespace WPJsonSchemas;

$data = get_rest_response( 'GET', '/wp/v2/block-patterns/categories' );

save_rest_array( [
	$data,
], 'block-pattern-categories' );
