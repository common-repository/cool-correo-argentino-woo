<?php
/**
 * Cool CA Cron to Update Branches Class
 *
 * @package  MANCA\CoolCA\SDK
 */

namespace MANCA\CoolCA\Process;

use MANCA\CoolCA\Sdk\CoolCASdk;
use MANCA\CoolCA\Helper\Helper;
use MANCA\CoolCA\Process\CronBranches;
defined( 'ABSPATH' ) || exit;

/**
 * Cron Processor's Main Class
 */
class ActionBranchesUpdate {

	/**
	 * Run Action
	 *
	 * @param array $states List of stats to get Agencies.
	 *
	 * @return bool
	 */
	public static function execute( array $states = array() ) {
		if ( ! empty( $states ) ) {
			$sdk           = new CoolCASdk( Helper::get_option( 'api-key' ) );
			$state         = array_shift( $states );
			$branches_data = $sdk->get_agencies( $state );
			$data_string   = Helper::encode_array( $branches_data[ $state ] );
			update_option( 'wc-coolca-branches-' . $state, $data_string, false );
		}
		if ( ! empty( $states ) ) {
			CronBranches::call_action( $states );
		}
		return true;
	}

	/**
	 * Handle Ajax Request
	 *
	 * @return void
	 */
	public static function ajax_callback_wp() {
		Helper::log( 'ajax ' . __CLASS__ . '.' . __FUNCTION__ );
		if ( ! isset( $_POST['coolca_nonce'] ) || ! isset( $_POST['states'] ) ) {
			wp_send_json_error();
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['coolca_nonce'] ) ), 'coolca-branch-cron' ) ) {
			wp_send_json_error();
		}
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$ret = static::execute( array_map( 'sanitize_text_field', array_map( 'wp_unslash', $_POST['states'] ) ) );
		if ( $ret ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}

		wp_send_json_success();
	}
}
