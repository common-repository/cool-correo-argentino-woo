<?php
/**
 * Update Shipping after Branch Selection
 *
 * @package  MANCA\CoolCA\CheckOut
 */

namespace MANCA\CoolCA\CheckOut;

use MANCA\CoolCA\Helper\Helper;
use MANCA\CoolCA\ShippingMethod\WC_CoolCA;

defined( 'ABSPATH' ) || exit();

/**
 * Checkout Class to mantain new custom fields for checkout.
 */
class ActionUpdateShippingBranch {

	/**
	 * Handle Ajax request
	 *
	 * @return void
	 */
	public static function ajax_callback_wp() {
		if ( ! isset( $_POST['coolca_nonce'] ) || ! isset( $_POST['coolca_branch'] ) ) {
			wp_send_json_error();
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['coolca_nonce'] ) ), 'coolca-branch-selection' ) ) {
			wp_send_json_error();
		}

		// Save selected point to session.
		WC()->session->set( 'coolca_branch_selected', isset( $_POST['coolca_branch'] ) ? sanitize_text_field( wp_unslash( $_POST['coolca_branch'] ) ) : null );
		WC()->session->set( 'coolca_branch_name_selected', isset( $_POST['coolca_branch_name'] ) ? sanitize_text_field( wp_unslash( $_POST['coolca_branch_name'] ) ) : null );
		WC()->session->set( 'coolca_branch_address_selected', isset( $_POST['coolca_branch_address'] ) ? sanitize_text_field( wp_unslash( $_POST['coolca_branch_address'] ) ) : null );

		// Force to Recalculate Shippings.
		$packages = WC()->cart->get_shipping_packages();
		foreach ( $packages as $package_key => $package ) {
			WC()->session->set( 'shipping_for_package_' . $package_key, false );
		}

		wp_send_json_success();
	}
}
