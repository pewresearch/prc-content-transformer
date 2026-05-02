<?php
/**
 * Email Provider.
 *
 * @package PRC\Platform\Content_Transformer\Providers
 */

namespace PRC\Platform\Content_Transformer\Providers;

/**
 * Transforms content into email-safe HTML compatible with Mailchimp templates.
 */
class Email_Provider implements Provider {

	public function get_name(): string {
		return 'Email HTML';
	}

	public function get_slug(): string {
		return 'email';
	}

	public function get_format_spec(): string {
		$spec_path = PRC_CONTENT_TRANSFORMER_DIR . '/includes/format-specs/email-html.md';
		if ( file_exists( $spec_path ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			return file_get_contents( $spec_path );
		}
		return '';
	}

	public function get_output_type(): string {
		return 'html';
	}

	/**
	 * Validate the output contains table-based email HTML.
	 *
	 * @param string $output The raw AI output.
	 * @return bool
	 */
	public function validate( string $output ): bool {
		$output = trim( $output );
		if ( empty( $output ) ) {
			return false;
		}

		// Must contain at least one table element (email layout requirement).
		if ( stripos( $output, '<table' ) === false ) {
			return false;
		}

		// Should not contain document-level elements.
		if ( stripos( $output, '<!DOCTYPE' ) !== false || stripos( $output, '<html' ) !== false ) {
			return false;
		}

		return true;
	}

	/**
	 * Post-process: strip any markdown code fences the AI might wrap around HTML.
	 *
	 * @param string $output The raw AI output.
	 * @return string
	 */
	public function post_process( string $output ): string {
		$output = trim( $output );

		if ( preg_match( '/^```(?:html)?\s*\n?(.*?)\n?```$/s', $output, $matches ) ) {
			$output = $matches[1];
		}

		return trim( $output );
	}

	public function is_available(): bool {
		return true;
	}
}
