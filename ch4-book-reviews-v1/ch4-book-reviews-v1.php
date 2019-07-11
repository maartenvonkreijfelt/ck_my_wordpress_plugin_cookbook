<?php

/*
  Plugin Name: Chapter 4 - Book Reviews V1
  Plugin URI: 
  Description: Companion to recipe 'Creating a custom post type'
  Author: ylefebvre
  Version: 1.0
  Author URI: http://ylefebvre.ca/
 */

/****************************************************************************
 * Code from recipe 'Creating a custom post type'
 ****************************************************************************/

add_action( 'init', 'ch4_br_create_book_post_type' );

function ch4_br_create_book_post_type() {
	register_post_type( 'book_reviews',
		array(
				'labels' => array(
				'name' => 'Book Reviews',
				'singular_name' => 'Book Review',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Book Review',
				'edit' => 'Edit',
				'edit_item' => 'Edit Book Review',
				'new_item' => 'New Book Review',
				'view' => 'View',
				'view_item' => 'View Book Review',
				'search_items' => 'Search Book Reviews',
				'not_found' => 'No Book Reviews found',
				'not_found_in_trash' => 'No Book Reviews found in Trash',
				'parent' => 'Parent Book Review'
			),
		'public' => true,
		'menu_position' => 20,
		'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
		'taxonomies' => array( '' ),
        'menu_icon' =>'dashicons-welcome-write-blog',
         //plugins_url( 'book-reviews.png', __FILE__ ),
		'has_archive' => false,
		'exclude_from_search' => true 
		)
	);
}


// Flush rewrite rules to add "review" as a permalink slug
function my_rewrite_flush() {
    ch4_br_create_book_post_type();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );


/**
//Another way to reset the permalinks rules (to make calls from the WordPress rewrite module to programmatically request for the permalinks configuration to be rebuilt) is as follows:
<pre class="EnlighterJSRAW" data-enlighter-language="php">
global $wp_rewrite;
$wp_rewrite-&gt;flush_rules();
</pre>
*/