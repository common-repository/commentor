<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');}

class Commentor_Hooks {

    public string $plugin_version = '1.0.0';

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        add_filter('comments_template', [$this, 'custom_comments_template'], 999999);
        add_action('wp_enqueue_scripts', [$this, 'register_and_enqueue_custom_styles']);
        add_action('wp_enqueue_scripts', [$this, 'register_and_enqueue_custom_scripts']);
        add_filter('script_loader_tag', [$this, 'add_type_to_script'], 10, 2);
        add_filter('register_block_type_args', [$this, 'replace_commentor_comment_block'], 99, 2);
    }

    public function custom_comments_template($comment_template) {
        $custom_template = COMMENTOR_PLUGIN_PATH . 'templates/comment.php';

        if (file_exists($custom_template)) {
            $comment_template = $custom_template;
        }

        return $comment_template;
    }

    public function register_and_enqueue_custom_styles() {
        wp_enqueue_style('commentor', COMMENTOR_PLUGIN_URI . 'assets/css/commentor.min.css', array(), $this->plugin_version, 'all');
    }

    public function register_and_enqueue_custom_scripts() {
        wp_enqueue_script('commentor-emoji', COMMENTOR_PLUGIN_URI . 'assets/js/emoji-picker-index.js', array('jquery'), $this->plugin_version, true);
        wp_enqueue_script('commentor', COMMENTOR_PLUGIN_URI . 'assets/js/commentor.min.js', array('jquery'), $this->plugin_version, true);
        wp_localize_script(
            'commentor',
            'ajax_data',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wp_nonce')
            )
        );
    }

    public function add_type_to_script($tag, $handle) {
        if ('commentor-emoji' === $handle) {
            $tag = str_replace(' src', ' type="module" src', $tag);
        }
        return $tag;
    }

    public function replace_commentor_comment_block($settings, $name) {
        if ($name == 'core/comments') {
            $settings['render_callback'] = [$this, 'render_commentor_block'];
        }

        return $settings;
    }

    public function render_commentor_block($attributes, $content, $block) {
        global $post;
        $post_id = isset($block->context['postId']) ? $block->context['postId'] : 0;

        ob_start();

        if ($post_id) {
            comments_template();
        }

        $output = ob_get_clean();
        $wrapperAttributes = esc_attr(get_block_wrapper_attributes());

        return sprintf('<div %1$s><div>%2$s</div></div>', $wrapperAttributes, $output);
    }

}

$Commentor_Hooks = new Commentor_Hooks();
