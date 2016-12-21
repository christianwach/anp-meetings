<?php
/**
 * ANP Meetings Content Filters
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */

function anp_meetings_add_meeting_meta( $post_id = null ) {
    $connected_agenda = get_posts( array(
      'connected_type' => 'event_to_agenda',
      'connected_items' => get_queried_object(),
      'nopaging' => true,
      'suppress_filters' => false
    ) );

    $connected_summary = get_posts( array(
      'connected_type' => 'event_to_summary',
      'connected_items' => get_queried_object(),
      'nopaging' => true,
      'suppress_filters' => false
    ) );

    $connected_proposal = get_posts( array(
      'connected_type' => 'event_to_proposal',
      'connected_items' => get_queried_object(),
      'nopaging' => true,
      'suppress_filters' => false
    ) );
    if( 'event' === get_post_type() &&  has_term( 'meeting', 'event-category' ) ) {
        ob_start();

        include_once( ANP_MEETINGS_PLUGIN_DIR . 'public/views/event-meta.php' );

        return ob_get_contents();

        ob_end_clean();
    }
}
add_action( 'eventorganiser_additional_event_meta', 'anp_meetings_add_meeting_meta' );

/**
 * Filter Title
 * Modify the title to display the meeting type and meeting date rather than post title
 * @uses the_title()
 *
 * @param string $title
 * @param int $id
 * @return string $title
 */
function anp_meeting_title_filter( $title, $id = null ) {

    if( is_admin() || !in_the_loop() || !is_main_query() ) {
        return $title;
    }

    // If meeting, display as {organization} - {meeting_type} - {meeting_date}
    if( 'event' == get_post_type() && has_term( 'meeting', 'event-category' ) ) {

        global $post;

        $post_id = ( !empty( $id ) ) ? $id : $post->ID;

        $type_terms = wp_get_post_terms( $post_id, 'meeting_type', array(
            'fields' => 'names'
        ) );
        $type_term = ( !empty( $type_terms ) ) ? $type_terms[0] : '';

        /**
         * Get Event Organiser Date
         *
         * @since 1.0.9
         *
         * @uses eo_the_start()
         *
         * @link http://codex.wp-event-organiser.com/function-eo_the_start.html
         */
        $meeting_date = eo_get_the_start( get_option( 'date_format' ), $post_id );

        if( empty( $type_term ) ) {
            return $title;
        }

        ///{meeting_type} - {meeting_date}
        $meeting_title = sprintf( '<span class="type">%1$s</span> <span class="dash">-</span> <time class="entry-date" datetime="%2$s">%3$s</time>',
            esc_attr( $type_term ),
            esc_attr( eo_get_the_start( 'c', $post_id ) ),
            esc_attr( eo_get_the_start( get_option( 'date_format' ), $post_id ) )
        );

        return $meeting_title;

    }

    return $title;

}
add_filter( 'the_title', 'anp_meeting_title_filter', 10, 2 );

/**
 * Modify Agenda Title
 * @param  string $title
 * @param  int $id
 * @return string $title
 */
function anp_agenda_title_filter( $title, $id = null ) {

    if( is_admin() || !in_the_loop() || !is_main_query() ) {
        return $title;
    }

    if( 'agenda' == get_post_type() || is_post_type_archive( 'agenda' ) ) {

        global $post;

        $connected_meeting = get_posts( array(
          'connected_type' => 'event_to_agenda',
          'connected_items' => $post,
          'nopaging' => true,
          'suppress_filters' => false
        ) );

        if( !empty( $connected_meeting ) ) {

            $post_id = $connected_meeting[0]->ID;

            $type_terms = wp_get_post_terms( $post_id, 'meeting_type', array(
                'fields' => 'names'
            ) );
            $type_term = ( !empty( $type_terms ) ) ? $type_terms[0] : '';

            if( empty( $type_term ) ) {
                return $title;
            }

            $meeting_title = sprintf( '<span class="type">%1$s</span> <span>%2$s</span> <span class="dash">-</span> <time class="entry-date" datetime="%3$s">%4$s</time>',
                esc_attr( $type_term ),
                __( 'Agenda', 'meetings' ),
                esc_attr( eo_get_the_start( 'c', $post_id, $connected_meeting[0]->occurrence_id ) ),
                esc_attr( eo_get_the_start( get_option( 'date_format' ), $post_id, $connected_meeting[0]->occurrence_id ) )
            );

            return $meeting_title;
        }

    }
    return $title;
}
add_filter( 'the_title', 'anp_agenda_title_filter', 10, 2 );

/**
 * Modify Summary Title
 * @param  string $title
 * @param  int $id
 * @return string $title
 */
function anp_summary_title_filter( $title, $id = null ) {

    if( is_admin() || !in_the_loop() || !is_main_query() ) {
        return $title;
    }

    if( 'summary' == get_post_type() || is_post_type_archive( 'summary' ) ) {

        global $post;

        $connected_meeting = get_posts( array(
          'connected_type' => 'event_to_summary',
          'connected_items' => $post,
          'nopaging' => true,
          'suppress_filters' => false
        ) );

        if( !empty( $connected_meeting ) ) {

            $post_id = $connected_meeting[0]->ID;

            $type_terms = wp_get_post_terms( $post_id, 'meeting_type', array(
                'fields' => 'names'
            ) );
            $type_term = ( !empty( $type_terms ) ) ? $type_terms[0] : '';

            if( empty( $type_term ) ) {
                return $title;
            }

            $meeting_title = sprintf( '<span class="type">%1$s</span> <span>%2$s</span> <span class="dash">-</span> <time class="entry-date" datetime="%3$s">%4$s</time>',
                esc_attr( $type_term ),
                __( 'Summary', 'meetings' ),
                esc_attr( eo_get_the_start( 'c', $post_id, $connected_meeting[0]->occurrence_id ) ),
                esc_attr( eo_get_the_start( get_option( 'date_format' ), $post_id, $connected_meeting[0]->occurrence_id ) )
            );

            return $meeting_title;
        }

    }
    return $title;
}
add_filter( 'the_title', 'anp_summary_title_filter', 10, 2 );

// /**
//  * Content Filter
//  * Modify `the_content` to display custom post meta data above and below content
//  * @uses the_content
//  *
//  * @param string $content
//  * @return string $content
//  */
//
// if( !function_exists( 'anp_meetings_content_filter' ) ) {
//
//     function anp_meetings_content_filter( $content ) {
//
//         if( is_admin() || !in_the_loop() || !is_main_query() ) {
//             return $content;
//         }
//
//         $post_types = array(
//             'events',
//             'proposal',
//             'summary',
//             'agenda'
//         );
//
//         $post_tax = array(
//             'organization',
//             'meeting_type',
//             'event-tag',
//             'proposal_status',
//         );
//
//
//         if ( ( is_post_type_archive( 'event' ) || is_tax( array( 'organization', 'meeting_type', 'event-tag' ) ) ) && in_the_loop() ) {
//
//             global $post;
//
//             ob_start();
//
//             include( ANP_MEETINGS_PLUGIN_DIR . 'views/content-archive.php' );
//
//             $content = ob_get_contents();
//
//             ob_end_clean();
//
//         }
//
//         if ( ( is_post_type_archive( $post_types ) || is_tax( $post_tax ) ) && in_the_loop() ) {
//
//             global $post;
//
//             ob_start();
//
//             include( ANP_MEETINGS_PLUGIN_DIR . 'views/content-archive.php' );
//
//             $content = ob_get_contents();
//
//             ob_end_clean();
//
//         }
//
//         if( is_singular( $post_types ) && in_the_loop() ) {
//
//             global $post;
//
//             ob_start();
//
//             include( ANP_MEETINGS_PLUGIN_DIR . 'views/content-single.php' );
//
//             $header = ob_get_contents();
//
//             ob_end_clean();
//
//             $body = wpautop( $post->post_content );
//
//             ob_start();
//
//             include( ANP_MEETINGS_PLUGIN_DIR . 'views/single-footer.php' );
//
//             $footer = ob_get_contents();
//
//             ob_end_clean();
//
//             $content = $header . $body . $footer;
//
//         }
//
//         return $content;
//
//     }
//
//     add_filter( 'the_content', 'anp_meetings_content_filter' );
//
// }


?>
