<?php
/*
  Plugin Name: 3 - Page Header Output V10 BAK
  Plugin URI:
  Description: Companion to recipe 'Displaying a confirmation message when options are saved'
  Author: Maarten von Kreijfelt
  Version: 1.0

 */



/****************************************************************************************************
 * Code from recipe 'Adding output content to page headers using plugin actions'  Chapter 2.1       *
 ****************************************************************************************************/

add_action( 'wp_head', 'ch2pho_page_header_output' );

function ch2pho_page_header_output() { ?>

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;
			i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();
			a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;
			m.parentNode.insertBefore(a,m)})(window,document,'script',
			'https://www.google-analytics.com/analytics.js','ga');

		ga( 'create', 'UA-0000000-0', 'auto' );
		ga( 'send', 'pageview' );
	</script>

<?php }





/*******************************************************************************************************************
 * Code from recipe 'Inserting link statistics tracking code in page body using plugin filters'  Chapter 2.1       *
 *******************************************************************************************************************/


add_filter( 'the_content', 'ch2lfa_link_filter_analytics' );

function ch2lfa_link_filter_analytics ( $the_content ) {
	$new_content = str_replace( 'href', 'onClick="recordOutboundLink(this);return false;" href', $the_content );

	return $new_content;
}

add_action( 'wp_footer', 'ch2lfa_footer_analytics_code' );

function ch2lfa_footer_analytics_code() { ?>

	<script type="text/javascript">
		function recordOutboundLink( link ) {
			ga('send', 'event', 'Outbound Links', 'Click',
				link.href, {
					'transport': 'beacon',
					'hitCallback': function() {
						document.location = link.href;
					}
				} );
		}
	</script>

<?php }

/****************************************************************************
 * Code from recipe 'Storing user settings using arrays'  Chapter 3.2       *
 ****************************************************************************/


register_activation_hook( __FILE__, 'ch3io_set_default_options' );


function ch3io_set_default_options() {
	if ( false === get_option( 'ch3io_ga_account_name' ) ) {
		add_option( 'ch3io_ga_account_name', 'UA-0000000-0' );
	}
}


register_activation_hook( __FILE__, 'ch2pho_set_default_options_array' );

function ch2pho_set_default_options_array() {
	ch2pho_get_options();
}

function ch2pho_get_options() {
	$options = get_option( 'ch2pho_options', array() );

	$new_options['ga_account_name'] = 'UA-000000-0';
	$new_options['track_outgoing_links'] = false;

	$merged_options = wp_parse_args( $options, $new_options );

	$compare_options = array_diff_key( $new_options, $options );
	if ( empty( $options ) || !empty( $compare_options ) ) {
		update_option( 'ch2pho_options', $merged_options );
	}
	return $merged_options;
}

/*****************************************************************
 * Code from recipe 'Creating an administration page menu item   *
 * in the settings menu' Chapter 3.4                                          *
 *****************************************************************/

add_action( 'admin_menu', 'ch2pho_settings_menu' );

function ch2pho_settings_menu() {
	add_options_page( 'My Google Analytics Configuration',
		'My Google Analytics', 'manage_options',
		'ch2pho-my-google-analytics', 'ch2pho_config_page' );
}

/**************************************************************************
 * Code from recipe 'Rendering admin page contents using HTML'  Chapter 3.8
 **************************************************************************/

function ch2pho_config_page() {
	// Retrieve plugin configuration options from database
	$options = ch2pho_get_options();
	?>

	<div id="ch2pho-general" class="wrap">
		<h2>My Google Analytics</h2><br />

		<?php if ( isset( $_GET['message'] ) &&
		           $_GET['message'] == '1' ) { ?>
			<div id='message' class='updated fade'>
				<p><strong>Settings Saved</strong></p></div>
		<?php } ?>

		<form method="post" action="admin-post.php">

			<input type="hidden" name="action"
			       value="save_ch2pho_options" />

			<!-- Adding security through hidden referrer field -->
			<?php wp_nonce_field( 'ch2pho' ); ?>

			Account Name: <input type="text" name="ga_account_name" value="<?php echo esc_html( $options['ga_account_name'] ); ?>"/><br />
			Track Outgoing Links <input type="checkbox" name="track_outgoing_links" <?php checked( $options['track_outgoing_links'] ); ?>/><br /><br />
			<input type="submit" value="Submit" class="button-primary"/>
		</form>
	</div>
<?php }

/*******************************************************************************
 * Code from recipe 'Processing and storing admin page post data'  Chapter 3.9 *
 *******************************************************************************/

add_action( 'admin_init', 'ch2pho_admin_init' );

function ch2pho_admin_init() {
	add_action( 'admin_post_save_ch2pho_options',
		'process_ch2pho_options' );
}

function process_ch2pho_options() {

	// Check that user has proper security level

	if ( !current_user_can( 'manage_options' ) )
		wp_die( 'Not allowed' );

	// Check that nonce field created in configuration form
	// is present

	check_admin_referer( 'ch2pho' );

	// Retrieve original plugin options array
	$options = ch2pho_get_options();

	// Cycle through all text form fields and store their values
	// in the options array

	foreach ( array( 'ga_account_name' ) as $option_name ) {
		if ( isset( $_POST[$option_name] ) ) {
			$options[$option_name] =
				sanitize_text_field($_POST[$option_name]);
		}
	}

	// Cycle through all check box form fields and set the options
	// array to true or false values based on presence of
	// variables

	foreach ( array( 'track_outgoing_links' ) as $option_name ) {
		if ( isset( $_POST[$option_name] ) ) {
			$options[$option_name] = true;
		} else {
			$options[$option_name] = false;
		}
	}

	// Store updated options array to database
	update_option( 'ch2pho_options', $options );

	// Redirect the page to the configuration form that was
	// processed

	wp_redirect( add_query_arg(array( 'page' => 'ch2pho-my-google-analytics', 'message' => '1' ),admin_url( 'options-general.php' ) ) );
	exit;
}