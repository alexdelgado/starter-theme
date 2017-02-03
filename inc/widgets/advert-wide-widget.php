<?php

class Advert_Wide_Widget extends WP_Widget {

    /**
     * Advert_Wide_Widget constructor.
     *
     * @param string $id_base         Optional Base ID for the widget, lowercase and unique. If left empty,
     *                                a portion of the widget's class name will be used Has to be unique.
     * @param string $name            Name for the widget displayed on the configuration page.
     * @param array  $widget_options  Optional. Widget options. See {@see wp_register_sidebar_widget()} for
     *                                information on accepted arguments. Default empty array.
     * @param array  $control_options Optional. Widget control options. See {@see wp_register_widget_control()}
     *                                for information on accepted arguments. Default empty array.
     */
    public function __construct()
    {
        parent::__construct(
            'advert_wide',
            __('Advert Wide Widget', 'theme'),
            array(
                'description' => __('Wide widget displaying an advert.', 'theme')
            )
        );
    }

    /**
     * Widget.
     *
     * Generates the widget markup and displays it on the front-end.
     *
     * @param array $args     Display arguments including before_title, after_title,
     *                        before_widget, and after_widget.
     * @param array $instance The settings for the particular instance of the widget.
     */
    public function widget($args = array(), $instance = array())
    {
        if(!empty($instance['ad_tag']))
            require(get_template_directory() .'/templates/widgets/advert-wide-widget.php');
    }

    /**
     * Form
     *
     * Generates the widgets fields visible in the WordPress admin.
     *
     * @param array $instance Current settings.
     */
    public function form($instance = array())
    {
        $shortcode = (isset($instance['ad_tag']) ? $instance['ad_tag'] : '');

        require(get_template_directory() .'/inc/views/widgets/advert-widget.php');
    }

    /**
     * Update
     *
     * This function should check that $new_instance is set correctly. The newly-calculated
     * value of `$instance` should be returned. If false is returned, the instance won't be
     * saved/updated.
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            {@see WP_Widget::form()}.
     * @param array $old_instance Old settings for this instance.
     *
     * @return array Settings to save or bool false to cancel saving.
     */
    public function update($new_instance = array(), $old_instance = array())
    {
        $instance['ad_tag'] = (isset($new_instance['ad_tag']) ? $new_instance['ad_tag'] : $old_instance['ad_tag']);

        return $instance;
    }
}
