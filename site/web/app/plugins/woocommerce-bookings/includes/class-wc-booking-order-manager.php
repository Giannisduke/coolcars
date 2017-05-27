<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles order status transitions and keeps bookings in sync
 */
class WC_Booking_Order_Manager {

	/**
	 * Constructor sets up actions
	 */
	public function __construct() {
		// Displaying user bookings on the frontend

		//  Add a "My Bookings" area to the My Account page
		add_action( 'init', array( $this, 'add_endpoint' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
		add_filter( 'the_title', array( $this, 'endpoint_title' ) );
		add_filter( 'woocommerce_account_menu_items', array( $this, 'my_account_menu_item' ) );
		add_action( 'woocommerce_account_' . $this->get_endpoint() . '_endpoint', array( $this, 'endpoint_content' ) );
		add_action( 'woocommerce_after_my_account', array( $this, 'legacy_account_page_content' ) );

		// Complete booking orders if virtual
		add_action( 'woocommerce_payment_complete_order_status', array( $this, 'complete_order' ), 20, 2 );

		// When an order is processed or completed, we can mark publish the pending bookings
		add_action( 'woocommerce_order_status_processing', array( $this, 'publish_bookings' ), 10, 1 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'publish_bookings' ), 10, 1 );

		// When an order is cancelled/fully refunded, cancel the bookings
		add_action( 'woocommerce_order_status_cancelled', array( $this, 'cancel_bookings' ), 10, 1 );
		add_action( 'woocommerce_order_status_refunded', array( $this, 'cancel_bookings' ), 10, 1 );
		add_action( 'woocommerce_order_partially_refunded', array( $this, 'cancel_bookings_for_partial_refunds' ), 10, 1 );

		// Remove the booking from the order when it's cancelled
		// Happens only if the booking requires confirmation and the order contains multiple bookings
		// which require confirmation
		add_action( 'woocommerce_booking_pending-confirmation_to_cancelled', array( $this, 'remove_cancelled_booking' ) );

		// Status transitions
		add_action( 'before_delete_post', array( $this, 'delete_post' ) );
		add_action( 'wp_trash_post', array( $this, 'trash_post' ) );
		add_action( 'untrash_post', array( $this, 'untrash_post' ) );

		// Prevent pending being cancelled
		add_filter( 'woocommerce_cancel_unpaid_order', array( $this, 'prevent_cancel' ), 10, 2 );

		// Control the my orders actions.
		add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'my_orders_actions' ), 10, 2 );

		// Sync order user with booking user
		add_action( 'updated_post_meta', array( $this, 'updated_post_meta' ), 10, 4 );
		add_action( 'added_post_meta', array( $this, 'updated_post_meta' ), 10, 4 );
		add_action( 'woocommerce_booking_in-cart_to_unpaid', array( $this, 'attach_new_user' ), 10, 1 );
		add_action( 'woocommerce_booking_in-cart_to_pending-confirmation', array( $this, 'attach_new_user' ), 10, 1 );
	}

	/**
	 * Return the my-account page endpoint.
	 *
	 * @since 1.9.11
	 * @return string
	 */
	public function get_endpoint() {
		return apply_filters( 'woocommerce_bookings_account_endpoint', 'bookings' );
	}

	/**
	 * Register new endpoint to use inside My Account page.
	 *
	 * @since 1.9.11
	 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
	 */
	public function add_endpoint() {
		add_rewrite_endpoint( $this->get_endpoint(), EP_ROOT | EP_PAGES );
	}

	/**
	 * Add new query var.
	 *
	 * @since 1.9.11
	 * @param array $vars
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		$vars[] = $this->get_endpoint();
		return $vars;
	}

	/**
	 * Change endpoint title.
	 *
	 * @since 1.9.11
	 * @param string $title
	 * @return string
	 */
	public function endpoint_title( $title ) {
		global $wp_query;
		$is_endpoint = isset( $wp_query->query_vars[ $this->get_endpoint() ] );

		if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
			$title = __( 'Bookings', 'woocommerce-bookings' );
			remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
		}

		return $title;
	}

	/**
	 * Insert the new endpoint into the My Account menu.
	 *
	 * @since 1.9.11
	 * @param array $items
	 * @return array
	 */
	public function my_account_menu_item( $items ) {
		// Remove logout menu item.
		if ( array_key_exists( 'customer-logout', $items ) ) {
			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );
		}

		// Add bookings menu item.
		$items[ $this->get_endpoint() ] = __( 'Bookings', 'woocommerce-bookings' );

		// Add back the logout item.
		if ( isset( $logout ) ) {
			$items['customer-logout'] = $logout;
		}

		return $items;
	}

	/**
	 * Endpoint HTML content.
	 *
	 * @since 1.9.11
	 */
	public function endpoint_content() {
		$this->my_bookings();
	}

	/**
	 * Display the account page content for WooCommerce versions before 2.6
	 *
	 * @since 1.9.11
	 */
	public function legacy_account_page_content() {
		if ( version_compare( WC()->version, '2.6', '<' ) ) {
			$this->my_bookings();
		}
	}

	/**
	 * Show a users bookings.
	 */
	public function my_bookings() {
		$current_date = date( 'YmdHis' );
		$user_id      = get_current_user_id();

		// Backwards Compatability for < WC 2.6
		$all_bookings = WC_Bookings_Controller::get_bookings_for_user( $user_id );

		$upcoming_bookings = WC_Bookings_Controller::get_bookings_for_user( $user_id, array(
			'orderby'       => 'start_date',
			'order'         => 'ASC',
			'meta_query'    => array(
				'relation' => 'AND',
				array(
					'key'     => '_booking_customer_id',
					'value'   => absint( $user_id ),
					'compare' => 'IN',
				),
				'start_date'  => array(
					'key'     => '_booking_start',
					'value'   => $current_date,
					'compare' => '>=',
				),
			),
		) );

		$past_bookings = WC_Bookings_Controller::get_bookings_for_user( $user_id, array(
			'orderby'       => 'start_date',
			'order'         => 'ASC',
			'meta_query'    => array(
				'relation' => 'AND',
				array(
					'key'     => '_booking_customer_id',
					'value'   => absint( $user_id ),
					'compare' => 'IN',
				),
				'start_date'  => array(
					'key'     => '_booking_start',
					'value'   => $current_date,
					'compare' => '<=',
				),
			),
		) );

		$tables = array();
		if ( ! empty( $upcoming_bookings ) ) {
			$tables['upcoming'] = array(
				'header'   => __( 'Upcoming Bookings', 'woocommerce-bookings' ),
				'bookings' => $upcoming_bookings,
			);
		}
		if ( ! empty( $past_bookings ) ) {
			$tables['past'] = array(
				'header'   => __( 'Past Bookings', 'woocommerce-bookings' ),
				'bookings' => $past_bookings,
			);
		}

		// Use the deprecated template if pre-WC 2.6
		if ( version_compare( WC()->version, '2.6.0', '>=' ) ) {
			wc_get_template( 'myaccount/bookings.php', array( 'tables' => apply_filters( 'woocommerce_bookings_account_tables', $tables ) ), 'woocommerce-bookings/', WC_BOOKINGS_TEMPLATE_PATH );
		} else {
			if ( $all_bookings ) {
				wc_get_template( 'myaccount/my-bookings.php', array( 'bookings' => $all_bookings ), 'woocommerce-bookings/', WC_BOOKINGS_TEMPLATE_PATH );
			}
		}
	}

	/**
	 * Called when an order is paid
	 * @param  int $order_id
	 */
	public function publish_bookings( $order_id ) {
		global $wpdb;

		$order = wc_get_order( $order_id );

		// Don't publish bookings for COD orders.
		if ( $order->has_status( 'processing' ) && 'cod' === $order->payment_method ) {
			return;
		}

		if ( class_exists( 'WC_Deposits' ) ) {
			//is this a final payment?
			$parent_id = wp_get_post_parent_id( $order_id );
			if ( ! empty( $parent_id ) ) {
				$order = wc_get_order( $parent_id );
			}
		}

		$bookings = array();

		foreach ( $order->get_items() as $order_item_id => $item ) {
			if ( 'line_item' == $item['type'] ) {
				$bookings = array_merge( $bookings, $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_booking_order_item_id' AND meta_value = %d", $order_item_id ) ) );
			}
		}

		foreach ( $bookings as $booking_id ) {
			$booking = get_wc_booking( $booking_id );
			$booking->paid();
		}
	}

	/**
	 * Complete virtual booking orders
	 * @param $order_status
	 * @param $order_id
	 *
	 * @return string
	 */
	public function complete_order( $order_status, $order_id ) {
		$order = wc_get_order( $order_id );

		if ( 'processing' == $order_status && ( 'on-hold' == $order->status || 'pending' == $order->status || 'failed' == $order->status ) ) {

			$virtual_booking_order = null;

			if ( count( $order->get_items() ) > 0 ) {

				foreach ( $order->get_items() as $item ) {

					if ( 'line_item' == $item['type'] ) {

						$_product = $order->get_product_from_item( $item );

						if ( ! $_product->is_virtual() || ! $_product->is_type( 'booking' ) ) {
							// once we've found one non-virtual product we know we're done, break out of the loop
							$virtual_booking_order = false;
							break;
						} else {
							$virtual_booking_order = true;
						}
					}
				}
			}

			// virtual order, mark as completed
			if ( $virtual_booking_order ) {
				return 'completed';
			}
		}

		// deposits order status support
		if ( class_exists( 'WC_Deposits' ) && 'partial-payment' === $order_status ) {
			global $wpdb;
			$bookings = array();
			foreach ( $order->get_items() as $order_item_id => $item ) {
				$bookings = array_merge( $bookings, $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_booking_order_item_id' AND meta_value = %d", $order_item_id ) ) );
			}

			foreach ( $bookings as $booking_id ) {
				$booking = new WC_Booking( $booking_id );
				$booking->populated = true;
				$booking->update_status( 'wc-partial-payment' );
			}
		}

		// non-virtual order, return original status
		return $order_status;
	}

	/**
	 * @since 1.9.13 Introduced.
	 *
	 * @param $order_id
	 */
	public function cancel_bookings_for_partial_refunds( $order_id ) {
		global $wpdb;

		$order    = wc_get_order( $order_id );
		$bookings = array();

		// Prevents infinite loop during synchronization
		update_post_meta( $order_id, '_booking_status_sync', true );

		foreach ( $order->get_items() as $order_item_id => $item ) {
			$refunded_qty = $order->get_qty_refunded_for_item( $order_item_id );
			if ( 'line_item' == $item['type'] && 0 !== $refunded_qty ) {
				$bookings = array_merge( $bookings, $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_booking_order_item_id' AND meta_value = %d", $order_item_id ) ) );
			}
		}

		foreach ( $bookings as $booking_id ) {
			if ( get_post_meta( $booking_id, '_booking_status_sync', true ) ) {
				continue;
			}

			$booking = get_wc_booking( $booking_id );
			$booking->update_status( 'cancelled' );
		}

		WC_Cache_Helper::get_transient_version( 'bookings', true );
		delete_post_meta( $order_id, '_booking_status_sync' );
	}
	/**
	 * Cancel bookings with order
	 * @param  int $order_id
	 */
	public function cancel_bookings( $order_id ) {
		global $wpdb;

		$order    = wc_get_order( $order_id );
		$bookings = array();

		// Prevents infinite loop during synchronization
		update_post_meta( $order_id, '_booking_status_sync', true );

		foreach ( $order->get_items() as $order_item_id => $item ) {
			if ( 'line_item' == $item['type'] ) {
				$bookings = array_merge( $bookings, $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_booking_order_item_id' AND meta_value = %d", $order_item_id ) ) );
			}
		}

		foreach ( $bookings as $booking_id ) {
			if ( get_post_meta( $booking_id, '_booking_status_sync', true ) ) {
				continue;
			}

			$booking = get_wc_booking( $booking_id );
			$booking->update_status( 'cancelled' );
		}

		WC_Cache_Helper::get_transient_version( 'bookings', true );
		delete_post_meta( $order_id, '_booking_status_sync' );
	}

	/**
	 * Removes bookings related to the order being deleted.
	 *
	 * @param mixed $order_id ID of post being deleted
	 */
	public function delete_post( $order_id ) {
		if ( ! current_user_can( 'delete_posts' ) ) {
			return;
		}

		if ( $order_id > 0 && 'shop_order' == get_post_type( $order_id ) ) {
			global $wpdb;

			$order    = wc_get_order( $order_id );
			$bookings = array();

			// Prevents infinite loop during synchronization
			update_post_meta( $order_id, '_booking_delete_sync', true );

			foreach ( $order->get_items() as $order_item_id => $item ) {
				if ( 'line_item' == $item['type'] ) {
					$bookings = array_merge( $bookings, $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_booking_order_item_id' AND meta_value = %d", $order_item_id ) ) );
				}
			}

			foreach ( $bookings as $booking_id ) {
				if ( get_post_meta( $booking_id, '_booking_delete_sync', true ) ) {
					continue;
				}

				wp_delete_post( $booking_id, true );
			}

			delete_post_meta( $order_id, '_booking_delete_sync' );
		}
	}

	/**
	 * Trash bookings with orders
	 *
	 * @param mixed $order_id
	 */
	public function trash_post( $order_id ) {
		if ( $order_id > 0 && 'shop_order' == get_post_type( $order_id ) ) {
			global $wpdb;

			$order    = wc_get_order( $order_id );
			$bookings = array();

			// Prevents infinite loop during synchronization
			update_post_meta( $order_id, '_booking_trash_sync', true );

			foreach ( $order->get_items() as $order_item_id => $item ) {
				if ( 'line_item' == $item['type'] ) {
					$bookings = array_merge( $bookings, $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_booking_order_item_id' AND meta_value = %d", $order_item_id ) ) );
				}
			}

			foreach ( $bookings as $booking_id ) {
				if ( get_post_meta( $booking_id, '_booking_trash_sync', true ) ) {
					continue;
				}

				wp_trash_post( $booking_id );
			}

			delete_post_meta( $order_id, '_booking_trash_sync' );
		}
	}

	/**
	 * Untrash bookings with orders
	 *
	 * @param mixed $order_id
	 */
	public function untrash_post( $order_id ) {
		if ( $order_id > 0 && 'shop_order' == get_post_type( $order_id ) ) {
			global $wpdb;

			$order    = wc_get_order( $order_id );
			$bookings = array();

			// Prevents infinite loop during synchronization
			update_post_meta( $order_id, '_booking_untrash_sync', true );

			foreach ( $order->get_items() as $order_item_id => $item ) {
				if ( 'line_item' == $item['type'] ) {
					$bookings = array_merge( $bookings, $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_booking_order_item_id' AND meta_value = %d", $order_item_id ) ) );
				}
			}

			foreach ( $bookings as $booking_id ) {
				if ( get_post_meta( $booking_id, '_booking_untrash_sync', true ) ) {
					continue;
				}

				wp_untrash_post( $booking_id );
			}

			delete_post_meta( $order_id, '_booking_untrash_sync' );
		}
	}

	/**
	 * Stops WC cancelling unpaid bookings orders
	 * @param  bool $return
	 * @param  object $order
	 * @return bool
	 */
	public function prevent_cancel( $return, $order ) {
		if ( '1' === get_post_meta( $order->id, '_booking_order', true ) ) {
			return false;
		}

		return $return;
	}

	/**
	 * My Orders custom actions.
	 * Remove the pay button when the booking requires confirmation.
	 *
	 * @param  array $actions
	 * @param  WC_Order $order
	 * @return array
	 */
	public function my_orders_actions( $actions, $order ) {
		global $wpdb;

		if ( $order->has_status( 'pending' ) && 'wc-booking-gateway' === $order->payment_method ) {
			$status = array();
			foreach ( $order->get_items() as $order_item_id => $item ) {
				if ( 'line_item' == $item['type'] ) {
					$_status = $wpdb->get_col( $wpdb->prepare( "
						SELECT posts.post_status
						FROM {$wpdb->postmeta} AS postmeta
							LEFT JOIN {$wpdb->posts} AS posts ON (postmeta.post_id = posts.ID)
						WHERE postmeta.meta_key = '_booking_order_item_id'
						AND postmeta.meta_value = %d
					", $order_item_id ) );

					$status = array_merge( $status, $_status );
				}
			}

			if ( in_array( 'pending-confirmation', $status ) && isset( $actions['pay'] ) ) {
				unset( $actions['pay'] );
			}
		}

		return $actions;
	}

	/**
	 * Sync customer between order + booking
	 */
	public function updated_post_meta( $meta_id, $object_id, $meta_key, $_meta_value ) {
		if ( '_customer_user' === $meta_key && 'shop_order' === get_post_type( $object_id ) ) {
			global $wpdb;

			$order    = wc_get_order( $object_id );
			$bookings = array();

			foreach ( $order->get_items() as $order_item_id => $item ) {
				if ( 'line_item' == $item['type'] ) {
					$bookings = array_merge( $bookings, $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_booking_order_item_id' AND meta_value = %d", $order_item_id ) ) );
				}
			}

			foreach ( $bookings as $booking_id ) {
				update_post_meta( $booking_id, '_booking_customer_id', $_meta_value );
			}
		}
	}

	/**
	 * Attaches a newly created user (during checkout) to a booking
	 */
	function attach_new_user( $booking_id ) {
		if ( 0 === (int) get_post_meta( $booking_id, '_booking_customer_id', true ) && get_current_user_id() > 0 ) {
			update_post_meta( $booking_id, '_booking_customer_id', get_current_user_id() );
		}
	}

	/**
	 * Removes the booking from an order
	 * when the order includes only bookings which require confirmation
	 *
	 * @param int $booking_id
	 */
	public function remove_cancelled_booking( $booking_id ) {
		$booking  = get_wc_booking( $booking_id );
		$order    = $booking->get_order();

		if ( ! empty( $order ) && is_array( $order->get_items() ) ) {
			foreach ( $order->get_items() as $order_item_id => $item ) {
				if ( $item[ __( 'Booking ID', 'woocommerce-bookings' ) ] == $booking_id ) {
					wc_delete_order_item( $order_item_id );
					$order->calculate_totals();
					$order->add_order_note( sprintf( __( 'The product %s has been removed from the order because the booking #%d cannot be confirmed.', 'woocommerce-bookings' ), $item['name'], $booking_id ), true );
				}
			}
		}
	}
}

new WC_Booking_Order_Manager();
