<?php

/*
* Child them to WordPress Twenty Twenty-Two for markelliottva.com
* Author: Mark Elliott ©2022, all rights reserved
*/
  
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'twentytwentytwo-child-style', get_stylesheet_uri(),
        array( 'twentytwentytwo' ), 
        wp_get_theme()->get('Version') // this only works if you have Version in the style header
    );
}