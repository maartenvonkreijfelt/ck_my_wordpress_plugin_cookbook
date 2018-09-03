<?php
/*
  Plugin Name:  2 - Generator Filter
  Plugin URI: 
  Description: Companion to recipe 'Modifying the generator tag title using plugin filters'. Beyond adding functionality or content to a site, the other major task commonly performed
by plugins is to augment, modify, or reduce information before it is displayed on the screen.
This is done by using WordPress filter hooks, which allow plugins to register a custom
function through the WordPress API to be called, since content is prepared before it is sent
to the browser. In this recipe, you will learn how to implement your first filter callback
function to modify the contents of the generator meta tag that is output as part of the site
header.
  Author: Maarten von Kreijfelt
  Version: 1.0
  Author URI: http://www.preludio.nl
 */

add_filter( 'the_generator', 'ch2gf_generator_filter', 10, 2 );

function ch2gf_generator_filter ( $html, $type ) {
	if ( $type == 'xhtml' ) {
		$html = preg_replace( '("WordPress.*?")', '"Maarten von Kreijfelt"', $html );
	}
    return $html;
}
