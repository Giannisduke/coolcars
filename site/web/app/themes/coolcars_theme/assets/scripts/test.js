jQuery(document).ready(function($) {

    var ajax_url = '/wp-admin/admin-ajax.php';
    var data = {
        'action': 'my_action',
        'whatever': 1234
    };
 
    $.post(ajax_url, data, function(response) {
        alert(response);
    });
});
