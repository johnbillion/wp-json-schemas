<?php

namespace WPJsonSchemas;

$data = [];

$entities = [
	'post'        => 'postType',
	'page'        => 'postType',
	'attachment'  => 'postType',
	'wp_template' => 'postType',
	'wp_block'    => 'postType',
	'category'    => 'taxonomy',
	'post_tag'    => 'taxonomy',
	'site'        => 'root',
];

foreach ( $entities as $name => $kind ) {
	$data[] = get_rest_response( 'GET', '/wp/v2/view-config', [
		'kind' => $kind,
		'name' => $name,
	] );
}

save_rest_array( $data, 'view-config', true );
