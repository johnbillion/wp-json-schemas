#!/usr/bin/env bash

# -e          Exit immediately if a pipeline returns a non-zero status
# -o pipefail Produce a failure return code if any command errors
set -eo pipefail

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
	validate_schema "$file"
done

for file in schemas/rest-api/*.json
do
	./node_modules/node-jq/bin/jq --tab '. + { "additionalProperties": false }' "$file" > tmp && mv tmp "$file"
done

for file in schemas/rest-api/collections/*.json
do
	validate_schema "$file"
done

for file in schemas/rest-api/*.json
do
	if [[ "$file" == "schemas/rest-api/error.json" ]]
	then
		continue
	fi
	./node_modules/node-jq/bin/jq --tab 'del(.additionalProperties)' "$file" > tmp && mv tmp "$file"
done
