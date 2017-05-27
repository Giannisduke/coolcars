<nav class="navbar navbar-dark navbar-fixed-top"  style="background-color:#363636">
<div class="row no-padding menu-box" >



  <div class="col-md-4 col-xs-12">
     <a class="brand" href="<?= esc_url(home_url('/')); ?>">

<img src='<?php echo esc_url( get_theme_mod( 'themeslug_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>

<h2><?php printf( esc_html__( '%s', 'sage' ), get_bloginfo ( 'description' ) ); ?></h2>
  </a>

  <?php pll_the_languages(array('show_flags'=>1, 'show_names'=>0)); ?>

</div>

<div class="col-md-4 col-xs-12 menu-box collapse"  id="responsiveCollapse">

<?php

  //Static homepage



  //Blog page



  //everything else
  // Use the new walker
  wp_nav_menu([
     'menu'            => 'pages',
     'theme_location'  => 'pages',
     'container'       => 'div',
     'container_id'    => 'exCollapsingNavbar2',
     'container_class' => 'collapse navbar-toggleable-sm',
     'menu_id'         => false,
     'menu_class'      => 'nav navbar-nav',
     'depth'           => 2,
     'fallback_cb'     => 'bs4navwalker::fallback',
     'walker'          => new bs4navwalker()
 ]);

  ?>


  </div>
  <div class="col-md-4 col-xs-12 navbar-text flex-items-xs-right">
<?php get_template_part('templates/unit-head_right') ?>
</div>

</div> <!-- row -->
</nav>
