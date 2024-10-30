<?php
/*
 * Plugin Name: Commentor
 * Description: Wordpress comment plugin
 * Version: 1.0.0
 * Author: Hossein Hasanpouri
 * Author URI: mailto:hosseinhp1198@gmail.com
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: commentor
 * Domain Path: /languages/
 */

if (! defined("ABSPATH")) {
    exit();
}

define('COMMENTOR_PLUGIN_URI', plugin_dir_url( __FILE__ ));
define('COMMENTOR_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

final class Commentor {

    const COMMENTOR_VERSION = '1.0.0';

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->includes();
        $this->hooks();
    }

    public function includes()
    {
        require_once COMMENTOR_PLUGIN_PATH . 'includes/helpers.php';
        require_once COMMENTOR_PLUGIN_PATH . 'includes/commentor_ajax.php';
        require_once COMMENTOR_PLUGIN_PATH . 'includes/commentor_hooks.php';
        require_once COMMENTOR_PLUGIN_PATH . 'includes/admin/commentor_admin_hooks.php';
        require_once COMMENTOR_PLUGIN_PATH . 'includes/admin/commentor_admin_ajax.php';
    }

    public function hooks()
    {
        add_action('init', [$this, 'load_commentor_text_domain']);
    }

    public function activation()
    {
        require_once COMMENTOR_PLUGIN_PATH . 'includes/commentor_install.php';
    }

    public function load_commentor_text_domain() {
        load_plugin_textdomain('commentor', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

}

$Commentor = new Commentor();

function commentor_activation() {
    $Commentor = new Commentor();
    $Commentor->activation();
}

register_activation_hook(__FILE__, 'commentor_activation');
