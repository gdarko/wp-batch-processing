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

trait WP_BP_Singleton {
	/**
	 * The current instance
	 */
	protected static $instance;

	/**
	 * Returns the current instance
	 */
	final public static function get_instance() {
		return isset( static::$instance )
			? static::$instance
			: static::$instance = new static;
	}

	/**
	 * WP_BP_Singleton constructor.
	 */
	final private function __construct() {
		$this->init();
	}

	// Prevent instances
	protected function init() {
	}

	final private function __wakeup() {
	}

	final private function __clone() {
	}
}