<?php
/**
 * Content Transformer AI Ability.
 *
 * @package PRC\Platform\Content_Transformer\AI_Experiment
 */

namespace PRC\Platform\Content_Transformer\AI_Experiment;

use PRC\Platform\Content_Transformer\Pipeline\Transformation_Pipeline;
use PRC\Platform\Content_Transformer\Providers\Provider_Registry;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers an AI ability that exposes content transformation via the
 * WordPress Abilities API.
 */
class Content_Transformer_Ability {

	/**
	 * Plugin file used to validate activation on the target site.
	 *
	 * @var string
	 */
	private const PLUGIN_FILE = 'prc-content-transformer/prc-content-transformer.php';

	/**
	 * Ability name.
	 *
	 * @var string
	 */
	public static $ability_name = 'prc-content-transformer/transform';

	/**
	 * Register the ability with the WP Abilities API.
	 *
	 * @hook wp_abilities_api_init
	 */
	public function register_ability() {
		wp_register_ability(
			self::$ability_name,
			array(
				'label'               => __( 'Transform Content', 'prc-content-transformer' ),
				'description'         => __( 'Transforms a post\'s content into a provider-specific format (Apple News, email HTML, plain text, etc.) using AI.', 'prc-content-transformer' ),
				'category'            => 'data-retrieval',
				'input_schema'        => array(
					'type'                 => 'object',
					'properties'           => array(
						'post_id'  => array(
							'type'        => 'integer',
							'description' => 'The post ID to transform.',
						),
						'provider' => array(
							'type'        => 'string',
							'description' => 'The provider slug (e.g. "plain-text", "apple-news", "email").',
						),
						'force'    => array(
							'type'        => 'boolean',
							'description' => 'Whether to bypass the cache and force a fresh transformation.',
							'default'     => false,
						),
						'site_id'  => \PRC\Platform\AI\Utils\site_id_input_schema_property(),
					),
					'required'             => array( 'post_id', 'provider' ),
					'additionalProperties' => false,
				),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'status'      => array(
							'type'        => 'string',
							'description' => 'Transformation status: success, cached, failed.',
						),
						'output'      => array(
							'type'        => 'string',
							'description' => 'The transformed content.',
						),
						'provider'    => array(
							'type'        => 'string',
							'description' => 'The provider slug used.',
						),
						'tokens_used' => array(
							'type'        => 'integer',
							'description' => 'Estimated tokens consumed.',
						),
						'error'       => array(
							'type'        => 'string',
							'description' => 'Error message, if any.',
						),
					),
				),
				'execute_callback'    => function ( $input ) {
					return $this->with_site(
						$input,
						function () use ( $input ) {
							return $this->execute( $input );
						}
					);
				},
				'permission_callback' => function ( $input = null ) {
					return $this->with_site(
						$input,
						function () {
							return current_user_can( 'edit_posts' );
						}
					);
				},
				'meta'                => array(
					'annotations'  => array(
						'instructions' => 'This ability transforms a WordPress post into a provider-specific format using AI. Supply a post_id and provider slug. Optionally pass site_id to run against a specific multisite blog; defaults to the content site (20). If this plugin is inactive on the target site, the ability returns plugin_inactive_on_site. Available providers can be discovered via the /providers REST endpoint.',
						'readonly'     => true,
						'destructive'  => false,
						'idempotent'   => false,
					),
					'show_in_rest' => true,
					'mcp'          => array(
						'public' => true,
						'type'   => 'tool',
					),
				),
			)
		);
	}

	/**
	 * Execute the content transformation ability.
	 *
	 * @param array $input The input parameters.
	 * @return array
	 */
	public function execute( $input ) {
		$post_id  = $input['post_id'] ?? 0;
		$provider = $input['provider'] ?? '';
		$force    = $input['force'] ?? false;

		if ( empty( $post_id ) ) {
			return array(
				'status' => 'failed',
				'error'  => 'A valid post_id is required.',
				'output' => '',
			);
		}

		if ( empty( $provider ) ) {
			$available = array_keys( Provider_Registry::get_available() );
			return array(
				'status' => 'failed',
				'error'  => 'A provider slug is required. Available: ' . implode( ', ', $available ),
				'output' => '',
			);
		}

		$result = Transformation_Pipeline::transform( $post_id, $provider, $force );

		return $result->to_array();
	}

	/**
	 * Run a callback on the requested target site.
	 *
	 * @param array|null $input    Ability input.
	 * @param callable   $callback Callback to run after site validation/switching.
	 * @return mixed
	 */
	private function with_site( $input, callable $callback ) {
		return \PRC\Platform\AI\Utils\with_site(
			\PRC\Platform\AI\Utils\resolve_site_id( is_array( $input ) ? $input : null ),
			self::PLUGIN_FILE,
			$callback
		);
	}
}
