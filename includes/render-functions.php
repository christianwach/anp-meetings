<?php

/**
 * ADMIN CONNECTION.
 *
 * Order posts alphabetically in the P2P connections box.
 */
if ( ! function_exists( 'wordpress_connection_box_order' ) ) {

    function wordpress_connection_box_order( $args, $ctype, $post_id ) {
        if ( ( 'meeting_to_agenda' == $ctype->name || 'meeting_to_summary' == $ctype->name || 'meeting_to_proposal' == $ctype->name ) ) {
            $args['orderby'] = 'title';
            $args['order'] = 'asc';
        }

        return $args;
    }

    add_filter( 'p2p_connectable_args', 'wordpress_connection_box_order', 10, 3 );

}



/**
 * Agenda.
 *
 * Render agenda associated with content.
 */
if ( ! function_exists( 'meeting_get_agenda' ) ) {

    function meeting_get_agenda( $post_id ) {

        $query_args = array(
            'connected_type' => 'meeting_to_agenda',
            'connected_items' => intval( $post_id ),
            'nopaging' => true
        );

        $agendas = get_posts( $query_args );

        if ( empty( $agendas ) ) {
            return;
        }

        $post = $agendas[0];

        $post_type_obj = get_post_type_object( get_post_type( $post->ID ) );
        $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';

        $content = sprintf( '<li class="agenda-link"><a href="%s" rel="bookmark" title="View %s"><span class="link-text">%s</span></a></li>',
          get_post_permalink( $post->ID ),
          ( $post_type_name ) ? $post_type_name : $post->post_title,
          ( $post_type_name ) ? $post_type_name : $post->post_title
        );

        // Filter added to allow content be overriden
        return apply_filters( 'meeting_get_agenda_content', $content, $post_id );
    }

}

/**
 * Summary.
 *
 * Render summary associated with content.
 */
if ( ! function_exists( 'meeting_get_summary' ) ) {

    function meeting_get_summary( $post_id ) {

        $query_args = array(
            'connected_type' => 'meeting_to_summary',
            'connected_items' => intval( $post_id ),
            'nopaging' => true
        );

        $summaries = get_posts( $query_args );

        if ( empty( $summaries ) ) {
          return;
        }

        $post = $summaries[0];

        $post_type_obj = get_post_type_object( get_post_type( $post->ID ) );
        $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';

        $content = sprintf( '<li class="summary-link"><a href="%s" rel="bookmark" title="View %s"><span class="link-text">%s</span></a></li>',
          get_post_permalink( $post->ID ),
          ( $post_type_name ) ? esc_attr( $post_type_name ) : esc_attr( $post->post_title ),
          ( $post_type_name ) ? $post_type_name : $post->post_title
        );

        // Filter added to allow content be overriden
        return apply_filters( 'meeting_get_summary_content', $content, $post_id );
    }

}



/**
 * Proposal.
 *
 * Render proposal associated with content.
 */
if ( ! function_exists( 'meeting_get_proposal' ) ) {

    function meeting_get_proposal( $post_id ) {

        $query_args = array(
            'connected_type' => 'meeting_to_proposal',
            'connected_items' => intval( $post_id ),
            'nopaging' => true
        );

        $proposals = get_posts( $query_args );

        if ( empty( $proposals ) ) {
          return;
        }

        $url = array(
            'connected_type' => 'meeting_to_proposal',
            'connected_items' => intval( $post_id ),
            'connected_direction' => 'from'
        );

        $content = sprintf( '<li class="proposal-link"><a href="%s" rel="bookmark" title="View %s"><span class="link-text">%s</span></a></li>',
          esc_url( add_query_arg( $url ) ),
          ( 1 == count( $proposals ) ) ? __( 'Proposal', 'meeting' ) : __( 'Proposals', 'meeting' ),
          ( 1 == count( $proposals ) ) ? __( 'Proposal', 'meeting' ) : __( 'Proposals', 'meeting' )
        );

        // Filter added to allow content be overriden
        return apply_filters( 'meeting_get_proposal_content', $content, $post_id );
    }

}



/**
 * Get Events.
 *
 * Return event connected to post.
 *
 * @since 1.0.0
 *
 * @param  int $post_id
 * @return string event
 */
 if ( ! function_exists( 'meeting_get_event' ) ) {

     function meeting_get_event( $post_id ) {

         $query_args = array(
             'connected_type' => 'meeting_to_event',
             'connected_items' => intval( $post_id ),
             'nopaging' => true
         );

         $events = get_posts( $query_args );

         if ( empty( $events ) ) {
           return;
         }

         $post = $events[0];

         $post_type_obj = get_post_type_object( get_post_type( $post->ID ) );
         $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';

         $content = sprintf( '<li class="event-link"><a href="%s" rel="bookmark" Title="View %s"><span class="link-text">%s</span></a></li>',
           get_post_permalink( $post->ID ),
           ( $post_type_name ) ? $post_type_name : $post->post_title,
           ( $post_type_name ) ? $post_type_name : $post->post_title
         );

         // Filter added to allow content be overriden
         return apply_filters( 'meeting_get_event_content', $content, $post_id );
     }

}



/**
 * Add markdown support for custom post types.
 */
if ( ! function_exists( 'meeting_markdown_support' )  ) {

    function meeting_markdown_support() {
        add_post_type_support( 'meeting', 'wpcom-markdown' );
        add_post_type_support( 'proposal', 'wpcom-markdown' );
        add_post_type_support( 'summary', 'wpcom-markdown' );
        add_post_type_support( 'agenda', 'wpcom-markdown' );
    }

    add_action( 'init', 'meeting_markdown_support' );

}


/**
 * TEMPLATE LOCATION.
 *
 * Templates can be overwritten by putting a template file of the same name in
 * plugins/wordpress-meeting/ folder of your active theme.
 */
if ( ! function_exists( 'include_meeting_templates' ) ) {

    function include_meeting_templates( $template_path ) {

        $post_types = array(
            'meeting',
            'proposal',
            'summary',
            'agenda'
        );

        $post_tax = array(
            'meeting_type',
            'meeting_tag',
            'proposal_status',
            'organization'
        );

        if ( is_post_type_archive( $post_types ) || is_tax( $post_tax ) ) {
            if ( $theme_file = locate_template( array( 'plugins/wordpress-meeting/archive.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = WORDPRESS_MEETINGS_PLUGIN_DIR . 'templates/archive.php';
            }
        }

        // elseif ( is_singular( $post_types ) ) {
        //     // checks if the file exists in the theme first,
        //     // otherwise serve the file from the plugin
        //     if ( $theme_file = locate_template( array ( 'plugins/wordpress-meeting/single.php' ) ) ) {
        //         $template_path = $theme_file;
        //     } else {
        //         $template_path = WORDPRESS_MEETINGS_PLUGIN_DIR . 'templates/single.php';
        //     }
        // }
        return $template_path;
    }
    add_filter( 'template_include', 'include_meeting_templates', 1 );

}


