<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');}

/**
 * Get post comments using post id
 *
 * @param int $post_id
 * @param int $per_page
 * @param int $page
 *
 * @return array
 */
function commentor_get_post_comments($post_id, $per_page = 10, $page = 1)
{
    $args = [
        'post_id' => $post_id,
        'status' => 'approve',
        'orderby' => 'comment_date_gmt',
        'order' => 'DESC',
        'number' => $per_page,
        'offset' => ($page - 1) * $per_page,
        'parent' => 0
    ];

    return get_comments($args);
}

/**
 * Get comment replies
 *
 * @param int $post_id
 * @param int $comment_id
 *
 * @return array
 */
function commentor_get_comment_replies($post_id, $comment_id)
{
    $args = [
        'post_id' => $post_id,
        'status' => 'approve',
        'orderby' => 'comment_date_gmt',
        'order' => 'DESC',
        'parent' => $comment_id
    ];

    return get_comments($args);
}

/**
 * Get comment likes count
 *
 * @param int $comment_id
 *
 * @return int
 */
function commentor_get_comment_likes_count($comment_id)
{
    $comment_likes_count = get_comment_meta($comment_id, 'likes', true);
    return ! empty($comment_likes_count) ? (int) $comment_likes_count : 0;
}

/**
 * Add comment like
 *
 * @param $comment_id
 * @param $user_id
 *
 * @return mixed
 */
function commentor_add_or_remove_comment_like($comment_id, $user_id = null) {
    global $wpdb;

    $user_id = ! empty($user_id) ? $user_id : get_current_user_id();

    $table_name = $wpdb->prefix . 'commentor_likes';

    $result = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d AND comment_id = %d", $user_id, $comment_id)
    );

    if (empty($result)) {

        $data = [
            'user_id' => $user_id,
            'comment_id' => $comment_id
        ];

        $insert_result = $wpdb->insert($table_name, $data);

        if ($insert_result === false) {
            return false;
        }

        $message = esc_html__('Comment liked', 'commentor');

    } else {

        $delete_result = $wpdb->delete($table_name, array(
            'user_id' => $user_id,
            'comment_id' => $comment_id
        ), array('%d', '%d'));

        if ($delete_result === false) {
            return false;
        }

        $message = esc_html__('Comment unliked', 'commentor');

    }

    return [
        'message' => $message,
        'total' => commentor_calculate_comment_likes($comment_id)
    ];
}

/**
 * Calculate comment likes and store comment meta
 *
 * @param $comment_id
 *
 * @return string|null
 */
function commentor_calculate_comment_likes($comment_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'commentor_likes';

    $result = $wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE comment_id = %d", $comment_id)
    );

    update_comment_meta($comment_id, 'likes', $result);

    return $result;
}

/**
 * Get time ago
 *
 * @param $date
 *
 * @return string
 */
function commentor_time_ago($date) {

    if (strtotime($date) === false) {
        return "Invalid timestamp";
    }

    $currentDateTime = new DateTime();
    $targetDateTime = new DateTime($date);
    $interval = $currentDateTime->diff($targetDateTime);

    $output = '';

    $timeUnits = array(
        'y' => esc_html__('y', 'commentor'),
        'm' => esc_html__('month', 'commentor'),
        'd' => esc_html__('d', 'commentor'),
        'h' => esc_html__('h', 'commentor'),
        'i' => esc_html__('m', 'commentor')
    );

    foreach ($timeUnits as $unit => $unitLabel) {
        if ($interval->$unit > 0) {
            $output = $interval->$unit . $unitLabel;
            break;
        }
    }

    if (empty($output)) {
        $output = esc_html__('just now', 'commentor');
    }

    return $output;
}

/**
 * Get total count of comment replies
 *
 * @param $comment_id
 *
 * @return string
 */
function commentor_get_comment_replies_count($comment_id)
{
    global $wpdb;

    $rows_count = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM $wpdb->comments
        WHERE comment_parent = %d
        AND comment_approved = 1
    ", $comment_id));

    return $rows_count;
}

/**
 * Get total count of post comments
 *
 * @param $post_id
 *
 * @return int
 */
function commentor_get_post_comments_count($post_id)
{
    $args = [
        'post_id' => $post_id,
        'status' => 'approve',
        'parent' => 0,
        'count' => true
    ];

    return get_comments($args);
}

/**
 * Check is user author of post
 *
 * @param $post_id
 * @param $user_id
 *
 * @return bool
 */
function commentor_is_post_author($post_id, $user_id)
{
    $post_author_id = get_post_field('post_author', $post_id);

    if ($user_id === $post_author_id) {
        return true;
    }

    return false;
}

/**
 * Get display author name for comments
 *
 * @param $comment_id
 *
 * @return string
 */
function commentor_comment_display_name($comment_id)
{
    $comment = get_comment($comment_id);

    $user_id = $comment->user_id;

    if (! empty($user_id) && get_option('commentor_admin_display_name') != '' && user_can($user_id, 'manage_options')) {
        return get_option('commentor_admin_display_name');
    }

    return $comment->comment_author;
}

/**
 * Get top posts order by comments count
 *
 * @return mixed
 *
 * @since 1.0.1
 */
function commentor_get_top_posts_by_comments_count()
{
    global $wpdb;

    $query = $wpdb->prepare("
        SELECT {$wpdb->posts}.ID as post_id, {$wpdb->posts}.post_title, {$wpdb->posts}.post_date, COUNT({$wpdb->comments}.comment_post_ID) as total_comments
        FROM {$wpdb->posts}
        LEFT JOIN {$wpdb->comments} ON {$wpdb->comments}.comment_post_ID = {$wpdb->posts}.ID
        WHERE {$wpdb->posts}.post_type = %s 
        AND {$wpdb->posts}.post_status = %s
        GROUP BY {$wpdb->posts}.ID, {$wpdb->posts}.post_title
        ORDER BY total_comments DESC
        LIMIT 5;
    ", 'post', 'publish');

    $results = $wpdb->get_results($query);

    return $results;
}

function commentor_get_top_users_by_comments_count()
{
    global $wpdb;

    $query = $wpdb->prepare("
        SELECT wp_users.display_name as display_name, COUNT(wp_comments.user_id) as total_comments
        FROM wp_users 
        LEFT JOIN wp_comments ON wp_comments.user_id = wp_users.ID
        GROUP BY wp_users.ID
        ORDER BY total_comments DESC
        LIMIT 5;
    ");

    $results = $wpdb->get_results($query);

    return $results;
}
