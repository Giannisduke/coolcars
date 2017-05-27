<?php
      /**
       * woocommerce_single_product_summary hook.
       *

       * @hooked woocommerce_template_single_add_to_cart - 10

       */

      // add_action( 'woocommerce_single_product_tabs', 'woocommerce_template_single_add_to_cart' , 10, 0 );
      do_action( 'woocommerce_single_product_summary' );
    //  do_action( 'woocommerce_placeholder' );
    ?>
