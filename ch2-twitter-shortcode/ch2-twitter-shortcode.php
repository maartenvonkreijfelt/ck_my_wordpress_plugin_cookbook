<?php

/*
  Plugin Name: 2 - Twitter Shortcode
  Plugin URI: 
  Description: Companion to recipe 'Creating a new simple shortcode'
  Author: Maarten von Kreijfelt
  Version: 1.0

 */

add_shortcode( 'tl', 'ch2ts_twitter_link_shortcode' );

function ch2ts_twitter_link_shortcode( $atts ) {
	$output = '<a href="https://twitter.com/ylefebvre">';
	$output .= 'Twitter Feed</a>';
	return $output;
}