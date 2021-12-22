<?php
/*
Plugin Name:        RoutePress
Plugin URI:         https://cudoo.com
Description:        Custom Routing solution for Wordpress.
Version:            1.0.0
Author:             Ali Usman Abbasi
Author URI:         http://learningonline.xyz
Tags: 				ali, usman, rp, dashboard, rewrite, htaccess, WordPress, admin,  pretty URLs, pretty links,
*/
if( ! class_exists( 'RoutePress' ) ) {
    final class RoutePress
    {
        const plugin_name = 'RoutePress';
        const version = '1.0.0';
        /**
         * @var RoutePress The single instance of the class
         * @since 2.1
         */
        protected static $instance = NULL;


        /**
         * Main RoutePress Instance
         *
         * Ensures only one instance of RoutePress is loaded or can be loaded.
         *
         * @since 1.0.0
         * @static
         * @return RoutePress - Main instance
         */
        public static function instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * RoutePress constructor.
         */
        public function __construct(  )
        {
            add_action('init', 'load_dashboard_page_template');
            $this->define_constants();
            $this->load_includes();
            $this->init_hooks();
        }


        /**
         * Define constants
         * @since 1.0.0
         */
        public function define_constants(){
            if(!defined('TEMPLATES_PATH')){
                define('TEMPLATES_PATH' , getcwd() . '/wp-content/plugins/routepress/templates/');
            }
            if(!defined('RP_PLUGIN_BASENAME')){
                define('RP_PLUGIN_BASENAME' , plugin_basename( __FILE__ ));
            }
            if(!defined('SEPERATOR')){
                define('SEPERATOR' , '/');
            }
            if(!defined('RP_DEBUG')){
                define('RP_DEBUG' , TRUE );
            }
        }


        /**
         * include required classes
         */
        public function load_includes(){
            include_once( 'includes/class-rp-install.php');
            include_once( 'includes/rp-template-functions.php' );
        }

        /**
         * call required hooks
         * @since  1.0.0
         */
        private function init_hooks() {
            register_activation_hook( __FILE__, array( 'RP_Install', 'install' ) );
            //add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
        }


        public function show_plugin_info(){
            echo $this::plugin_name;
            echo ' '.$this::version;
        }
    }

}


$RoutePress = new RoutePress();