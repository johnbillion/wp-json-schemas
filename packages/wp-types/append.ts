
type ValueOf<T> = T[keyof T]

/**
 * An enveloped REST API response (with `?_envelope`).
 *
 * @template T A REST API response type.
 */
export interface WP_REST_API_Envelope<T extends ValueOf<WP["REST_API"]>> {
	/**
	 * The response body
	 */
	body: T;
	/**
	 * The HTTP status code
	 */
	status: WP_Http_Status_Code;
	/**
	 * The HTTP headers
	 */
	headers: {
		[k: string]: string|number;
	};
	[k: string]: unknown;
}
