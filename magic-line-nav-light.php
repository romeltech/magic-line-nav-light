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
* tut url = https://www.smashingmagazine.com/2016/04/three-approaches-to-adding-configurable-fields-to-your-plugin/
**/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly.
}

define('WP_DEBUG', true);

if(! class_exists('MagicLineNavigationLight') ){


    class MagicLineNavigationLight{

        /**
         * Contstructor
         */
        public function __construct() {
            // Hook into the admin menu
            add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
            add_action( 'admin_init', array( $this, 'setup_sections' ) );
            add_action( 'admin_init', array( $this, 'setup_fields' ) );

           
        }

        /**
         * Add Plugin Menu and Page
         */
        public function create_plugin_settings_page() {
            // Add the menu item and page
            $page_title = 'Magic Line Navigation';
            $menu_title = 'Magic Line';
            $capability = 'manage_options';
            $slug = 'smashing_fields';
            $callback = array( $this, 'plugin_settings_page_content' );
            $icon = 'dashicons-editor-underline';
            $position = 100;
            add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
        }


        /**
         * Plugin Page Content
         */
        public function plugin_settings_page_content() { ?>
            <div class="wrap">
                <h1>Magic Line Navigation</h1>
                <?php settings_errors(); ?>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'smashing_fields' );
                        do_settings_sections( 'smashing_fields' );
                        submit_button();
                    ?>
                </form>
            </div> <?php
        }

        public function setup_sections() {
            add_settings_section( 'our_first_section', 'Setup', array( $this, 'section_callback' ), 'smashing_fields' );
        }

        public function section_callback( $arguments ) {
            switch( $arguments['id'] ){
                case 'our_first_section':
                    echo 'Here you can add the navigation you want to have a magic line effect';
                    break;
                // case 'our_second_section':
                //     echo 'This one is number two';
                //     break;
                // case 'our_third_section':
                //     echo 'Third time is the charm!';
                //     break;
            }
        }

        public function setup_fields() {
            $fields = array(
                array(
                    'uid' => 'our_first_field',
                    'label' => 'Navigation Selector',
                    'section' => 'our_first_section',
                    'type' => 'text',
                    'options' => false,
                    'placeholder' => 'Add your Navigation Selector',
                    'helper' => null,
                    'supplemental' => null,
                    'default' => 'ul.header-nav li.menu-itm a.nav-top-link'
                )
            );
            foreach( $fields as $field ){
                add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'smashing_fields', $field['section'], $field );
                register_setting( 'smashing_fields', $field['uid'] );
            }
        }


        public function field_callback( $arguments ) {
            $value = get_option( $arguments['uid'] ); // Get the current value, if there is one
            if( ! $value ) { // If no value exists
                $value = $arguments['default']; // Set to our default
            }
        
            // Check which type of field we want
            switch( $arguments['type'] ){
                case 'text': // If it is a text field
                    printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" style="width:400px;"/>', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                    break;
            }
        }


        /**
         * Call to frontend
         */
        // add_action('wp_head', 'mll_script');
        function mll_script(){
            echo get_option('our_first_field');
        }

    }

    if( class_exists('MagicLineNavigationLight') ){
        $MLL = new MagicLineNavigationLight();
    }

    // Activation Hook
    register_activation_hook(__FILE__, array( $MLL, 'activate') );

    // Deactivation Hook
    register_deactivation_hook(__FILE__, array( $MLL, 'deactivate') );

}