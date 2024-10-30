<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');}

class Commentor_Admin_Ajax {

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        add_action('wp_ajax_commentor_admin_settings', [$this , 'commentor_admin_settings']);
    }

    public function commentor_admin_settings()
    {
        $wp_nonce = ! empty($_POST['wp_nonce']) ? sanitize_text_field(wp_unslash($_POST['wp_nonce'])) : null;

        if (! wp_verify_nonce($wp_nonce, 'wp_nonce')) {
            wp_send_json_error([
                'message' => esc_html__('Your request encountered a problem', 'commentor')
            ]);
        }

        if (! current_user_can('manage_options')) {
            wp_send_json_error([
                'message' => esc_html__('You dont have access to update settings', 'commentor')
            ]);
        }

        $successful_count = 0;

        $settings = ! empty($_POST['settings']) ? $_POST['settings'] : [];

        foreach ($settings as $setting) {
            $update_result = update_option(sanitize_text_field($setting['key']), sanitize_text_field($setting['value']));

            if ($update_result) {
                $successful_count++;
            }
        }

        wp_send_json_success([
            'message' => esc_html__('Settings saved successfully', 'commentor')
        ]);
    }

}

$Commentor_Admin_Ajax = new Commentor_Admin_Ajax();
