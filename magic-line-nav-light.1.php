<?php
/**

*
* @package Mel-7
* tut url = https://www.smashingmagazine.com/2016/04/three-approaches-to-adding-configurable-fields-to-your-plugin/
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



        /**
         * initialize Magic Line Navigation
         */
        function mll_setup(){
            add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
            add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
            add_action( 'admin_init', array( $this, 'setup_sections' ) );
            add_action( 'admin_init', array( $this, 'setup_fields' ) );
  
        }



        /**
         * settings_link
         */
        public function settings_link($links){
            $settings_link = '<a href="admin.php?page=magic_line_nav">Settings</a>';
            $documentation_link = '<a href="https://mel-7.com/" target="__blank">Documentation</a>';
            array_push($links, $settings_link, $documentation_link);
            return $links;
        }



        /**
         * add_admin_pages
         */
        public function add_admin_pages(){
            add_menu_page('Magic Line Navigation', 'Magic Line', 'manage_options', 'magic_line_nav', array($this, 'admin_index'), 'dashicons-editor-underline', 110);
        }
        public function admin_index(){
            require_once plugin_dir_path(__FILE__) . '/admin/admin.php';
        }




        

        /**
         * setup_sections
         */
        public function setup_sections() {
            add_settings_section( 'our_first_section', 'Magic Line Navigation Setup', array( $this, 'section_callback' ), 'magic_line_nav' );
        }
        public function section_callback( $arguments ) {
            switch( $arguments['id'] ){
                case 'our_first_section':
                    echo 'Here you can setup the magic line effect.';
                    break;
            }
        }



        /**
         * setup_fields
         */
        public function setup_fields() {
            add_settings_field( 'our_first_field', 'Navigation Class or ID', array( $this, 'field_callback' ), 'magic_line_nav', 'our_first_section' );
        }
        public function field_callback( $arguments ) {
            echo '<input name="our_first_field" id="our_first_field" type="text"  value="' . get_option( 'our_first_field' ) . '" style="width:400px;" />';
            register_setting( 'magic_line_nav', 'our_first_field' );
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

    if( class_exists('MagicLineNavigationLight') ){
        $MLL = new MagicLineNavigationLight();
        $MLL->mll_setup();
    }

    // Activation Hook
    register_activation_hook(__FILE__, array( $MLL, 'activate') );

    // Deactivation Hook
    register_deactivation_hook(__FILE__, array( $MLL, 'deactivate') );

}