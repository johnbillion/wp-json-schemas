#!/usr/bin/env bash

# -e          Exit immediately if a pipeline returns a non-zero status
# -o pipefail Produce a failure return code if any command errors
set -eo pipefail

function test_missing_properties() {
	./node_modules/node-jq/bin/jq -e '(.required // []) as $req | (.properties // []) as $props | ($req - ($req | map(select(. as $r | $props | has($r)))) | length) == 0' "$1" > /dev/null \
		|| ( echo "Properties in the 'required' element of $1 are missing from the 'properties' element" && exit 1 )
}

function validate_schema() {
	local schema="$1"
	local file="${2:-$schema}"
	local base=${file//schemas\//}
	local base=${base/.json/}
	./node_modules/.bin/ajv validate -m tests/hyper-schema/hyper-schema.json -r "schemas/**/*.json" -s "$schema" -d "tests/data/$base/*.json"
}

for file in schemas/*.json
do
	test_missing_properties "$file"
	validate_schema "$file"
done

for file in schemas/rest-api/*.json
do
	test_missing_properties "$file"
	validate_schema "$file"
done

# Validate the wp/v2/pages response against the posts schema
validate_schema "schemas/rest-api/posts.json" "rest-api/pages"
