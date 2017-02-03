<?php

class Mobile_Nav_Walker extends Walker_Nav_Menu {

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        global $wp_query;

        // code indent
        $indent = ($depth > 0 ? str_repeat( "\t", $depth) : '');

        // depth dependent classes
        $depth_classes =
            array(
                ($depth == 0 ? 'main-menu-item' : 'sub-menu-item'),
                ($depth >= 2 ? 'sub-sub-menu-item' : ''),
                ($depth % 2 ? 'menu-item-odd' : 'menu-item-even'),
                'menu-item-depth-'. $depth
            );

        $depth_class_names = esc_attr(implode( ' ', $depth_classes));

        // passed classes
        $classes = (empty($item->classes) ? array() : (array) $item->classes);
        $class_names = esc_attr(implode(' ', apply_filters('nav_menu_css_class', array_filter( $classes ), $item)));

        // build html
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID .'" class="'. $depth_class_names .' '. $class_names .'">';

        // link attributes
        $attributes = (!empty($item->attr_title) ? ' title="'. esc_attr($item->attr_title) .'"' : '');
        $attributes .= (!empty($item->target) ? ' target="'. esc_attr($item->target) .'"' : '');
        $attributes .= (!empty($item->xfn) ? ' rel="'. esc_attr($item->xfn) .'"' : '');
        $attributes .= (!empty($item->url) ? ' href="'. esc_attr($item->url) .'"' : '');
        $attributes .= ' class="menu-link '. ($depth > 0 ? 'sub-menu-link' : 'main-menu-link') .'"';

        $before = (!empty($args->before) ? $args->before : '');
        $after = (!empty($args->after) ? $args->after : '');

        if(is_array($item->classes) && in_array('menu-item-has-children', $item->classes))
            $after .= '<a href="#" class="toggle-sub-menu"><span class="fa fa-plus-circle"></span></a>';

        $item_output =
            sprintf(
                '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
                $before,
                $attributes,
                (!empty($args->link_before) ? $args->link_before : ''),
                apply_filters( 'the_title', $item->title, $item->ID ),
                (!empty($args->link_after) ? $args->link_after : ''),
                $after
            );

        // build html
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
