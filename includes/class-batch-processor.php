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
 * Class WP_Batch_Processor
 */
class WP_Batch_Processor {

	use WP_BP_Singleton;

	/**
	 * List of batches
	 * @var WP_Batch[]
	 */
	protected $batches;

	/**
	 * Initializes the runner
	 */
	public function init() {
		$this->batches = array();
	}


	/**
	 * Register batch
	 *
	 * @param WP_Batch $batch
	 */
	public function register( $batch ) {
		$this->batches[] = $batch;
	}

	/**
	 * Returns array of registered batches
	 * @return WP_Batch[]
	 */
	public function get_batches() {
		return $this->batches;
	}

	/**
	 * Returns a batch from the registered ones.
	 *
	 * @param $id
	 *
	 * @return null|WP_Batch
	 */
	public function get_batch( $id ) {
		foreach ( $this->batches as $batch ) {
			if ( $batch->id === $id ) {
				return $batch;
			}
		}

		return null;
	}

	/**
	 * Boot the plugin
	 */
	public static function boot() {
		self::load_paths();
		WP_Batch_Processor_Admin::get_instance();
		WP_Batch_Processing_Ajax_Handler::get_instance();
		WP_Batch_Processor::get_instance();
	}

	/**
	 * Determine the library URL.
	 * Note: This won't work if the library is outside of the wp-content directory
	 * and also contains multiple 'wp-content' words in the path.
	 */
	private static function load_paths() {
		if ( ! defined( 'WP_BP_PATH' ) || ! defined( 'WP_BP_URL' ) ) {
			$path        = trailingslashit( dirname( dirname( __FILE__ ) ) );
			$content_dir = basename( untrailingslashit( WP_CONTENT_DIR ) );
			$library_uri = substr( strstr( trailingslashit( $path ), $content_dir ), strlen( $content_dir ) );
			$url         = untrailingslashit( WP_CONTENT_URL ) . $library_uri;
			if ( ! defined( 'WP_BP_PATH' ) ) {
				define( 'WP_BP_PATH', $path );
			}
			if ( ! defined( 'WP_BP_URL' ) ) {
				define( 'WP_BP_URL', trailingslashit( $url ) );
			}
		}
	}

}
