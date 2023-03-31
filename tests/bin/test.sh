#!/usr/bin/env bash

# -e          Exit immediately if a pipeline returns a non-zero status
# -o pipefail Produce a failure return code if any command errors
set -eo pipefail

function validate_schema() {
	local file="$1"
	local base=${file//schemas\//}
	local base=${base/.json/}
	./node_modules/.bin/ajv validate -m tests/hyper-schema/hyper-schema.json -r "schemas/**/*.json" -s "$file" -d "tests/data/$base/*.json"
}

for file in schemas/*.json
do
	validate_schema "$file"
done

for file in schemas/rest-api/*.json
do
	validate_schema "$file"
done
