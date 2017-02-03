<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'theme'); ?></label>
    <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($title); ?>" class="widefat">
</p>
<p>
    <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Link URL:', 'theme'); ?></label>
    <input type="text" name="<?php echo $this->get_field_name('url'); ?>" id="<?php echo $this->get_field_id('url'); ?>" value="<?php echo esc_attr($url); ?>" class="widefat">
</p>
<p>
    <label for="<?php echo $this->get_field_id('cover_image'); ?>"><?php _e('Cover Image:', 'theme'); ?></label>
    <input type="text" name="<?php echo $this->get_field_name('cover_image'); ?>" id="<?php echo $this->get_field_id('cover_image'); ?>" value="<?php echo esc_attr($cover_image); ?>" class="widefat upload">
    <input type="button" value="Select Image" class="button button-large upload_media" data-target="#<?php echo $this->get_field_id('cover_image'); ?>">
</p>
<p>
    <label for="<?php echo $this->get_field_id('image_credit'); ?>"><?php _e('Image Credit:', 'theme'); ?></label>
    <input type="text" name="<?php echo $this->get_field_name('image_credit'); ?>" id="<?php echo $this->get_field_id('image_credit'); ?>" value="<?php echo esc_attr($image_credit); ?>" class="widefat">
</p>
<p>
    <label for="<?php echo $this->get_field_id('new_window'); ?>">
        <input type="hidden" name="<?php echo $this->get_field_name('new_window'); ?>" value="false">
        <input type="checkbox" name="<?php echo $this->get_field_name('new_window'); ?>" id="<?php echo $this->get_field_id('new_window'); ?>" value="true" <?php checked($target, 'true'); ?>>
        <?php _e('Open link in a new window/tab', 'theme'); ?>
    </label>
</p>