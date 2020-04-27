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
function bootstrap(): void {
	add_action( CRON_KEY, __NAMESPACE__ . '\\unpublish_post' );
	add_action( 'trashed_post', __NAMESPACE__ . '\\unschedule_unpublish' );
	add_action( 'untrashed_post', __NAMESPACE__ . '\\reschedule_unpublish' );
}

/**
 * Get unpublish timestamp
 *
 * @param int $post_id Post ID.
 *
 * @return int|null Unpublish timestamp if set. NULL otherwise.
 */
function get_unpublish_timestamp( int $post_id ) : ? int {
	$value = get_post_meta( $post_id, POST_META_KEY, true );
	$value = ! empty( $value ) ? absint( $value ) : null;

	return $value;
}

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
	return wp_clear_scheduled_hook( CRON_KEY, array( $post_id ) );
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

/**
 * Reschedule unpublish
 *
 * @param int $post_id Post ID.
 *
 * @return bool True if event successfully scheduled.
 *              False for failure or there's no unpublish timestamp set.
 */
function reschedule_unpublish( int $post_id ) : bool {
	$timestamp = get_unpublish_timestamp( $post_id );

	if ( empty( $timestamp ) ) {
		return false;
	}

	return schedule_unpublish( $post_id, $timestamp );
}

/**
 * Unpublish post
 *
 * @param int $post_id Post ID to unpublish.
 *
 * @return WP_Post|false|null — Post data on success, false or null on failure.
 */
function unpublish_post( int $post_id ) {
	$unpublish_timestamp = get_unpublish_timestamp( $post_id );

	if ( empty( $unpublish_timestamp ) ) {
		return false;
	}

	if ( $unpublish_timestamp > time() ) {
		return schedule_unpublish( $post_id, $unpublish_timestamp );
	}

	return wp_trash_post( $post_id );
}
