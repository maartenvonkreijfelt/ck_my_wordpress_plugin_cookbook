<?php
/**
 * Plugin Name: Chapter 4 -  Wordpress Tutorials v2
 * Plugin URI:
 *   Description: Companion to recipe 'Adding a new section to the custom post type editor' (in chapter 4.2)
 *   Author: Maarten von Kreijfelt
 *   Version: 1.0
 */



/****************************************************************************
 * Code from recipe 'Creating a custom post type'
 ****************************************************************************/

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
/****************************************************************************
 * Code from recipe 'Adding a new section to the custom post type editor'
 ****************************************************************************/

// Register function to be called when admin interface is visited
add_action( 'admin_init', 'ch4_br_admin_init' );

// Function to register new meta box for book review post editor
function ch4_br_admin_init() {
    add_meta_box( 'ch4_br_review_details_meta_box', 'Tutorials Review Details', 'ch4_br_display_review_details_meta_box', 'wordpress_tutorials', 'normal', 'high' );
}

// Function to display meta box contents
function ch4_br_display_review_details_meta_box( $wordpress_tutorials ) {
    // Retrieve current author and rating based on book review ID
    $tutorial_author = esc_html( get_post_meta( $wordpress_tutorials->ID, 'tutorial_author', true ) );
    $tutorial_rating = intval( get_post_meta( $wordpress_tutorials->ID, 'tutorial_rating', true ) );
    ?>
    <table>
        <tr>
            <td style="width: 150px">Tutorial Author</td>
            <td><input type='text' size='80' name='tutorial_review_author_name' value='<?php echo $tutorial_author; ?>' /></td>
        </tr>
        <tr>
            <td style="width: 150px">Tutorial Rating</td>
            <td>
                <select style="width: 100px" name="tutorial_review_rating">
                    <!-- Loop to generate all items in dropdown list -->
                    <?php for ( $rating = 5; $rating >= 1; $rating -- ) { ?>
                    <option value="<?php echo $rating; ?>" <?php echo selected( $rating, $tutorial_rating ); ?>><?php echo $rating; ?> stars
                        <?php } ?>
                </select>
            </td>
        </tr>
    </table>

<?php }

// Register function to be called when posts are saved
// The function will receive 2 arguments
add_action( 'save_post', 'ch4_br_add_book_review_fields', 10, 2 );

function ch4_br_add_book_review_fields( $post_id = false, $post = false ) {
    // Check post type for wordpress tutorials
    if ( 'wordpress_tutorials' == $post->post_type ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['tutorial_review_author_name'] ) ) {
            update_post_meta( $post_id, 'tutorial_author', sanitize_text_field( $_POST['tutorial_review_author_name'] ) );
        }

        if ( isset( $_POST['tutorial_review_rating'] ) && !empty( $_POST['tutorial_review_rating'] ) ) {
            update_post_meta( $post_id, 'tutorial_rating', intval( $_POST['tutorial_review_rating'] ) );
        }
    }
}


