<?php

class Dashboard implements AdminPage{

    function __construct(){}

    public function admin_init(){
        return false;
    }

    public function admin_menu(){

        add_menu_page(
            'Fnsk',
            'Fnsk',
            'manage_options',
            'fnsk',
            array( $this->page, 'dashboard' )
        );

        add_submenu_page(
            'fnsk',
            'ダッシュボード',
            'ダッシュボード',
            'manage_options',
            'fnsk',
            array( $this->page, 'dashboard' )
        );

    }

    public function admin_enqueue_scripts(){
        wp_enqueue_style( 'dashboard.css', THEME_URL . '/css/dashboard.css', array(), filemtime( THEME_DIR . '/css/dashboard.css' ) );
    }

}
