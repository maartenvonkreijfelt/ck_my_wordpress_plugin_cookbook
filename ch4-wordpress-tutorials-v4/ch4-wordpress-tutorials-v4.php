<?php

/*
  Plugin Name: Chapter 4 - Wordpress Tutorials V4
  Plugin URI: 
  Description: Companion to recipe 'Displaying custom post type data in shortcodes Chapter 4.4'
  Author: Maarten von Kreijfelt
  Version: 1.0
  Author URI:
 */





/****************************************************************************
 * Code from recipe 'Creating a custom post type' Chapter 4.1
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
 * Code from recipe 'Adding a new section to the custom post type editor' Chapter 4.2
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




/************************************************************************************
 * Code from recipe 'Displaying single custom post type items using a custom layout' Chapter 4.3
 ************************************************************************************/

add_filter( 'template_include', 'ch4_br_template_include', 1 );

function ch4_br_template_include( $template_path ){

    if ( 'wordpress_tutorials' == get_post_type() ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise install content filter
            if ( $theme_file = locate_template( array( 'single-wordpress_tutorials.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                add_filter( 'the_content', 'ch4_br_display_single_book_review', 20 );
            }
        }
    }

    return $template_path;
}

function ch4_br_display_single_book_review( $content ) {
    if ( !empty( get_the_ID() ) ) {
        // Display featured image in right-aligned floating div
        $content .= '<div style="float: right; margin: 10px">';
        $content .= get_the_post_thumbnail( get_the_ID(), 'medium' );
        $content .= '</div>';

        $content .= '<div class="entry-content">';

        // Display Author Name
        $content .= '<strong>Author: </strong>';
        $content .= esc_html( get_post_meta( get_the_ID(), 'tutorial_author', true ) );
        $content .= '<br />';

        // Display yellow stars based on rating -->
        $content .= '<strong>Rating: </strong>';

        $nb_stars = intval( get_post_meta( get_the_ID(), 'tutorial_rating', true ) );

        for ( $star_counter = 1; $star_counter <= 5; $star_counter++ ) {
            if ( $star_counter <= $nb_stars ) {
                $content .= '<img src="' . plugins_url( 'star-icon.png', __FILE__ ) . '" />';
            } else {
                $content .= '<img src="' .
                    plugins_url( 'star-icon-grey.png', __FILE__ ) . '" />';
            }
        }

        // Display book review contents
        $content .= '<br /><br />' . get_the_content( get_the_ID() ) . '</div>';

        return $content;
    }
}

/****************************************************************************
 * Code from recipe 'Displaying custom post type data in shortcodes' Chapter 4.4
 ****************************************************************************/

//add_shortcode( 'book-review-list', 'ch4_br_book_review_list' );
add_shortcode( 'wordpress-tutorial-list', 'ch4_br_book_review_list' );


// Implementation of short code function
function ch4_br_book_review_list() {
	// Preparation of query array to retrieve 5 book reviews
	$query_params = array( 'post_type' => 'wordpress_tutorials',
                           'post_status' => 'publish',
                           'posts_per_page' => 5 );
	
	// Retrieve page query variable, if present
	$page_num = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	// If page number is higher than 1, add to query array
	if ( $page_num != 1 ) {
		$query_params['paged'] = $page_num;
	}

	// Execution of post query
	$wordpress_tutorial_query = new WP_Query;
    $wordpress_tutorial_query->query( $query_params );
	
	// Check if any posts were returned by query
	if ( $wordpress_tutorial_query->have_posts() ) {
		// Display posts in table layout
		$output = '<table>';
		$output .= '<tr><th style="width: 350px"><strong>Title</strong></th>';
		$output .= '<th><strong>Author</strong></th></tr>';

		// Cycle through all items retrieved
		while ( $wordpress_tutorial_query->have_posts() ) {
			$wordpress_tutorial_query->the_post();
			$output .= '<tr><td><a href="' . get_permalink() . '">';
			$output .= get_the_title( get_the_ID() ) . '</a></td>';
			$output .= '<td>' . esc_html( get_post_meta( get_the_ID(), 'tutorial_author', true ) );
			$output .= '</td></tr>';
		}

		$output .= '</table>';

		// Display page navigation links
		if ( $book_review_query->max_num_pages > 1 ) {
			$output .= '<nav id="nav-below">';
			$output .= '<div class="nav-previous">';
			$output .= get_next_posts_link( '<span class="meta-nav">&larr;</span> Older reviews', $wordpress_tutorial_query->max_num_pages );
			$output .= '</div>';
			$output .= "<div class='nav-next'>";
			$output .= get_previous_posts_link( 'Newer reviews <span class="meta-nav">&rarr;</span>', $wordpress_tutorial_query->max_num_pages );
			$output .= '</div>';
			$output .= '</nav>';
		}

		// Reset post data query
		wp_reset_postdata();
	}

	return $output;
}