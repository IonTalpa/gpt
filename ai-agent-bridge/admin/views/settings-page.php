<div class="wrap">
<h1><?php echo esc_html__('AI Agent Bridge', 'ai-agent-bridge'); ?></h1>
<form method="post" action="options.php">
<?php settings_fields('ai_agent'); ?>
<table class="form-table" role="presentation">
<tr>
<th scope="row"><label for="ai_agent_shared_secret"><?php _e('Shared Secret', 'ai-agent-bridge'); ?></label></th>
<td><input type="text" name="ai_agent_shared_secret" id="ai_agent_shared_secret" value="<?php echo esc_attr(get_option('ai_agent_shared_secret')); ?>" class="regular-text" /></td>
</tr>
<tr>
<th scope="row"><label for="ai_agent_ip_allowlist"><?php _e('IP Allowlist (comma separated)', 'ai-agent-bridge'); ?></label></th>
<td><input type="text" name="ai_agent_ip_allowlist" id="ai_agent_ip_allowlist" value="<?php echo esc_attr(get_option('ai_agent_ip_allowlist')); ?>" class="regular-text" /></td>
</tr>
</table>
<?php submit_button(); ?>
</form>
<button id="ai-agent-ping" class="button"><?php _e('Test Ping', 'ai-agent-bridge'); ?></button>
<div id="ai-agent-ping-result"></div>
</div>
<script>
(function($){
    $('#ai-agent-ping').on('click', function(){
        $('#ai-agent-ping-result').text('...');
        $.post(ajaxurl, {action:'ai_agent_ping', _ajax_nonce:'<?php echo wp_create_nonce('ai_agent_ping'); ?>'}, function(r){
            $('#ai-agent-ping-result').text(r.response ? r.response.message : 'error');
        });
    });
})(jQuery);
</script>
