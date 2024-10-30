<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');}

class Commentor_Install {

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->create_commentor_likes_table();
    }

    public function create_commentor_likes_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'commentor_likes';

        $query = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (
            user_id      INT        UNSIGNED NOT NULL,
            comment_id   INT        UNSIGNED NOT NULL,
            date         DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
            KEY user_id (`user_id`),
            KEY comment_id (`comment_id`)
        );';

        $wpdb->query($query);
    }

}

$Commentor_Install = new Commentor_Install();
