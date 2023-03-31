#!/usr/bin/env bash

# -e          Exit immediately if a pipeline returns a non-zero status
# -o pipefail Produce a failure return code if any command errors
set -eo pipefail

function test_missing_properties() {
	local file="$1"
	if [ "[]" != "$(./node_modules/node-jq/bin/jq -r '(.required // []) as $req | (.properties // []) as $props | $req - ($req | map(select(. as $r | $props | has($r))))' "$file")" ]
	then
		echo "Properties in the 'required' element of ${file} are missing from the 'properties' element" 1>&2
		exit 1
	fi
}

function validate_schema() {
	local file="$1"
	local base=${file#schemas/}
	local base=${base%.json}
	ls tests/data/${base}/*.json >/dev/null
	./node_modules/.bin/ajv validate -m tests/hyper-schema/hyper-schema.json -r "schemas/**/*.json" -s "$file" -d "tests/data/${base}/*.json"
}

for schema in schemas/*.json schemas/rest-api/*.json
do
	test_missing_properties "$schema"
	validate_schema "$schema"
done
