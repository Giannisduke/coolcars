
<?php
if ( is_user_logged_in() ) {

  echo ''.get_template_part('templates/unit-head_loged_yes').'';

} else {
  echo ''.get_template_part('templates/unit-head_loged_no').'';


}
?>
