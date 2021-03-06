<?php
/**
  Plugin Name: ch4-bookreview
  Plugin URI:
  Description: Companion to recipe 4.1 'Creating a custom post type'
  Author: Maarten von Kreijfelt
  Version: 1.0

 */

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
                'not_found_in_trash' =>
                    'No Book Reviews found in Trash',
                'parent' => 'Parent Book Review'
            ),
            'public' => true,
            'menu_position' => 20,
            'supports' => array(
                'title',
                'editor',
                'comments',
                'thumbnail',
                'custom-fields' ),
                'taxonomies' => array( '' ),
                'menu_icon' =>'dashicons-welcome-write-blog',
                //plugins_url( 'book-reviews.png', __FILE__ ),
            'has_archive' => true,
            'exclude_from_search' => true
        )
    );
}

add_action( 'init', 'ch4_br_create_book_post_type' );
