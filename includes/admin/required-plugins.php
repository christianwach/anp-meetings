<?php
/**
 * WordPress Meetings Required Plugins
 *
 * @author	Pea, Glocal
 * @license	 GPL-2.0+
 * @link		http://glocal.coop
 * @since	 1.2.0
 * @package	 WordPress_Meetings
 */



/**
 * Require TGM Plugin Activation Libary.
 *
 * @since 1.2.0
 */
require_once WORDPRESS_MEETINGS_PATH . 'includes/libs/tgm-plugin-activation/class-tgm-plugin-activation.php';




/**
 * Register Required Plugins and Configurations.
 *
 * @since 1.2.0
 *
 * @uses tgmpa()
 * @link http://tgmpluginactivation.com
 */
if ( class_exists( 'TGM_Plugin_Activation' ) ) {

	function wp_meetings_register_required_plugins() {

		$plugins = array(
			array(
				'name'		=> 'Posts 2 Posts',
				'slug'		=> 'posts-to-posts',
				'required'	=> true
			)
		);

		/**
		 * Allow Filtering List of Plugins.
		 *
		 * @since 1.2.0
		 *
		 * @param array $plugins The existing plugin data.
		 * @return array $plugins The modified plugin data.
		 */
		$plugins = apply_filters( 'wordpress_meetings_required_plugins', $plugins );

		$config = array(
			'id' => 'meetings', // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '', // Default absolute path to bundled plugins.
			'menu' => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug' => 'plugins.php', // Parent menu slug.
			'capability' => 'manage_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices' => true, // Show admin notices or not.
			'dismissable' => false, // If false, a user cannot dismiss the nag message.
			'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true, // Automatically activate plugins after installation or not.
			'message' => '', // Message to output right before the plugins table.
			'strings' => array(
				'notice_can_install_required' => _n_noop(
					'The Meetings plugin requires the following plugin: %1$s.',
					'The Meetings plugin requires the following plugins: %1$s.',
					'wordpress-meetings'
				),
			)
		);

		/**
		 * Allow filtering of Configuration data.
		 *
		 * @since 1.2.0
		 *
		 * @param array $config The existing config data.
		 * @return array $config The modified config data.
		 */
		$config = apply_filters( 'wordpress_meetings_required_plugins_configs', $config );

		tgmpa( $plugins, $config );

	}

}

add_action( 'tgmpa_register', 'wp_meetings_register_required_plugins' );


