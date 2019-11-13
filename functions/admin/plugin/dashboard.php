<?php

require_once THEME_DIR . '/functions/admin/callback/dashboard_callback.php';

class Dashboard implements AdminPage{

    function __construct(){}

    public function admin_init(){

        register_setting(
            'fnsk_dashboard_group',
            'fnsk_dashboard',
            ''
        );

        add_settings_section(
            'fnsk_dashboard_section',
            'トップセクション',
            array(DashboardCallback::class,'render_section'),
            'fnsk'
        );

        add_settings_section(
            'fnsk_dashboard_breadcrumb_section',
            'パンくずリスト',
            array(DashboardCallback::class,'render_section'),
            'fnsk'
        );

        add_settings_field(
            'fnsk_dashboard_field',
            'トップフィールド',
            array(DashboardCallback::class,'render_field'),
            'fnsk',
            'fnsk_dashboard_section'
        );

        add_settings_field(
            'fnsk_dashboard_section_breadcrumb_field',
            '入力してください',
            array(DashboardCallback::class,'render_field'),
            'fnsk',
            'fnsk_dashboard_breadcrumb_section'
        );


    }

    public function admin_menu(){

        add_menu_page(
            'Fnsk',
            'Fnsk',
            'manage_options',
            'fnsk',
            array(DashboardCallback::class,'render_page')
        );

        add_submenu_page(
            'fnsk',
            'ダッシュボード',
            'ダッシュボード',
            'manage_options',
            'fnsk',
            array(DashboardCallback::class,'render_page')
        );

    }

    public function admin_enqueue_scripts(){
        wp_enqueue_style( 'dashboard.css', THEME_URL . '/css/dashboard.css', array(), filemtime( THEME_DIR . '/css/dashboard.css' ) );
    }

}
