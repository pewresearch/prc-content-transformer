<?php
/**
 * Plain Text Provider.
 *
 * @package PRC\Platform\Content_Transformer\Providers
 */

declare(strict_types=1);

namespace PRC\Platform\Content_Transformer\Providers;

/**
 * Transforms content into clean plain text.
 *
 * This is the simplest provider and serves as a baseline for testing
 * the transformation pipeline end-to-end.
 */
class Plain_Text_Provider implements Provider {

	/**
	 * Human-readable provider name.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'Plain Text';
	}

	/**
	 * Unique provider slug.
	 *
	 * @return string
	 */
	public function get_slug(): string {
		return 'plain-text';
	}

	/**
	 * Format specification sent to the AI model.
	 *
	 * @return string
	 */
	public function get_format_spec(): string {
		$spec_path = PRC_CONTENT_TRANSFORMER_DIR . '/includes/format-specs/plain-text.md';
		if ( file_exists( $spec_path ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			return (string) file_get_contents( $spec_path );
		}
		return '';
	}

	/**
	 * Output content type.
	 *
	 * @return string
	 */
	public function get_output_type(): string {
		return 'text';
	}

	/**
	 * Validate the raw AI output.
	 *
	 * @param string $output The raw AI output.
	 * @return bool
	 */
	public function validate( string $output ): bool {
		return ! empty( trim( $output ) );
	}

	/**
	 * Clean up the raw AI output.
	 *
	 * @param string $output The raw AI output.
	 * @return string
	 */
	public function post_process( string $output ): string {
		return trim( $output );
	}

	/**
	 * Whether the provider can run on this site.
	 *
	 * @return bool
	 */
	public function is_available(): bool {
		return true;
	}
}
