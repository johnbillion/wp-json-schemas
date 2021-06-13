<?php

namespace WPJsonSchemas;

$comment = get_comments( [
	'number'  => 0,
	'orderby' => 'comment_ID',
	'order'   => 'ASC',
] );

save_object_array( $comment, 'comment' );

$view_data = get_rest_response( 'GET', '/wp/v2/comments', [
	'context' => 'view',
	'per_page' => 100,
] );
$edit_data = get_rest_response( 'GET', '/wp/v2/comments', [
	'context' => 'edit',
	'per_page' => 100,
] );

save_rest_array( [
	$view_data,
	$edit_data,
], 'comments' );
