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
		}

	}

}
