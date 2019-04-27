<?php
/**
* Plugin Name: Magic Line Navigation Light
* Plugin URI: https://mel-7.com/
* Description: A very light plugin that creates Magic Line effect for your navigation.
* Version: 0.0.1
* Author: Romel Indemne
* Author URI: http://mel-7.com/
* Text Domain: magic-line-light
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*
* @package Mel-7
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
            $settings_link = '<a href="options-general.php?page=magic_line_nav_light">Settings</a>';
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
            $menu_title = 'Magic Line Navigation';
            $capability = 'manage_options';
            $slug = 'magic_line_nav_light';
            $callback = array( $this, 'plugin_settings_page_content' );
            add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
        }


        /**
         * Plugin Page Content
         */
        public function plugin_settings_page_content() { ?>
            <style>
                .dntn-flex{
                    display:-webkit-box;
                    display:-ms-flexbox;
                    display:flex;
                    -webkit-box-orient: horizontal;
                    -webkit-box-direction: normal;
                        -ms-flex-direction: row;
                            flex-direction: row;
                    -ms-flex-wrap: wrap;
                        flex-wrap: wrap;
                    -webkit-box-align: center;
                        -ms-flex-align: center;
                            align-items: center;
                    -webkit-box-pack: start;
                        -ms-flex-pack: start;
                            justify-content: flex-start;
                }
                .dntn-item{
                    width: 300px;
                    padding: 15px;
                    text-align: center;
                    background-color: #ffffff;
                    height: 100px;
                    margin-right: 30px;
                    -webkit-box-shadow: 0px 0px 10px #e0e0e0;
                            box-shadow: 0px 0px 10px #e0e0e0;
                }
                .dntn-item h4{
                    margin-top: 0;
                }
            </style>
            <div class="wrap">
                <h1><?php _e('Magic Line Navigation', 'magic-line-light');?></h1>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'magic_line_nav_light' );
                        do_settings_sections( 'magic_line_nav_light' );
                        submit_button();
                    ?>
                </form>
                <div class="donation-wrapper">
                    <h3>Enjoying the plugin? </h3>
                    <div class="dntn-flex">
                        <div class="dntn-item">
                        <h4><?php _e('I accept donations.', 'magic-line-light');?></h4>
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post"  target="_blank" >
                        <input type="hidden" name="cmd" value="_s-xclick" />
                        <input type="hidden" name="hosted_button_id" value="RDB9U52ESMPB4" />
                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button"/>
                        <img alt="" border="0" src="https://www.paypal.com/en_PH/i/scr/pixel.gif" width="1" height="1" />
                        </form>
                        </div>
                        <div class="dntn-item">
                        <h4><?php _e('Or a drink.', 'magic-line-light');?></h4>
                        <script type='text/javascript' src='https://ko-fi.com/widgets/widget_2.js'></script><script type='text/javascript'>kofiwidget2.init('Buy me a drink', '#46b798', 'C0C5RTHE');kofiwidget2.draw();</script> 
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }

        public function setup_sections() {
            add_settings_section( 'magic_line_nav_color', 'Settings', array( $this, 'section_callback' ), 'magic_line_nav_light' );
            add_settings_section( 'magic_line_nav_height', null, array( $this, 'section_callback' ), 'magic_line_nav_light' );
            add_settings_section( 'magic_line_nav_border_radius', null, array( $this, 'section_callback' ), 'magic_line_nav_light' );
            add_settings_section( 'magic_line_nav_bottom', null, array( $this, 'section_callback' ), 'magic_line_nav_light' );
            add_settings_section( 'magic_line_nav_cpt', null, array( $this, 'section_callback' ), 'magic_line_nav_light' );
        }

        public function section_callback( $arguments ) {
            switch( $arguments['id'] ){
                case 'magic_line_nav_color':
                    // echo 'Set the Magicline color';
                    break;
                case 'magic_line_nav_height':
                    // echo 'Set the Magicline height';
                    break;
                case 'magic_line_nav_border_radius':
                    // echo 'Set the Magicline border radius';
                    break;
                case 'magic_line_nav_bottom':
                    // echo 'Set the Magicline bottom space';
                    break;
                case 'magic_line_nav_cpt':
                    // echo 'Here you can add the navigation you want to have a magic line effect';
                    break;
            }
        }

        public function setup_fields() {
            $fields = array(
                // Color
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
                // Height
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
                ),
                // Border Radius
                array(
                    'uid' => 'ml_border_radius',
                    'label' => 'Border Radius',
                    'section' => 'magic_line_nav_border_radius',
                    'type' => 'text',
                    'options' => false,
                    'placeholder' => '0',
                    'helper' => null,
                    'supplemental' => 'Input the magicline border radius in px e.g. 2px',
                    'default' => '0'
                ),
                array(
                    'uid' => 'ml_bottom',
                    'label' => 'Bottom',
                    'section' => 'magic_line_nav_bottom',
                    'type' => 'text',
                    'options' => false,
                    'placeholder' => '0',
                    'helper' => null,
                    'supplemental' => 'Input the magicline bottom space e.g. -5px',
                    'default' => '-2px'
                ),
                // CPT
                array(
                    'uid' => 'ml_cpt',
                    'label' => 'Custom Post Type',
                    'section' => 'magic_line_nav_height',
                    'type' => 'text',
                    'options' => false,
                    'placeholder' => 'product',
                    'helper' => null,
                    'supplemental' => 'Input your Custom Post type slugs that are present on the navigation e.g. project. If more than one, Separated by comma(,) without spaces e.g. project,location,person',
                    'default' => ''
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


        $ml_cpt = get_option('ml_cpt') !== '' ? get_option('ml_cpt') : "";
        $ml_height = get_option('ml_height') !== '' ? get_option('ml_height') : "2px";
        $ml_color = get_option('ml_color') !== '' ? get_option('ml_color') : "#7200af";
        $ml_border_radius = get_option('ml_border_radius') !== '' ? get_option('ml_border_radius') : "0";
        $ml_bottom = get_option('ml_bottom') !== '' ? get_option('ml_bottom') : "-2px";
        
        // Arrays
        $cptArr = array();
        $thecptArr = array();
        
        // Add a prefix single to check the page
        $cptArr = explode(",",$ml_cpt);        
        foreach ($cptArr as &$value) {
            $value = 'single-'.$value;
        }

        $cptCount = count($cptArr);
        for ($i = 0; $i < $cptCount; $i++) {
            array_push($thecptArr, $cptArr[$i]);
        }
        // echo $thecptArr[1];

        ?>
        <!-- .magic-line-nav-parent li {padding-right: 40px;}
        .magic-line-nav-parent li a{padding-right: 0;} -->

        <!-- Magic Line Navigation is Active -->
        <!-- .magic-line-nav-parent li { display: -webkit-inline-box;display: -ms-inline-flexbox;display: inline-flex; padding-right:15px; }.magic-line-nav-parent li a{padding:0;margin:0;} -->
        <!-- .magic-line-nav-parent li a{padding-right:0 !important;padding-left:0 !important;} -->
        <style>.magic-line-nav-parent{ position: relative;}.magic-line-nav-parent li { display: -webkit-inline-box;display: -ms-inline-flexbox;display: inline-flex; padding:0 5px !important;margin:0 5px !important;}.magic-line-nav-parent li ul{ top: 100%;}#magic-line{ position: absolute; bottom: <?php echo $ml_bottom; ?>; left: 0;height:<?php echo $ml_height; ?>; background: <?php echo $ml_color;?>; margin: 0 auto !important; border-radius: <?php echo $ml_border_radius;?>}@media all and (max-width: 900px) {#magic-line{display:none;}}</style>
        
        <script type="text/javascript">
            (function($){
                'use strict';

                // Variables used
                var leftPos, newWidth, $magicLine, cpts,
                $navParent, $magicLineClass, $el, $currentNavItem,
                $curentMenuItemCount, $curentPageParentCount;
                var $theLi, $currentText; 
                // cpts = echo json_encode($cptArr); ?>;
                cpts = 'here';

                // Magic line Class
                $magicLineClass = $(".magic-line-nav"); 
                $magicLineClass.parent().addClass('magic-line-nav-parent');
                
                // Append Magic line
                $navParent = $(".magic-line-nav-parent");  
                $navParent.append("<span id='magic-line'></span>");

                // Check if custom post type page
                // console.log(cpts.length);
                var $cptCount = 0;
                var $cptBodyClass;
                for ( var i = 0; i < cpts.length; i++ ){
                    if ( $('body').hasClass( cpts[i] ) ){
                        // console.log('the class: '+cpts[i]);
                        // e.g. nav-single-project
                        $cptBodyClass = 'nav-'+cpts[i];
                        $cptCount++;
                    }
                }
                // console.log('cpt body class : '+$cptBodyClass);

                if($cptCount > 0){
                    // $currentText = $('.magic-line-nav-parent li.'+$cptBodyClass+' a');
                    $currentNavItem = $('.magic-line-nav-parent li.'+$cptBodyClass);
                }else{
                    // Count the li tags if has the correct classes
                    $curentMenuItemCount = $('.magic-line-nav-parent li.current-menu-item').length;
                    $curentPageParentCount = $('.magic-line-nav-parent li.current_page_parent').length;

                    // console.log('current menu item : '+$curentMenuItemCount);
                    // console.log('current Page Count : '+$curentPageParentCount);
                    // if li have classes of current-menu-item and current_page_parent
                    if($curentMenuItemCount == 0 && $curentPageParentCount == 0 ){
                        // if no classes magicline will initiate on the li with .magic-line-nav class
                        // $currentText = $('.magic-line-nav-parent li.magic-line-nav a');
                        $currentNavItem = $('.magic-line-nav-parent li.magic-line-nav');

                        $theLi = 'magic-line-nav';
                    }else if($curentMenuItemCount == 0 && $curentPageParentCount > 0 ){
                        // if current-menu-item is not present and has current_page_parent magic line will initiate in current_page_parent 
                        // $currentText = $('.magic-line-nav-parent li.current_page_parent a');
                        $currentNavItem = $('.magic-line-nav-parent li.current_page_parent');                        
                        $theLi = 'current_page_parent';
                    }else{
                        // Magic line will initiate in current-menu-li
                        $currentText = $('.magic-line-nav-parent li.current-menu-item a');
                        $currentNavItem = $('.magic-line-nav-parent li.current-menu-item');
                        $theLi = 'current-menu-item';
                    }
                }

                // var elPaddingTotal = parseInt($currentNavItem.css('padding-left'));
                var elPaddingTotal = parseInt($currentNavItem.css('padding-left')) + parseInt($currentNavItem.css('padding-right'))
                // var elPaddingTotal = parseInt($currentNavItem.css('padding-left'));
                var elMarginTotal = parseInt($currentNavItem.css('margin-left')) + parseInt($currentNavItem.css('margin-right'));

                var elTotalLeftSpace = parseInt($currentNavItem.css('margin-left')) + parseInt($currentNavItem.css('padding-left'));

                var elInitTotal = elPaddingTotal + elMarginTotal;
                

                var sum = $currentNavItem.position().left + elTotalLeftSpace;
                // console.log('elPaddingTotal '+elPaddingTotal);
                // Set Magic line initial state
                $magicLine = $('#magic-line');
                console.log('left: '+sum);
                // var currentNavItema = $currentText[0].getBoundingClientRect().width;
                console.log('a tag: '+ $currentNavItem.find('a')[0].getBoundingClientRect().width);
                $magicLine.width($currentNavItem.find('a')[0].getBoundingClientRect().width)
                    .css({'left' : sum, 'padding-left' : 0, 'padding-right' : 0 })
                    .data('origLeft', $magicLine.position().left)
                    .data('origWidth', $magicLine.width() );
                // Magic line on hover
                $(".magic-line-nav-parent > li").hover(function() {
                    $el = $(this);
                    var $ela = $(this).find('a');
                    var hoverelPaddingTotal = parseInt($el.css('padding-left'));
                    var hoverElMarginLeft = parseInt($el.css('margin-left'));
                    var hoverTotalWidth = hoverElMarginLeft + hoverelPaddingTotal;
                    // newWidth = $ela.width();
                    newWidth = $ela[0].getBoundingClientRect().width;
                    leftPos = $el.position().left + hoverTotalWidth;
                    $magicLine.stop().animate({
                        left: leftPos,
                        width: newWidth
                    });

                }, function() {
                    $magicLine.stop().animate({
                        left: $magicLine.data("origLeft"),
                        width: $magicLine.data("origWidth")
                    });    
                    console.log(elInitTotal);
                });

                $(".magic-line-nav-parent li a").click(function() {
                    $(this).parent().siblings().removeClass("current-menu-item");
                    $(this).parent().addClass("clicked-menu-item current-menu-item");

                    var clickedPaddingTotal = parseInt($(".magic-line-nav-parent li.clicked-menu-item").css('padding-left'));
                    var clickedMarginTotal = parseInt($(".magic-line-nav-parent li.clicked-menu-item").css('margin-left'));
                    var clickedTotal = clickedPaddingTotal + clickedMarginTotal;

                    $magicLine
                    .width($(".magic-line-nav-parent li.clicked-menu-item a")[0].getBoundingClientRect().width )
                    .css("left", $(".magic-line-nav-parent li.clicked-menu-item").position().left + parseInt($(".magic-line-nav-parent li.clicked-menu-item").css('padding-left')) + parseInt($(".magic-line-nav-parent li.clicked-menu-item").css('margin-left')))
                    .data("origLeft", $magicLine.position().left)
                    .data("origWidth", $magicLine.width());
                    // .data("origWidth", $magicLine.outerWidth(true)); 
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