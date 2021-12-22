<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('RP_Install')) {

    /**
     * @class RP_Install
     * @version 1.0.0
     *
     * */
    class RP_Install
    {
        protected static $instance = NULL;
        public $parent_items = NULL;
        public $child_items = NULL;
        public $dynamic_sub_price;

        public static function instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function install()
        {
            /* frontend actions */
            if (self::is_frontend()===true) {
                //init when WP is initialized
                add_action( 'init'    , array(__CLASS__, 'add_dashboard_templates') );

            }


            /* backend actions  */
//            if( self::is_frontend()===false ){
//                add_action( 'init', array( __CLASS__, 'install_actions' ), 5 );
//            }

            /* admin actions */
            //show notices
            //add_action( 'admin_init', array( __CLASS__, 'check_version' ), 5 );
        }

        public function set_price($price){
            $this->dynamic_sub_price = $price;
        }
        public function get_price() {
            return $this->dynamic_sub_price;
        }


        /**
         * check if request coming from frontend or not
         * @return bool
         */
        private function is_frontend()
        {
            if ( is_admin() ) {
                return false;
            }
            return true;
        }


        /**
         * template loader
         */
        public function add_dashboard_templates($endpoints)
        {
            if(!is_user_logged_in()){
                ?>
                <script>
                    window.location = "<?php echo site_url();?>/login";
                </script>
                <?php
            }
            //get menu item eg: https//www.cudoo.com/enterprise_dashboard/
            //compare it with server URI

            //automate router pages
            $dashboard_menu_items = wp_get_nav_menu_items( 'Dashboard Pages');
            foreach ($dashboard_menu_items as $menu_item){
                /*    //get parents array
                if($endpoints[1] === 'enterprise_dashboard' AND empty($endpoints[2]){
                    echo 'Default page.';
                    return 'default';
                }*/

                $url = rtrim($menu_item->url, '/');
                $server_uri = rtrim(site_url().$_SERVER['REQUEST_URI'], '/');
                if( $server_uri == $url ){
                        $template = str_replace(site_url(), "", $url);
                        $template = str_replace('/enterprise_dashboard/', "", $template);
                        echo 'loading:'.$template;
                        return $template;
                    }
            }

            foreach ($dashboard_menu_items as $menu_item) {
                if (strpos($server_uri, $menu_item->url) !== false) {
                    if ($endpoints[2] === 'users') {
                        if (is_numeric($endpoints[5]) AND $endpoints[4] === 'page') {
                            //send query var page
                            return 'single-users|' . $endpoints[3] . '|' . $endpoints[5];
                        } else {
                            return 'single-users';
                        }
                    }
                    if ($endpoints[2] === 'courses') {
                        if (is_numeric($endpoints[5]) AND $endpoints[4] === 'page') {
                            //send query var page
                            return 'single-courses|' . $endpoints[3] . '|' . $endpoints[5];
                        } else {
                            return 'single-courses';
                        }
                    }

                    //edit user
                    if ($endpoints[4] === 'edit-user') {
                        if ( is_numeric($endpoints[5]) ) {
                            //echo 'loading:'.$endpoints[2].'/'.$endpoints[3].'/'.$endpoints[4];
                            return $endpoints[2].'/'.$endpoints[3].'/'.$endpoints[4];
                        } else {
                            return '404';
                        }
                    }
                }
            }

            // default dashboard template
            if( $endpoints[1] === 'enterprise_dashboard' ){
                return 'default';
            }
        }



        /**
         * URI Endpoints
         */
        public function get_uri_endpoints()
        {
            return explode(SEPERATOR, $_SERVER['REQUEST_URI']);
        }

    }

}

$RP_install = new RP_install();
