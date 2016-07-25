(function( $ ) {
	var $editLink = $( '.edit-unpublish-timestamp' );
	var $fieldset = $( '#unpublish-timestampdiv' );

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

		// TODO: Reset fields to current values.
	});

	$( '.clear-unpublish-timestamp' ).on( 'click', function( e ) {
		e.preventDefault();
		$fieldset.find( ':input' ).val( '' );

		// TODO: Update stamp.
	});

	$( '.save-unpublish-timestamp' ).on( 'click', function( e ) {
		e.preventDefault();
		$editLink.show();
		hideFieldset();

		// TODO: Save new values.
	});
})( jQuery );
