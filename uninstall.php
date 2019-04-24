<?php
/**
 * Uninstall
 * 
 * @package Mel-7
 * 
**/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}