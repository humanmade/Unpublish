<?php
/*
Plugin Name: Unpublish
Version: 1.3
Description: Unpublish your content
Author: Human Made Ltd
Author URI: http://hmn.md/
Plugin URI: http://hmn.md/
Text Domain: unpublish
Domain Path: /languages
*/

class Unpublish {

	public static $supports_key = 'unpublish';
	public static $deprecated_cron_key = 'unpublish_cron';
	public static $cron_key = 'unpublish_post_cron';
	public static $post_meta_key = 'unpublish_timestamp';

	protected static $instance;

	public static function get_instance() {

		if ( empty( self::$instance ) ) {
			self::$instance = new Unpublish;
			// Standard setup methods
			foreach ( array( 'setup_variables', 'includes', 'setup_actions' ) as $method ) {
				if ( method_exists( self::$instance, $method ) ) {
					self::$instance->$method();
				}
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
		$this->cron_frequency = 'twicedaily';
	}

	/**
	 * Load any / all customizations to the admin
	 */
	public function action_load_customizations() {

		$post_type = get_current_screen()->post_type;
		if ( post_type_supports( $post_type, self::$supports_key ) ) {
			add_action( 'admin_enqueue_scripts', array( self::$instance, 'enqueue_scripts_styles' ) );
			add_action( 'save_post_' . $post_type, array( self::$instance, 'action_save_unpublish_timestamp' ) );
		}

	}

	/**
	 *  Enqueue scripts & styles
	 */
	public function enqueue_scripts_styles() {
		wp_enqueue_style( 'unpublish', plugins_url( 'css/unpublish.css', __FILE__ ), array(), '0.1-alpha' );
		wp_enqueue_script( 'unpublish', plugins_url( 'js/unpublish.js', __FILE__ ), array( 'jquery' ), '0.1-alpha', true );
		wp_localize_script( 'unpublish', 'unpublish', array(
			/* translators: 1: month, 2: day, 3: year, 4: hour, 5: minute */
			'dateFormat' => __( '%1$s %2$s, %3$s @ %4$s:%5$s', 'unpublish' ),
		) );
	}

	/**
	 * Save the unpublish time for a given post
	 */
	public function action_save_unpublish_timestamp( $post_id ) {
		if ( ! isset( $_POST['unpublish-nonce'] ) || ! wp_verify_nonce( $_POST['unpublish-nonce'], 'unpublish' ) ) {
			return;
		}

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

		if ( ! $valid_date ) {
			return;
		}

		$timestamp = strtotime( get_gmt_from_date( $unpublish_date ) );

		update_post_meta( $post_id, self::$post_meta_key, $timestamp );
	}
}
