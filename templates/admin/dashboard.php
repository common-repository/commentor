<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');} ?>

<div class="wrap">
    <h1><?php echo esc_html__('Dashboard', 'commentor') ?></h1>
    <div class="commentor-dashboard <?php echo esc_attr(is_rtl() ? 'rtl' : '') ?>">
        <div class="statistics">
            <div class="item">
                <div class="value"><?php echo esc_html(wp_count_comments()->total_comments) ?></div>
                <div class="label"><?php echo esc_html__('Comments', 'commentor') ?></div>
                <a href="<?php echo esc_attr(admin_url() . 'edit-comments.php') ?>" target="_blank" class="see-all"><?php echo esc_html__('See all', 'commentor') ?></a>
            </div>
            <div class="item">
                <div class="value"><?php echo esc_html(wp_count_comments()->moderated) ?></div>
                <div class="label"><?php echo esc_html__('Pending', 'commentor') ?></div>
                <a href="<?php echo esc_attr(admin_url() . 'edit-comments.php?comment_status=moderated') ?>" target="_blank" class="see-all"><?php echo esc_html__('See all', 'commentor') ?></a>
            </div>
            <div class="item">
                <div class="value"><?php echo esc_html(wp_count_comments()->approved) ?></div>
                <div class="label"><?php echo esc_html__('Approved', 'commentor') ?></div>
                <a href="<?php echo esc_attr(admin_url() . 'edit-comments.php?comment_status=approved') ?>" target="_blank" class="see-all"><?php echo esc_html__('See all', 'commentor') ?></a>
            </div>
            <div class="item">
                <div class="value"><?php echo esc_html(wp_count_comments()->spam) ?></div>
                <div class="label"><?php echo esc_html__('Spam', 'commentor') ?></div>
                <a href="<?php echo esc_attr(admin_url() . 'edit-comments.php?comment_status=spam') ?>" target="_blank" class="see-all"><?php echo esc_html__('See all', 'commentor') ?></a>
            </div>
            <div class="item">
                <div class="value"><?php echo esc_html(wp_count_comments()->trash) ?></div>
                <div class="label"><?php echo esc_html__('Trash', 'commentor') ?></div>
                <a href="<?php echo esc_attr(admin_url() . 'edit-comments.php?comment_status=trash') ?>" target="_blank" class="see-all"><?php echo esc_html__('See all', 'commentor') ?></a>
            </div>
        </div>
        <div class="second-row">
            <div class="top-users">
                <div class="title"><?php echo esc_html__('Top users', 'commentor') ?></div>
                <div class="sub-title"><?php echo esc_html__('List of users with the most comments', 'commentor') ?></div>
                <div class="content">
                    <div class="chart">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
            <div class="top-users top-posts">
                <div class="title"><?php echo esc_html__('Top posts', 'commentor') ?></div>
                <div class="sub-title"><?php echo esc_html__('List of posts with the most comments', 'commentor') ?></div>
                <div class="content">
                    <?php foreach (commentor_get_top_posts_by_comments_count() as $comment_post_key => $comment_post) : ?>
                    <div class="item">
                        <div class="row"><?php echo esc_html($comment_post_key + 1) ?></div>
                        <div class="item-content">
                            <div class="title"><?php echo esc_html($comment_post->post_title) ?></div>
                            <div class="details">
                                <div class="comments"><?php echo esc_html(sprintf(esc_html__('%s comments', 'commentor'), $comment_post->total_comments)) ?></div>
                                <div class="date"><?php echo esc_html(sprintf(esc_html__('Published at %s', 'commentor'), date_format(date_create($comment_post->post_date), 'Y/m/d'))) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let commentsCount = [<?php foreach (commentor_get_top_users_by_comments_count() as $user_comment) { echo esc_html($user_comment->total_comments) . ', '; } ?>];
    let usersCount = [<?php foreach (commentor_get_top_users_by_comments_count() as $user_comment) { echo '"' . esc_html($user_comment->display_name) . '"' . ', '; } ?>];
</script>

