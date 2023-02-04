<?php
/**
 * Event Listing
 *
 * @package       EVENTLISTI
 * @author        albiorixtech
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Event Listing
 * Plugin URI:    https://www.albiorixtech.com/
 * Description:   Event listing for your Upcoming events.
 * Version:       1.0.0
 * Author:        albiorixtech
 * Author URI:    https://www.albiorixtech.com/
 * Text Domain:   event-listing
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Event Listing. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// enqueue css script
function elp_frontend_scripts() {
	wp_enqueue_style( 'elp_style', plugins_url('assets/css/elp-style.css',__FILE__ ) );
}
add_action('wp_enqueue_scripts', 'elp_frontend_scripts');

// create event post type
if ( ! function_exists( 'elp_postype' ) ) {
function elp_postype() {
	$labels = array(
		'name' => _x( 'event_cat', 'elp' ),
		'singular_name'         => _x( 'Events', 'elp' ),
		'menu_name'             => __( 'Event', 'elp' ),
		'name_admin_bar'        => __( 'Event', 'elp' ),
		'archives'              => __( 'Event Archives', 'elp' ),
		'attributes'            => __( 'Event Attributes', 'elp' ),
		'parent_item_colon'     => __( 'Parent Event:', 'elp' ),
		'all_items'             => __( 'All Event', 'elp' ),
		'add_new_item'          => __( 'Add New Event', 'elp' ),
		'add_new'               => __( 'Add New Event', 'elp' ),
		'new_item'              => __( 'Event', 'elp' ),
		'edit_item'             => __( 'Edit Event', 'elp' ),
		'update_item'           => __( 'Update Event', 'elp' ),
		'view_item'             => __( 'View Event', 'elp' ),
		'view_items'            => __( 'View Event', 'elp' ),
		'search_items'          => __( 'Search Event', 'elp' ),
		'not_found'             => __( 'Event Not found', 'elp' ),
		'not_found_in_trash'    => __( 'Event Not found in Trash', 'elp' ),
		'featured_image'        => __( 'Event Image', 'elp' ),
		'set_featured_image'    => __( 'Set featured image', 'elp' ),
		'remove_featured_image' => __( 'Remove featured image', 'elp' ),
		'use_featured_image'    => __( 'Use as featured image', 'elp' ),
		'insert_into_item'      => __( 'Insert into Event', 'elp' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Event', 'elp' ),
		'items_list'            => __( 'Event list', 'elp' ),
		'items_list_navigation' => __( 'Event list navigation', 'elp' ),
		'filter_items_list'     => __( 'Filter Event list', 'elp' ),
	);
	$args = array(
		'label'                 => __( 'Events', 'elp' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'taxonomies'            => array( 'event_cat' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-calendar-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'event_list', $args );
	
}
add_action( 'init', 'elp_postype' );
}
// create event categories
if ( ! function_exists( 'elp_taxonomy' ) ) {
function elp_taxonomy() {

	$elp_cat_args = array(
		'label' => __( 'Event categories', 'event-list' ),
		'hierarchical' => true,
		'show_in_nav_menus' => true,
		'show_admin_column' => true,
		'show_in_rest' => true,
		'rewrite' => array( 'slug' => 'event_cat' )
	);
	register_taxonomy( 'event_cat', 'event_list', $elp_cat_args );
}
add_action( 'init', 'elp_taxonomy' );
}


// create metabox
function elp_metabox() {
	add_meta_box(
		'elp-event-metabox',
		__( 'Event details', 'elp-event-list' ),
		'elp_metabox_callback',
		'event_list',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'elp_metabox' );

function elp_metabox_callback( $post ) {
	
	$event_date= get_post_meta( $post->ID, 'elp-event-date', true );
	$location = get_post_meta( $post->ID, 'elp-event-location', true );
	$fees = get_post_meta( $post->ID, 'epl-event-fees', true );
?>
	<p><label for="event-date"><?php esc_attr_e( 'Date', 'elp-event-list' ); ?></label>
	<input class="" id="event-date" type="date" name="event-date" required  placeholder="<?php esc_attr_e( 'Use datepicker', 'elp-event-list' ); ?>" value="<?php echo esc_attr( $event_date ); ?>" /></p>

	<p><label for="event-location"><?php esc_attr_e( 'Location', 'elp-event-list' ); ?></label>
	<input class="" id="event-location" type="text" name="event-location" placeholder="<?php esc_attr_e( 'Location: Vadodara', 'elp-event-list' ); ?>" value="<?php echo esc_attr( $location ); ?>" /></p>

	<p><label for="event-fees"><?php esc_attr_e( 'Fees', 'elp-event-list' ); ?></label>
	<input class="" id="event-fees" type="text" name="event-fees" placeholder="<?php esc_attr_e( '$0.00', 'elp-event-list' ); ?>" value="<?php echo esc_attr( $fees ); ?>" />
	</p>
	<?php
}

// save event
function elp_save_event_details( $post_id ) {
	
	// if this is an autosave, our form has not been submitted, so do nothing
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// check user permission
	if ( ( get_post_type() != 'event_list' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	// checking values and save fields
	if ( isset( $_POST['event-date'] ) ) {
		update_post_meta( $post_id, 'elp-event-date', sanitize_text_field( $_POST['event-date'] ) );
	}
	if ( isset( $_POST['event-location'] ) ) {
		update_post_meta( $post_id, 'elp-event-location', sanitize_text_field($_POST['event-location']));
	}
	if ( isset( $_POST['event-fees'] ) ) {
		update_post_meta( $post_id, 'epl-event-fees',  sanitize_text_field($_POST['event-fees']));
	}
	
}
add_action( 'save_post', 'elp_save_event_details' );


// events shortcode
function elp_events_shortcode( $atts ) {
	// shortcode attributes
	$atts = shortcode_atts(array(
		'posts_per_page' => '',
		'order' => 'DESC',
		'no_events_text' => __('There are no events listed.', 'epl-event-list')
	), $atts );

	// initialize output
	$output = '';
	// main container
	if ( empty($atts['class']) ) {
		$custom_class = '';
	} else {
		$custom_class = ' '.$atts['class'];
	}
	$output .= '<div id="elp" class="elp-shortcode elp-shortcode-events'.esc_attr($custom_class).'">';
		// query
		global $paged;
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		$elp_query_args = array(
			'post_type' => 'event_list',
			'event_cat' => $atts['event_cat'],
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			//'meta_key' => 'event-start-date',
			'orderby' => 'meta_value_num menu_order',
			'order' => $atts['order'],
			'posts_per_page' => $atts['posts_per_page'],
			'offset' => $atts['offset'],
 			'paged' => $paged
		);
		$elp_query = new WP_Query( $elp_query_args );
		 $unique = [];
		if ( $elp_query->have_posts() ) :
			while( $elp_query->have_posts() ): $elp_query->the_post();
				// include event variables
				//include 'elp-variables.php';
				//echo '<br>';
//the_title().'</br>';
				// include event template
				include 'elp-listing-template.php';
			endwhile;
				// pagination
				$output .= '<div class="elp-nav">';
					$output .= get_next_posts_link(  __( 'Next &raquo;', 'very-simple-event-list' ), $elp_query->max_num_pages );
					$output .= get_previous_posts_link( __( '&laquo; Previous', 'very-simple-event-list' ) );
				$output .= '</div>';
			
			// reset post data
			wp_reset_postdata();
		else:
			// if no events
			$output .= '<p class="elp-no-events">';
			$output .= esc_attr($atts['no_events_text']);
			$output .= '</p>';
		endif;
	$output .= '</div>';

	// return output
	return $output;
}
add_shortcode('elp-events', 'elp_events_shortcode');
