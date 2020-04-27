<?php

declare( strict_types = 1 );

namespace HM\Unpublish\Post_Meta;

use HM\Unpublish;

/**
 * Post Meta Bootstrapper
 */
function boostrap() : void {
	add_filter( 'is_protected_meta', __NAMESPACE__ . '\\protect_meta_key', 10, 3 );
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
