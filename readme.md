[![](https://img.shields.io/badge/npm-wp--json--schemas-7700ee.svg?style=flat-square)](https://www.npmjs.com/package/wp-json-schemas)
[![](https://img.shields.io/badge/npm-wp--types-7700ee.svg?style=flat-square)](https://www.npmjs.com/package/wp-types)
[![](https://img.shields.io/github/actions/workflow/status/johnbillion/wp-json-schemas/test.yml?branch=trunk&style=flat-square)](https://github.com/johnbillion/wp-json-schemas/actions)

# WordPress JSON Schemas

This package provides well-documented JSON schemas that describe the shape of:

* WordPress core PHP objects such as `WP_Post`, `WP_Term`, and `WP_User`
* WordPress REST API responses such as those from `/wp/v2/posts`, `/wp/v2/categories`, and `/wp/v2/users`
* Various property types and values of both

The schemas in this library are used to generate [the WordPress TypeScript definitions provided by the `wp-types` package](https://www.npmjs.com/package/wp-types).

The schemas were last updated for WordPress 6.4.

## What's included?

### PHP Object Schemas

* `WP_Post`
* `WP_Term`
* `WP_User`
* `WP_Comment`
* `WP_Error`
* `WP_Query`
* `WP_Block`
* `WP_Block_Type`
* `WP_Block_Template`
* `WP_Site`
* `WP_Locale`
* `WP_Taxonomy`
* `WP_Post_Type`
* `WP_Role`
* `WP_Network`
* `WP_Screen`

### REST API Response Schemas

Route                                                   | Schema
------------------------------------------------------- | ------
/wp/v2/block-directory/search                           | `WP_REST_API_Block_Directory_Items`
/wp/v2/block-patterns/categories                        | `WP_REST_API_Block_Pattern_Categories`
/wp/v2/block-patterns/patterns                          | `WP_REST_API_Block_Patterns`
/wp/v2/block-renderer/{name}                            | `WP_REST_API_Rendered_Block`
/wp/v2/block-types                                      | `WP_REST_API_Block_Types`
/wp/v2/block-types/{namespace}                          | `WP_REST_API_Block_Type`
/wp/v2/block-types/{namespace}/{name}                   | `WP_REST_API_Block_Type`
/wp/v2/blocks                                           | `WP_REST_API_Blocks`
/wp/v2/blocks/{id}                                      | `WP_REST_API_Block`
/wp/v2/blocks/{id}/autosaves                            | Todo
/wp/v2/blocks/{parent}/autosaves/{id}                   | Todo
/wp/v2/blocks/{parent}/revisions                        | `WP_REST_API_Revisions`
/wp/v2/blocks/{parent}/revisions/{id}                   | `WP_REST_API_Revision`
/wp/v2/categories                                       | `WP_REST_API_Categories`
/wp/v2/categories/{id}                                  | `WP_REST_API_Category`
/wp/v2/comments                                         | `WP_REST_API_Comments`
/wp/v2/comments/{id}                                    | `WP_REST_API_Comment`
/wp/v2/wp/v2/font-collections                           | Todo
/wp/v2/wp/v2/font-collections/{slug}                    | Todo
/wp/v2/wp/v2/font-families                              | Todo
/wp/v2/wp/v2/font-families/{id}/                        | Todo
/wp/v2/wp/v2/font-families/{id}/font-faces              | Todo
/wp/v2/wp/v2/font-families/{id}/font-faces/{id}         | Todo
/wp/v2/global-styles/{id}                               | Todo
/wp/v2/global-styles/{parent}/revisions                 | Todo
/wp/v2/global-styles/{parent}/revisions/{id}            | Todo
/wp/v2/global-styles/themes/{stylesheet}/variations     | Todo
/wp/v2/global-styles/themes/{stylesheet}                | Todo
/wp/v2/media                                            | `WP_REST_API_Attachments`
/wp/v2/media/{id}                                       | `WP_REST_API_Attachment`
/wp/v2/media/{id}/edit                                  | Todo
/wp/v2/media/{id}/post-process                          | Todo
/wp/v2/menu-items                                       | Todo
/wp/v2/menu-items/{id}                                  | Todo
/wp/v2/menu-items/{id}/autosaves                        | Todo
/wp/v2/menu-items/{parent}/autosaves/{id}               | Todo
/wp/v2/menu-locations                                   | Todo
/wp/v2/menu-locations/{location}                        | Todo
/wp/v2/menus                                            | Todo
/wp/v2/menus/{id}                                       | Todo
/wp/v2/navigation                                       | Todo
/wp/v2/navigation/{id}                                  | Todo
/wp/v2/navigation/{id}/autosaves                        | Todo
/wp/v2/navigation/{parent}/autosaves/{id}               | Todo
/wp/v2/navigation/{parent}/revisions                    | Todo
/wp/v2/navigation/{parent}/revisions/{id}               | Todo
/wp/v2/pages                                            | `WP_REST_API_Pages`
/wp/v2/pages/{id}                                       | `WP_REST_API_Page`
/wp/v2/pages/{id}/autosaves                             | Todo
/wp/v2/pages/{parent}/autosaves/{id}                    | Todo
/wp/v2/pages/{parent}/revisions                         | `WP_REST_API_Revisions`
/wp/v2/pages/{parent}/revisions/{id}                    | `WP_REST_API_Revision`
/wp/v2/pattern-directory/patterns                       | Todo
/wp/v2/plugins                                          | Todo
/wp/v2/plugins/{plugin}                                 | Todo
/wp/v2/posts                                            | `WP_REST_API_Posts`
/wp/v2/posts/{id}                                       | `WP_REST_API_Post`
/wp/v2/posts/{id}/autosaves                             | Todo
/wp/v2/posts/{parent}/autosaves/{id}                    | Todo
/wp/v2/posts/{parent}/revisions                         | `WP_REST_API_Revisions`
/wp/v2/posts/{parent}/revisions/{id}                    | `WP_REST_API_Revision`
/wp/v2/search                                           | `WP_REST_API_Search_Results`
/wp/v2/settings                                         | `WP_REST_API_Settings`
/wp/v2/sidebars                                         | Todo
/wp/v2/sidebars/{id}                                    | Todo
/wp/v2/statuses                                         | `WP_REST_API_Statuses`
/wp/v2/statuses/{status}                                | `WP_REST_API_Status`
/wp/v2/tags                                             | `WP_REST_API_Tags`
/wp/v2/tags/{id}                                        | `WP_REST_API_Tag`
/wp/v2/taxonomies                                       | `WP_REST_API_Taxonomies`
/wp/v2/taxonomies/{taxonomy}                            | `WP_REST_API_Taxonomy`
/wp/v2/templates                                        | Todo
/wp/v2/templates/{id}                                   | Todo
/wp/v2/templates/{id}/autosaves                         | Todo
/wp/v2/templates/{parent}/autosaves/{id}                | Todo
/wp/v2/templates/{parent}/revisions                     | Todo
/wp/v2/templates/{parent}/revisions/{id}                | Todo
/wp/v2/templates/lookup                                 | Todo
/wp/v2/template-parts                                   | Todo
/wp/v2/template-parts/{id}                              | Todo
/wp/v2/template-parts/{id}/autosaves                    | Todo
/wp/v2/template-parts/{parent}/autosaves/{id}           | Todo
/wp/v2/template-parts/{parent}/revisions                | Todo
/wp/v2/template-parts/{parent}/revisions/{id}           | Todo
/wp/v2/template-parts/lookup                            | Todo
/wp/v2/themes                                           | Todo
/wp/v2/themes/{stylesheet}                              | Todo
/wp/v2/types                                            | `WP_REST_API_Types`
/wp/v2/types/{type}                                     | `WP_REST_API_Type`
/wp/v2/users                                            | `WP_REST_API_Users`
/wp/v2/users/({id}\|me)                                 | `WP_REST_API_User`
/wp/v2/users/({id}\|me)/application-passwords           | `WP_REST_API_Application_Passwords`
/wp/v2/users/({id}\|me)/application-passwords/{uuid}    | `WP_REST_API_Application_Password`
/wp/v2/users/({id}\|me)/application-passwords/introspect| `WP_REST_API_Application_Password`
/wp/v2/widget-types                                     | Todo
/wp/v2/widget-types/{id}                                | Todo
/wp/v2/widget-types/{id}/encode                         | Todo
/wp/v2/widget-types/{id}/render                         | Todo
/wp/v2/widgets                                          | Todo
/wp/v2/widgets/{id}                                     | Todo
/wp/v2/wp_pattern_category                              | Todo
/wp/v2/wp_pattern_category/{id}                         | Todo
Any REST API error                                      | `WP_REST_API_Error`

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
* `WP_HTTP_Status_Code`

## Installation

```sh
npm install wp-json-schemas
```

### Versioning

This package is versioned so that you can specify both the schema version and the WordPress branch version in a way that's compatible with semantic versioning. Given version `x.y.z`:

* The major version number (`x`) indicates the schema version number, currently `3`
* The minor version number (`y`) indicates the WordPress branch version number without its decimal place, eg. `64` for WordPress 6.4
* The patch version number (`z`) indicates the schema patch version number

Examples:

* `~3.64.0` - Schema version 3 for WordPress 6.4
* `~3.60.0` - Schema version 3 for WordPress 6.0
* `~3.57.0` - Schema version 3 for WordPress 5.7
* `^3.0.0` - Schema version 3 for the latest WordPress version

## Usage

Usage depends on what you're doing with the schemas. You could use them for validation or just for understanding the shape of an object.

If you're using TypeScript, check out [the TypeScript definitions provided by the `wp-types` package](https://www.npmjs.com/package/wp-types).

## FAQs

### When do these schemas apply?

The PHP object schemas apply whenever a supported PHP object is encoded to JSON. For example:

```php
printf(
	'let wpPost = %s;',
	wp_json_encode( get_post() )
);
```

The REST API object schemas apply to the response to a REST API request. For example:

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

## Tests

To run the tests:

* `nvm use`
* `npm install`
* `composer install`
* `composer test`

## License

MIT
