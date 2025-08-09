<?php
class AI_Agent_Bridge_REST {
    public static function init() {
        add_action('rest_api_init', [__CLASS__, 'register']);
    }

    public static function register() {
        register_rest_route('ai-agent/v1', '/posts', [
            'methods' => 'POST',
            'permission_callback' => [AI_Agent_Bridge_Auth::class, 'permission'],
            'callback' => [__CLASS__, 'create_post'],
        ]);
        register_rest_route('ai-agent/v1', '/posts/(?P<id>\d+)', [
            'methods' => 'PUT',
            'permission_callback' => [AI_Agent_Bridge_Auth::class, 'permission'],
            'callback' => [__CLASS__, 'update_post'],
        ]);
        register_rest_route('ai-agent/v1', '/media', [
            'methods' => 'POST',
            'permission_callback' => [AI_Agent_Bridge_Auth::class, 'permission'],
            'callback' => [__CLASS__, 'add_media'],
        ]);
        register_rest_route('ai-agent/v1', '/ping', [
            'methods' => 'GET',
            'permission_callback' => [AI_Agent_Bridge_Auth::class, 'permission'],
            'callback' => [__CLASS__, 'ping'],
        ]);
        register_rest_route('ai-agent/v1', '/preview', [
            'methods' => 'POST',
            'permission_callback' => [AI_Agent_Bridge_Auth::class, 'permission'],
            'callback' => [__CLASS__, 'preview_link'],
        ]);
        register_rest_route('ai-agent/v1', '/rollback', [
            'methods' => 'POST',
            'permission_callback' => [AI_Agent_Bridge_Auth::class, 'permission'],
            'callback' => [__CLASS__, 'rollback'],
        ]);
    }

    public static function create_post($request) {
        $p = $request->get_json_params();
        $post_id = wp_insert_post([
            'post_title' => wp_strip_all_tags($p['title'] ?? ''),
            'post_content' => $p['content'] ?? '',
            'post_status' => $p['status'] ?? 'draft',
        ]);
        AI_Agent_Bridge_Logger::log('create', ['id' => $post_id]);
        return ['id' => $post_id];
    }

    public static function update_post($request) {
        $id = (int)$request['id'];
        $p = $request->get_json_params();
        $post_id = wp_update_post([
            'ID' => $id,
            'post_content' => $p['content'] ?? '',
        ], true);
        AI_Agent_Bridge_Logger::log('update', ['id' => $id]);
        return ['id' => $post_id];
    }

    public static function add_media($request) {
        $p = $request->get_json_params();
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        if (!empty($p['url'])) {
            $id = media_sideload_image(esc_url_raw($p['url']), 0, null, 'id');
        } elseif (!empty($p['base64'])) {
            $data = base64_decode($p['base64']);
            $name = 'ai-' . time() . '.jpg';
            $bits = wp_upload_bits($name, null, $data);
            $file = $bits['file'];
            $type = wp_check_filetype($file);
            $id = wp_insert_attachment(['post_mime_type' => $type['type'], 'post_title' => $name], $file);
            wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $file));
        } else {
            return new WP_Error('invalid', 'no media');
        }
        AI_Agent_Bridge_Logger::log('media', ['id' => $id]);
        return ['id' => (int)$id];
    }

    public static function ping() {
        return ['pong' => time()];
    }

    public static function preview_link($request) {
        $p = $request->get_json_params();
        $link = get_preview_post_link((int)$p['post_id']);
        return ['link' => $link];
    }

    public static function rollback($request) {
        $p = $request->get_json_params();
        $r = wp_restore_post_revision((int)$p['revision_id']);
        AI_Agent_Bridge_Logger::log('rollback', $p);
        return ['restored' => (bool)$r];
    }
}
