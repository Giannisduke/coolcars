jQuery( document ).ready( function($) {

    $(document).on( 'click', '.delete-post', function() {
      $(this).closest(".internship").addClass("active");
        var id = $(this).data('id');
        var nonce = $(this).data('nonce');
        var post = $(this).parents('.post:first');
        $.ajax({
            type: 'post',
            url: MyAjax.ajaxurl,
            data: {
                action: 'my_delete_post',
                nonce: nonce,
                id: id
            },
            success: function( result ) {
                if( result === 'success' ) {



                }
            }
        });
        return false;
    });
});
