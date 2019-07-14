<?php
/*
Plugin Name: WP Batch Processing
Plugin URI: https://github.com/gdarko/wp-batch-processing
Description: Batch Processing for WordPress. Imagine you have to send custom emails to a lots of users based on some kind of logic. This plugin makes batch tasks easy.
Version: 1.0.1
Author: Darko Gjorgjijoski
Author URI: https://darkog.com
License: GPL-2+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! is_admin() ) {
	return;
}

define( 'WP_BP_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_BP_URL', plugin_dir_url( __FILE__ ) );

require_once 'includes/class-bp-helper.php';
require_once 'includes/class-bp-singleton.php';
require_once 'includes/class-batch-item.php';
require_once 'includes/class-batch.php';
require_once 'includes/class-batch-processor.php';
require_once 'includes/class-batch-ajax-handler.php';
require_once 'includes/class-batch-list-table.php';
require_once 'includes/class-batch-processor-admin.php';

// Examples
// require_once 'examples/class-example-batch.php';