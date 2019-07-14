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
 * Class WP_Batch_Processing_Ajax_Handler
 */
class WP_Batch_Processing_Ajax_Handler {

	use WP_BP_Singleton;

	/**
	 * Setup the ajax endpoints
	 */
	protected function init() {
		add_action( 'wp_ajax_dg_process_next_batch_item', array( $this, 'process_next_item' ) );
		add_action( 'wp_ajax_dg_restart_batch', array( $this, 'restart_batch' ) );
	}

	/**
	 * This is used to handle the processing of each item
	 * and return the status to inform the user.
	 */
	public function process_next_item() {

		// Check ajax referrer
		if ( ! check_ajax_referer( WP_Batch_Processor_Admin::NONCE, 'nonce', false ) ) {
			wp_send_json_error( array(
				'message' => 'Permission denied.',
			) );
			exit();
		}

		// Validate the batch id.
		$batch_id = isset( $_REQUEST['batch_id'] ) ? $_REQUEST['batch_id'] : false;
		if ( ! $batch_id ) {
			wp_send_json_error( array(
				'message' => 'Invalid batch id',
			) );
			exit();
		}

		// Get the batch object
		$batch = WP_Batch_Processor::get_instance()->get_batch( $batch_id );

		// Process the next item.
		$next_item = $batch->get_next_item();

		// No next item for processing. The batch processing is finished, probably.
		$is_finished = ( false === $next_item );

		if ( $is_finished ) {
			$total_processed = $batch->get_processed_count();
			$total_items     = $batch->get_items_count();
			$percentage      = $batch->get_percentage();
			$batch->finish();
			wp_send_json_success( array(
				'message'         => apply_filters( 'dg_batch_item_error_message', __( 'Processing finished.', 'wp-batch-processing' ) ),
				'is_finished'     => 1,
				'total_processed' => $total_processed,
				'total_items'     => $total_items,
				'percentage'      => $percentage,
			) );
		} else {
			@set_time_limit( 0 );
			$response = $batch->process( $next_item );
			$batch->mark_as_processed( $next_item->id );
			$total_processed = $batch->get_processed_count();
			$total_items     = $batch->get_items_count();
			$percentage      = $batch->get_percentage();
			if ( is_wp_error( $response ) ) {
				$error_message = apply_filters( 'dg_batch_item_error_message', 'Error processing item with id ' . $next_item->id . ': ' . $response->get_error_message(), $next_item );
				wp_send_json_error( array(
					'message'         => $error_message,
					'is_finished'     => 0,
					'total_processed' => $total_processed,
					'total_items'     => $total_items,
					'percentage'      => $percentage,
				) );
			} else {
				$success_message = apply_filters( 'dg_batch_item_success_message', 'Perocessed item with id ' . $next_item->id, $next_item );
				wp_send_json_success( array(
					'message'         => $success_message,
					'is_finished'     => 0,
					'total_processed' => $total_processed,
					'total_items'     => $total_items,
					'percentage'      => $percentage,
				) );
			}
			exit;

		}
	}

	/**
	 * Used to restart the batch.
	 * Just clear the data.
	 */
	public function restart_batch() {
		// Check ajax referrer
		if ( ! check_ajax_referer( WP_Batch_Processor_Admin::NONCE, 'nonce', false ) ) {
			wp_send_json_error( array(
				'message' => 'Permission denied.',
			) );
			exit;
		}
		// Validate the batch id.
		$batch_id = isset( $_REQUEST['batch_id'] ) ? $_REQUEST['batch_id'] : false;
		if ( ! $batch_id ) {
			wp_send_json_error( array(
				'message' => 'Invalid batch id',
			) );
			exit;
		}
		// Get the batch object
		$batch = WP_Batch_Processor::get_instance()->get_batch( $batch_id );
		// Restart the batch.
		$batch->restart();
		// Send json
		wp_send_json_success();
	}
}

// Init
WP_Batch_Processing_Ajax_Handler::get_instance();