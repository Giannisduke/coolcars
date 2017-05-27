<nav class="navbar navbar-inverse bg-inverse">

  <ul class="nav justify-content-end">
  <li class="nav-item">
    <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

        $count = WC()->cart->cart_contents_count;
        ?><a class="cart-contents " href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php
        if ( $count > 0 ) {
            ?>
            <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
            <?php
        }
            ?></a>

    <?php } ?>
  </li>

</ul>
</nav>
