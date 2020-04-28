import './classic-editor.scss';

( $ => {
	const $editLink = $( '.edit-unpublish-timestamp' );
	const $fieldset = $( '#unpublish-timestampdiv' );
	const $stamp = $( '#unpublish-timestamp strong' );
	const originalStamp = $stamp.text();

	const hideFieldset = () => $fieldset.slideUp( 'fast' );

	const showFieldset = () => $fieldset.slideDown( 'fast' );

	const validate = () => {
		const now = new Date();
		const aa = $( '#unpublish-aa' ).val();
		const mm = $( '#unpublish-mm' ).val();
		const jj = $( '#unpublish-jj' ).val();
		const hh = $( '#unpublish-hh' ).val();
		const mn = $( '#unpublish-mn' ).val();

		// The unpublish date has just been cleared.
		if ( aa === '' && mm === '' && jj === '' && hh === '' && mn === '' ) {
			return true;
		}

		const attemptedDate = new Date( aa, mm - 1, jj, hh, mn );
		const originalDate = new Date(
			$( '.unpublish-aa-orig' ).val(),
			$( '.unpublish-mm-orig' ).val() - 1,
			$( '.unpublish-jj-orig' ).val(),
			$( '.unpublish-hh-orig' ).val(),
			$( '.unpublish-mn-orig' ).val(),
		);

		// No change made.
		if ( attemptedDate.getTime() === originalDate.getTime() ) {
			$stamp.html( originalStamp );

			return true;
		}

		// TODO.
		/* eslint-disable eqeqeq */
		if (
			attemptedDate.getFullYear() != aa ||
			1 + attemptedDate.getMonth() != mm ||
			attemptedDate.getDate() != jj ||
			attemptedDate.getMinutes() != mn ||
			attemptedDate <= now
		) {
			return false;
		}
		/* eslint-enable */

		$stamp.html(
			unpublish.dateFormat
				.replace( '%1$s', $( 'option[value="' + mm + '"]', '#unpublish-mm' ).attr( 'data-text' ) )
				.replace( '%2$s', parseInt( jj, 10 ) )
				.replace( '%3$s', aa )
				.replace( '%4$s', ( '00' + hh ).slice( -2 ) )
				.replace( '%5$s', ( '00' + mn ).slice( -2 ) ),
		);

		return true;
	};

	$editLink.on( 'click', e => {
		e.preventDefault();
		$editLink.hide();
		showFieldset();
	} );

	$( '.cancel-unpublish-timestamp' ).on( 'click', e => {
		e.preventDefault();
		$editLink.show();
		hideFieldset();

		$stamp.html( originalStamp );

		$fieldset.find( ':input' ).each( ( i, input ) => {
			const $input = $( input );
			const originalVal = $( '.' + $input.attr( 'name' ) + '-orig' ).val();

			$input.val( originalVal );
		} );
	} );

	$( '.clear-unpublish-timestamp' ).on( 'click', e => {
		e.preventDefault();
		$fieldset.find( ':input' ).val( '' );
		$stamp.html( '&mdash;' );
	} );

	$( '.save-unpublish-timestamp' ).on( 'click', e => {
		e.preventDefault();

		const isValid = validate();

		if ( isValid ) {
			$fieldset.removeClass( 'form-invalid' );
			$editLink.show();
			hideFieldset();
		} else {
			$fieldset.addClass( 'form-invalid' );
		}
	} );
} )( jQuery );
