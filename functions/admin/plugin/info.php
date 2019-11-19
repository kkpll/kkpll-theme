<?php

require_once THEME_DIR . '/functions/admin/callback/info_callback.php';

class Info implements AdminPage{

    function __construct(){}

    public function admin_init(){
        register_setting(
            'fnsk_info_group',
            'fnsk_info',
            array(InfoCallback::class,'sanitize')
        );

        add_settings_section(
            'fnsk_info_section',
            '',
            array(InfoCallback::class,'render_section'),
            'fnsk_info'
        );

        if( $info = get_option('fnsk_info') ){

            foreach( (array)$info as $key => $value ){

                add_settings_field(
                    'fnsk_slideshow_field_'.$key,
                    $info[$key]['label'],
                    array(InfoCallback::class,'render_field'),
                    'fnsk_info',
                    'fnsk_info_section',
                    array( 'info' => array('key'=>$info[$key]['key'], 'label'=>$info[$key]['label'], 'value'=>$info[$key]['value']) )
                );

            }

        }else{

            add_settings_field(
                'fnsk_slideshow_field',
                '新しい情報',
                array(InfoCallback::class,'render_field'),
                'fnsk_info',
                'fnsk_info_section',
            );

        }

    }

    public function admin_menu(){

        add_submenu_page(
            'fnsk',
            '基本情報',
            '基本情報',
            'manage_options',
            'fnsk_info',
            array( InfoCallback::class, 'render_page' )
        );

    }

    public function admin_enqueue_scripts(){
        // wp_enqueue_style( 'dashboard.css', THEME_URL . '/css/dashboard.css', array(), filemtime( THEME_DIR . '/css/dashboard.css' ) );
    }

}


//基本情報を取得する関数
function get_info($key,$label=null){
    $info = get_option('fnsk_info');
    if($label==='label'){
        $value = $info[$key]['label'];
    }else{
        $value = $info[$key]['value'];
    }
    return $value;
}
