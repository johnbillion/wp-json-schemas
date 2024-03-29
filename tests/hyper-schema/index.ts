/* eslint-disable */
/**
 * This file was automatically generated by json-schema-to-typescript.
 * DO NOT MODIFY IT BY HAND. Instead, modify the source JSONSchema file,
 * and run json-schema-to-typescript to regenerate this file.
 */

export type JSONHyperSchema = JSONHyperSchema1 & JSONHyperSchema2;
export type JSONHyperSchema1 = CoreSchemaMetaSchema;
export type CoreSchemaMetaSchema =
	| {
			$id?: string;
			$schema?: string;
			$ref?: string;
			$comment?: string;
			title?: string;
			description?: string;
			default?: true;
			readOnly?: boolean;
			writeOnly?: boolean;
			examples?: true[];
			multipleOf?: number;
			maximum?: number;
			exclusiveMaximum?: number;
			minimum?: number;
			exclusiveMinimum?: number;
			maxLength?: number;
			minLength?: number;
			pattern?: string;
			additionalItems?: CoreSchemaMetaSchema;
			items?: CoreSchemaMetaSchema | [CoreSchemaMetaSchema, ...CoreSchemaMetaSchema[]];
			maxItems?: number;
			minItems?: number;
			uniqueItems?: boolean;
			contains?: CoreSchemaMetaSchema;
			maxProperties?: number;
			minProperties?: number;
			required?: string[];
			additionalProperties?: CoreSchemaMetaSchema;
			definitions?: {
				[k: string]: CoreSchemaMetaSchema;
			};
			properties?: {
				[k: string]: CoreSchemaMetaSchema;
			};
			patternProperties?: {
				[k: string]: CoreSchemaMetaSchema;
			};
			dependencies?: {
				[k: string]: CoreSchemaMetaSchema | string[];
			};
			propertyNames?: CoreSchemaMetaSchema;
			const?: true;
			enum?: [true, ...unknown[]];
			type?:
				| ("array" | "boolean" | "integer" | "null" | "number" | "object" | "string")
				| [
						"array" | "boolean" | "integer" | "null" | "number" | "object" | "string",
						...("array" | "boolean" | "integer" | "null" | "number" | "object" | "string")[]
				  ];
			format?: string;
			contentMediaType?: string;
			contentEncoding?: string;
			if?: CoreSchemaMetaSchema;
			then?: CoreSchemaMetaSchema;
			else?: CoreSchemaMetaSchema;
			allOf?: [CoreSchemaMetaSchema, ...CoreSchemaMetaSchema[]];
			anyOf?: [CoreSchemaMetaSchema, ...CoreSchemaMetaSchema[]];
			oneOf?: [CoreSchemaMetaSchema, ...CoreSchemaMetaSchema[]];
			not?: CoreSchemaMetaSchema;
			[k: string]: unknown;
	  }
	| boolean;
export type SchemaArray = JSONHyperSchema1[];
/**
 * JSON Schema describing the link target
 */
export type JSONHyperSchema3 = JSONHyperSchema4 & JSONHyperSchema5;
export type JSONHyperSchema4 = CoreSchemaMetaSchema;
/**
 * Schema describing the data to submit along with the request
 */
export type JSONHyperSchema6 = JSONHyperSchema7 & JSONHyperSchema8;
export type JSONHyperSchema7 = CoreSchemaMetaSchema;

export interface JSONHyperSchema2 {
	additionalItems?: boolean | JSONHyperSchema1;
	additionalProperties?: boolean | JSONHyperSchema1;
	dependencies?: {
		[k: string]: JSONHyperSchema1 | unknown[];
	};
	items?: JSONHyperSchema1 | SchemaArray;
	definitions?: {
		[k: string]: JSONHyperSchema1;
	};
	patternProperties?: {
		[k: string]: JSONHyperSchema1;
	};
	properties?: {
		[k: string]: JSONHyperSchema1;
	};
	allOf?: SchemaArray;
	anyOf?: SchemaArray;
	oneOf?: SchemaArray;
	not?: JSONHyperSchema1;
	links?: LinkDescriptionObject[];
	fragmentResolution?: string;
	media?: {
		/**
		 * A media type, as described in RFC 2046
		 */
		type?: string;
		/**
		 * A content encoding scheme, as described in RFC 2045
		 */
		binaryEncoding?: string;
		[k: string]: unknown;
	};
	/**
	 * Instances' URIs must start with this value for this schema to apply to them
	 */
	pathStart?: string;
	[k: string]: unknown;
}
export interface LinkDescriptionObject {
	/**
	 * a URI template, as defined by RFC 6570, with the addition of the $, ( and ) characters for pre-processing
	 */
	href: string;
	/**
	 * relation to the target resource of the link
	 */
	rel: string;
	/**
	 * a title for the link
	 */
	title?: string;
	targetSchema?: JSONHyperSchema3;
	/**
	 * media type (as defined by RFC 2046) describing the link target
	 */
	mediaType?: string;
	/**
	 * method for requesting the target of the link (e.g. for HTTP this might be "GET" or "DELETE")
	 */
	method?: string;
	/**
	 * The media type in which to submit data along with the request
	 */
	encType?: string;
	schema?: JSONHyperSchema6;
	[k: string]: unknown;
}
export interface JSONHyperSchema5 {
	additionalItems?: boolean | JSONHyperSchema1;
	additionalProperties?: boolean | JSONHyperSchema1;
	dependencies?: {
		[k: string]: JSONHyperSchema1 | unknown[];
	};
	items?: JSONHyperSchema1 | SchemaArray;
	definitions?: {
		[k: string]: JSONHyperSchema1;
	};
	patternProperties?: {
		[k: string]: JSONHyperSchema1;
	};
	properties?: {
		[k: string]: JSONHyperSchema1;
	};
	allOf?: SchemaArray;
	anyOf?: SchemaArray;
	oneOf?: SchemaArray;
	not?: JSONHyperSchema1;
	links?: LinkDescriptionObject[];
	fragmentResolution?: string;
	media?: {
		/**
		 * A media type, as described in RFC 2046
		 */
		type?: string;
		/**
		 * A content encoding scheme, as described in RFC 2045
		 */
		binaryEncoding?: string;
		[k: string]: unknown;
	};
	/**
	 * Instances' URIs must start with this value for this schema to apply to them
	 */
	pathStart?: string;
	[k: string]: unknown;
}
export interface JSONHyperSchema8 {
	additionalItems?: boolean | JSONHyperSchema1;
	additionalProperties?: boolean | JSONHyperSchema1;
	dependencies?: {
		[k: string]: JSONHyperSchema1 | unknown[];
	};
	items?: JSONHyperSchema1 | SchemaArray;
	definitions?: {
		[k: string]: JSONHyperSchema1;
	};
	patternProperties?: {
		[k: string]: JSONHyperSchema1;
	};
	properties?: {
		[k: string]: JSONHyperSchema1;
	};
	allOf?: SchemaArray;
	anyOf?: SchemaArray;
	oneOf?: SchemaArray;
	not?: JSONHyperSchema1;
	links?: LinkDescriptionObject[];
	fragmentResolution?: string;
	media?: {
		/**
		 * A media type, as described in RFC 2046
		 */
		type?: string;
		/**
		 * A content encoding scheme, as described in RFC 2045
		 */
		binaryEncoding?: string;
		[k: string]: unknown;
	};
	/**
	 * Instances' URIs must start with this value for this schema to apply to them
	 */
	pathStart?: string;
	[k: string]: unknown;
}
