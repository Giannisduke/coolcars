<nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">

  <div id="app" class="container">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div  id="navbarNavDropdown" class="navbar-collapse collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item icon_fb">

            </li>


        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <?php do_action( 'your_theme_header_top' ); ?>
            </li>
            <li class="nav-item icon_cart">
cart
            </li>
        </ul>
        </div>
      </div>
</nav>
<nav class="navbar navbar-toggleable-md navbar-light fixed-top brand">
  <div class="container">
<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="col-lg-3">
<a href="#intro">

<img src='<?php echo esc_url( get_theme_mod( 'themeslug_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>

<h1><?php printf( esc_html__( '%s', 'coolcars' ), get_bloginfo ( 'description' ) ); ?></h1>
</a>
</div>

  <div  id="navbarNavDropdown" class="navbar-collapse collapse">
    <ul class="navbar-nav mr-auto">
       <li class="nav-item active">

       </li>
     </ul>
    <?php if ( has_nav_menu( 'primary' ) ) : ?>
      <?php
        wp_nav_menu( array(
          'theme_location' => 'primary',

        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => ' <ul class="navbar-nav">%3$s</ul>',
        'depth'           => 0,
          'menu_class'     => 'primary-menu',
          'walker' => new IBenic_Walker()
         ) );
      ?>
    </div>
    </div>
    </nav><!-- .main-navigation -->
  <?php endif; ?>
