<?php
/**
 * Plugin Name: Chapter 4 -  Wordpress Tutorials v1
 * Plugin URI:
 *   Description: Companion to recipe 'Creating a custom post type' (in chapter 4.1)
 *   Author: Maarten von Kreijfelt
 *   Version: 1.0
 */

function ch4_br_create_book_post_type() {
    register_post_type( 'wordpress_tutorials',
        array(
            'labels' => array(
                'name' => 'Wordpress Tutorials',
                'singular_name' => 'Wordpress Tutorial',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Wordpress Tutorial',
                'edit' => 'Edit',
                'edit_item' => 'Edit Wordpress Tutorial',
                'new_item' => 'New Wordpress Tutorial',
                'view' => 'View',
                'view_item' => 'View Wordpress Tutorial',
                'search_items' => 'Search Wordpress Tutorials',
                'not_found' => 'No Wordpress Tutorial found',
                'not_found_in_trash' =>
                    'No Wordpress Tutorials found in Trash',
                'parent' => 'Parent Wordpress Tutorial'
            ),
            'public' => true,
            'menu_position' => 20,
            'supports' =>
                array( 'title', 'editor', 'comments',
                    'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' =>'dashicons-wordpress',
            //plugins_url( 'wordpress-tutorials.png', __FILE__ ),
            'has_archive' => true,
            'exclude_from_search' => true
        )
    );
}

add_action( 'init', 'ch4_br_create_book_post_type' );




// Flush rewrite rules to add "review" as a permalink slug
function my_rewrite_flush() {
    ch4_br_create_book_post_type();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );


// Another way  to reset the permalinks rules is as follows:
//global $wp_rewrite;
//$wp_rewrite->flush_rules();