<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane fade" id="profile">
    <?php
    			/**
    			 * woocommerce_single_product_summary hook.
    			 *

    			 * @hooked woocommerce_template_single_add_to_cart - 10

    			 */

          // add_action( 'woocommerce_single_product_tabs', 'woocommerce_template_single_add_to_cart' , 10, 0 );
    			do_action( 'woocommerce_single_product_tabs' );
        //  do_action( 'woocommerce_placeholder' );
    		?>
      </div>
  <div role="tabpanel" class="tab-pane in active" id="buzz">

<?php echo do_shortcode("[woocommerce_checkout]"); ?>
  </div>
  <div role="tabpanel" class="tab-pane fade" id="references"><?php echo do_shortcode("[woocommerce_cart]"); ?></div>
</div>
