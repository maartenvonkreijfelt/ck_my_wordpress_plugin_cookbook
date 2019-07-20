<?php


/*
  Plugin Name: Chapter 9 - Load jquery v1
  Plugin URI:
  Description: Companion to recipe 'Safely loading jQuery onto WordPress web pages' Chapter 9.1
  Author: Maarten von Kreijfelt
  Version: 2.0
  Author URI:
 */


function ch9lj_front_facing_pages() {
    wp_enqueue_script( 'jquery' );
}

add_action( 'wp_enqueue_scripts', 'ch9lj_front_facing_pages' );