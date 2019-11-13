<?php

require_once THEME_DIR . '/functions/admin/callback/slideshow_callback.php';

class Slideshow implements AdminPage{

    function __construct(){}

    public function admin_init(){
        register_setting(
            'fnsk_slideshow_group',
            'fnsk_slideshow',
            array(SlideshowCallback::class,'sanitize')
        );

        add_settings_section(
            'fnsk_slideshow_section',
            'スライドショーセクション',
            array(SlideshowCallback::class,'render_section'),
            'fnsk_slideshow'
        );

        add_settings_field(
            'fnsk_slideshow_field',
            'スライドショーフィールド',
            array(SlideshowCallback::class,'render_field'),
            'fnsk_slideshow',
            'fnsk_slideshow_section'
        );

    }

    public function admin_menu(){

        add_submenu_page(
            'fnsk',
            'スライドショー',
            'スライドショー',
            'manage_options',
            'fnsk_slideshow',
            array( SlideshowCallback::class, 'render_page' )
        );

    }

    public function admin_enqueue_scripts(){
        // wp_enqueue_style( 'dashboard.css', THEME_URL . '/css/dashboard.css', array(), filemtime( THEME_DIR . '/css/dashboard.css' ) );
    }

}
