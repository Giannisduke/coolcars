<div class="row">
  <div class="col-md-4">
<time class="updated" datetime="<?= get_post_time('c', true); ?>"><?= __('updated: ', 'sage'); ?><?= get_the_date(); ?></time>
  </div>
<div class="col-md-8 byline hotel vcard"><?= __('Hotel:', 'sage'); ?> <a href="<?php echo ''.get_permalink( $post->post_parent ).'';?>" rel="author" class="fn">
  <?php echo get_the_title( $post->post_parent );?></a></div>
</div>
