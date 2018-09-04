<?php
/*
  Plugin Name: 2 - Object-Oriented - Private Item Text
  Plugin URI: 
  Description: Companion to recipe 'Writing plugins using object-oriented PHP'
  Author: Maarten von Kreijfelt
  Version: 1.0

 */

class CH2_OO_Private_Item_Text {
	function __construct() {

		// Declare enclosing shortcode 'private' with associated function
		add_shortcode( 'private', array( $this, 'ch2pit_private_shortcode' ) );


		// Associate function to queue stylesheet to be output in page header
		add_action( 'wp_enqueue_scripts', array( $this, 'ch2pit_queue_stylesheet' ) );

	}


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



// Function to load style in stylesheet queue
	function ch2pit_queue_stylesheet() {
		wp_enqueue_style( 'privateshortcodestyle', plugins_url( 'stylesheet.css', __FILE__ ) );
	}


}
$my_ch2_oo_private_item_text = new CH2_OO_Private_Item_Text();































