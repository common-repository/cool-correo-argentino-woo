<?php
/**
 * Cool CA SDK Class
 *
 * @package  MANCA\CoolCA\SDK
 */

namespace MANCA\CoolCA\SDK;

use MANCA\CoolCA\Helper\Helper;
defined( 'ABSPATH' ) || exit;

/**
 * A main class that holds all our settings logic
 */
class BranchesSdk {

	/**
	 * Get branches from public API.
	 *
	 * @return array
	 */
	public static function get_branches() {
		$ret      = array();
		$response = wp_remote_get( 'https://coolca.manca.com.ar/branches.js', array() );
		Helper::log( '******************************' );
		Helper::log( 'Init get_branches----->' );
		Helper::log( wp_remote_retrieve_response_code( $response ) );
		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			foreach ( $data['data'] as $branch ) {
				foreach ( $branch as $key => $value ) {
					$ret[ $key ] = $value;
				}
			}
		}
		Helper::log( $ret );
		return $ret;
	}
}
