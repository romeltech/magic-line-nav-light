<?php
/**
 * Activate
 * 
 * @package Mel-7
 * 
**/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
 }
 
if(! class_exists('MLLSetup') ){

    class MLLSetup{


        public $plugin;
        function __construct(){
            $this->plugin = plugin_basename(__FILE__);
        }

        // initialize Magic Line Navigation
        function run(){
            add_action('admin_menu', array($this, 'add_admin_pages') );
            add_filter("plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
            add_action( 'admin_init', array( $this, 'setup_sections' ) );
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
            require_once plugin_dir_path(__FILE__) . '/admin/admin.php';
        }

        // Admin Custom Fields
        public function mll_custom_fields(){
            // // Register Setting
            // register_setting($setting["option_group"], $setting["option_name"], ( isset($setting["callback"]) ? $setting["callback"] : '' ) );

            // // Add Settings Section
            // add_settings_section($section["id"], $section["title"], ( isset($section["callback"]) ? $section["callback"] : '' ), $section["page"]) );
            
            // // Add Settings Field
            // add_settings_field($field["id"], $field["title"], ( isset($setting["callback"]) ? $setting["callback"] : '' ), $field["page"], $field["section"], ( isset($field["args"]) ? $field["args"] : '' ) );
        }


    }

    if( class_exists('MLLSetup') ){
        $MLLrun = new MLLSetup();
    }

}