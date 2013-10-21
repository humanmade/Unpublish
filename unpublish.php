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

	protected static $instance;

	public static function get_instance() {

		if ( empty( self::$instance ) ) {
			self::$instance = new Unpublish;
			// Standard setup methods
			foreach( array( 'setup_globals', 'includes', 'setup_actions' ) as $method ) {
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
	 * Get a given view (if it exists)
	 * 
	 * @param string     $view      The slug of the view
	 * @return string
	 */
	public function get_view( $view, $vars = array() ) {

		if ( isset( $this->template_dir ) )
			$template_dir = $this->template_dir;
		else
			$template_dir = $this->plugin_dir . '/inc/templates/';

		$view_file = $template_dir . $view . '.tpl.php';
		if ( ! file_exists( $view_file ) )
			return '';

		extract( $vars, EXTR_SKIP );
		ob_start();
		include $view_file;
		return ob_get_clean();
	}

}

/**
 * Load the plugin
 */
function Unpublish() {
	return Unpublish::get_instance();
}
add_action( 'plugins_loaded', 'Unpublish' );