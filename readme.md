[![](https://img.shields.io/badge/npm-wp--json--schemas-9966ff.svg?style=flat-square)](https://www.npmjs.com/package/wp-json-schemas)
[![](https://img.shields.io/badge/npm-wp--types-9966ff.svg?style=flat-square)](https://www.npmjs.com/package/wp-types)
[![](https://img.shields.io/github/workflow/status/johnbillion/wp-json-schemas/Test/master?style=flat-square)](https://github.com/johnbillion/wp-json-schemas/actions)

# WordPress JSON Schemas

This package provides well-documented JSON schemas that describe the shape of:

* WordPress core PHP objects such as `WP_Post`, `WP_Term`, and `WP_User`
* WordPress REST API responses such as those from `/wp/v2/posts`, `/wp/v2/categories`, and `/wp/v2/users`
* Various property types and values of both

The schemas in this library are used to generate [the WordPress TypeScript definitions provided by the `wp-types` package](https://www.npmjs.com/package/wp-types).

## What's included?

### PHP Object Schemas

* `WP_Post`
* `WP_Term`
* `WP_User`
* `WP_Comment`
* `WP_Site`
* `WP_Taxonomy`
* `WP_Post_Type`
* `WP_Error`

### REST API Response Schemas

Schema                | Applies to
--------------------- | ----------
`WP_REST_API_Post`    | /wp/v2/posts <br> /wp/v2/pages
`WP_REST_API_Term`    | /wp/v2/categories <br> /wp/v2/tags
`WP_REST_API_User`    | /wp/v2/users
`WP_REST_API_Comment` | /wp/v2/comments
`WP_REST_API_Media`   | /wp/v2/media
`WP_REST_API_Error`   | Any REST API error

The REST API schemas use JSON Hyper-Schema.

### Property Schemas

Schemas are provided for various properties:

* `WP_Error_Data`
* `WP_Error_Messages`
* `WP_Post_Type_Caps`
* `WP_Post_Type_Labels`
* `WP_Post_Type_Rewrite`
* `WP_Taxonomy_Caps`
* `WP_Taxonomy_Labels`
* `WP_Taxonomy_Rewrite`
* `WP_User_Cap_Name`
* `WP_User_Caps`
* `WP_User_Data`

### Enums

Enums are provided for various values:

* `WP_Comment_Status_Name`
* `WP_Comment_Type_Name`
* `WP_Post_Comment_Status_Name`
* `WP_Post_Format_Name`
* `WP_Post_Status_Name`
* `WP_Post_Type_Name`
* `WP_Taxonomy_Name`
* `WP_User_Role_Name`
* `WP_Http_Status_Code`

## Installation

```sh
npm install wp-json-schemas
```

## Usage

Usage depends on what you're doing with the schemas. You could use them for validation or just for understanding the shape of an object.

If you're using TypeScript, check out [the TypeScript definitions provided by the `wp-types` package](https://www.npmjs.com/package/wp-types).

## FAQs

### When do these schemas apply?

The PHP object schemas apply whenever a supported PHP object is represented as JSON. For example:

```php
printf(
	'let wpPost = %s;',
	wp_json_encode( get_post() )
);
```

The REST API object schemas apply to each of the objects you get in response to a REST API request. For example:

```js
const api = wp.apiFetch( {
	path: '/wp/v2/categories/'
} );
```

The schemas also apply outside of an HTTP request, for example if you're saving data as a JSON file and reading it in a Node application.

### Why are there different schemas for PHP objects and REST API responses?

An object in a REST API response is not the same as its corresponding object in PHP, in fact they are substantially different.

Schemas are available via an OPTIONS request to the REST API endpoints, but the schemas do not adhere strictly to the JSON Schema standard. [Here's an article by Timothy B. Jacobs with more info](https://timothybjacobs.com/2017/05/17/json-schema-and-the-wp-rest-api/).

### Are these schemas automatically generated from WordPress core?

No. I started down that path (using [wp-parser-lib](https://github.com/johnbillion/wp-parser-lib)) but realised it's quicker to generate them manually and then copy most of the documentation from core, especially as I'm adding extra documentation and schemas for properties when I can.

### How do I know the schemas are accurate?

They're all tested against actual output from WordPress core.

### Why aren't the descriptions very good?

The descriptions are mostly copied from WordPress core. I'll update the descriptions in this schema as they're improved in core.

## Tests

To run the tests:

* `npm install`
* `composer install`
* `composer test`

## License

MIT
