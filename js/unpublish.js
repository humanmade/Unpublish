(function( $ ) {
	var $editLink = $( '.edit-unpublish-timestamp' );
	var $fieldset = $( '#unpublish-timestampdiv' );
	var $stamp    = $( '#unpublish-timestamp strong' );

	var hideFieldset = function() {
		$fieldset.slideUp( 'fast' );
	}

	var showFieldset = function() {
		$fieldset.slideDown( 'fast' );
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

		$fieldset.find( ':input' ).each( function( i, input ) {
			var $input     = $( input );
			var currentVal = $( '.' + $input.attr( 'name' ) + '-curr' ).val();

			$input.val( currentVal );
		});
	});

	$( '.clear-unpublish-timestamp' ).on( 'click', function( e ) {
		e.preventDefault();
		$fieldset.find( ':input' ).val( '' );
		$stamp.html( '&mdash;' );
	});

	$( '.save-unpublish-timestamp' ).on( 'click', function( e ) {
		e.preventDefault();
		$editLink.show();
		hideFieldset();

		// TODO: Save new values.
	});
})( jQuery );
