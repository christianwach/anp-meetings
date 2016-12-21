/**
 * Admin Scripts
 */
jQuery(document).ready(function($) {

  $eventCategoryEl = $( '#taxonomy-event-category #in-event-category-22' );
  metaBoxes = [
    $( '#organizationdiv' ),
    $( '#meeting_typediv' ),
    $( '#p2p-from-event_to_agenda' ),
    $( '#p2p-from-event_to_summary' ),
    $( '#p2p-from-event_to_proposal' )
  ];

  if( false == $( $eventCategoryEl ).prop( 'checked' ) ) {
    $( metaBoxes ).each( function(item) {
       $(this).hide();
    });
  }

  $( $eventCategoryEl ).change( function( event ) {
    if( this.checked ) {
      $( metaBoxes ).each( function(item) {
         $(this).show();
      });
    } else {
      $( metaBoxes ).each( function(item) {
         $(this).hide();
      });
    }
  });

});
