<?php

class Starter_Theme_Settings {

    static $instance = false;

    public function __construct()
    {
        $this->_add_actions();
    }

    /**
     * Add Admin Menu
     *
     * Add menu page to admin navigation.
     */
    public function add_admin_menu()
    {
        add_menu_page(
            'Theme Settings',
            'Theme',
            'manage_options',
            'theme-settings',
            array($this, 'generate_settings_page')
        );

        add_submenu_page(
            'theme-settings',
            'Theme Settings',
            'Theme Settings',
            'manage_options',
            'theme-settings',
            array($this, 'generate_settings_page')
        );

         add_submenu_page(
            'theme-settings',
            'Ad Placement Settings',
            'Ad Placements',
            'manage_options',
            'ad-placement-settings',
            array($this, 'generate_settings_page')
        );
    }

    /**
     * Generate Options Page
     *
     * Dynamically generates the theme options page.
     */
    public function generate_settings_page()
    {
        $active_tab = (isset($_GET["page"]) ? $_GET["page"] : 'theme-settings');

        require(get_stylesheet_directory() .'/inc/views/theme-settings.php');
    }

   /**
     * Register Theme Settings
     *
     * Register Settings for theme settings page.
     */
    public function register_theme_settings()
    {
        /**
         * Theme Settings
         */
        register_setting('theme_settings_group', 'theme_settings');

        // Social
        add_settings_section(
            'theme_settings',
            __('Social Media Links', 'theme'),
            array($this, 'settings_description_callback'),
            'theme-settings'
        );

        // Facebook User ID
        add_settings_field(
            'facebook_link',
            'Facebook Link',
            array($this, 'render_text_field'),
            'theme-settings',
            'theme_settings',
            array(
                'label_for' => 'facebook_link',
                'placeholder' => 'https://www.facebook.com/',
                'settings_id' => 'theme_settings'
            )
        );

        // Instagram User ID
        add_settings_field(
            'instagram_link',
            'Instagram Link',
            array($this, 'render_text_field'),
            'theme-settings',
            'theme_settings',
            array(
                'label_for' => 'instagram_link',
                'placeholder' => 'https://instagram.com/',
                'settings_id' => 'theme_settings'
            )
        );

        // Twitter User ID
        add_settings_field(
            'twitter_link',
            'Twitter Link',
            array($this, 'render_text_field'),
            'theme-settings',
            'theme_settings',
            array(
                'label_for' => 'twitter_link',
                'placeholder' => 'https://twitter.com/',
                'settings_id' => 'theme_settings'
            )
        );

        // Youtube User ID
        add_settings_field(
            'youtube_link',
            'Youtube Link',
            array($this, 'render_text_field'),
            'theme-settings',
            'theme_settings',
            array(
                'label_for' => 'youtube_link',
                'placeholder' => 'https://www.youtube.com/',
                'settings_id' => 'theme_settings'
            )
        );

        /**
         * Ad Placement Settings
         */
        register_setting('ad_placement_settings_group', 'ad_placement_settings');

        // global placements
        add_settings_section(
            'global',
            __('Global Placements', 'theme'),
            array($this, 'settings_description_callback'),
            'ad-placement-settings'
        );

        add_settings_field(
            'global_header',
            'Global Header',
            array($this, 'render_textarea_field'),
            'ad-placement-settings',
            'global',
            array(
                'label_for' => 'global_header',
                'settings_id' => 'ad_placement_settings'
            )
        );

        add_settings_field(
            'global_banner',
            'Global Banner',
            array($this, 'render_textarea_field'),
            'ad-placement-settings',
            'global',
            array(
                'label_for' => 'global_banner',
                'settings_id' => 'ad_placement_settings'
            )
        );

        add_settings_field(
            'global_footer',
            'Global Footer',
            array($this, 'render_textarea_field'),
            'ad-placement-settings',
            'global',
            array(
                'label_for' => 'global_footer',
                'settings_id' => 'ad_placement_settings'
            )
        );
    }

    /**
     * Settings Description Callback
     *
     * Displays the description for all theme settings sections.
     */
    public function settings_description_callback()
    {
    }

    /**
     * Render Checkbox
     *
     * Dynamically generates a checkbox with the given attributes.
     */
    public function render_checkbox($params = array())
    {
        $settings_id = (!empty($params['settings_id']) ? $params['settings_id'] : 'theme-settings');
        $options = get_option($settings_id);

        $name = (!empty($params['label_for']) ? $params['label_for'] : '');
        $label = (!empty($params['label']) ? $params['label'] : '');
        $value = (!empty($options[$name]) ? $options[$name] : '');
        $checked = checked('true', $value, false);

        $html = sprintf('<input type="hidden" name="%1$s[%2$s]" id="%2$s_hidden" value="false">', $settings_id, $name);
        $html .= sprintf('<input type="checkbox" name="%1$s[%2$s]" id="%2$s" value="true" %3$s>', $settings_id, $name, $checked);
        $html .= sprintf('<label for="%1$s">%2$s</label>', $name, $label);

        echo $html;
    }

    /**
     * Render Dropdown
     *
     * Dynamically generates a dropdown menu with the given attributes.
     */
    public function render_dropdown($params = array())
    {
        $settings_id = (!empty($params['settings_id']) ? $params['settings_id'] : 'theme-settings');
        $options = get_option($settings_id);

        $name = (!empty($params['label_for']) ? $params['label_for'] : '');
        $value = (!empty($options[$name]) ? $options[$name] : '');

        $html = sprintf('<select name="%1$s[%2$s]" id="%2$s">', $settings_id, $name);
            $html .= '<option value="">Select One</option>';

            if(!empty($params['options']))
            {
                foreach($params['options'] as $key => $val)
                    $html .= sprintf('<option value="%s" %s>%s</option>', $key, selected($key, $value, false), $val);
            }

        $html .= '</select>';

        echo $html;
    }

    /**
     * Render Text Area Field
     *
     * Dynamically generates a text area field with the given attributes.
     */
    public function render_textarea_field($params = array())
    {
        $settings_id = (!empty($params['settings_id']) ? $params['settings_id'] : 'theme-settings');
        $options = get_option($settings_id);

        $name = (!empty($params['label_for']) ? $params['label_for'] : '');
        $value = (!empty($options[$name]) ? $options[$name] : '');
        $placeholder = (!empty($params['placeholder']) ? 'placeholder="' . $params['placeholder'] . '"' : '');

        $html = sprintf(
            '<textarea name="%1$s[%2$s]" id="%2$s" class="regular-text" rows="5" cols="50" %4$s>%3$s</textarea>',
            $settings_id,
            $name,
            $value,
            $placeholder
        );

        echo $html;
    }

    /**
     * Render Text Field
     *
     * Dynamically generates a text field with the given attributes.
     */
    public function render_text_field($params = array())
    {
        $settings_id = (!empty($params['settings_id']) ? $params['settings_id'] : 'theme-settings');
        $options = get_option($settings_id);

        $name = (!empty($params['label_for']) ? $params['label_for'] : '');
        $value = (!empty($options[$name]) ? $options[$name] : '');
        $placeholder = (!empty($params['placeholder']) ? 'placeholder="' . $params['placeholder'] . '"' : '');
        $class = (!empty($params['class']) ? $params['class'] : '');

        $html = sprintf(
            '<input type="text" name="%1$s[%2$s]" id="%2$s" value="%3$s" class="regular-text %4$s" %5$s>',
            $settings_id,
            $name,
            $value,
            $class,
            $placeholder
        );

        echo $html;
    }

    /**
     * Render Upload Field
     *
     * Dynamically generates an upload field with the given attributes.
     */
    public function render_upload_field($params = array())
    {
        $settings_id = (!empty($params['settings_id']) ? $params['settings_id'] : 'theme-settings');
        $options = get_option($settings_id);

        $name = (!empty($params['label_for']) ? $params['label_for'] : '');
        $value = (!empty($options[$name]) ? $options[$name] : '');
        $placeholder = (!empty($params['placeholder']) ? 'placeholder="' . $params['placeholder'] . '"' : '');

        $html =  sprintf(
            '<input type="text" name="%1$s[%2$s]" id="%2$s" value="%3$s" class="regular-text" %4$s>',
            $settings_id,
            $name, $value,
            $placeholder
        );

        $html .= sprintf('<button class="button upload_media" data-target="#%1$s">Upload Logo</button>', $name);

        echo $html;
    }

    /**
     * Singleton
     *
     * Returns a single instance of the current class.
     */
    public static function singleton()
    {
        if (!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * Add Actions
     *
     * Defines all the WordPress actions and filters used by this class.
     */
    private function _add_actions()
    {
        // back-end actions
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_theme_settings'));
    }
}
