<?php
/**
 * Transformation Result value object.
 *
 * @package PRC\Platform\Content_Transformer\Pipeline
 */

declare(strict_types=1);

namespace PRC\Platform\Content_Transformer\Pipeline;

/**
 * Immutable value object representing the outcome of a content transformation.
 */
class Transformation_Result {

	/**
	 * Transformation status: 'success', 'failed', 'pending', or 'cached'.
	 *
	 * @var string
	 */
	private $status;

	/**
	 * Transformed output content.
	 *
	 * @var string
	 */
	private $output;

	/**
	 * Provider slug that produced this result.
	 *
	 * @var string
	 */
	private $provider;

	/**
	 * Unix timestamp of when the transformation completed.
	 *
	 * @var int
	 */
	private $timestamp;

	/**
	 * Estimated token usage for the AI call.
	 *
	 * @var int
	 */
	private $tokens_used;

	/**
	 * Error message, if any.
	 *
	 * @var string
	 */
	private $error;

	/**
	 * Create a transformation result.
	 *
	 * @param string $status      The transformation status.
	 * @param string $output      The transformed content.
	 * @param string $provider    The provider slug.
	 * @param int    $timestamp   Unix timestamp.
	 * @param int    $tokens_used Estimated tokens consumed.
	 * @param string $error       Error message on failure.
	 */
	public function __construct(
		string $status,
		string $output = '',
		string $provider = '',
		int $timestamp = 0,
		int $tokens_used = 0,
		string $error = ''
	) {
		$this->status      = $status;
		$this->output      = $output;
		$this->provider    = $provider;
		$this->timestamp   = 0 === $timestamp ? time() : $timestamp;
		$this->tokens_used = $tokens_used;
		$this->error       = $error;
	}

	/**
	 * Transformation status.
	 *
	 * @return string
	 */
	public function get_status(): string {
		return $this->status;
	}

	/**
	 * Transformed output content.
	 *
	 * @return string
	 */
	public function get_output(): string {
		return $this->output;
	}

	/**
	 * Provider slug that produced this result.
	 *
	 * @return string
	 */
	public function get_provider(): string {
		return $this->provider;
	}

	/**
	 * Unix timestamp of completion.
	 *
	 * @return int
	 */
	public function get_timestamp(): int {
		return $this->timestamp;
	}

	/**
	 * Estimated token usage.
	 *
	 * @return int
	 */
	public function get_tokens_used(): int {
		return $this->tokens_used;
	}

	/**
	 * Error message, or an empty string.
	 *
	 * @return string
	 */
	public function get_error(): string {
		return $this->error;
	}

	/**
	 * Whether the result holds usable output (fresh or cached).
	 *
	 * @return bool
	 */
	public function is_success(): bool {
		return 'success' === $this->status || 'cached' === $this->status;
	}

	/**
	 * Serialize to an array (for post meta storage and REST responses).
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array(
			'status'      => $this->status,
			'output'      => $this->output,
			'provider'    => $this->provider,
			'timestamp'   => $this->timestamp,
			'tokens_used' => $this->tokens_used,
			'error'       => $this->error,
		);
	}

	/**
	 * Reconstitute from a stored array.
	 *
	 * @param array $data The serialized result data.
	 * @return self
	 */
	public static function from_array( array $data ): self {
		return new self(
			(string) ( $data['status'] ?? 'failed' ),
			(string) ( $data['output'] ?? '' ),
			(string) ( $data['provider'] ?? '' ),
			(int) ( $data['timestamp'] ?? 0 ),
			(int) ( $data['tokens_used'] ?? 0 ),
			(string) ( $data['error'] ?? '' )
		);
	}
}
