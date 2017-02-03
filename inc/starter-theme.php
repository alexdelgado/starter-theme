<?php

class Starter_Theme {

    static $instance = false;

    public function __construct()
    {
        $this->_add_actions();
    }

    /**
     * Enqueue Admin Assets
     *
     * Enqueues the necessary css and js files when the WordPress admin is loaded.
     */
    public function enqueue_admin_assets()
    {
        wp_enqueue_style('theme-admin', get_template_directory_uri() .'/css/wp-admin.css', array('thickbox'), false);
        wp_enqueue_script('theme-admin', get_template_directory_uri() .'/js/wp-admin.min.js', array('jquery', 'jquery-ui-autocomplete', 'thickbox', 'media-upload'), null, true);
    }

    /**
     * Add Attachment Meta Fields
     *
     * Adds additional meta fields to WordPress attachments screen.
     */
    public function add_attachment_meta_fields($fields = array(), $post = null)
    {
        if(!empty($post))
            $copyright = get_post_meta($post->ID, 'copyright', true);

        $fields['copyright'] =
            array(
                'value' => (!empty($copyright) ? $copyright : ''),
                'label' => __('Copyright'),
                'helps' => __('Set the copyright information for this file.')
        );

        return $fields;
    }

    /**
     * Save Attachment Meta
     *
     * Handles the sanitizing and saving of the attachment meta fields.
     *
     * @param int $attachment_id
     */
    public function save_attachment_meta($attachment_id = null)
    {
        $copyright = $_REQUEST['attachments'][$attachment_id]['copyright'];

        if(isset($copyright))
            update_post_meta($attachment_id, 'copyright', $copyright);
    }

    /**
     * Enqueue Assets
     *
     * Enqueues the necessary css and js files when the theme is loaded.
     */
    public function enqueue_assets()
    {
        wp_deregister_style('video-js');
        wp_deregister_style('video-js-kg-skin');
        wp_deregister_style('kgvid_video_styles');

        wp_enqueue_style('theme', get_template_directory_uri() .'/css/theme.css', array('font-awesome', 'videojs'), false);
        wp_enqueue_script('theme', get_template_directory_uri() .'/js/theme.min.js', array('jquery'), null, true);
        wp_localize_script('theme', 'theme', $this->_enqueue_theme_constants());
    }

    /**
     * Singleton
     *
     * Returns a single instance of the current class.
     */
    public static function singleton()
    {
        if(!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * Set Theme Options
     *
     * Configures the necessary WordPress theme options once the theme is activated.
     */
    public static function set_theme_options()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /**
         * Enable support for Post Thumbnails on posts and pages.
         * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in various locations.
        register_nav_menus(
            array(
                'primary' => __('Main Navigation (Desktop)', 'theme'),
                'sub' => __('Sub Navigation (Desktop)', 'theme'),
                'primary-mobile' => __('Main Navigation (Mobile)', 'theme'),
                'site-footer' => __('Footer Navigation (Desktop Only)', 'theme'),
            )
        );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array('search-form', 'gallery', 'caption'));

        // Allow WordPress to generate the title tag dynamically.
        add_theme_support('title-tag');

        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style('css/editor.css');
    }

    /**
     * Get Theme Options
     *
     * Queries the database for the given setting if one is defined, otherwise
     * returns all settings for the given group.
     *
     * @param string $group
     * @param string $option (optional)
     *
     * @return The value of the given option (if exists) or an array containing all theme settings.
     */
    public static function get_theme_options($group = null, $option = null)
    {
        $options = get_option($group);

        if(!empty($option))
            return (!empty($options[$option]) ? $options[$option] : '');

        return (array) $options;
    }

    /**
     * Add Actions
     *
     * Defines all the WordPress actions and filters used by this theme.
     */
    private function _add_actions()
    {
        // back-end actions
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('edit_attachment', array($this, 'save_attachment_meta'));

        add_filter('attachment_fields_to_edit', array($this, 'add_attachment_meta_fields'));

        // front-end actions
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'), 15);
    }

    /**
     * Enqueue Theme Constants
     *
     * Enqueues the necessary theme constants for use throughout the site.
     */
    private function _enqueue_theme_constants($output = array())
    {
        $settings = $this->get_theme_options('theme-settings');

        return $output;
    }

    /**
     * Get Mapped Domain
     *
     * Queries the database for the domain mapped to the given blog.
     *
     * @param int $blog_id
     */
    private function _get_mapped_domain($blog_id = null)
    {
        // Enable WordPress DB connection
        global $wpdb;

        $s = $wpdb->suppress_errors();

        // get primary domain, if we don't have one then return original url.
        $domain = $wpdb->get_var("SELECT domain FROM {$wpdb->base_prefix}domain_mapping WHERE blog_id = '{$blog_id}' AND active = 1 LIMIT 1");

        if(empty($domain))
            return untrailingslashit(get_site_url($blog_id));

        $wpdb->suppress_errors($s);

        return untrailingslashit($domain);
    }
}
