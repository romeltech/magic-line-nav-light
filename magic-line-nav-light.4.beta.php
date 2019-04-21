<?php
/**
*
* @package Mel-7
* tut url = https://www.smashingmagazine.com/2016/04/three-approaches-to-adding-configurable-fields-to-your-plugin/
* Color Picker
*    https://make.wordpress.org/core/2012/11/30/new-color-picker-in-wp-3-5/
*    https://code.tutsplus.com/articles/how-to-use-wordpress-color-picker-api--wp-33067
*    http://jsfiddle.net/BXnXJ/
**/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly.
}

// define('WP_DEBUG', true);

define( 'MAGIC_LINE_NAV_LIGHT_VERSION', '0.0.1' ); 
define( 'MAGIC_LINE_NAV_LIGHT_AUTHOR', 'Romel Indemne' );

if(! class_exists('MagicLineNavigationLight') ){

    class MagicLineNavigationLight{

        /**
         * Contstructor
         */
        public function __construct() {
            // Hook into the admin menu
            add_filter( 'plugin_action_links_'.plugin_basename(__FILE__) , array( $this, 'settings_link' ) );
            add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
            add_action( 'admin_init', array( $this, 'setup_sections' ) );
            add_action( 'admin_init', array( $this, 'setup_fields' ) );
            add_action('admin_enqueue_scripts', array( $this, 'ml_enqueue_scripts' ) );
        }

        /**
         * Activate function
         */
        public function activate() {

        }

        /**
         * Deactivate function
         */
        public function deactivate() {

        }
    
        /**
         * Enqueue Scripts
         */
        public function ml_enqueue_scripts() { 
     
            // Make sure to add the wp-color-picker dependecy to js file
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'cpa_custom_js', plugins_url( '/assets/js/ml-admin.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '', true  );
        }


        /**
         * settings_link
         */
        public function settings_link($links){
            $settings_link = '<a href="admin.php?page=magic_line_nav_light">Settings</a>';
            $documentation_link = '<a href="https://mel-7.com/" target="__blank">Documentation</a>';
            array_push($links, $settings_link, $documentation_link);
            return $links;
        }


        /**
         * Add Plugin Menu and Page
         */
        public function create_plugin_settings_page() {
            // Add the menu item and page
            $page_title = 'Magic Line Navigation';
            $menu_title = 'Magic Line';
            $capability = 'manage_options';
            $slug = 'magic_line_nav_light';
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
                <h1><?php _e('Magic Line Navigation', 'magic-line-light');?></h1>
                <?php settings_errors(); ?>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'magic_line_nav_light' );
                        do_settings_sections( 'magic_line_nav_light' );
                        submit_button();
                    ?>
                </form>
            </div> <?php
        }

        public function setup_sections() {
            add_settings_section( 'magic_line_nav_color', 'Settings', array( $this, 'section_callback' ), 'magic_line_nav_light' );
            add_settings_section( 'magic_line_nav_height', null, array( $this, 'section_callback' ), 'magic_line_nav_light' );
        }

        public function section_callback( $arguments ) {
            switch( $arguments['id'] ){
                case 'magic_line_nav_color':
                    // echo 'Set the Magicline color';
                    break;
                case 'magic_line_nav_height':
                    // echo 'Here you can add the navigation you want to have a magic line effect';
                    break;
            }
        }

        public function setup_fields() {
            $fields = array(
                array(
                    'uid' => 'ml_color',
                    'label' => 'Color',
                    'section' => 'magic_line_nav_color',
                    'type' => 'text',
                    'options' => false,
                    'placeholder' => null,
                    'helper' => null,
                    'supplemental' => 'Set the magicline color',
                    'default' => '#fe4902'
                ),
                array(
                    'uid' => 'ml_height',
                    'label' => 'Height',
                    'section' => 'magic_line_nav_height',
                    'type' => 'text',
                    'options' => false,
                    'placeholder' => 'Add your Navigation Selector',
                    'helper' => null,
                    'supplemental' => 'Input the magicline height in px e.g. 2px',
                    'default' => '2px'
                )
            );
            foreach( $fields as $field ){
                add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'magic_line_nav_light', $field['section'], $field );
                register_setting( 'magic_line_nav_light', $field['uid'] );
            }
        }

        public function field_callback( $arguments ) {
            $value = get_option( $arguments['uid'] ); // Get the current value, if there is one
            if( ! $value ) { // If no value exists
                $value = $arguments['default']; // Set to our default
            }
        
            // Check the field
            switch( $arguments['type'] ){
                case 'text': // If it is a text field
                    if($arguments['section'] == 'magic_line_nav_color'){
                        printf( '<input name="%1$s" id="%1$s" type="%2$s" value="%4$s" class="ml-color-field" >', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                    }else{

                        printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                    }
                    break;
            }

            // Check help text
            if( $helper = $arguments['helper'] ){
                printf( '<span class="helper"> %s</span>', $helper ); // Display
            }

            // Check supplemental text
            if( $supplimental = $arguments['supplemental'] ){
                printf( '<p class="description">%s</p>', $supplimental ); // Display
            }

        }

    }

    if( class_exists('MagicLineNavigationLight') ){
        $MLL = new MagicLineNavigationLight();
    }

    /**
     * Display to Frontend
     */
    function magic_line_nav_light_script() {

        $ml_height = get_option('ml_height'); 
        $ml_color = get_option('ml_color'); 
        ?>

        <!-- Magic Line Navigation -->
        <style>
        .magic-line-nav-parent{ position: relative;}
        .magic-line-nav-parent li { display: inline-flex; }
        #magic-line{ position: absolute; bottom: -2px; left: 0;height:<?php echo $ml_height; ?>; background: <?php echo $ml_color;?>; margin: 0 auto !important; }
        .magic-line-nav-parent li ul{ top: 100%;}
        @media all and (max-width: 900px) {#magic-line{display:none;}}</style>
        <script>
            (function($){
                'use strict';
                var leftPos, newWidth, $magicLine,
                $navParent, $magicLineClass, $el;
                
                // Magic line Class
                $magicLineClass = $(".magic-line-nav"); 
                $magicLineClass.parent().addClass('magic-line-nav-parent');
                
                // Set the active nav
                $magicLineClass.addClass('ml-active');
                
                // Append Magic line
                $navParent = $(".magic-line-nav-parent");  
                $navParent.append("<li id='magic-line'></li>");
                
                // Set Magic line initial state
                $magicLine = $('#magic-line');
                $magicLine.width($('.magic-line-nav-parent li.ml-active').width())
                    .css('left', $('.magic-line-nav-parent li.ml-active a').position().left)
                    .data('origLeft', $magicLine.position().left)
                    .data('origWidth', $magicLine.width());

                // $('.magic-line-nav-parent li a').click(function() {
                //     var $this = $(this);
                //     $this.parent().addClass('ml-active').siblings().removeClass('ml-active');
                //     $magicLine
                //     .data('origLeft', $this.position().left)
                //     .data('origWidth', $this.parent().width());
                //     return false;
                // });

                // Magic line on hover
                $(".magic-line-nav-parent > li").hover(function() {
                    $el = $(this);
                    leftPos = $el.position().left;
                    newWidth = $el.width();
                    // newWidth = $el.outerWidth(true);
                    $magicLine.stop().animate({
                        left: leftPos,
                        width: newWidth
                // }, 1000, 'swing');
                });
                }, function() {
                    $magicLine.stop().animate({
                        left: $magicLine.data("origLeft"),
                        width: $magicLine.data("origWidth")
                    });    
                    console.log($magicLine.data("origLeft"));
                });

            })(jQuery);
        </script>
        <!-- End of Magic Line Navigation Script -->
        <?php
    }
    add_action('wp_footer', 'magic_line_nav_light_script');

    // Activation Hook
    register_activation_hook(__FILE__, array( $MLL, 'activate') );

    // Deactivation Hook
    register_deactivation_hook(__FILE__, array( $MLL, 'deactivate') );

}