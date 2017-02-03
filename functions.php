<?php

include_once('inc/starter-theme.php');
include_once('inc/starter-theme-settings.php');
include_once('inc/starter-theme-widgets.php');
include_once('inc/starter-theme-mobile-nav-walker.php');

add_action('init', array('starter_theme', 'singleton'));
add_action('init', array('starter_theme_settings', 'singleton'));

add_action('widgets_init', array('starter_theme_widgets', 'singleton'));
add_action('after_setup_theme', array('starter_theme', 'set_theme_options'));
