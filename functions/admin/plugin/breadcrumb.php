<?php

require_once THEME_DIR . '/functions/admin/callback/breadcrumb_callback.php';

class Breadcrumb implements AdminPage{

    function __construct(){}

    public function admin_init(){
        register_setting(
            'fnsk_breadcrumb_group',
            'fnsk_breadcrumb',
            array(BreadcrumbCallback::class,'sanitize')
        );

        add_settings_section(
            'fnsk_breadcrumb_section',
            'パンくずセクション',
            array(BreadcrumbCallback::class,'render_section'),
            'fnsk_breadcrumb'
        );

        add_settings_field(
            'fnsk_slideshow_field',
            'パンくずフィールド',
            array(BreadcrumbCallback::class,'render_field'),
            'fnsk_breadcrumb',
            'fnsk_breadcrumb_section'
        );

    }

    public function admin_menu(){

        add_submenu_page(
            'fnsk',
            'パンくずリスト',
            'パンくずリスト',
            'manage_options',
            'fnsk_breadcrumb',
            array( BreadcrumbCallback::class, 'render_page' )
        );

    }

    public function admin_enqueue_scripts(){
        // wp_enqueue_style( 'dashboard.css', THEME_URL . '/css/dashboard.css', array(), filemtime( THEME_DIR . '/css/dashboard.css' ) );
    }

}
