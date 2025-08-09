<?php
class AI_Agent_Bridge_Auth {
    private static function b64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function verify_jwt($token, $secret) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;
        list($h, $p, $s) = $parts;
        $sig = self::b64url_decode($s);
        $expected = hash_hmac('sha256', "$h.$p", $secret, true);
        if (!hash_equals($expected, $sig)) return false;
        $payload = json_decode(self::b64url_decode($p), true);
        if (isset($payload['exp']) && time() >= $payload['exp']) return false;
        return $payload;
    }

    public static function permission($request) {
        $secret = get_option('ai_agent_shared_secret');
        if (!$secret) return new WP_Error('forbidden', 'secret');

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $allow = get_option('ai_agent_ip_allowlist');
        if ($allow) {
            $list = array_map('trim', explode(',', $allow));
            if (!in_array($ip, $list, true)) return new WP_Error('forbidden', 'ip');
        }

        $rate_key = 'ai_agent_rate_' . md5($ip);
        $hits = (int)get_transient($rate_key);
        if ($hits > 30) return new WP_Error('rate_limited', 'rate');
        set_transient($rate_key, $hits + 1, MINUTE_IN_SECONDS);

        $timestamp = (int)$request->get_header('x-ai-timestamp');
        if (abs(time() - $timestamp) > 300) return new WP_Error('forbidden', 'ts');

        $nonce = $request->get_header('x-ai-nonce');
        if (!$nonce || get_transient('ai_agent_nonce_' . $nonce)) return new WP_Error('forbidden', 'nonce');
        set_transient('ai_agent_nonce_' . $nonce, 1, 300);

        $idem = $request->get_header('idempotency-key');
        if ($idem && get_transient('ai_agent_idem_' . $idem)) return new WP_Error('conflict', 'idempotent');
        if ($idem) set_transient('ai_agent_idem_' . $idem, 1, DAY_IN_SECONDS);

        $auth = $request->get_header('authorization');
        if (!preg_match('/Bearer\s+(.*)/', $auth, $m)) return new WP_Error('forbidden', 'auth');
        $payload = self::verify_jwt(trim($m[1]), $secret);
        if (!$payload) return new WP_Error('forbidden', 'jwt');

        return true;
    }
}
