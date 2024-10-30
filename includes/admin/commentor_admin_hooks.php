<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');}

class Commentor_Admin_Hooks {

    public string $plugin_version = '1.0.0';

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_custom_script']);
        add_action('admin_menu', [$this, 'commentor_dashboard_menu']);
        add_action('admin_menu', [$this, 'commentor_settings_menu']);
    }

    public function enqueue_custom_script() {
        $screen = get_current_screen();

        if ($screen->id === 'toplevel_page_commentor-dashboard') {
            wp_enqueue_style('commentor-admin', COMMENTOR_PLUGIN_URI . 'assets/css/admin.css', array(), $this->plugin_version, 'all');
            wp_enqueue_script( 'commentor-apex-chart', COMMENTOR_PLUGIN_URI . 'assets/js/apex-chart.js', array('jquery'), $this->plugin_version, true);
        }

        if ($screen->id === 'toplevel_page_commentor-dashboard' || $screen->id === 'commentor_page_commentor-settings') {
            wp_enqueue_script( 'commentor-admin', COMMENTOR_PLUGIN_URI . 'assets/js/admin.js', array('jquery'), $this->plugin_version, true);
            wp_localize_script('commentor-admin', 'commentor_data', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wp_nonce')
            ));
        }
    }

    public function commentor_dashboard_menu() {
        add_menu_page(
            __('Commentor', 'commentor'),
            __('Commentor', 'commentor'),
            'manage_options',
            'commentor-dashboard',
            [$this, 'commentor_dashboard_menu_callback'],
            'dashicons-format-chat',
            85
        );
    }

    function commentor_dashboard_menu_callback() {
        include COMMENTOR_PLUGIN_PATH . 'templates/admin/dashboard.php';
    }

    public function commentor_settings_menu() {
        add_submenu_page(
            'commentor-dashboard',
            __('Settings', 'commentor'),
            __('Settings', 'commentor'),
            'manage_options',
            'commentor-settings',
            [$this, 'commentor_settings_menu_callback'],
            5
        );
    }

    function commentor_settings_menu_callback() {
        include COMMENTOR_PLUGIN_PATH . 'templates/admin/settings.php';
    }

}

$Commentor_Admin_Hooks = new Commentor_Admin_Hooks();
