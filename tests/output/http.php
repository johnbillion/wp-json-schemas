<?php

namespace WPJsonSchemas;

$http = _wp_http_get_object();
$reflection = new \ReflectionClass( $http );
$constants = $reflection->getConstants();
$file = 'schemas/rest-api/properties/http-status-code.json';

set_schema_fields(
	$file,
	[
		'tsEnumNames' => array_keys( $constants ),
		'enum' => array_values( $constants ),
	]
);
