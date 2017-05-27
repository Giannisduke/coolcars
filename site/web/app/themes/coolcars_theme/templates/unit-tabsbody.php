<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane in active" id="profile">
    <?php
    			/**
    			 * woocommerce_single_product_summary hook.
    			 *

    			 * @hooked woocommerce_template_single_add_to_cart - 10

    			 */

          // add_action( 'woocommerce_single_product_tabs', 'woocommerce_template_single_add_to_cart' , 10, 0 );
    			do_action( 'woocommerce_single_product_tabs' );
    		?>
      </div>
  <div role="tabpanel" class="tab-pane fade" id="buzz">
    <div class="mini_checkout">
		mini_checkout
		</div>
  </div>
  <div role="tabpanel" class="tab-pane fade" id="references"><?php echo do_shortcode("[woocommerce_cart]"); ?></div>
</div>
