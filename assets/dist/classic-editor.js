/* global unpublish */

(function( $ ) {
	var $editLink     = $( '.edit-unpublish-timestamp' );
	var $fieldset     = $( '#unpublish-timestampdiv' );
	var $stamp        = $( '#unpublish-timestamp strong' );
	var originalStamp = $stamp.text();

	var hideFieldset = function() {
		$fieldset.slideUp( 'fast' );
	}

	var showFieldset = function() {
		$fieldset.slideDown( 'fast' );
	}

	var validate = function() {
		var attemptedDate, originalDate,
			now = new Date(),
			aa  = $('#unpublish-aa').val(),
			mm  = $('#unpublish-mm').val(),
			jj  = $('#unpublish-jj').val(),
			hh  = $('#unpublish-hh').val(),
			mn  = $('#unpublish-mn').val();

		// The unpublish date has just been cleared.
		if ( aa === '' && mm === '' && jj === '' && hh === '' && mn === '' ) {
			return true;
		}

		attemptedDate = new Date( aa, mm - 1, jj, hh, mn );
		originalDate  = new Date(
			$( '.unpublish-aa-orig' ).val(),
			$( '.unpublish-mm-orig' ).val() -1,
			$( '.unpublish-jj-orig' ).val(),
			$( '.unpublish-hh-orig' ).val(),
			$( '.unpublish-mn-orig' ).val()
		);

		// No change made.
		if ( attemptedDate.getTime() === originalDate.getTime() ) {
			$stamp.html( originalStamp );

			return true;
		}

		if ( attemptedDate.getFullYear() != aa
			|| (1 + attemptedDate.getMonth()) != mm
			|| attemptedDate.getDate() != jj
			|| attemptedDate.getMinutes() != mn
			|| attemptedDate <= now
		) {
			return false;
		}

		$stamp.html(
			unpublish.dateFormat
				.replace( '%1$s', $( 'option[value="' + mm + '"]', '#unpublish-mm' ).attr( 'data-text' ) )
				.replace( '%2$s', parseInt( jj, 10 ) )
				.replace( '%3$s', aa )
				.replace( '%4$s', ( '00' + hh ).slice( -2 ) )
				.replace( '%5$s', ( '00' + mn ).slice( -2 ) )
		);

		return true;
	}

	$editLink.on( 'click', function( e ) {
		e.preventDefault();
		$editLink.hide();
		showFieldset();
	});

	$( '.cancel-unpublish-timestamp' ).on( 'click', function( e ) {
		e.preventDefault();
		$editLink.show();
		hideFieldset();

		$stamp.html( originalStamp );

		$fieldset.find( ':input' ).each( function( i, input ) {
			var $input      = $( input );
			var originalVal = $( '.' + $input.attr( 'name' ) + '-orig' ).val();

			$input.val( originalVal );
		});
	});

	$( '.clear-unpublish-timestamp' ).on( 'click', function( e ) {
		e.preventDefault();
		$fieldset.find( ':input' ).val( '' );
		$stamp.html( '&mdash;' );
	});

	$( '.save-unpublish-timestamp' ).on( 'click', function( e ) {
		e.preventDefault();

		var isValid = validate();

		if ( isValid ) {
			$fieldset.removeClass( 'form-invalid' );
			$editLink.show();
			hideFieldset();
		} else {
			$fieldset.addClass( 'form-invalid' );
		}
	});
})( jQuery );
