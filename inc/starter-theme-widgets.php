<?php

class Starter_Theme_Widgets {

    static $instance = false;

    public function __construct()
    {
        $this->_add_actions();

        $this->_register_sidebars();

        $this->_deregister_widgets();
        $this->_register_widgets();
    }

    /**
     * Do Query Post Title
     *
     * Searches for post title returned by javascript autocomplete function and returns the result as a json object.
     */
    public function do_query_post_title()
    {
        global $wpdb;

        $response = array();
        $post_type = (!empty($_GET['post_type']) ? $_GET['post_type'] : '');
        $term = (!empty($_GET['term']) ? $wpdb->esc_like($_GET['term']) : '');

        $titles = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_title LIKE %s AND post_status = 'publish' AND post_type = %s;",
                '%'. $term .'%',
                $post_type
            )
        );

        foreach($titles as $title)
        {
            $response[] =
                array(
                    'id' => $title->ID,
                    'label' => $title->post_title,
                    'value' => $title->post_title
                );
        }

        echo json_encode($response);
        wp_die();
    }

     /**
     * Singleton
     *
     * Returns a single instance of the current class.
     */
    public static function singleton()
    {
        if ( ! self::$instance )
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
        add_action('admin_enqueue_scripts', function() { wp_enqueue_media(); });
        add_action('wp_ajax_query_post_title', array($this, 'do_query_post_title'));
    }

    /**
     * Register Sidebars
     *
     * Adds the sidebars used by this theme to WordPress.
     */
    private function _register_sidebars()
    {
        register_sidebar(
            array(
                'name' => 'Homepage Sidebar',
                'id' => 'homepage_sidebar',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h2>',
                'after_title' => '</h2>',
            )
        );
    }

    /**
     * De-Register Widgets
     *
     * Removes unused/unneccessary widgets from the WordPress admin.
     */
    private function _deregister_widgets()
    {
        unregister_widget('P2P_Widget');
        unregister_widget('WP_Widget_Archives');
        unregister_widget('WP_Widget_Calendar');
        unregister_widget('WP_Widget_Categories');
        unregister_widget('WP_Widget_Meta');
        unregister_widget('WP_Widget_Pages');
        unregister_widget('WP_Widget_Recent_Comments');
        unregister_widget('WP_Widget_Recent_Posts');
        unregister_widget('WP_Widget_RSS');
        unregister_widget('WP_Widget_Search');
        unregister_widget('WP_Widget_Tag_Cloud');
    }

    /**
     * Register Widgets
     *
     * Adds this theme's custom widgets to the WordPress admin.
     */
    private function _register_widgets()
    {
        require_once(get_template_directory() .'/inc/widgets/advert-narrow-widget.php');
        register_widget('Advert_Narrow_Widget');

        require_once(get_template_directory() .'/inc/widgets/advert-wide-widget.php');
        register_widget('Advert_Wide_Widget');

        require_once(get_template_directory() .'/inc/widgets/generic-narrow-widget.php');
        register_widget('Generic_Narrow_Widget');

        require_once(get_template_directory() .'/inc/widgets/generic-wide-widget.php');
        register_widget('Generic_Wide_Widget');
    }
}
