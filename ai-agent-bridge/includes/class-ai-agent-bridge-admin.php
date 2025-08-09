<?php
class AI_Agent_Bridge_Admin {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_init', [__CLASS__, 'settings']);
        add_action('wp_ajax_ai_agent_ping', [__CLASS__, 'ajax_ping']);
    }

    public static function menu() {
        add_options_page('AI Agent', 'AI Agent', 'manage_options', 'ai-agent', [__CLASS__, 'page']);
    }

    public static function settings() {
        register_setting('ai_agent', 'ai_agent_shared_secret');
        register_setting('ai_agent', 'ai_agent_ip_allowlist');
    }

    public static function page() {
        include AI_AGENT_BRIDGE_PATH . 'admin/views/settings-page.php';
    }

    public static function ajax_ping() {
        check_ajax_referer('ai_agent_ping');
        $secret = get_option('ai_agent_shared_secret');
        if (!$secret) wp_send_json_error('no secret');
        $header = self::b64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = self::b64url(json_encode(['iat' => time(), 'exp' => time() + 60]));
        $sig = self::b64url(hash_hmac('sha256', "$header.$payload", $secret, true));
        $token = "$header.$payload.$sig";
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'X-AI-Timestamp' => time(),
                'X-AI-Nonce' => wp_generate_uuid4(),
                'Idempotency-Key' => wp_generate_uuid4(),
            ],
        ];
        $res = wp_remote_get(rest_url('ai-agent/v1/ping'), $args);
        wp_send_json($res['response']);
    }

    private static function b64url($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
