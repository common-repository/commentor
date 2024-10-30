<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');}

$post_id = get_the_ID();
$comments_per_page = get_option('commentor_display_count', '10');
$comments = commentor_get_post_comments($post_id, $comments_per_page);
$total_comments_count = commentor_get_post_comments_count($post_id);
?>

<div class="commentor <?php echo esc_attr(get_option('commentor_form_direction', 'ltr')) ?>" style="background-color: <?php echo esc_attr(get_option('commentor_box_background', '#ffffff')) ?>; padding: <?php echo esc_attr(get_option('commentor_box_padding', '16') . 'px') ?>" data-is-user-logged-in="<?php echo esc_attr(is_user_logged_in() ? 'true' : 'false') ?>">
    <div class="commentor-notice hidden"></div>
    <input type="hidden" id="postId" value="<?php echo esc_attr($post_id) ?>">
    <div class="title-container">
        <div class="title"><?php echo esc_html__('Comments', 'commentor') ?></div>
        <div class="total-count"><?php echo esc_html($total_comments_count) . ' ' . esc_html__('Comments', 'commentor') ?></div>
    </div>
    <?php include COMMENTOR_PLUGIN_PATH . 'templates/comment-form.php' ?>
    <div class="notice"><?php echo esc_html__('Comments will be displayed after approval by the administrator', 'commentor') ?></div>
    <?php if (! empty($comments)) : ?>
    <div class="comments">
        <?php foreach ($comments as $comment) : ?>
        <?php
        $comment_replies = commentor_get_comment_replies($post_id, $comment->comment_ID);
        $comment_likes_count = commentor_get_comment_likes_count($comment->comment_ID);
        $comment_replies_count = commentor_get_comment_replies_count($comment->comment_ID);
        $is_comment_of_post_author = ! empty($comment->user_id) && commentor_is_post_author($post_id, $comment->user_id);
        ?>
        <?php include COMMENTOR_PLUGIN_PATH . 'templates/comment-item.php' ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if ($total_comments_count > $comments_per_page) : ?>
    <div class="load-more-comments">
        <button style="background-color: <?php echo esc_attr(get_option('commentor_primary_color', '#4f46e5')) ?>">
            <span class="icon hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 3a9 9 0 1 0 9 9" />
                </svg>
            </span>
            <?php echo esc_html__('Load more comments', 'commentor') ?>
        </button>
    </div>
    <?php endif; ?>
    <?php include COMMENTOR_PLUGIN_PATH . 'templates/comment-guest-popup.php' ?>
</div>
