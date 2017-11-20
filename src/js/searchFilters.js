/**
 * Define what happens when the document is ready.
 *
 * @since 0.1
 *
 * @param {Object} $ The jQuery object.
 */
jQuery( document ).ready( function( $ ) {

	var queryVars;

	/**
	 * Get the URL variables for the current page.
	 *
	 * @since 0.1
	 *
	 * @return {Array} vars The URL vars.
	 */
	function getUrlVars() {

		var url = window.location.href,
			vars = {},
			hash,
			hashes = url.split( '?' )[1];

		if ( hashes ) {
			var hash = hashes.split( '&' );
		} else {
			return false;
		}

		for ( var i = 0; i < hash.length; i++ ) {
			params=hash[i].split( '=' );
			vars[params[0]] = params[1];
		}

		return vars;

	}

	queryVars = getUrlVars();

	if ( queryVars ) {

		$( 'li[data-filter=' + queryVars.meeting_type + ']' ).addClass( 'active' );
		$( 'li[data-filter=' + queryVars.meeting_tag + ']' ).addClass( 'active' );
		$( 'li[data-filter=' + queryVars.proposal_status + ']' ).addClass( 'active' );

	} else {

		$( 'li.all' ).addClass( 'active' );

	}

} );


