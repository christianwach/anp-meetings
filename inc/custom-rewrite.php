<?php
/**
 * ANP Meetings Rewrite Rules
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.9
 * @package   ANP_Meetings
 */

/**
 * Set up Custom Rewrite Rules
 * Creates rewrite rules for each meetings post type and custom taxonomy term
 *
 * @since 1.0.9
 *
 * @param  $wp_rewrite
 * @return void
 */
function meetings_custom_rewrite_rules( $wp_rewrite ) {

    $rules = array();

    $post_types = get_post_types( array(
        'public'            => true,
        '_builtin'          => false,
        'capability_type'   => 'meeting'
    ), 'objects' );

    $taxonomies = get_taxonomies( array(
        'public'            => true,
        '_builtin'          => false
    ), 'objects' );

    foreach ( $post_types as $post_type ) {
        $post_type_name = $post_type->name;
        $post_type_slug = $post_type->rewrite['slug'];

        foreach ( $taxonomies as $taxonomy ) {

            if( !is_array( $taxonomy->object_type ) || empty( $taxonomy->object_type ) ) {
                return;
            }

            if( ( count( $taxonomy->object_type ) > 1 && in_array( $post_type_name, $taxonomy->object_type ) ) || ( $taxonomy->object_type[0] == $post_type_name  ) ) {

                $terms = get_categories( array(
                    'type'          => $post_type_name,
                    'taxonomy'      => $taxonomy->name,
                    'hide_empty'    => 0
                ) );

                foreach ( $terms as $term ) {
                    $rules[$post_type_slug . '/' . $term->slug . '/?$'] = 'index.php?post_type=' . $post_type_name . '&' . $term->taxonomy . '=' . $term->slug;
                    $rules[$post_type_slug . '/' . $term->slug . '/page/?([0-9]{1,})/?$'] = 'index.php?post_type=' . $post_type_name . '&' . $term->taxonomy . '=' . $term->slug . '&paged=' . $wp_rewrite->preg_index( 1 );
                }
            }
        }
    }

    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_action( 'generate_rewrite_rules', 'meetings_custom_rewrite_rules' );

/**
 * Flush Rewrite Rules on Taxonomy Term Change
 *
 * @since 1.0.9
 *
 * @uses edited_$taxonomy hook
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/edited_$taxonomy
 */
function anp_meetings_flush_rewrite_on_tax_change() {
    flush_rewrite_rules();
}
add_action( 'edited_meeting_type', 'anp_meetings_flush_rewrite_on_tax_change', 10, 2 );
add_action( 'edited_meeting_tag', 'anp_meetings_flush_rewrite_on_tax_change', 10, 2 );
add_action( 'edited_organization', 'anp_meetings_flush_rewrite_on_tax_change', 10, 2 );
add_action( 'edited_proposal_status', 'anp_meetings_flush_rewrite_on_tax_change', 10, 2 );
