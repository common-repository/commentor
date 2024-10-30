<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');} ?>

<div class="input-box">
    <textarea rows="4" placeholder="<?php echo esc_html__('Add your comment...', 'commentor') ?>"></textarea>
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
        <button style="background-color: <?php echo esc_attr(get_option('commentor_primary_color', '#4f46e5')) ?>"><?php echo esc_html__('Post comment', 'commentor') ?></button>
        <div class="emoji-container hidden">
            <emoji-picker class="light"></emoji-picker>
        </div>
    </div>
</div>