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


/*
 * CUSTOM CONTENT FILTERS
 */

/**
 * Filter Title
 * Modify the title to display the meeting type and meeting date rather than post title
 * @uses the_title()
 *
 * @param string $title
 * @param int $id
 * @return string $title
 */
if(! function_exists( 'anp_meetings_title_filter' ) ) {

    function anp_meetings_title_filter( $title, $id = null ) {

        if( is_admin() || !in_the_loop() || !is_main_query() ) {
            return $title;
        }

        // If meeting, display as {organization} - {meeting_type} - {meeting_date}
        if( is_singular( 'meeting' ) || is_post_type_archive( 'meeting' ) || is_tax( array( 'meeting_type', 'meeting_tag' ) ) ) {

            global $post;

            $meeting_title = [];

            $org_terms = wp_get_post_terms( $post->ID, 'organization', array(
                'fields' => 'names'
            ) );
            $org_terms = ( !empty( $org_terms ) ) ? $org_terms[0] : '' ;

            $type_terms = wp_get_post_terms( $post->ID, 'meeting_type', array(
                'fields' => 'names'
            ) );
            $type_terms = ( !empty( $type_terms ) ) ? $type_terms[0] : '';

            $meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, 'meeting_date', true ) ) );

            if( empty( $org_terms ) && empty( $type_terms ) ) {
                return $post->post_title;
            }

            if( !empty( $org_terms ) ) {
                array_push( $meeting_title, '<span class="organization">' . $org_terms . '</span>' );
            }
            if( !empty( $type_terms ) ) {
                array_push( $meeting_title, '<span class="type">' . $type_terms . '</span>' );
            }
            if( !empty( $meeting_date ) ) {
                array_push( $meeting_title, '<time>' . $meeting_date . '</time>' );
            }

            return implode( ' - ', $meeting_title );

        }

        // If agenda or summary, display as {post_type name - singular} - {meeting_type} - {meeting_date}
        if( is_post_type_archive( array( 'agenda', 'summary' ) ) || is_singular( array( 'agenda', 'summary' ) ) ) {

            global $post;
            $meeting_title = [];

            $post_type_object = get_post_type_object( get_post_type( get_the_ID() ) );

            $post_type_name = $post_type_object->labels->singular_name;

            $org_terms = wp_get_post_terms( $post->ID, 'organization', array(
                'fields' => 'names'
            ) );
            $org_terms = ( !empty( $org_terms ) ) ? $org_terms[0] : '' ;

            $meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, 'meeting_date', true ) ) );

            if( !empty( $post_type_name ) ) {
                array_push( $meeting_title, '<span class="post-type">' . $post_type_name . '</span>' );
            }
            if( !empty( $org_terms ) ) {
                array_push( $meeting_title, '<span class="organization">' . $org_terms . '</span>' );
            }
            if( !empty( $type_terms ) ) {
                array_push( $meeting_title, '<span class="type">' . $type_terms . '</span>' );
            }
            if( !empty( $meeting_date ) ) {
                array_push( $meeting_title, '<time>' . $meeting_date . '</time>' );
            }

            return implode( ' - ', $meeting_title );

        }

        return $title;

    }

    add_filter( 'the_title', 'anp_meetings_title_filter', 10, 2 );

}


/**
 * Content Filter
 * Modify `the_content` to display custom post meta data above and below content
 * @uses the_content
 *
 * @param string $content
 * @return string $content
 */

if( !function_exists( 'anp_meetings_content_filter' ) ) {

    function anp_meetings_content_filter( $content ) {

        if( is_admin() || !in_the_loop() || !is_main_query() ) {
            return $content;
        }

        $post_types = array(
            'meeting',
            'proposal',
            'summary',
            'agenda'
        );

        $post_tax = array(
            'organization',
            'meeting_type',
            'meeting_tag',
            'proposal_status',
        );


        if ( ( is_post_type_archive( 'meeting' ) || is_tax( array( 'organization', 'meeting_type', 'meeting_tag' ) ) ) && in_the_loop() ) {

            global $post;

            ob_start();

            include( ANP_MEETINGS_PLUGIN_DIR . 'views/content-archive.php' );

            $content = ob_get_contents();

            ob_end_clean();

        }

        if ( ( is_post_type_archive( $post_types ) || is_tax( $post_tax ) ) && in_the_loop() ) {

            global $post;

            ob_start();

            include( ANP_MEETINGS_PLUGIN_DIR . 'views/content-archive.php' );

            $content = ob_get_contents();

            ob_end_clean();

        }

        if( is_singular( $post_types ) && in_the_loop() ) {

            global $post;

            ob_start();

            include( ANP_MEETINGS_PLUGIN_DIR . 'views/content-single.php' );

            $header = ob_get_contents();

            ob_end_clean();

            $body = wpautop( $post->post_content );

            ob_start();

            include( ANP_MEETINGS_PLUGIN_DIR . 'views/single-footer.php' );

            $footer = ob_get_contents();

            ob_end_clean();

            $content = $header . $body . $footer;

        }

        return $content;

    }

    add_filter( 'the_content', 'anp_meetings_content_filter' );

}


?>
