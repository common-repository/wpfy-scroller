<?php
/*
Plugin Name: WPFY Scroller
Plugin URI: https://akramuldev.com/plugins/wpfy-scoller/
Description: A light weight WordPress plugin to add a button in bottom of the site to scroll to top.
Version: 1.1
Author: Akramul Hasan
Author URI: https://www.akramuldev.com
Tag: wordpress plugin, simple, scroll to top, back to top, scroll top
Text Domain: wpfyscroll
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('WPFYScroller')) {
    class WPFYScroller
    {
        function __construct()
        {
            add_action('admin_menu', [$this, 'adminPage']);
            add_action('admin_init', [$this, 'settingFields']);
            add_action('wp_enqueue_scripts', [$this, 'loadAssets']);

            //Insert ICON markup on Footer
            add_action('wp_footer', [$this, 'loadHTML'], 100);

            // Register javascript
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_js']);
        }

        function enqueue_admin_js()
        {
            wp_enqueue_style('wp-color-picker');
            // Make sure to add the wp-color-picker dependecy to js file
            wp_enqueue_script(
                'cpa_custom_js',
                plugins_url('/assets/js/colorPicker.js', __FILE__),
                ['jquery', 'wp-color-picker'],
                false,
                true
            );
        }

        function settingFields()
        {
            //add_settings_section( 'wcp_first_section', null, null, 'word-count-settings-page' );

            add_settings_section(
                'wpfyscrollersection',
                null,
                null,
                'wpfyscroller-settings-page'
            );

            //Icon Size
            add_settings_field(
                'iconwidth',
                __('Icon Size', 'wpfyscroll'),
                [$this, 'widthHTML'],
                'wpfyscroller-settings-page',
                'wpfyscrollersection'
            );
            register_setting('wpfyscrollerfields', 'iconwidth', [
                'sanitize_callback' => 'sanitize_text_field',
                'default' => '35',
            ]);

            //Icon BG
            add_settings_field(
                'iconBg',
                __('Background Color','wpfyscroll'),
                [$this, 'bg_settings_field'],
                'wpfyscroller-settings-page',
                'wpfyscrollersection'
            );
            register_setting('wpfyscrollerfields', 'iconBg', [
                'sanitize_callback' => 'sanitize_hex_color',
                'default' => '#000000',
            ]);

            //Icon BG Hover
            add_settings_field(
                'iconhoverBg',
                __('Hover Background Color','wpfyscroll'),
                [$this, 'bg_hover_settings_field'],
                'wpfyscroller-settings-page',
                'wpfyscrollersection'
            );
            register_setting('wpfyscrollerfields', 'iconhoverBg', [
                'sanitize_callback' => 'sanitize_hex_color',
                'default' => '#f5f5f5',
            ]);
        }
        function bg_settings_field()
        {
            ?>
     
            
            <input type="text" name="iconBg" value="<?php echo esc_attr(get_option(
                'iconBg'
            )); ?>" class="cpa-color-picker" >
             
        <?php
        }
       // function for button hover background color ;
        function bg_hover_settings_field()
        {
            ?>
     
            <input type="text" name="iconhoverBg" value="<?php echo esc_attr(get_option(
                'iconhoverBg'
            )); ?>" class="cpa-color-picker-hover" >
             
        <?php
        }
        //Function for icon width
        function widthHTML()
        {
            ?>
            <input type="number" name="iconwidth" value="<?php echo esc_attr(
                get_option('iconwidth')
            ); ?>">
        <?php
        }
        //Loading assetss
        function loadAssets()
        {
            wp_enqueue_style(
                'wpfyscroller-css',
                plugins_url('/assets/css/style.css', __FILE__)
            );
            wp_enqueue_script(
                'wpfyscroller-js',
                plugins_url('/assets/js/scroll-main.js', __FILE__),
                ['jquery'],
                null,
                true
            );
        }
        //Markup area
        function loadHTML()
        {
            $iconWidth = get_option('iconwidth', 35);
            $iconBg = get_option('iconBg', '#000000'); 
            $iconhoverBg = get_option('iconhoverBg', '#f5f5f5'); 
            ?>
                <style>
                    a.topbutton:hover{
                        background:<?php echo $iconhoverBg; ?> !important;
                        color: #ffffff!important;
                    }
                </style>
            <?php 

            echo '<a style="width: ' .
                $iconWidth .
                'px; height: ' .
                $iconWidth .
                'px; background:' .
                $iconBg .
                '" href="#" class="topbutton"><div class="icon-wrap"><span class="top-icon">&#8593;</span></div></a>';
        }
        // Admin page area
        function adminPage()
        {
            add_options_page(
                __('WPFY Scroller Settings','wpfyscroll'),
                __('WPFY Scroller', 'wpfyscroll'),
                'manage_options',
                'wpfy-scroller-settings-page',
                [$this, 'adminPageHTML']
            );
        }

        function adminPageHTML()
        {
            ?>
        <div class="wrap">
            <h1 class=""><?php _e(
                'WPFY Scroller Settings',
                'wpfyscroll'
            ); ?></h1>
                <form action="options.php" method="POST">
                
                    <?php
                    settings_fields('wpfyscrollerfields');
                    do_settings_sections('wpfyscroller-settings-page');
                    submit_button();?>
                </form>
        </div>
        <?php
        }
    }

    $wpfyScroller = new WPFYScroller();
} //end class_exists
