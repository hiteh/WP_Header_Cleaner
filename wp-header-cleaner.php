<?php
/*
Plugin Name: WP Header Cleaner by 222digits
Plugin URI: #
Description: Simple WordPress plugin that removes garbage form your website header
Version: 1.0
Author: 222digits - Hubert Warzycha
Author URI: http://222digits.pl
License: GPL2
*/

defined( 'ABSPATH' ) or die();

if( ! class_exists( 'WP_Header_Cleaner' ) ) {

	class WP_Header_Cleaner {

        /**
         * Plugin settings.
         *
         * @var WP_Header_Cleaner_Settings
         */
        protected $settings;

        /**
         * Construct the plugin object
         *
         * @param WP_Header_Cleaner_Settings $settings
         * @return void
         */
        public function __construct( WP_Header_Cleaner_Settings $settings )
        {
            $this->settings = $settings;   
        }

        /**
         * Init plugin
         *
         * @return void
         */
        public function init()
        {
            $this->settings->init_admin_settings();
            $this->settings->init_admin_menu();
            $this->add_plugin_settings_link();

            foreach ( $this->settings->data as $setting => $priority ) {
                $item = explode( '-', $setting );
                
                if( 1 == get_option( $setting ) )
                {
                    if( 0 !== $priority ) {
                        remove_action( $item[0], $item[1], $priority );
                    } else{
                        remove_action( $item[0], $item[1] );
                    }
                }
            }
        }

        /**
         * Activate the plugin
         *
         * @return void
         */
        public static function on_activation()
        {
            // do something
        }

        /**
         * Deactivate the plugin
         *
         * @return void
         */     
        public static function on_deactivation()
        {
            // do something
        }

        /**
         * Uninstall the plugin
         *
         * @return void
         */     
        public static function on_uninstall()
        {
            // do something
        }

        /**
         * Add plugin settings link
         *
         * @return void
         */
        public function add_plugin_settings_link()
        {
            add_filter('plugin_action_links_' . plugin_basename(__FILE__) , array( $this, 'plugin_settings_link' ));
        }

        /**
         *  Add the settings link to the plugins page
         *
         * @param array $links
         * @return array
         */
        public function plugin_settings_link( array $links )
        {
            $settings_link = sprintf(
                '<a href="options-general.php?page=wp_header_cleaner_d222">%1$s</a>',
                __( 'Settings', 'wp_header_cleaner_d222' )
            );
            array_unshift( $links, $settings_link );
            return $links;
        }
	}
}

if( class_exists( 'WP_Header_Cleaner' ) )
{
    // Plugin settings
    require_once( sprintf("%s/settings.php", dirname(__FILE__) ) );

    global $wp_header_cleaner;

    // Instantiate the plugin class
    $wp_header_cleaner = new WP_Header_Cleaner ( 
        new WP_Header_Cleaner_Settings ( 
            [   // Action                               //Priority
                'wp_head-wp_generator'                  => 0,
                'wp_head-feed_links'                    => 2,
                'wp_head-feed_links_extra'              => 3,
                'wp_head-rsd_link'                      => 0,
                'wp_head-wlwmanifest_link'              => 0,
                'wp_head-wp_resource_hints'             => 2,
                'wp_head-print_emoji_detection_script'  => 7,
                'wp_head-rest_output_link_wp_head'      => 0,
                'wp_head-wp_oembed_add_discovery_links' => 0,
                'wp_head-rel_canonical'                 => 0,
                'wp_head-wp_shortlink_wp_head'          => 10,
                'wp_print_styles-print_emoji_styles'    => 0,
            ] 
        ) 
    );
    // Init plugin
    $wp_header_cleaner->init();

    // Start this plugin once all other plugins are fully loaded
    add_action( 'init', 'WP_Header_Cleaner_Run', 99 );
    
    function WP_Header_Cleaner_Run() {
        register_activation_hook(   __FILE__, array( 'WP_Header_Cleaner', 'on_activation' ) );
        register_deactivation_hook( __FILE__, array( 'WP_Header_Cleaner', 'on_deactivation' ) );
        register_uninstall_hook(    __FILE__, array( 'WP_Header_Cleaner', 'on_uninstall' ) );
    }
}
