<?php

if( !defined( 'ABSPATH') ){
    exit;
}

require_once THEME_DIR . '/functions/admin/class/admin_page.php';
require_once THEME_DIR . '/functions/admin/class/dashboard.php';

class MyAdmin {

    private $plugins = array();

    public function __construct(){

        $this->plugins = array(
            'dashboard' => Dashboard::class,
        );

        foreach( $this->plugins as $name => $plugin ){
            $this->plugins[$name] = new $plugin();
        }

        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

    }

    public function admin_init(){
        $this->checkMethod( __FUNCTION__ );
    }

    public function admin_menu(){
        $this->checkMethod( __FUNCTION__ );
    }

    public function admin_enqueue_scripts( $page ){

        $data = array(
            'ajaxurl' => admin_url( 'admin-ajax.url' ),
        );

        wp_localize_script( 'jquery', 'fnsk', $data );

        foreach( $this->plugins as $name => $plugin ){
            if( method_exists( $plugin, __FUNCTION__ ) ){
                if( $page === 'fnsk_page_fnsk_' . $name . '_page' ){
                    call_user_func( array( $plugin,  __FUNCTION__ ) );
                }
            }
        }

    }

    private function checkMethod( $function_name ){
        foreach( $this->plugins as $plugin ){
            if( method_exists( $plugin, $function_name ) ){
                call_user_func( array( $plugin, $function_name ) );
            }
        }
    }

}

new MyAdmin();
