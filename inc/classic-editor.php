<?php

declare( strict_types = 1 );

namespace HM\Unpublish\Classic_Editor;

use HM\Unpublish;
use WP_Post;

const ASSET_HANDLE = 'unpublish-classic-editor';
const NONCE_NAME = 'unpublish-nonce';

function bootstrap() : void {
	add_action( 'load-post.php', __NAMESPACE__ .  '\\attach_hooks' );
	add_action( 'load-post-new.php', __NAMESPACE__ .  '\\attach_hooks' );
}

/**
 * Attach hooks for classic editor screen
 */
function attach_hooks() : void {
	$post_type = get_current_screen()->post_type;

	if ( ! post_type_supports( $post_type, Unpublish\FEATURE_NAME ) ) {
		return;
	}

	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );
	add_action( 'post_submitbox_misc_actions', __NAMESPACE__ . '\\render_field', 1 );
	add_action( 'save_post_' . $post_type, __NAMESPACE__ . '\\save_unpublish_timestamp', 10, 2 );
}

/**
 *  Get month names
 *
 *  @global WP_Locale $wp_locale
 *
 *  @return array Array of month names.
 */
function get_month_names() : array {
	global $wp_locale;

	$month_names = [];

	for ( $i = 1; $i < 13; $i = $i + 1 ) {
		$month_num     = zeroise( $i, 2 );
		$month_text    = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
		$month_names[] = array(
			'value' => $month_num,
			'text'  => $month_text,
			'label' => sprintf( _x( '%1$s-%2$s', 'month number-name', 'unpublish' ), $month_num, $month_text ),
		);
	}

	return $month_names;
}

/**
 * Render the UI for changing the unpublish time of a post
 */
function render_field() : void {
	$date_units = ['aa', 'mm', 'jj', 'hh', 'mn' ];
	$month_names = get_month_names();
	$unpublish_timestamp = Unpublish\get_unpublish_timestamp( get_the_ID() );

	if ( ! empty( $unpublish_timestamp ) ) {
		$local_timestamp = strtotime( get_date_from_gmt( date( 'Y-m-d H:i:s', $unpublish_timestamp ) ) );
		/* translators: Unpublish box date format, see https://www.php.net/manual/en/function.date.php */
		$datetime_format = __( 'M j, Y @ H:i', 'unpublish' );
		$unpublish_date  = date_i18n( $datetime_format, $local_timestamp );
		$date_parts      = array(
			'jj' => date( 'd', $local_timestamp ),
			'mm' => date( 'm', $local_timestamp ),
			'aa' => date( 'Y', $local_timestamp ),
			'hh' => date( 'H', $local_timestamp ),
			'mn' => date( 'i', $local_timestamp ),
		);
	} else {
		$unpublish_date = '&mdash;';
		$date_parts     = array(
			'jj' => '',
			'mm' => '',
			'aa' => '',
			'hh' => '',
			'mn' => '',
		);
	}
?>
<div class="misc-pub-section endtime misc-pub-endtime">
	<span id="unpublish-timestamp" class="dashicons-before dashicons-calendar-alt"><?php printf( __( 'Unpublish on: <strong>%s</strong>', 'unpublish' ), $unpublish_date ); // xss ok ?></span>
	<a href="#edit-unpublish-timestamp" class="edit-unpublish-timestamp hide-if-no-js">
		<span aria-hidden="true"><?php esc_html_e( 'Edit', 'unpublish' ); ?></span>
		<span class="screen-reader-text"><?php esc_html_e( 'Edit unpublish date and time', 'unpublish' ); ?></span>
	</a>

	<fieldset id="unpublish-timestampdiv" class="hide-if-js">
		<legend class="screen-reader-text"><?php esc_html_e( 'Unpublish Date and time', 'unpublish' ); ?></legend>
		<div class="unpublish-timestamp-wrap">
			<label>
				<span class="screen-reader-text"><?php esc_html_e( 'Month', 'unpublish' ); ?></span>
				<select id="unpublish-mm" name="unpublish-mm">
					<option value=""><?php esc_html_e( '&mdash;', 'unpublish' ); ?></option>
					<?php foreach ( $month_names as $month ) : ?>
						<?php printf(
							'<option value="%s" data-text="%s"%s>%s</option>',
							esc_attr( $month['value'] ),
							esc_attr( $month['text'] ),
							selected( $date_parts['mm'], $month['value'], false ),
							esc_html( $month['label'] )
						); ?>
					<?php endforeach; ?>
				</select>
			</label>
			<label>
				<span class="screen-reader-text"><?php esc_html_e( 'Day', 'unpublish' ); ?></span>
				<input id="unpublish-jj" name="unpublish-jj" size="2" maxlength="2" autocomplete="off" type="text" value="<?php echo esc_attr( $date_parts['jj'] ); ?>" />
			</label>,<label>
				<span class="screen-reader-text"><?php esc_html_e( 'Year', 'unpublish' ); ?></span>
				<input id="unpublish-aa" name="unpublish-aa" size="4" maxlength="4" autocomplete="off" type="text" value="<?php echo esc_attr( $date_parts['aa'] ); ?>" />
			</label>
			@
			<label>
				<span class="screen-reader-text"><?php esc_html_e( 'Hour', 'unpublish' ); ?></span>
				<input id="unpublish-hh" name="unpublish-hh" size="2" maxlength="2" autocomplete="off" type="text" value="<?php echo esc_attr( $date_parts['hh'] ); ?>" />
			</label>
			:
			<label>
				<span class="screen-reader-text"><?php esc_html_e( 'Minute', 'unpublish' ); ?></span>
				<input id="unpublish-mn" name="unpublish-mn" size="2" maxlength="2" autocomplete="off" type="text" value="<?php echo esc_attr( $date_parts['mn'] ); ?>" />
			</label>
			<p>
				<a href="#edit-unpublish-timestamp" class="save-unpublish-timestamp hide-if-no-js button"><?php esc_html_e( 'OK', 'unpublish' ); ?></a>
				<a href="#edit-unpublish-timestamp" class="cancel-unpublish-timestamp hide-if-no-js button-cancel"><?php esc_html_e( 'Cancel', 'unpublish' ); ?></a>
				<a href="#edit-unpublish-timestamp" class="clear-unpublish-timestamp hide-if-no-js button-cancel" title="<?php esc_attr_e( 'Clear unpublish date', 'unpublish' ); ?>"><?php esc_html_e( 'Clear', 'unpublish' ); ?></a>
			</p>
		</div>
	</fieldset>

	<?php wp_nonce_field( NONCE_NAME, NONCE_NAME ); ?>
	<?php foreach ( $date_units as $unit ) : ?>
		<input type="hidden" class="<?php echo sanitize_html_class( sprintf( 'unpublish-%s-orig', $unit ) ); ?>" value="<?php echo esc_attr( $date_parts[ $unit ] ); ?>" />
	<?php endforeach; ?>
</div>
<?php
}

/**
 *  Enqueue assets
 */
function enqueue_assets() : void {
	wp_enqueue_style(
		ASSET_HANDLE,
		plugins_url( 'assets/dist/classic-editor.css', __DIR__ ),
		[],
		Unpublish\VERSION
	);
	wp_enqueue_script(
		ASSET_HANDLE,
		plugins_url( 'assets/dist/classic-editor.js', __DIR__ ),
		[ 'jquery' ],
		Unpublish\VERSION,
		true
	);

	wp_localize_script( ASSET_HANDLE, 'unpublish', array(
		/* translators: 1: month, 2: day, 3: year, 4: hour, 5: minute */
		'dateFormat' => __( '%1$s %2$s, %3$s @ %4$s:%5$s', 'unpublish' ),
	) );
}

/**
 * Save the unpublish time for a given post
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post Post object.
 */
function save_unpublish_timestamp( int $post_id, WP_Post $post ) : void {
	if ( ! post_type_supports( $post->post_type, Unpublish\FEATURE_NAME ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	check_admin_referer( NONCE_NAME, NONCE_NAME );

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
		delete_post_meta( $post_id, Unpublish\POST_META_KEY );
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

	update_post_meta( $post_id, Unpublish\POST_META_KEY, $timestamp );
}
