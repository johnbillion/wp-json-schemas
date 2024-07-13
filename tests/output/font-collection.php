<?php

namespace WPJsonSchemas;

$data = get_rest_response( 'GET', '/wp/v2/font-collections' );

save_rest_array( [
	$data,
], 'font-collections' );

$font_collection_schema = download_url( 'https://schemas.wp.org/trunk/font-collection.json' );

if ( is_wp_error( $font_collection_schema ) ) {
	throw new \Exception( 'Failed to download font-collection schema.' );
}

rename( $font_collection_schema, dirname( ABSPATH ) . '/external-schemas/font-collection.json' );
