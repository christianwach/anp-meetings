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
         if ( $taxonomy->object_type[0] == $post_type_name ) {
             $terms = get_categories( array( 'type' => $post_type_name, 'taxonomy' => $taxonomy->name, 'hide_empty' => 0 ) );
             foreach ( $terms as $term ) {
                 $rules[$post_type_slug . '/' . $term->slug . '/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug;
                 $rules[$post_type_slug . '/' . $term->slug . '/page/?([0-9]{1,})/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug . '&paged=' . $wp_rewrite->preg_index( 1 );
             }
         }
     }
    }

    $wp_rewrite->rules = $rules + $wp_rewrite->rules;

}
add_action( 'generate_rewrite_rules', 'meetings_custom_rewrite_rules' );
