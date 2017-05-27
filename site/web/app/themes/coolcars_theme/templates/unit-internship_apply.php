<div class="col-xs-12 menu-box apply-form">
<?php
if( is_user_logged_in() ) {
  gravity_form( 'New Application', false, false, false, '', false );
}
else {
  echo '<button type="button" class="btn btn-primary btn-lg" data-href='.get_permalink( $id ).' data-toggle="modal" data-target="#login_modal">';
  echo '' . __('Apply', 'sage') . '';
  echo '</button>';
 }
?>

</div>
