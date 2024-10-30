<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');}

class Commentor_Ajax {

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        add_action('wp_ajax_commentor_create_comment', [$this , 'create_comment']);
        add_action('wp_ajax_nopriv_commentor_create_comment', [$this , 'create_comment']);

        add_action('wp_ajax_commentor_like_comment', [$this , 'like_comment']);
        add_action('wp_ajax_nopriv_commentor_like_comment', [$this , 'like_comment']);

        add_action('wp_ajax_commentor_load_comments', [$this , 'load_comments']);
        add_action('wp_ajax_nopriv_commentor_load_comments', [$this , 'load_comments']);
    }

    public function create_comment()
    {
        $wp_nonce = ! empty($_POST['wp_nonce']) ? sanitize_text_field(wp_unslash($_POST['wp_nonce'])) : null;

        if (! wp_verify_nonce($wp_nonce, 'wp_nonce')) {
            wp_send_json_error([
                'message' => esc_html__('Your request encountered a problem', 'commentor')
            ]);
        }

        if (empty($_POST['message'])) {
            wp_send_json_error([
                'message' => esc_html__('The comment cannot be empty', 'commentor')
            ]);
        }

        if (empty($_POST['post_id']) || ! is_numeric($_POST['post_id'])) {
            wp_send_json_error([
                'message' => esc_html__('The information sent is defective', 'commentor')
            ]);
        }

        $post_id = sanitize_text_field($_POST['post_id']);
        $post = get_post($post_id);

        if (empty($post)) {
            wp_send_json_error([
                'message' => esc_html__('Post not available', 'commentor')
            ]);
        }

        $comments_enabled = comments_open($post_id);

        if (! $comments_enabled) {
            wp_send_json_error([
                'message' => esc_html__('It is not possible to comment on this post', 'commentor')
            ]);
        }

        $current_user = is_user_logged_in() ? wp_get_current_user() : null;

        $comment_author = ! empty($current_user->display_name) ? $current_user->display_name : (! empty($_POST['name']) ? sanitize_text_field($_POST['name']) : esc_html__('Guest', 'commentor'));
        $comment_author_email = ! empty($current_user->user_email) ? $current_user->user_email : (! empty($_POST['email']) ? sanitize_text_field($_POST['email']) : '');

        $comment_data = [
            'comment_post_ID' => $post_id,
            'comment_author' => $comment_author,
            'comment_author_email' => $comment_author_email,
            'comment_content' => sanitize_text_field($_POST['message']),
            'comment_approved' => 0,
            'comment_parent' => ! empty($_POST['reply_to']) && is_numeric($_POST['reply_to']) ? sanitize_text_field($_POST['reply_to']) : 0
        ];

        if (! empty($current_user->ID)) {
            $comment_data['user_id'] = $current_user->ID;
        }

        $comment_id = wp_insert_comment($comment_data);

        if (! $comment_id) {
            wp_send_json_error([
                'message' => esc_html__('There was a problem, please try again', 'commentor')
            ]);
        }

        wp_send_json_success([
            'message' => ! empty($_POST['reply_to']) ? esc_html__('Your reply has been sent', 'commentor') : esc_html__('Your comment has been sent', 'commentor')
        ]);
    }

    public function like_comment()
    {
        $wp_nonce = ! empty($_POST['wp_nonce']) ? sanitize_text_field(wp_unslash($_POST['wp_nonce'])) : null;

        if (! wp_verify_nonce($wp_nonce, 'wp_nonce')) {
            wp_send_json_error([
                'message' => esc_html__('Your request encountered a problem', 'commentor')
            ]);
        }

        if (! is_user_logged_in()) {
            wp_send_json_error([
                'message' => esc_html__('You must be logged in to like', 'commentor')
            ]);
        }

        if (empty($_POST['comment_id']) || ! is_numeric($_POST['comment_id'])) {
            wp_send_json_error([
                'message' => esc_html__('The information sent is defective', 'commentor')
            ]);
        }

        $comment_id = sanitize_text_field($_POST['comment_id']);
        $like_result = commentor_add_or_remove_comment_like($comment_id);

        if ($like_result === false) {
            wp_send_json_error([
                'message' => esc_html__('There was a problem, please try again', 'commentor')
            ]);
        }

        if ($like_result['total'] > 1) {
            $total_message = $like_result['total'] . ' ' . esc_html__('Likes', 'commentor');
        } elseif ($like_result['total'] == 1) {
            $total_message = $like_result['total'] . ' ' . esc_html__('Like', 'commentor');
        } else {
            $total_message = esc_html__('Like', 'commentor');
        }

        wp_send_json_success([
            'message' => $like_result['message'],
            'current_total' => $total_message
        ]);
    }

    public function load_comments()
    {
        $wp_nonce = ! empty($_POST['wp_nonce']) ? sanitize_text_field(wp_unslash($_POST['wp_nonce'])) : null;

        if (! wp_verify_nonce($wp_nonce, 'wp_nonce')) {
            wp_send_json_error([
                'message' => esc_html__('Your request encountered a problem', 'commentor')
            ]);
        }

        if (empty($_POST['post_id'])) {
            wp_send_json_error([
                'message' => esc_html__('The information sent is defective', 'commentor')
            ]);
        }

        $post_id = sanitize_text_field($_POST['post_id']);
        $page = ! empty($_POST['page']) ? sanitize_text_field($_POST['page']) : 1;
        $comments_per_page = get_option('commentor_display_count', '10');
        $total_comments = commentor_get_post_comments_count($post_id);
        $comments = commentor_get_post_comments($post_id, $comments_per_page, $page);
        $has_more = false;
        $fina_html = '';

        if (ceil($total_comments / $comments_per_page) > $page) {
            $has_more = true;
        }

        foreach ($comments as $comment) {
            ob_start();
            $comment_replies = commentor_get_comment_replies($post_id, $comment->comment_ID);
            $comment_likes_count = commentor_get_comment_likes_count($comment->comment_ID);
            $comment_replies_count = commentor_get_comment_replies_count($comment->comment_ID);
            $is_comment_of_post_author = ! empty($comment->user_id) && commentor_is_post_author($post_id, $comment->user_id);
            include COMMENTOR_PLUGIN_PATH . 'templates/comment-item.php';
            $fina_html .= ob_get_clean();
        }

        wp_send_json_success([
            'html' => $fina_html,
            'has_more' => $has_more
        ]);
    }

}

$Comment_Ajax = new Commentor_Ajax();
