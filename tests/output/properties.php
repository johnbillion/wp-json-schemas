<?php

namespace WPJsonSchemas;

$post_types = get_post_types( [], 'objects' );
$taxonomies = get_taxonomies( [], 'objects' );

// Post type labels.
$post_type_labels = wp_list_pluck( $post_types, 'labels' );
save_object_array( $post_type_labels, 'properties/post-type-labels' );

// Taxonomy labels.
$taxonomy_labels = wp_list_pluck( $taxonomies, 'labels' );
save_object_array( $taxonomy_labels, 'properties/taxonomy-labels' );
