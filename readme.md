# WordPress JSON Schemas

This package provides well-documented JSON schemas that describe the shape of WordPress core PHP objects such as `WP_Error`, `WP_Post`, and `WP_User`, and the allowed values for several of their properties, when they're represented as JSON.

The schemas in this library are used directly to generate [the TypeScript definitions provided by the `wp-types` package](https://www.npmjs.com/package/wp-types).

## What's included?

### Core Objects

* `WP_Error`
* `WP_Post`
* `WP_User`

### Property Values

String enums are included for known possible values of some properties:

* `WP_Post_Status_Name`
* `WP_Post_Type_Name`
* `WP_User_Role`

## Installation

```sh
npm install wp-json-schemas
```

## Usage

Usage really depends what you're doing with the schemas. You could use them for validation or just for understanding the shape of an object.

## FAQs

### When do these schemas apply?

They apply whenever a supported PHP object is represented as JSON. How you do that depends on your application, but here are some examples:

```php
// get_post() returns a WP_Post or null:
printf(
	'let wpPost = %s;',
	wp_json_encode( get_post() )
);

// get_userdata() returns a WP_User or false:
printf(
	'let wpUser = %s;',
	wp_json_encode( get_userdata( 123 ) )
);
```

The schemas also apply outside of an HTTP request of course, for example if you're saving data as a JSON file and reading it in a Node application.

### Do these schemas apply to REST API responses?

No. WordPress core objects in REST API responses are of a different shape, and schemas for these are available via an OPTIONS request to a REST API endpoint. [Here's an article by Timothy B. Jacobs with all the info](https://timothybjacobs.com/2017/05/17/json-schema-and-the-wp-rest-api/).

### Why doesn't object X include property Y?

If it's not a public property of the object's class then it won't be included when encoding the object as JSON. You'll need to handle it separately.

### Are these schemas automatically generated from WordPress core?

No. I started down that path (using [wp-parser-lib](https://github.com/johnbillion/wp-parser-lib)) but realised it's quicker to generate them manually and then copy most of the documentation from core, especially as I'm adding extra documentation and schemas for properties when I can.
