<?php
class AI_Agent_Bridge_Logger {
    public static function install() {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_agent_logs';
        $charset = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table (id BIGINT AUTO_INCREMENT PRIMARY KEY, action VARCHAR(100), data LONGTEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP) $charset;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public static function log($action, $data = []) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_agent_logs';
        $wpdb->insert($table, [
            'action' => $action,
            'data' => wp_json_encode($data),
        ]);
    }
}
