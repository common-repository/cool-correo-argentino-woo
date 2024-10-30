<?php
/**
 * Settings Trait
 *
 * @package  MANCA\CoolCA\Helper
 */

namespace MANCA\CoolCA\Helper;

trait CABranchTrait {

	/**
	 * Gets branch dropdown
	 *
	 * @param string $state Provice to get branches from.
	 * @return array
	 */
	public static function get_branches_dropdown( $state = null ) {
		$branches = self::get_branches();

		$ret = array();
		foreach ( $branches as $key => $value ) {
			if ( $state === $value['s'] || empty( $state ) ) {
					$ret[ $key ] = $value['d'] . '(' . $value['a'] . ', ' . $value['c'] . ')';
			}
		}
		return $ret;
	}

	/**
	 * Gets branch array
	 *
	 * @return array
	 */
	public static function get_branches() {
		$branches = get_option( 'wc-coolca-branches', true );
		return $branches;
	}
}
