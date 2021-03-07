[![NPM](https://img.shields.io/badge/npm-wp--types-9966ff.svg?style=flat-square)](https://www.npmjs.com/package/wp-types)

# WordPress TypeScript Definitions

This package provides well-documented TypeScript definitions that describe the shape of:

* WordPress PHP objects such as `WP_Post`, `WP_Term`, and `WP_User`
* WordPress REST API responses such as from `/wp/v2/posts` and `/wp/v2/users`
* Allowed values for several of their properties

## What's included?

### Interfaces for PHP objects

* `WP_Post`
* `WP_Term`
* `WP_User`
* `WP_Comment`
* `WP_Site`
* `WP_Taxonomy`
* `WP_Post_Type`
* `WP_Error`, plus:
  - `WP_Error_With_Error`
  - `WP_Error_Without_Error`

### Interfaces for REST API response objects

* `WP_REST_API_Post`
* `WP_REST_API_Term`
* `WP_REST_API_User`
* `WP_REST_API_Comment`
* `WP_REST_API_Media`
* `WP_REST_API_Error` (for any REST API error response)

### Properties

Interfaces are used for the structure of several properties:

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

String enums are used for values of several properties:

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
npm install wp-types --save-dev
```

## Usage

Usage with objects from PHP represented as JSON:

```ts
import type { WP_Post } from 'wp-types';

function get_title( post: WP_Post ): string {
	return post.post_title;
}
```

Usage with the REST API, for example when using `apiFetch()`:

```ts
import type {
	WP_REST_API_Post,
	WP_REST_API_Term,
	WP_REST_API_User,
	WP_REST_API_Comment,
	WP_REST_API_Media,
} from 'wp-types';

// Posts, Pages, and custom post types:
const api: Promise<WP_REST_API_Post[]> = wp.apiFetch( {
	path: '/wp/v2/posts/',
} );

// Categories, Tags, and custom taxonomies:
const api: Promise<WP_REST_API_Term[]> = wp.apiFetch( {
	path: '/wp/v2/categories/',
} );

// Users:
const api: Promise<WP_REST_API_User[]> = wp.apiFetch( {
	path: '/wp/v2/users/',
} );

// Comments:
const api: Promise<WP_REST_API_Comment[]> = wp.apiFetch( {
	path: '/wp/v2/comments/',
} );

// Media attachments:
const api: Promise<WP_REST_API_Media[]> = wp.apiFetch( {
	path: '/wp/v2/media/',
} );

// Errors from any of the above:
api.catch( ( error: WP_REST_API_Error ) => {
	alert( error.message );
} );
```

## FAQs

### When do these definitions apply?

The PHP object definitions apply whenever a supported PHP object is represented as JSON. How you do that depends on your application, but here is an example:

```php
printf(
	'let wpPost = %s;',
	wp_json_encode( get_post() )
);
```

The REST API object definitions apply to each of the objects you get in response to a REST API request, for example when using `apiFetch()`.

The definitions also apply outside of the browser, for example if you're saving data as JSON and reading it in a Node application.

### How do I know these definitions are valid?

They're generated directly from [the `wp-json-schemas` package](https://github.com/johnbillion/wp-json-schemas), which is itself tested using output from WordPress core.

If you'd like to contribute to these definitions, please contribute upstream to the `wp-json-schemas` package. Thanks!

### Can I use the enums as values in my code?

Yes, but:

* You might need `preserveConstEnums` enabled in your TypeScript config
* You should import enums as you would a regular module, not as a `type`
* You cannot iterate enums (this is a TypeScript restriction)

Example:

```ts
import { WP_Post_Type_Name } from 'wp-types';

console.log( WP_Post_Type_Name.auto_draft );
```

## Tests

To test the TypeScript compilation:

```
npm run test
```

## License

MIT
