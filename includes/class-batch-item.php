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
 * Class WP_Batch_Item
 */
class WP_Batch_Item {

	/**
	 * Unique identifier of the batch item
	 * @var int
	 */
	public $id;

	/**
	 * Additional data for the item
	 * @var mixed
	 */
	public $data;

	/**
	 * WP_Batch_Item constructor
	 *
	 * @param $id
	 * @param $data
	 */
	public function __construct($id, $data = null) {
		$this->id = $id;
		$this->data = $data;
	}

	/**
	 * Return data value
	 *
	 * @param $key
	 * @param null $default
	 *
	 * @return mixed|null
	 */
	public function get_value($key, $default = null) {
		return isset($this->data[$key]) ? $this->data[$key] : $default;
	}
}