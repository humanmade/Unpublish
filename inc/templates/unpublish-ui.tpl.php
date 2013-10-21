<div class="misc-pub-section"><label><?php esc_html_e( 'Unpublish on:', 'unpublish' ) ;?> </label>
	<span class="unpublish-display-date"><strong><?php echo ! empty( $unpublish_date ) ? esc_html( $unpublish_date ) : __( 'Never', 'unpublish' ); ?></strong></span>
	<a href="#" id="unpublish-edit-date" class="hide-if-no-js"><?php esc_html_e( 'Edit', 'unpublish' ) ;?></a>
	<div class="hide-if-js">
		<input id="<?php echo Unpublish::$supports_key; ?>" name="<?php echo Unpublish::$supports_key; ?>" value="<?php echo esc_attr( $unpublish_date ); ?>" />
		<br />
		<span class="description"><?php echo sprintf( __( 'Enter a date in a format like "%s"', 'unpublish' ), date( $date_format . ' ' . $time_format, current_time( 'timestamp' ) ) ); ?></span>
	</div>
</div>

<script>
	jQuery(document).ready(function($){

		$('#unpublish-edit-date').on('click',function(){
			$(this).hide();
			$('#unpublish-display-date').hide();
			$(this).next().show();
		});

	});
</script>