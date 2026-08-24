#!/usr/bin/env bash

# -e          Exit immediately if a pipeline returns a non-zero status
# -o pipefail Produce a failure return code if any command errors
set -eo pipefail

# json2ts emits an index signature (`[k: string]: unknown`) for any object
# schema that isn't closed with `additionalProperties: false`, and it doesn't
# implement `unevaluatedProperties`. Closing these schemas permanently would
# reject valid data during validation, so they're closed just for the duration
# of the type generation and then restored from a copy.
backup=$(mktemp -d)
trap 'rm -rf "$backup"' EXIT
cp -R schemas "$backup"

# Set additionalProperties to false for all partial schemas
for file in schemas/rest-api/partials/**/*.json
do
	jq --tab '. + { "additionalProperties": false }' "$file" > tmp && mv tmp "$file"
done

# Close the property schemas that are composed into others via allOf, plus the
# schemas they compose. An allOf branch needs closing in its own right because
# json2ts turns each branch into a separate member of an intersection type.
for file in schemas/rest-api/properties/*.json
do
	jq --tab '
		if has("allOf") then
			del(.unevaluatedProperties)
			| .allOf |= map(if has("properties") then . + { "additionalProperties": false } else . end)
			| . + { "additionalProperties": false }
		elif has("properties") and (has("additionalProperties") | not) then
			. + { "additionalProperties": false }
		else
			.
		end
	' "$file" > tmp && mv tmp "$file"
done

# Generate TypeScript types
./node_modules/.bin/json2ts -i schema.json -o packages/wp-types/index.ts --style.trailingComma=all --style.useTabs

# Restore the schemas
cp -R "$backup/schemas/." schemas/

# Append append.ts to the generated types
cat packages/wp-types/append.ts >> packages/wp-types/index.ts

# Don't export the partial interfaces
sed -i.bak 's/export interface WP_REST_API_Partial_/interface WP_REST_API_Partial_/g' packages/wp-types/index.ts
rm packages/wp-types/index.ts.bak
