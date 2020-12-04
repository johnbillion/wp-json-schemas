# WordPress JSON Schemas

This package provides well-documented JSON schemas that describe the shape of:

* WordPress core PHP objects such as `WP_Error`, `WP_Post`, and `WP_User`
* WordPress REST API responses such as those from `/wp/v2/posts` and `/wp/v2/users`
* The allowed values for several of the properties of both

The schemas in this library are used directly to generate [the TypeScript definitions provided by the `wp-types` package](https://www.npmjs.com/package/wp-types).

## What's included?

### Core Objects

* `WP_Comment`
* `WP_Error`, plus:
  - `WP_Error_With_Error`
  - `WP_Error_Without_Error`
* `WP_Post`
* `WP_Post_Type`
* `WP_Term`
* `WP_User`

### REST API responses

* `WP_REST_API_Comment`
* `WP_REST_API_Post`
* `WP_REST_API_Term`
* `WP_REST_API_User`
* `WP_REST_API_Error` (for any REST API error response)

### Properties

Schemas are used for the structure of some object properties:

* `WP_Error_Data`
* `WP_Error_Messages`
* `WP_Post_Type_Caps`
* `WP_User_Cap_Name`
* `WP_User_Caps`
* `WP_User_Data`

### Enums

String enum schemas are used for values of some object properties:

* `WP_Comment_Status_Name`
* `WP_Comment_Type_Name`
* `WP_Post_Comment_Status_Name`
* `WP_Post_Format_Name`
* `WP_Post_Status_Name`
* `WP_Post_Type_Name`
* `WP_Taxonomy_Name`
* `WP_User_Role_Name`

## Installation

```sh
npm install wp-json-schemas
```

## Usage

Usage really depends what you're doing with the schemas. You could use them for validation or just for understanding the shape of an object.

## FAQs

### When do these schemas apply?

The core object schemas apply whenever a supported PHP object is represented as JSON. How you do that depends on your application, but here is an example:

```php
printf(
	'let wpPost = %s;',
	wp_json_encode( get_post() )
);
```

The REST API object schemas apply to the object (or array of objects) you get in response to a REST API request.

The schemas also apply outside of an HTTP request of course, for example if you're saving data as a JSON file and reading it in a Node application.

### Why are there different schemas for PHP objects and REST API responses?

Objects in REST API responses are of a different shape to their PHP counterparts. A post object in a REST API response is not the same as a `WP_Post` object in PHP, in fact it's substantially different.

Schemas for REST API responses are available via an OPTIONS request to a REST API endpoint, but the schema does not adhere strictly to the JSON Schema standard. [Here's an article by Timothy B. Jacobs with more info](https://timothybjacobs.com/2017/05/17/json-schema-and-the-wp-rest-api/).

### Why doesn't object X include property Y?

If it's not a public property of the object's class then it won't be included when encoding the object as JSON. You'll need to handle it separately.

### Are these schemas automatically generated from WordPress core?

No. I started down that path (using [wp-parser-lib](https://github.com/johnbillion/wp-parser-lib)) but realised it's quicker to generate them manually and then copy most of the documentation from core, especially as I'm adding extra documentation and schemas for properties when I can.

### Why aren't the descriptions very good?

The descriptions for objects and properties are mostly copied from WordPress core, many of which are poor quality. I'll update the descriptions in this schema as they're improved in core.
