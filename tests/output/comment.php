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

// Notes and reactions are excluded from comment queries unless explicitly requested.
$note = get_rest_response( 'POST', '/wp/v2/comments', [
	'post'    => 1,
	'type'    => 'note',
	'content' => 'This is a note.',
	// An approved note is a resolved thread, which can't receive reactions.
	'status'  => 'hold',
] );

if ( $note->is_error() ) {
	throw new \Exception( 'Failed to create note: ' . $note->as_error()->get_error_message() );
}

$reaction = get_rest_response( 'POST', '/wp/v2/comments', [
	'post'    => 1,
	'parent'  => $note->get_data()['id'],
	'type'    => 'reaction',
	'content' => '1f44d',
] );

if ( $reaction->is_error() ) {
	throw new \Exception( 'Failed to create reaction: ' . $reaction->as_error()->get_error_message() );
}

$note_view_data = get_rest_response( 'GET', '/wp/v2/comments', [
	'context' => 'view',
	'type'    => 'note',
	'status'  => 'all',
] );
$note_edit_data = get_rest_response( 'GET', '/wp/v2/comments', [
	'context' => 'edit',
	'type'    => 'note',
	'status'  => 'all',
] );
$reaction_edit_data = get_rest_response( 'GET', '/wp/v2/comments', [
	'context' => 'edit',
	'type'    => 'reaction',
	'status'  => 'all',
] );

save_rest_array( [
	$view_data,
	$edit_data,
	$note_view_data,
	$note_edit_data,
	$reaction_edit_data,
], 'comments' );
