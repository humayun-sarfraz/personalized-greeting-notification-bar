jQuery( document ).ready( function( $ ) {
    $( '.pgnb-dismiss' ).on( 'click', function() {
        $.post( pgnb_data.ajax_url, {
            action: 'pgnb_dismiss',
            nonce:  pgnb_data.nonce
        }, function( res ) {
            if ( res.success ) {
                $( '#pgnb-bar' ).slideUp();
            }
        } );
    } );
} );
