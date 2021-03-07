# Contributing to WordPress JSON Schemas

Bug fixes and new schema contributions are very welcome.

There is no fully automatic process to create these schemas. A schema for a PHP object is created from the public properties of its class, and a schema for a REST API response is created from an OPTIONS request to its REST API endpoint.

## Creating a REST API response schema

The WordPress REST API response doesn't fully adhere to the JSON schema spec, so we need to tweak its output in order to generate a valid schema.

* Perform an `OPTIONS` request to an endpoint that returns the object (or array of the objects) that you want to create a schema for. For example `/wp-json/wp/v2/users`.
* Use the `schema` property from the response as a basis for the schema.
* Set the `$schema`, `$id`, `title`, and `description` properties using the same format as existing schemas.
* Remove any properties that have a `context` of `[]` as these are write-only and not exposed. So far I've only seen this for the `password` property of a user.
* Remove any `properties` properties that have a value of `[]`.
* All properties that have a `context` of `view` should be added to the `required` property.
* Remove the `context`, `required`, and `readonly` properties from each property as these are not valid JSON schema properties.
* Add `additionalProperties` information to any `object` properties as appropriate.
* Cross-reference the properties with those in the `get_item_schema()` method of the controller class. There may be properties that are conditionally added.
* Add the schema to the root `schema.json` using a `$ref` to the schema file.
* Run `npm run test`.

## Creating a PHP object schema

The schema for a PHP object is created using the docblocks from its class properties and a lot of manual adjustment.

* JSON encode an instance of the class you're interested in. For example:

      echo json_encode( get_post( 123 ), JSON_PRETTY_PRINT );

* Make a note of all of its property names.
* Open the corresponding PHP file for the class.
* Correlate this public property list with those defined in the class.
  - Why? An object can have a public property that's not declared on the class. In this case, you'll need to provide the type and description yourself.
* Wave your hands over your keyboard.
* Add the schema to the root `schema.json` using a `$ref` to the schema file.
* Run `npm run test`.

## Releasing a new version

* `npm run test`
* `npm version <major|minor|patch>`
* `git push`
* `git push --tags`

When a tag is pushed, the following packages will be deployed to npm automatically via a GitHub Action:

* [wp-json-schemas](https://www.npmjs.com/package/wp-json-schemas)
* [wp-types](https://www.npmjs.com/package/wp-types)
