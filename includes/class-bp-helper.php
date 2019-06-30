<?php
/********************************************************************
 * Copyright (C) 2019 Darko Gjorgjijoski (https://darkog.com)
 *
 * This file is part of WP Batch Processing
 *
 * WP Batch Processing is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * WP Batch Processing is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Batch Processing. If not, see <https://www.gnu.org/licenses/>.
 **********************************************************************/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access is not allowed.' );
}

/**
 * Class WP_BP_Helper
 */
class WP_BP_Helper {

	/**
	 * Renders a view
	 *
	 * @param $view
	 * @param array $data
	 */
	public static function render( $view, $data = array() ) {
		$path = WP_BP_PATH . 'views' . DIRECTORY_SEPARATOR . $view . '.php';
		if ( file_exists( $path ) ) {
			if ( ! empty( $data ) ) {
				extract( $data );
			}
			include( $path );
		} else {
			echo 'View ' . $view . ' not found';
		}
	}
}