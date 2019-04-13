<?php
/**
* Plugin Name: Magic Line Navigation Light
* Plugin URI: https://mel-7.com/
* Description: A very light plugin that creates Magic Line effect for your navigation.
* Version: 0.0.1
* Author: Romel Indemne
* Author URI: http://mel-7.com/
* License:     GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*
* @package Mel-7
*
**/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly.
}

define('WP_DEBUG', true);

if(! class_exists('MagicLineNavigationLight') ){


    class MagicLineNavigationLight{


        public $plugin;

        function __construct(){
            $this->plugin = plugin_basename(__FILE__);
        }

        // Activate
        function activate(){
            require_once plugin_dir_path(__FILE__) . 'inc/magic-line-nav-light-activate.php';
            MLLActivate::activate();
        }
        
        // Deactivate
        function deactivate(){
            require_once plugin_dir_path(__FILE__) . 'inc/magic-line-nav-light-deactivate.php';
            MLLDeactivate::deactivate();
        }

        function uninstall(){
            // delete all plugin data from db
        }

        // initialize Magic Line Navigation
        function init_mll(){
            add_action('admin_menu', array($this, 'add_admin_pages') );
            add_filter("plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
        }

        // Custom settings link
        public function settings_link($links){
            $settings_link = '<a href="admin.php?page=magic_line_nav">Settings</a>';
            $documentation_link = '<a href="https://mel-7.com/" target="__blank">Documentation</a>';
            array_push($links, $settings_link, $documentation_link);
            return $links;
        }

        // Admin Menu
        public function add_admin_pages(){
            add_menu_page('Magic Line Navigation', 'Magic Line', 'manage_options', 'magic_line_nav', array($this, 'admin_index'), 'dashicons-editor-underline', 110);
        }

        // Admin Page
        public function admin_index(){
            echo $this->plugin;
        }

        // Admin Custom Fields
        public function mll_custom_fields(){
            // Register Setting
            register_setting($option_group, $option_name, array('') );

            // Add Settings Section

            // Add Settings Field
        }

    }

    if( class_exists('MagicLineNavigationLight') ){
        $MLL = new MagicLineNavigationLight();
        $MLL->init_mll();
    }

    // Activation Hook
    register_activation_hook(__FILE__, array( $MLL, 'activate') );

    // Deactivation Hook
    register_deactivation_hook(__FILE__, array( $MLL, 'deactivate') );

}