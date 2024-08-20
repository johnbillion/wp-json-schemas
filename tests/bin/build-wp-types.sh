#!/usr/bin/env bash

# -e          Exit immediately if a pipeline returns a non-zero status
# -o pipefail Produce a failure return code if any command errors
set -eo pipefail

./node_modules/.bin/json2ts -i schema.json -o packages/wp-types/index.ts --style.trailingComma=all --style.useTabs
cat packages/wp-types/append.ts >> packages/wp-types/index.ts
sed -i.bak 's/export interface WP_REST_API_Partial_/interface WP_REST_API_Partial_/g' packages/wp-types/index.ts
rm packages/wp-types/index.ts.bak
