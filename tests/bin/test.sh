#!/usr/bin/env bash

# -e          Exit immediately if a pipeline returns a non-zero status
# -o pipefail Produce a failure return code if any command errors
set -eo pipefail

# Validate a schema file
function validate_schema() {
	local file="$1"
	local base=${file//schemas\//}

	# https://github.com/ajv-validator/ajv-cli/issues/172
	local schemas=$(find schemas -type f -name "*.json" | grep -v "$file")
	local rflag="${schemas//$'\n'/ -r }"

	local filename=${base/.json/}

	ls tests/data/$filename/*.json > /dev/null
	./node_modules/.bin/ajv validate --spec=draft2019 --strict --strict-schema=false -c ajv-formats -m tests/external-schemas/hyper-schema.json -r schema.json -r $rflag -s "$file" -d "tests/data/$filename/*.json"
}

# Modify a schema file using a jq transformation and an optional jq condition
function modify_schema() {
	local file="$1"
	local changes="$2"
	local condition="$3"

	if [[ "$condition" != "" ]]
	then
		if [[ $(./node_modules/node-jq/bin/jq -e "$condition" "$file") == false ]]
		then
			return
		fi
	fi

	./node_modules/node-jq/bin/jq --tab "$changes" "$file" > tmp
	mv tmp "$file"
}

# Cleanup function to remove unevaluatedProperties and additionalProperties
function cleanup() {
	for file in schemas/rest-api/*.json
	do
		if [[ "${IGNORE_FILES[*]}" =~ "${file}" ]]
		then
			continue
		fi
		modify_schema "$file" 'del(.unevaluatedProperties)'
		modify_schema "$file" 'del(.properties._embedded.additionalProperties)'
	done
}

# Files to ignore when disallowing additional properties
IGNORE_FILES=("schemas/rest-api/error.json")

# Always cleanup regardless of how the script exits
trap cleanup EXIT

# Validate all PHP object schemas in parallel
pids=()
for file in schemas/*.json
do
	validate_schema "$file" &
	pids+=($!)
done

# Wait for all jobs and capture exit codes
for pid in "${pids[@]}"
do
	wait "$pid" || exit $?
done

# Disallow additional root properties in all REST API schemas (via unevaluatedProperties)
# Disallow additional properties in the _embedded field in all REST API schemas
for file in schemas/rest-api/*.json
do
	if [[ "${IGNORE_FILES[*]}" =~ "${file}" ]]
	then
		continue
	fi
	modify_schema "$file" '. + { "unevaluatedProperties": false }'
	modify_schema "$file" '.properties._embedded += { "additionalProperties": false }' '.properties._embedded != null'
done

# Validation for REST API collections in parallel:
pids=()
for file in schemas/rest-api/collections/*.json
do
	validate_schema "$file" &
	pids+=($!)
done

# Validation for REST API entities that don't have a directly corresponding collection:
validate_schema schemas/rest-api/global-style-variation.json &
pids+=($!)
validate_schema schemas/rest-api/global-style-config.json &
pids+=($!)

# Wait for all parallel validation jobs to complete and capture exit codes
for pid in "${pids[@]}"
do
	wait "$pid" || exit $?
done
