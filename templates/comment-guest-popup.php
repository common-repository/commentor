<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');} ?>

<div class="guest-popup-container hidden">
    <div class="guest-popup">
        <div class="title"><?php echo esc_html__('Thanks for comment', 'commentor') ?></div>
        <div class="sub-title"><?php echo esc_html__('Please enter your name and email first or login', 'commentor') ?></div>
        <div class="name" data-error-message="<?php echo esc_html__('Name should be entered', 'commentor') ?>">
            <input type="text" placeholder="<?php echo esc_html__('Enter your name', 'commentor') ?>">
        </div>
        <div class="email" data-error-message="<?php echo esc_html__('Email should be entered', 'commentor') ?>">
            <input type="email" placeholder="<?php echo esc_html__('Enter your email', 'commentor') ?>">
        </div>
        <button><?php echo esc_html__('Submit', 'commentor') ?></button>
    </div>
</div>