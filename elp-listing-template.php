<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	$event_date= get_post_meta( get_the_ID(), 'elp-event-date', true );
	$location = get_post_meta( get_the_ID(), 'elp-event-location', true );
	$fees = get_post_meta( get_the_ID(), 'epl-event-fees', true );
	
	
	$terms = get_the_terms( get_the_ID(), 'event_cat' ); 
		foreach($terms as $term) {
            // Get the unique array from loop
            if( !in_array( $term->term_id, $unique ) )
			{
                $unique[] = $term->term_id;
                echo '<a href="#'.$term->name.'" class="epl-cate-filter">'.$term->name.'</a>';
            }

        }
// container

	$output .='<div id="event-'.get_the_ID().'" class="elp-content">';
	//image
	$output .= '<div class="elp-image">';
	$output .= get_the_post_thumbnail(get_the_ID(), "thumbnail", array( "class" => "alignleft" ) );
	$output .= '</div>';
	// title
	$output .= '<h3 class="elp-title">'.get_the_title().'</h3>';	
	// combine date icon and title or not		
	$output .= '<div class="elp-details">';
	// date
    $output .='<div class="elp-date"><strong>Date:</strong>' .$event_date.'</div>'; 
	$output .='<div class="elp-location"><strong>Location:</strong>'. $location.'</div>'; 
	$output .='<div class="elp-date"><strong>Fees:</strong>' .$fees.'</div>'; 
	$output .= '</div>';
	
// end event container
$output .= '</div>';