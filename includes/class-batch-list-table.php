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

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class WP_BP_List_Table
 */
class WP_BP_List_Table extends \WP_List_Table {

	function __construct() {
		parent::__construct( array(
			'singular' => 'batch',
			'plural'   => 'batches',
			'ajax'     => false
		) );
	}

	function get_table_classes() {
		return array( 'widefat', 'fixed', 'striped', $this->_args['plural'] );
	}

	/**
	 * Message to show if no designation found
	 *
	 * @return void
	 */
	function no_items() {
		_e( 'No batches found. Read the documentation on the plugin github page to see how to register ones.', 'wp-batch-processing' );
	}

	/**
	 * Default column values if no callback found
	 *
	 * @param object $item
	 * @param string $column_name
	 *
	 * @return string
	 */
	function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'title':
				return $item->title;

			case 'total_processed':
				return $item->get_processed_count();

			case 'total_items':
				return $item->get_items_count();

			default:
				return isset( $item->$column_name ) ? $item->$column_name : '';
		}
	}

	/**
	 * Get the column names
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = array(
			'title'           => __( 'Title', 'wp-batch-processing' ),
			'total_processed' => __( 'Total Processed', 'wp-batch-processing' ),
			'total_items'     => __( 'Total Items', 'wp-batch-processing' ),
		);

		return $columns;
	}

	/**
	 * Render the designation name column
	 *
	 * @param object $item
	 *
	 * @return string
	 */
	function column_title( $item ) {

		$actions         = array();
		$actions['edit'] = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=dg-batches&action=view&id=' . $item->id ), $item->id, __( 'Manage Batch', 'wp-batch-processing' ), __( 'Manage', 'wp-batch-processing' ) );

		return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=dg-batches&action=view&id=' . $item->id ), $item->title, $this->row_actions( $actions ) );
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	function get_sortable_columns() {
		$sortable_columns = array();

		return $sortable_columns;
	}

	/**
	 * Set the bulk actions
	 *
	 * @return array
	 */
	function get_bulk_actions() {
		$actions = array();

		return $actions;
	}

	/**
	 * Render the checkbox column
	 *
	 * @param object $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return '';
	}

	/**
	 * Prepare the class items
	 *
	 * @return void
	 */
	function prepare_items() {

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';

		$this->items = WP_Batch_Processor::get_instance()->get_batches();
	}
}