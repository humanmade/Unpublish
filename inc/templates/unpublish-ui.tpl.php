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

	<input type="hidden" name="unpublish-nonce" value="<?php echo esc_attr( wp_create_nonce( 'unpublish' ) ); ?>" />
	<?php foreach ( $date_units as $unit ) : ?>
		<input type="hidden" class="<?php echo sanitize_html_class( sprintf( 'unpublish-%s-curr', $unit ) ); ?>" value="<?php echo esc_attr( $date_parts[ $unit ] ); ?>" />
	<?php endforeach; ?>
</div>
