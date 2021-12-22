<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


if( ! class_exists( 'RP_Uninstall' ) ) {

    /**
     * @class RP_Uninstall
     * @version 1.0.0
     *
     * */
    class RP_Uninstall {

        /**
         * Uninstall the plugin
         * @since 1.0.0
         * */
        public static function uninstall() {
            //silence
        }
    }
}

RP_Uninstall::uninstall();
