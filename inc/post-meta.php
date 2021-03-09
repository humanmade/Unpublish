<?php

declare( strict_types = 1 );

namespace HM\Unpublish\Post_Meta;

use HM\Unpublish;

/**
 * Post Meta Bootstrapper
 */
function bootstrap() : void {
	add_action( 'registered_post_type', __NAMESPACE__ . '\\register_meta', 99 );
	add_action( 'added_post_meta', __NAMESPACE__ . '\\update_schedule', 10, 4 );
	add_action( 'updated_post_meta', __NAMESPACE__ . '\\update_schedule', 10, 4 );
	add_action( 'deleted_post_meta', __NAMESPACE__ . '\\remove_schedule', 10, 3 );
	add_filter( 'is_protected_meta', __NAMESPACE__ . '\\protect_meta_key', 10, 3 );
}

/**
 * Register meta
 *
 * @param string $post_type Post type.
 */
function register_meta( string $post_type ) : void {
	if ( ! post_type_supports( $post_type, Unpublish\FEATURE_NAME ) ) {
		return;
	}

	register_post_meta( $post_type, Unpublish\POST_META_KEY, [
		'description'  => __( 'Unpublish time', 'unpublish' ),
		'show_in_rest' => true,
		'single'       => true,
		'type'         => 'integer',
	] );
}

/**
 * Update schedule when post meta is added/updated
 *
 * @param int    $meta_id    ID of updated metadata entry.
 * @param int    $object_id  Object ID.
 * @param string $meta_key   Meta key.
 * @param mixed  $meta_value Meta value.
 *
 * @return void
 */
function update_schedule( int $meta_id, int $object_id, string $meta_key, $meta_value ) : void {
	if ( $meta_key !== Unpublish\POST_META_KEY ) {
		return;
	}

	if ( $meta_value ) {
		Unpublish\schedule_unpublish( $object_id, absint( $meta_value ) );
	} else {
		Unpublish\unschedule_unpublish( $object_id );
	}
}

/**
 * Remove schedule
 *
 * @param int[]  $meta_ids   An array of deleted metadata entry IDs.
 * @param int    $object_id  Object ID.
 * @param string $meta_key   Meta key.
 */
function remove_schedule( array $meta_ids, int $object_id, string $meta_key ) : void {
	if ( $meta_key === Unpublish\POST_META_KEY ) {
		Unpublish\unschedule_unpublish( $object_id );
	}
}

/**
 * Protect meta key so it doesn't show up on Custom Fields meta box
 *
 * @param bool   $is_protected Whether the key is protected. Default false.
 * @param string $meta_key     Meta key.
 * @param string $meta_type    Meta type.
 *
 * @return bool
 */
function protect_meta_key( bool $is_protected, string $meta_key, string $meta_type ) : bool {
	if ( $meta_key === Unpublish\POST_META_KEY && $meta_type === 'post' ) {
		$is_protected = true;
	}

	return $is_protected;
}
