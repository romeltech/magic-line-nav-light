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
            add_filter( 'plugin_action_links_'.plugin_basename(__FILE__) , array( $this, 'settings_link' ) );
            add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
            add_action( 'admin_init', array( $this, 'setup_sections' ) );
            add_action( 'admin_init', array( $this, 'setup_fields' ) );

            
        }

        /**
         * Call to frontend
         */

        // function mll_script(){
        //     return 'mel';
        // //     echo '<script>'.get_option('our_first_field').'</script>';
        // }

        /*
        function testingone(){ 
            if( get_option( 'our_first_field' ) ) {
                ?>
                <script>var Script = GoesHere; </script>
                <?php
            }
        }
        */


        /**
         * settings_link
         */
        public function settings_link($links){
            $settings_link = '<a href="admin.php?page=smashing_fields">Settings</a>';
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
    }

    if( class_exists('MagicLineNavigationLight') ){
        $MLL = new MagicLineNavigationLight();
    }





    /**
     * Display to Frontend
     */
    function magic_line_nav_light_script() {

        $mll_selector = get_option('our_first_field');
        
        /*
            $magicLine
            .width($("#top-menu li.current-menu-item a").width())
            .css("left", $("#top-menu li.current-menu-item").position().left)
            .data("origLeft", $magicLine.position().left)
            .data("origWidth", $magicLine.width());
        */

        ?>
        <?php// echo $mll_selector.'.current-menu-item'; ?>
        <!-- Magic Line Navigation -->
        <div id="mll" hidden 
        data-mllselector="<?php echo get_option('our_first_field');?>" 
        data-mllwidth="<?php echo get_option('our_first_field').' li.current-menu-item';?>" 
        data-mllposition="<?php echo get_option('our_first_field').' li.current-menu-item';?>">
        </div>
        <!-- <style>#top-menu.nav { position: relative;}#top-menu.nav li { display: inline-flex; }#magic-line { position: absolute; bottom: -2px; left: 0; height: 2px; background: #fe4902; }#main-header ul.sub-menu{ top: 100%;}</style> -->
        <style>
        <?php echo $mll_selector;?>{ position: relative;}
        <?php echo $mll_selector;?> li { display: inline-flex; }
        #magic-line{ position: absolute; bottom: -2px; left: 0; height: 2px; background: #fe4902; margin: 0 auto !important; }
        <?php echo $mll_selector;?> li ul{ top: 100%;}</style>
        <script>
            (function($){
                
                var $mll_selector = $("#mll").data("mllselector");

                
                var $mll_width = $("#mll").data("mllwidth");
                // var $the_width = $($mll_width).outerWidth(true); 


                var $mll_width = $("#mll").data("mllwidth");
                var $mll_position = $("#mll").data("mllposition");

                console.log($mll_selector);
                console.log($mll_width);
                console.log($mll_position);

                $($mll_selector).append("<li id='magic-line'></li>");
                var $magicLine = $("#magic-line");
                $magicLine
                // .width($($mll_width).width())
                .width($($mll_width).outerWidth(true))
                .css("left", $($mll_position).position().left)
                .data("origLeft", $magicLine.position().left)
                .data("origWidth", $magicLine.width());
                $($mll_selector+" > li").hover(function() {
                    $el = $(this);
                    leftPos = $el.position().left;
                    // newWidth = $el.width();
                    newWidth = $el.outerWidth(true);
                    $magicLine.stop().animate({
                        left: leftPos,
                        width: newWidth
                });
                }, function() {
                    $magicLine.stop().animate({
                        left: $magicLine.data("origLeft"),
                        width: $magicLine.data("origWidth")
                    });    
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