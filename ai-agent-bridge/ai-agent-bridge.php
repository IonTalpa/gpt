<?php
/**
 * Plugin Name: AI Agent Bridge
 * Description: Bridge for AI backend to manage content via signed requests.
 * Version: 1.0.0
 * Author: ChatGPT
 * Text Domain: ai-agent-bridge
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AI_AGENT_BRIDGE_VERSION', '1.0.0');
define('AI_AGENT_BRIDGE_PATH', plugin_dir_path(__FILE__));
define('AI_AGENT_BRIDGE_URL', plugin_dir_url(__FILE__));

// load i18n
function ai_agent_bridge_load_textdomain() {
    load_plugin_textdomain('ai-agent-bridge', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'ai_agent_bridge_load_textdomain');

// includes
require_once AI_AGENT_BRIDGE_PATH . 'includes/class-ai-agent-bridge-logger.php';
require_once AI_AGENT_BRIDGE_PATH . 'includes/class-ai-agent-bridge-auth.php';
require_once AI_AGENT_BRIDGE_PATH . 'includes/class-ai-agent-bridge-rest.php';
require_once AI_AGENT_BRIDGE_PATH . 'includes/class-ai-agent-bridge-admin.php';

// init
add_action('init', function() {
    AI_Agent_Bridge_REST::init();
    AI_Agent_Bridge_Admin::init();
});

register_activation_hook(__FILE__, ['AI_Agent_Bridge_Logger', 'install']);
