<?php if (! defined('ABSPATH')) {die('Direct access to this location is not allowed.');} ?>

<div class="wrap">
    <h1><?php echo esc_html__('Commentor settings', 'commentor') ?></h1>
    <div class="notice notice-success" style="display: none">
        <p></p>
    </div>
    <table class="form-table" id="commentor_settings">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="commentor_form_direction"><?php echo esc_html__('Form direction', 'commentor') ?></label>
                </th>
                <td>
                    <select name="commentor_form_direction" id="commentor_form_direction">
                        <option value="ltr" <?php echo esc_attr(get_option('commentor_form_direction', 'ltr') == 'ltr' ? 'selected' : '') ?>><?php echo esc_html__('Left to right', 'commentor') ?></option>
                        <option value="rtl" <?php echo esc_attr(get_option('commentor_form_direction', 'ltr') == 'rtl' ? 'selected' : '') ?>><?php echo esc_html__('Right to left', 'commentor') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="commentor_primary_color"><?php echo esc_html__('Primary color', 'commentor') ?></label>
                </th>
                <td>
                    <input type="color" name="commentor_primary_color" id="commentor_primary_color" value="<?php echo esc_attr(get_option('commentor_primary_color', '#4f46e5')) ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="commentor_display_count"><?php echo esc_html__('Display count', 'commentor') ?></label>
                </th>
                <td>
                    <input type="number" name="commentor_display_count" id="commentor_display_count" value="<?php echo esc_attr(get_option('commentor_display_count', '10')) ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="commentor_box_background"><?php echo esc_html__('Box background', 'commentor') ?></label>
                </th>
                <td>
                    <input type="color" name="commentor_box_background" id="commentor_box_background" value="<?php echo esc_attr(get_option('commentor_box_background', '#ffffff')) ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="commentor_box_padding"><?php echo esc_html__('Box padding', 'commentor') ?></label>
                </th>
                <td>
                    <input type="number" name="commentor_box_padding" id="commentor_box_padding" value="<?php echo esc_attr(get_option('commentor_box_padding', '16')) ?>">
                    <p class="description"><?php echo esc_html__('Enter in px', 'commentor') ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="commentor_distinguish_the_author"><?php echo esc_html__('Distinguish the author', 'commentor') ?></label>
                </th>
                <td>
                    <select name="commentor_distinguish_the_author" id="commentor_distinguish_the_author">
                        <option value="yes" <?php echo esc_attr(get_option('commentor_distinguish_the_author', 'no') == 'yes' ? 'selected' : '') ?>><?php echo esc_html__('Yes', 'commentor') ?></option>
                        <option value="no" <?php echo esc_attr(get_option('commentor_distinguish_the_author', 'no') == 'no' ? 'selected' : '') ?>><?php echo esc_html__('No', 'commentor') ?></option>
                    </select>
                    <p class="description"><?php echo esc_html__('Show author comments with different color', 'commentor') ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="commentor_admin_display_name"><?php echo esc_html__('Admin display name', 'commentor') ?></label>
                </th>
                <td>
                    <input type="text" name="commentor_admin_display_name" id="commentor_admin_display_name" value="<?php echo esc_attr(get_option('commentor_admin_display_name', '')) ?>">
                    <p class="description"><?php echo esc_html__('Replace administrators name with this name on comments (leave blank to disable)', 'commentor') ?></p>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <button class="button button-primary"><?php echo esc_html__('Save changes', 'commentor') ?></button>
    </p>
</div>