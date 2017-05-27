<?php while (have_posts()) : the_post(); ?>
  <div class="container">
    <div class="row">
      <div class="col-md-9">
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
      <?php get_template_part('templates/unit-internship_info'); ?>

    </header>
    <article <?php post_class(); ?>>
    <div class="entry-content">
      <div class="hide">
      <div class="entry-form">
<?php gravity_form( 'Edit Internship', false, false, false, '', false ); ?>
</div>
</div>

<h5 class="m-0-top">Details</h5>
      <?php the_content(); ?>

    </div>

  </article>
</div> <!-- col-md-9 -->
<div class="col-md-3">
<?php get_template_part('templates/unit-internship_side'); ?>
</div> <!-- row -->
</div> <!-- row -->
</div> <!-- container -->
<?php endwhile; ?>
