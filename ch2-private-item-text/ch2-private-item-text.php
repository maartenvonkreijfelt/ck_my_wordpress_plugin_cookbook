<?php
/*
  Plugin Name: Chapter 2 - Private Item Text
  Plugin URI: 
  Description: Companion to recipe 'Creating a new enclosing shortcode'
  Author: Maarten von Kreijfelt
  Version: 1.0

 */

// Declare enclosing shortcode 'private' with associated function
add_shortcode( 'private', 'ch2pit_private_shortcode' );

// Function that is called when the 'private' shortcode is found
function ch2pit_private_shortcode( $atts, $content = null ) {
    if ( is_user_logged_in() )
        return '<div class="private">' . $content . '</div>';
    else {
        $output = '<div class="register">';
		$output .= 'You need to become a member to access ';
		$output .= 'this content.</div>';
		return $output;	
	}
}