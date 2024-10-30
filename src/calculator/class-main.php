<?php
/**
 * Calculator Class
 *
 * Version 1.1.2 - Add Product Calculator
 *
 * @package  MANCA\CoolCA\Calculator
 */

namespace MANCA\CoolCA\Calculator;

defined( 'ABSPATH' ) || exit;

use MANCA\CoolCA\Helper\Helper;
use MANCA\CoolCA\ShippingMethod\WC_CoolCA;
use WC_Shipping;

/**
 * Main Plugin Process Class
 */
class Main {

	/**
	 * Show Calculator
	 *
	 * @param string $product_id Woo Product Id.
	 *
	 * @return void
	 */
	public static function show_calculator( $product_id = '' ) {
		$product_id = empty( $product_id ) ? get_the_ID() : $product_id;
		$product    = wc_get_product( $product_id );
		if ( 'yes' === Helper::get_option( 'product-shipping-calculator' ) && ! $product->is_virtual() ) {
			$data                    = array();
			$data['rates']           = self::run_calculator_for_product( $product_id );
			$data['product_id']      = $product_id;
			$data['current_address'] = self::get_current_shipping_address();
			Helper::get_template_part( 'product', 'shipping-cost', $data );
			wp_enqueue_style( 'coolca-product-shipping-cost' );
			wp_enqueue_script( 'coolca-product-shipping-cost' );
		}
	}

	/**
	 * Get Current Address
	 *
	 * @return string
	 */
	public static function get_current_shipping_address() {
		$country  = WC()->customer->get_shipping_country();
		$state    = WC()->customer->get_shipping_state();
		$postcode = WC()->customer->get_shipping_postcode();
		return 'Argentina, ' . WC()->countries->get_states( $country )[ $state ] . ', ' . $postcode;
	}
	/**
	 * Show Calculator
	 *
	 * @param string $product_id Woo Product Id.
	 * @return array
	 */
	public static function run_calculator_for_product( $product_id ) {
		self::add_product_to_cart( $product_id );
		$array = self::exec_cart_shipping_calc( self::create_calculation_package() );
		self::remove_product_from_cart( $product_id );
		return $array;
	}

	/**
	 * Executes Cart Shipping Calculation
	 *
	 * @param array $packages Package.
	 *
	 * @return array
	 */
	public static function exec_cart_shipping_calc( $packages = array() ) {
		// Force to recalc.
		$packages = WC()->cart->get_shipping_packages();
		foreach ( $packages as $package_key => $package ) {
			WC()->session->set( 'shipping_for_package_' . $package_key, false );
		}
		$ret = array();
		WC()->cart->calculate_shipping( $packages );
		WC()->cart->calculate_totals();
		// Obtener los métodos de envío disponibles después de calcular los costos.
		$shipping_methods = WC()->shipping->get_shipping_methods();
		// Recorrer los métodos de envío y mostrar el costo.
		foreach ( $shipping_methods as $shipping_method ) {
			foreach ( $shipping_method->rates as $key => $rate ) {
				if ( 'coolca' === $rate->get_method_id() ) {
					$ret[] = array(
						'id'   => $rate->get_method_id(),
						'name' => $shipping_method->get_title(),
						'cost' => wc_price( $rate->get_cost() ),
					);
				}
			}
		}
		return $ret;
	}

	/**
	 * Add product to cart
	 *
	 * @param string $product_id Woo Product Id.
	 * @param int    $qty Quantity.
	 *
	 * @return void
	 */
	public static function add_product_to_cart( $product_id, $qty = 1 ) {
		WC()->cart->add_to_cart( $product_id, $qty );
	}

	/**
	 * Generates Package
	 *
	 * @return array
	 */
	public static function create_calculation_package() {
		$new_package = array(
			'contents'    => WC()->cart->get_cart(),
			'destination' => array(
				'country'  => 'ARG', // Cambia al país correspondiente.
				'state'    => WC()->customer->get_shipping_state(), // Cambia al estado correspondiente.
				'postcode' => WC()->customer->get_shipping_postcode(), // Cambia al código postal correspondiente.
			),
		);

		// Agregar el nuevo paquete de envío al array de paquetes.
		$packages[] = $new_package;
		return $packages;
	}
	/**
	 * Remove product to cart
	 *
	 * @param string $product_id Woo Product Id.
	 * @param int    $qty Quantity.
	 *
	 * @return void
	 */
	public static function remove_product_from_cart( $product_id, $qty = 1 ) {
		$product_cart_id = WC()->cart->generate_cart_id( $product_id );
		$cart_item_key   = WC()->cart->find_product_in_cart( $product_cart_id );
		if ( $cart_item_key ) {
			// Obtener la información del producto en el carrito.
			$cart_item = WC()->cart->get_cart_item( $cart_item_key );
			// Obtener la cantidad actual del producto en el carrito.
			$current_quantity = $cart_item['quantity'];
			// Verificar si la cantidad es mayor a 1 para disminuir en una unidad.
			if ( $current_quantity > 1 ) {
				// Disminuir en una unidad la cantidad del producto.
				$new_quantity = $current_quantity - $qty;
				// Eliminar el producto del carrito.
				WC()->cart->remove_cart_item( $cart_item_key );
				// Agregar nuevamente el producto con la cantidad reducida en una unidad.
				$cart_item['quantity'] = $new_quantity;
				WC()->cart->add_to_cart( $product_id, $new_quantity );
			} else {
				// Si la cantidad es 1, eliminar completamente el producto del carrito.
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		}
	}

	/**
	 * Ajax Update Product Shipping Calculator
	 */
	public static function ajax_callback_wp() {
		if ( ! isset( $_REQUEST['coolca_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['coolca_nonce'] ) ), 'cool-ca' ) ) {
			wp_send_json_error();
		}

		// Save selected point to session.
		WC()->customer->set_shipping_state( isset( $_REQUEST['calc_shipping_state'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['calc_shipping_state'] ) ) : null );
		WC()->customer->set_shipping_postcode( isset( $_REQUEST['calc_shipping_postcode'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['calc_shipping_postcode'] ) ) : null );
		$data = array();
		if ( isset( $_REQUEST['product_id'] ) && ! empty( $_REQUEST['product_id'] ) ) {
			$data['rates'] = self::run_calculator_for_product( sanitize_text_field( wp_unslash( $_REQUEST['product_id'] ) ) );
		}
		$data['current_address'] = self::get_current_shipping_address();
		wp_send_json_success( $data );
	}
}
