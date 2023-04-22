<?php

namespace WPJsonSchemas;

$templates = get_block_templates( [], 'wp_template' );
$template_parts = get_block_templates( [], 'wp_template_part' );

save_object_array( array_merge( $templates, $template_parts ), 'block-template' );
