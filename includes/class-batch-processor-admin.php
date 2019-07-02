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
 * Class WP_Batch_Processor_Admin
 */
class WP_Batch_Processor_Admin {

	use WP_BP_Singleton;

	const NONCE = 'wp-batch-processing';

	/**
	 * Kick-in the class
	 */
	protected function init() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( $this, 'setup' ), 0 );
	}

	/**
	 * Init hook. Only run when it is on its own pages.
	 */
	public function setup() {
		if ( $this->is_batch_runner_screen() || $this->is_batch_runner_ajax() ) {
			do_action( 'wp_batch_processing_init' );
		}
	}

	/**
	 * Enqueues admin scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		if ( ! $this->is_batch_runner_screen( 'view' ) ) {
			return;
		}

		wp_enqueue_script(
			'wp-batch-processing',
			WP_BP_URL . 'assets/processor.js',
			array( 'jquery' ),
			filemtime( WP_BP_PATH . 'assets/processor.js' ),
			true
		);
		wp_localize_script( 'wp-batch-processing', 'DgBatchRunner', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'wp-batch-processing' ),
			'batch_id' => isset( $_GET['id'] ) ? $_GET['id'] : 0,
			'delay'    => apply_filters( 'wp_batch_processing_delay', 0 ), // Set delay in seconds before processing the next item. Default 0. No delay.
			'text'     => array(
				'processing' => __( 'Processing...', 'wp-batch-processing' ),
				'start'      => __( 'Start', 'wp-batch-processing' ),
			)
		) );
	}

	/**
	 * Add menu items
	 *
	 * @return void
	 */
	public function admin_menu() {

		add_menu_page(
			__( 'Manage Batches', 'wp-batch-processing' ),
			__( 'Batches ', 'wp-batch-processing' ),
			'manage_options', 'dg-batches',
			array( $this, 'plugin_page' ),
			'dashicons-grid-view', null
		);

		add_submenu_page(
			'dg-batches',
			__( 'Batches', 'wp-batch-processing' ),
			__( 'Batches', 'wp-batch-processing' ),
			'manage_options',
			'dg-batches',
			array( $this, 'plugin_page' )
		);
	}

	/**
	 * Handles the plugin page
	 *
	 * @return void
	 */
	public function plugin_page() {
		$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
		$id     = isset( $_GET['id'] ) ? $_GET['id'] : 0;
		switch ( $action ) {
			case 'view':
				$view = 'batch-view';
				break;
			default:
				$view = 'batch-list';
				break;
		}
		WP_BP_Helper::render( $view, array( 'id' => $id ) );
	}

	/**
	 * Returns true if the it is ajax action
	 * @return bool
	 */
	private function is_batch_runner_ajax() {
		return isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'dg_process_next_batch_item' || isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'dg_restart_batch';
	}

	/**
	 * Returns true if the current screen is batch runners one.
	 * @return bool
	 */
	private function is_batch_runner_screen( $action = null ) {
		$is_main_screen = isset( $_GET['page'] ) && $_GET['page'] === 'dg-batches';
		if ( ! is_null( $action ) ) {
			$is_main_screen = $is_main_screen && isset($_GET['action']) && $_GET['action'] === $action;
		}

		return $is_main_screen;
	}
}

WP_Batch_Processor_Admin::get_instance();
