<?php
/**
 * WordPress Meetings Options.
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.3.0
 * @package   WordPress_Meetings
 */



 class WordPress_Meetings_Admin {

 	/**
	 * Option key, and option page slug.
	 *
	 * @var string
	 */
 	protected $key = 'anp_meetings_options';

 	/**
	 * Options page metabox id.
	 *
	 * @var string
	 */
 	protected $metabox_id = 'anp_meetings_option_metabox';

 	/**
 	 * Options Page title.
 	 *
 	 * @var string
 	 */
 	protected $title = '';

 	/**
 	 * Options Page hook.
 	 *
 	 * @var string
 	 */
 	protected $options_page = '';

 	/**
 	 * Holds an instance of the object.
 	 *
 	 * @var WordPress_Meetings_Admin
 	 */
 	protected static $instance = null;

 	/**
 	 * Returns the running object.
 	 *
 	 * @return WordPress_Meetings_Admin
 	 */
 	public static function get_instance() {
 		if ( null === self::$instance ) {
 			self::$instance = new self();
 			self::$instance->hooks();
 		}

 		return self::$instance;
 	}



 	/**
 	 * Constructor.
 	 *
 	 * @since 1.3.0
 	 */
 	protected function __construct() {
 		// Set our title
 		$this->title = __( 'Meetings', 'wordpress-meetings' );
 	}



 	/**
 	 * Initiate our hooks.
 	 *
 	 * @since 1.3.0
 	 */
 	public function hooks() {
 		add_action( 'admin_init', array( $this, 'init' ) );
 		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
 		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
 	}



 	/**
 	 * Register our setting to WP.
 	 *
 	 * @since  1.3.0
 	 */
 	public function init() {
 		register_setting( $this->key, $this->key );
 	}



 	/**
 	 * Add menu options page.
 	 *
 	 * @since 1.3.0
 	 */
 	public function add_options_page() {

		$this->options_page = add_submenu_page(
			'options-general.php',
			$this->title,
			$this->title,
			'manage_options', $this->key,
			array( $this, 'admin_page_display' )
		);

 		// Include CMB CSS in the head to avoid FOUC
 		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );

 	}



 	/**
 	 * Admin page markup. Mostly handled by CMB2.
 	 *
 	 * @since  1.3.0
 	 */
 	public function admin_page_display() {
 		?>
 		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
 			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
 			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
 		</div>
 		<?php
 	}



 	/**
 	 * Add the options metabox to the array of metaboxes.
 	 *
 	 * @uses new_cmb2_box()
 	 *
 	 * @since  1.3.0
 	 */
 	function add_options_page_metabox() {

 		// hook in our save notices
 		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

 		$cmb = new_cmb2_box( array(
 			'id'         => $this->metabox_id,
 			'hookup'     => false,
 			'cmb_styles' => false,
 			'show_on'    => array(
 				// These are important, don't remove
 				'key'   => 'options-page',
 				'value' => array( $this->key, )
 			),
 		) );

		$cmb->add_field( array(
			'name'    => __( 'Disable CSS', 'wordpress-meetings' ),
			'id'      => 'anp_meetings_css',
			'type'    => 'checkbox',
			'default' => false,
			'desc'    => __( 'Check this option to prevent any stylesheets from the Meetings plugin being loaded on the front-end', 'wordpress-meetings' ),
		) );

 	}



 	/**
 	 * Register settings notices for display.
 	 *
 	 * @since  1.3.0
 	 *
 	 * @param  int   $object_id Option key
 	 * @param  array $updated   Array of updated fields
 	 */
 	public function settings_notices( $object_id, $updated ) {
 		if ( $object_id !== $this->key || empty( $updated ) ) {
 			return;
 		}

 		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'wordpress-meetings' ), 'updated' );
 		settings_errors( $this->key . '-notices' );
 	}



 	/**
 	 * Public getter method for retrieving protected/private variables.
 	 *
 	 * @since  1.3.0
 	 *
 	 * @param  string  $field Field to retrieve
 	 * @return mixed          Field value or exception is thrown
 	 */
 	public function __get( $field ) {
 		// Allowed fields to retrieve
 		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
 			return $this->{$field};
 		}

 		throw new Exception( 'Invalid property: ' . $field );
 	}



 } // class ends



 /**
  * Helper function to get/return the WordPress_Meetings_Admin object.
  *
  * @since  1.3.0
  *
  * @return WordPress_Meetings_Admin object
  */
 function wordpress_meetings_admin() {
 	return WordPress_Meetings_Admin::get_instance();
 }



 /**
  * Wrapper function around cmb2_get_option.
  *
  * @since  1.3.0
  *
  * @param  string $key     Options array key
  * @param  mixed  $default Optional default value
  * @return mixed           Option value
  */
 function wordpress_meetings_get_option( $key = '', $default = false ) {
 	if ( function_exists( 'cmb2_get_option' ) ) {
 		// Use cmb2_get_option as it passes through some key filters.
 		return cmb2_get_option( wordpress_meetings_admin()->key, $key, $default );
 	}

 	// Fallback to get_option if CMB2 is not loaded yet.
 	$opts = get_option( wordpress_meetings_admin()->key, $default );

 	$val = $default;

 	if ( 'all' == $key ) {
 		$val = $opts;
 	} elseif ( array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
 		$val = $opts[ $key ];
 	}

 	return $val;
 }

 // Get it started
 wordpress_meetings_admin();


