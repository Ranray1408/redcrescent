( function( $ ) {
	// Notice close.
	$( document ).on( 'click', '.notice-onetap .notice-dismiss, .notice-onetap .already-did', function() {
		// Hide notice.
		$( '.notice-onetap' ).slideUp( 300 );

		$.ajax( {
			// eslint-disable-next-line no-undef
			url: adminLocalize.ajaxUrl,
			type: 'POST',
			data: {
				action: 'onetap_action_dismiss_notice',
				// eslint-disable-next-line no-undef
				mynonce: adminLocalize.ajaxNonce,
			},
			success( response ) {
				// eslint-disable-next-line no-console
				console.log( 'Ajax success:', response );
			},
			error( error ) {
				// eslint-disable-next-line no-console
				console.error( 'Ajax error:', error );
			},
		} );
	} );
}( jQuery ) );
