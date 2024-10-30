<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');} ?>

<comment data-id="<?php echo esc_attr($comment->comment_ID) ?>">
    <div class="inner">
        <div class="avatar <?php echo esc_attr(get_option('commentor_distinguish_the_author', 'no') == 'yes' && $is_comment_of_post_author  ? 'author' : '') ?>" style="border-color: <?php echo esc_attr(get_option('commentor_primary_color', '#4f46e5')) ?>">
            <img src="<?php echo esc_attr(get_avatar_url($comment->comment_ID, array('size' => 64))) ?>" alt="<?php echo esc_attr($comment->comment_author) ?>">
        </div>
        <div class="content">
            <div class="name"><?php echo esc_html(commentor_comment_display_name($comment->comment_ID)) ?></div>
            <div class="message"><?php echo esc_html($comment->comment_content) ?></div>
            <div class="more">
                <div class="time-ago"><?php echo esc_html(commentor_time_ago($comment->comment_date)) ?></div>
                <div class="likes" data-comment-id="<?php echo esc_attr($comment->comment_ID) ?>">
                    <?php if ($comment_likes_count > 0) : ?>
                        <span><?php echo esc_html($comment_likes_count) . ' ' . esc_html__('Likes', 'commentor') ?></span>
                    <?php else : ?>
                        <span><?php echo esc_html__('Like', 'commentor') ?></span>
                    <?php endif; ?>
                </div>
                <div class="reply">
                    <?php if ($comment_replies_count > 0) : ?>
                        <span><?php echo esc_html($comment_replies_count) . ' ' . esc_html__('Replies', 'commentor') ?></span>
                    <?php endif; ?>
                </div>
                <?php if ($comment_replies_count > 0) : ?>
                    <div class="load-replies" data-comment-id="<?php echo esc_attr($comment->comment_ID) ?>"><?php echo esc_html__('Load replies', 'commentor') ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="reply-box">
        <input type="text" placeholder="<?php echo esc_html__('Add new reply ...', 'commentor') ?>">
        <div class="actions">
            <div class="emoji-selector">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 12C3 7.029 7.029 3 12 3C16.971 3 21 7.029 21 12" stroke="#5B5C5F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21 12C21 16.971 16.971 21 12 21C7.029 21 3 16.971 3 12" stroke="#5B5C5F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.5 9V10" stroke="#5B5C5F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.5 9V10" stroke="#5B5C5F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.5 14.6875C15.5 14.6875 14.187 15.9995 12 15.9995C9.812 15.9995 8.5 14.6875 8.5 14.6875" stroke="#5B5C5F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <button class="send" style="color: <?php echo esc_attr(get_option('commentor_primary_color', '#4f46e5')) ?>"><?php echo esc_html__('Send', 'commentor') ?></button>
        </div>
        <div class="emoji-container hidden">
            <emoji-picker class="light"></emoji-picker>
        </div>
    </div>
    <?php if (! empty($comment_replies)) : ?>
        <div class="replies hidden">
            <?php foreach ($comment_replies as $reply) : ?>
                <?php
                $comment_likes_count = commentor_get_comment_likes_count($reply->comment_ID);
                $is_reply_of_post_author = ! empty($reply->user_id) && commentor_is_post_author($post_id, $reply->user_id);
                ?>
                <reply>
                    <div class="inner">
                        <div class="avatar <?php echo esc_attr(get_option('commentor_distinguish_the_author', 'no') == 'yes' && $is_reply_of_post_author  ? 'author' : '') ?>" style="border-color: <?php echo esc_attr(get_option('commentor_primary_color', '#4f46e5')) ?>">
                            <img src="<?php echo esc_attr(get_avatar_url($comment->comment_ID, array('size' => 64))) ?>" alt="<?php echo esc_attr($reply->comment_author) ?>">
                        </div>
                        <div class="content">
                            <div class="message-container">
                                <div class="name"><?php echo esc_html(commentor_comment_display_name($reply->comment_ID)) ?></div>
                                <div class="message"><?php echo esc_html($reply->comment_content) ?></div>
                            </div>
                            <div class="more">
                                <div class="time-ago"><?php echo esc_html(commentor_time_ago($reply->comment_date)) ?></div>
                                <div class="likes" data-comment-id="<?php echo esc_attr($reply->comment_ID) ?>">
                                    <?php if ($comment_likes_count > 0) : ?>
                                        <span><?php echo esc_html($comment_likes_count) . ' ' . esc_html__('Likes', 'commentor') ?></span>
                                    <?php else : ?>
                                        <span><?php echo esc_html__('Like', 'commentor') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </reply>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</comment>