<?php
/*
Plugin Name: Unpublish
Version: 0.1-alpha
Description: Unpublish your content
Author: Daniel Bachhuber, Human Made
Author URI: http://hmn.md/
Plugin URI: http://hmn.md/
Text Domain: unpublish
Domain Path: /languages
*/

class Unpublish {

	public static $supports_key = 'unpublish';
	public static $cron_key = 'unpublish_cron';
	public static $post_meta_key = 'unpublish_timestamp';

	protected static $instance;

	public static function get_instance() {

		if ( empty( self::$instance ) ) {
			self::$instance = new Unpublish;
			// Standard setup methods
			foreach( array( 'setup_variables', 'includes', 'setup_actions' ) as $method ) {
				if ( method_exists( self::$instance, $method ) )
					self::$instance->$method();
			}
		}
		return self::$instance;
	}

	private function __construct() {
		/** Prevent the class from being loaded more than once **/
	}

	/**
	 * Set up variables associated with the plugin
	 */
	private function setup_variables() {
		$this->file           = __FILE__;
		$this->basename       = plugin_basename( $this->file );
		$this->plugin_dir     = plugin_dir_path( $this->file );
		$this->plugin_url     = plugin_dir_url( $this->file );

		$this->date_format    = get_option( 'date_format' );
		$this->time_format    = get_option( 'time_format' );

		$this->cron_frequency = 'twicedaily';
	}

	/**
	 * Set up action associated with the plugin
	 */
	private function setup_actions() {

		add_action( 'load-post.php', array( self::$instance, 'action_load_customizations' ) );
		add_action( 'load-post-new.php', array( self::$instance, 'action_load_customizations' ) );

		if ( ! wp_next_scheduled( self::$cron_key ) ) {
			wp_schedule_event( time(), $this->cron_frequency, self::$cron_key );
		}

		add_action( self::$cron_key, array( self::$instance, 'unpublish_content' ) );

	}

	/**
	 * Load any / all customizations to the admin
	 */
	public function action_load_customizations() {

		$post_type = get_current_screen()->post_type;
		if ( post_type_supports( $post_type, self::$supports_key ) ) {
			add_action( 'post_submitbox_misc_actions', array( self::$instance, 'render_unpublish_ui' ) );
			add_action( 'admin_enqueue_scripts', array( self::$instance, 'enqueue_scripts_styles' ) );
			add_action( 'save_post_' . $post_type, array( self::$instance, 'action_save_unpublish_timestamp' ) );
		}

	}

	/**
	 *  Get month names
	 *
	 *  global WP_Locale $wp_locale
	 *
	 *  @return array Array of month names.
	 */
	protected function get_month_names() {
		global $wp_locale;

		$month_names = [];

		for ( $i = 1; $i < 13; $i = $i + 1 ) {
			$month_num     = zeroise( $i, 2 );
			$month_text    = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
			$month_names[] = array(
				'value' => $month_num,
				'text'  => $month_text,
				'label' => sprintf( _x( '%1$s-%2$s', 'month number-name', 'unpublish' ), $month_num, $month_text ),
			);
		}

		return $month_names;
	}

	/**
	 * Render the UI for changing the unpublish time of a post
	 */
	public function render_unpublish_ui() {

		$unpublish_timestamp = get_post_meta( get_the_ID(), self::$post_meta_key, true );
		if ( ! empty( $unpublish_timestamp ) ) {
			$datetime_format = sprintf( __( '%s @ %s', 'unpublish' ), $this->date_format, $this->time_format );
			$unpublish_date  = date_i18n( $datetime_format, $unpublish_timestamp );
			$date_parts      = array(
				'jj' => date( 'd', $unpublish_timestamp ),
				'mm' => date( 'm', $unpublish_timestamp ),
				'aa' => date( 'Y', $unpublish_timestamp ),
				'hh' => date( 'H', $unpublish_timestamp ),
				'mn' => date( 'i', $unpublish_timestamp ),
			);
		} else {
			$unpublish_date = '&mdash;';
			$date_parts     = array(
				'jj' => '',
				'mm' => '',
				'aa' => '',
				'hh' => '',
				'mn' => '',
			);
		}

		$vars = array(
			'unpublish_date' => $unpublish_date,
			'month_names'    => $this->get_month_names(),
			'date_parts'     => $date_parts,
			'date_units'     => array( 'aa', 'mm', 'jj', 'hh', 'mn' ),
		);

		echo $this->get_view( 'unpublish-ui', $vars ); // xss ok
	}

	/**
	 *  Enqueue scripts & styles
	 */
	public function enqueue_scripts_styles() {
		wp_enqueue_style( 'unpublish', plugins_url( 'css/unpublish.css', __FILE__ ), array(), '0.1-alpha' );
		wp_enqueue_script( 'unpublish', plugins_url( 'js/unpublish.js', __FILE__ ), array( 'jquery' ), '0.1-alpha', true );
	}

	/**
	 * Save the unpublish time for a given post
	 */
	public function action_save_unpublish_timestamp( $post_id ) {
		check_admin_referer( 'unpublish', 'unpublish-nonce' );

		if ( ! post_type_supports( get_post_type( $post_id ), self::$supports_key ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$units       = array( 'aa', 'mm', 'jj', 'hh', 'mn' );
		$units_count = count( $units );
		$date_parts  = [];

		foreach ( $units as $unit ) {
			$key = sprintf( 'unpublish-%s', $unit );
			$date_parts[ $unit ] = $_POST[ $key ];
		}

		$date_parts = array_filter( $date_parts );

		// The unpublish date has just been cleared.
		if ( empty( $date_parts ) ) {
			delete_post_meta( $post_id, self::$post_meta_key );
			return;
		}

		// Bail if one of the fields is empty.
		if ( count( $date_parts ) !== $units_count ) {
			return;
		}

		$unpublish_date = vsprintf( '%04d-%02d-%02d %02d:%02d:00', $date_parts );
		$valid_date     = wp_checkdate( $date_parts['mm'], $date_parts['jj'], $date_parts['aa'], $unpublish_date );

		if ( $valid_date ) {
			update_post_meta( $post_id, self::$post_meta_key, strtotime( $unpublish_date ) );
		}
	}

	/**
	 * Unpublish any content that needs unpublishing
	 */
	public function unpublish_content() {
		global $_wp_post_type_features;

		$post_types = array();
		foreach( $_wp_post_type_features as $post_type => $features ) {
			if ( ! empty( $features[self::$supports_key] ) )
				$post_types[]= $post_type;
		}

		$args = array(
			'fields'          => 'ids',
			'post_type'       => $post_types,
			'post_status'     => 'any',
			'posts_per_page'  => 40,
			'meta_query'      => array(
				array(
					'meta_key'    => self::$post_meta_key,
					'meta_value'  => current_time( 'timestamp' ),
					'compare'     => '<',
					'type'        => 'NUMERIC',
					),
				array(
					'meta_key'    => self::$post_meta_key,
					'meta_value'  => current_time( 'timestamp' ),
					'compare'     => 'EXISTS',
					)
				)
			);
		$query = new WP_Query( $args );

		foreach( $query->posts as $post_id ) {
			wp_trash_post( $post_id );
		}

	}

	/**
	 * Get a given view (if it exists)
	 *
	 * @param string     $view      The slug of the view
	 * @return string
	 */
	public function get_view( $view, $vars = array() ) {

		if ( isset( $this->template_dir ) ) {
			$template_dir = $this->template_dir;
		} else {
			$template_dir = $this->plugin_dir . '/inc/templates/';
		}

		$view_file = $template_dir . $view . '.tpl.php';
		if ( ! file_exists( $view_file ) ) {
			return '';
		}

		extract( $vars, EXTR_SKIP );
		ob_start();
		include $view_file;
		return ob_get_clean();
	}
}

/**
 * Load the plugin
 */
function unpublish() {
	return Unpublish::get_instance();
}
add_action( 'plugins_loaded', 'unpublish' );
