<?php
/**
 * Apple News Provider.
 *
 * @package PRC\Platform\Content_Transformer\Providers
 */

namespace PRC\Platform\Content_Transformer\Providers;

/**
 * Transforms content into Apple News Format (ANF) JSON.
 */
class Apple_News_Provider implements Provider {

	public function get_name(): string {
		return 'Apple News';
	}

	public function get_slug(): string {
		return 'apple-news';
	}

	public function get_format_spec(): string {
		$spec_path = PRC_CONTENT_TRANSFORMER_DIR . '/includes/format-specs/apple-news.md';
		if ( file_exists( $spec_path ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			return file_get_contents( $spec_path );
		}
		return '';
	}

	public function get_output_type(): string {
		return 'json';
	}

	/**
	 * Validate the output is valid ANF JSON with required top-level fields.
	 *
	 * @param string $output The raw AI output.
	 * @return bool
	 */
	public function validate( string $output ): bool {
		$data = json_decode( $output, true );
		if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $data ) ) {
			return false;
		}

		// Required top-level fields per ANF spec.
		$required = array( 'version', 'identifier', 'title', 'layout', 'components' );
		foreach ( $required as $field ) {
			if ( ! isset( $data[ $field ] ) ) {
				return false;
			}
		}

		if ( ! is_array( $data['components'] ) || empty( $data['components'] ) ) {
			return false;
		}

		// Every component must have a role.
		foreach ( $data['components'] as $component ) {
			if ( ! isset( $component['role'] ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Post-process: strip any markdown code fences the AI might wrap around JSON.
	 *
	 * @param string $output The raw AI output.
	 * @return string
	 */
	public function post_process( string $output ): string {
		$output = trim( $output );

		// Strip leading ```json and trailing ```.
		if ( preg_match( '/^```(?:json)?\s*\n?(.*?)\n?```$/s', $output, $matches ) ) {
			$output = $matches[1];
		}

		return trim( $output );
	}

	public function is_available(): bool {
		return true;
	}
}
