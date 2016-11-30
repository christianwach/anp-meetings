<?php
/**
 * ANP Meetings Admin
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.9
 * @package   ANP_Meetings
 */

/**
 * Enqueue Admin Scripts
 *
 * @since 1.0.9
 *
 * @uses admin_enqueue_scripts
 * 
 * @return void
 */
function anp_meetings_admin_enqueue_scripts() {
    $current_screen = get_current_screen();
    if( 'event' === $current_screen->id ) {
        wp_enqueue_script( 'anp-meetings-admin',  ANP_MEETINGS_PLUGIN_URL . 'js/admin.js', array( 'jquery' ), null, true );
    }
    return;
}
add_action( 'admin_enqueue_scripts', 'anp_meetings_admin_enqueue_scripts' );
