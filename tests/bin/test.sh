#!/usr/bin/env bash

# -e          Exit immediately if a pipeline returns a non-zero status
# -o pipefail Produce a failure return code if any command errors
set -eo pipefail

function test_missing_properties() {
	./node_modules/node-jq/bin/jq -e '(.required // []) as $req | (.properties // []) as $props | ($req - ($req | map(select(. as $r | $props | has($r)))) | length) == 0' "$1" > /dev/null \
		|| ( echo "Properties in the 'required' element of $1 are missing from the 'properties' element" && exit 1 )
}

function validate_schema() {
	local file="$1"
	local base=${file//schemas\//}

	# https://github.com/ajv-validator/ajv-cli/issues/172
	local schemas=$(find schemas -type f -name "*.json" | grep -v "$file")
	local rflag="${schemas//$'\n'/ -r }"

	local filename=${base/.json/}

	./node_modules/.bin/ajv validate --strict --strict-schema=false -c ajv-formats -m tests/hyper-schema/hyper-schema.json -r schema.json -r $rflag -s "$file" -d "tests/data/$filename/*.json"
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
