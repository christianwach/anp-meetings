/**
 * Admin Scripts
 */
jQuery(document).ready(function($) {

  console.log( 'LOADED!!' );

  $( '#is_meeting' ).change( function( event ) {
    console.log( this.checked  );
    if( this.checked ) {
      $( '.cmb2-id-organizational-group' ).show();
      $( '.cmb2-id-meeting-type' ).show();
      $( '#p2p-from-event_to_agenda' ).show();
      $( '#p2p-from-event_to_summary' ).show();
      $( '#p2p-from-event_to_proposal' ).show();
    } else {
      $( '.cmb2-id-organizational-group' ).hide();
      $( '.cmb2-id-meeting-type' ).hide();
      $( '#p2p-from-event_to_agenda' ).hide();
      $( '#p2p-from-event_to_summary' ).hide();
      $( '#p2p-from-event_to_proposal' ).hide();
    }
  });

});
