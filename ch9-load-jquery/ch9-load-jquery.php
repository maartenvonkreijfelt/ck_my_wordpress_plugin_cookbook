<?php
/*
  Plugin Name:  9 - Load jQuery
  Plugin URI: 
  Description: Companion to recipe 'Safely loading jQuery onto WordPress web pages'
  Author: Maarten von Kreijfelt
  Version: 1.0
  Author URI: http://www.preludio.nl
 */

// Register function to be called when scripts are being queued
add_action( 'wp_enqueue_scripts', 'ch9lj_front_facing_pages' );

// Function to request to load jquery script on front-facing pages
function ch9lj_front_facing_pages() {
	wp_enqueue_script( 'jquery' );
}