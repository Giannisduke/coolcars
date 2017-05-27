<?php
echo get_the_title( $post->post_parent );
echo '<button type="button" class="btn btn-primary btn-sm button-edit" data-href='.get_permalink( $id ).' data-toggle="modal" data-target="#edit'.$id.'">';
  echo '' . __('Edit', 'sage') . '';
  echo '</button>';
  echo '<div class="modal fade" id="edit'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
  echo '<div class="modal-dialog" role="document">';
  echo '<div class="modal-content">';
  echo '<div class="modal-header">';
  echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
  echo '<span aria-hidden="true">&times;</span>';
  echo '</button>';
  echo '<h4 class="modal-title" id="myModalLabel">Edit: '.get_the_title( $id ).'?</h4>';
  echo '</div>';
  echo '<div class="modal-body editform">';
  //echo do_shortcode("[gravityform id=2 title=true description=true ajax=false]");

  echo '</div>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
?>
