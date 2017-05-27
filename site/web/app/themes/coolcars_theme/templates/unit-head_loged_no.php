<?php
echo '<button type="button" class="btn btn-primary btn-sm" data-href='.get_permalink( $id ).' data-toggle="modal" data-target="#login_modal">';
echo '' . __('Register', 'sage') . '';
echo '</button>';
echo '<button type="button" class="btn btn-outline-primary btn-sm" data-href='.get_permalink( $id ).' data-toggle="modal" data-target="#login_modal">';
echo '' . __('Login', 'sage') . '';
echo '</button>';
  echo ''.get_template_part('templates/unit-login_modal').'';
?>
