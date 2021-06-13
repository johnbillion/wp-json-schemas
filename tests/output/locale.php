<?php

namespace WPJsonSchemas;

$locales = [
	'en_US' => $GLOBALS['wp_locale'],
];

$translations = array_keys( wp_get_installed_translations( 'core' )['default'] );

foreach ( $translations as $locale ) {
	switch_to_locale( $locale );
	$locales[ $locale ] = $GLOBALS['wp_locale'];
}

save_object_array( $locales, 'locale' );
