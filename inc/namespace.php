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

/**
 *  Schedule unpublishing post
 *
 *  @param int $post_id   Post ID.
 *  @param int $timestamp Timestamp.
 *
 * @return bool True if event successfully scheduled. False for failure.
 */
function schedule_unpublish( int $post_id, int $timestamp ) : bool {
	unschedule_unpublish( $post_id );

	if ( $timestamp > current_time( 'timestamp', true ) ) {
		return wp_schedule_single_event( $timestamp, CRON_KEY, [ $post_id ] );
	}

	return false;
}
