<?php

declare( strict_types = 1 );

namespace HM\Unpublish;

const CRON_KEY = 'unpublish_post_cron';
const FEATURE_NAME = 'unpublish';
const POST_META_KEY = 'unpublish_timestamp';

/**
 * Bootstrapper
 *
 * @return void
 */
function bootstrap(): void {}

/**
 * Unschedule unpublishing post
 *
 * @param int $post_id Post ID.
 *
 * @return mixed On success an integer indicating number of events unscheduled
 *               (0 indicates no events were registered with the hook and
 *               arguments combination), false if unscheduling one or more events fail.
 */
function unschedule_unpublish( int $post_id ) {
	return wp_clear_scheduled_hook( self::$cron_key, array( $post_id ) );
}
