<?php
/**
 * PRC Content Transformer
 *
 * @package           PRC_Content_Transformer
 * @author            Seth Rubenstein
 * @copyright         2026 Pew Research Center
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       PRC Content Transformer
 * Plugin URI:        https://github.com/pewresearch/prc-platform
 * Description:       AI-powered middleware that transforms WordPress content into provider-specific formats (Apple News, email HTML, plain text).
 * Version:           1.0.0
 * Requires at least: 6.8
 * Requires PHP:      8.2
 * Author:            Pew Research Center
 * Author URI:        https://www.pewresearch.org  // pragma: allowlist secret
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       prc-content-transformer
 * Requires Plugins:  prc-scripts
 */

namespace PRC\Platform\Content_Transformer;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PRC_CONTENT_TRANSFORMER_FILE', __FILE__ );
define( 'PRC_CONTENT_TRANSFORMER_DIR', __DIR__ );
define( 'PRC_CONTENT_TRANSFORMER_VERSION', '1.0.0' );

require plugin_dir_path( __FILE__ ) . 'includes/class-bootstrap.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
function run_prc_content_transformer() {
	$plugin = new Bootstrap();
	$plugin->run();
}
run_prc_content_transformer();
