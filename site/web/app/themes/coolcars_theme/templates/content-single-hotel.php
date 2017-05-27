<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <div class="hide">
      <div class="entry-form">
<?php gravity_form( 5, false, false, false, '', false ); ?>
</div>
</div>
      <?php the_content(); ?>
    </div>
    <?php
    	// setup your arguments:
    	$args = array(
    		// We want to call all posts the custom post type 'help'
    		'post_type' => 'internship',
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
    	<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
    <?php endwhile; ?>
    <?php else : ?>
    	<!-- This post has no children -->
    <?php endif; ?>
    <footer>
      <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
    </footer>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
