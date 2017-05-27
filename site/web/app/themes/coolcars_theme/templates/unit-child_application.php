<?php
  // setup your arguments:
  $args = array(
    // We want to call all posts the custom post type 'help'
    'post_type' => 'application',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'order' => 'ASC',
    // Next, we want to see if the current post is a parent of any help posts
    'post_parent' => $post->ID
  );
  // We then pass these to a wordpress query, and setup our loop
  $loop = new WP_Query( $args );
?>
<?php if ($loop->have_posts()) : while ($loop->have_posts()) : $loop->the_post(); ?>
  <!-- This post has children, so we'll list them out -->
  <ul>
    <li>
  <a href="<?php echo get_post_meta(get_the_ID(), 'usernamelink', TRUE); ?>" rel="bookmark"><?php the_title(); ?></a>
</li>
</ul>
<?php endwhile; ?>
<?php else : ?>
  <!-- This post has no children -->
<?php endif; ?>
