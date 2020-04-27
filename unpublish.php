<?php

/**
 * Plugin Name: Unpublish
 * Version: 2.0.0-alpha1
 * Description: Unpublish your content
 * Author: Human Made Ltd
 * Author URI: http://humanmade.com
 * Plugin URI: http://humanmade.com
 * Text Domain: unpublish
 * Domain Path: /languages
 */

declare( strict_types = 1 );

namespace HM\Unpublish;

require_once __DIR__ . '/inc/classic-editor.php';
require_once __DIR__ . '/inc/namespace.php';
require_once __DIR__ . '/inc/post-meta.php';

bootstrap();
Classic_Editor\bootstrap();
Post_Meta\bootstrap();
