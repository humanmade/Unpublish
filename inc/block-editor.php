<?php

declare( strict_types = 1 );

namespace HM\Unpublish\Block_Editor;

use HM\Unpublish;

const ASSET_HANDLE = 'unpublish-block-editor';

/**
 * Block editor bootstrapper
 */
function bootstrap() : void {
	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_assets' );
}

/**
 * Enqueue block editor assets
 */
function enqueue_assets() : void {
	wp_enqueue_script(
		ASSET_HANDLE,
		plugins_url( 'assets/dist/block-editor.js', __DIR__ ),
		[
			'wp-api-fetch',
			'wp-compose',
			'wp-components',
			'wp-data',
			'wp-dom-ready',
			'wp-element',
			'wp-edit-post',
			'wp-i18n',
			'wp-plugins',
			'wp-url',
		],
		Unpublish\VERSION
	);
}
