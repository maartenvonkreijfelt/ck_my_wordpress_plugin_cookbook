<?php
/*
  Plugin Name: 2 - Add Favicon
  Plugin URI:
  Description: Companion to recipe 'Using WordPress path utility functions' , Using WordPress path utility functions to
load external files and images
  Author: Maarten von Kreijfelt
  Version: 1.0
  Author URI: http://www.preludio.nl
 */

add_action( 'wp_head', 'ch2fi_page_header_output' );

function ch2fi_page_header_output() {
	$site_icon_url = get_site_icon_url();
	if ( !empty( $site_icon_url ) ) {
		wp_site_icon();
	} else {
		$icon_url = plugins_url( 'favicon.ico', __FILE__ );
?>

    <link rel="shortcut icon" href="<?php echo $icon_url; ?>" />
 <?php  
	}
}


