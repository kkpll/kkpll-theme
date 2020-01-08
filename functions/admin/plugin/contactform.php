<?php

require_once THEME_DIR . '/functions/admin/callback/contactform_callback.php';

class Contactform implements AdminPage{

    function __construct(){}

    public function admin_init(){

        register_setting(
            'fnsk_contactform_group',
            'fnsk_contactform',
            array(ContactformCallback::class,'sanitize')
        );

        add_settings_section(
            'fnsk_contactform_section',
            '',
            array(ContactformCallback::class,'render_section'),
            'fnsk_contactform'
        );

        if( $contactforms = get_option('fnsk_contactform') ){

            foreach( (array)$contactforms as $key => $value ){

                add_settings_field(
                    'fnsk_contactform_field_'.$key,
                    $contactforms[$key]['label'],
                    array(ContactformCallback::class,'render_field'),
                    'fnsk_contactform',
                    'fnsk_contactform_section',
                    array( 'contactform' => array('id'=>$key, 'label'=>$contactforms[$key]['label'], 'form'=>$contactforms[$key] ) )
                );

            }

        }else{

            add_settings_field(
                'fnsk_slideshow_field',
                '新しいお問い合わせフォーム',
                array(ContactformCallback::class,'render_field'),
                'fnsk_contactform',
                'fnsk_contactform_section',
            );

        }

    }

    public function admin_menu(){

        add_submenu_page(
            'fnsk',
            'お問い合わせ',
            'お問い合わせ',
            'manage_options',
            'fnsk_contactform',
            array( ContactformCallback::class, 'render_page' )
        );

    }

    public function admin_enqueue_scripts(){
        wp_enqueue_script( 'contactform.js', THEME_URL.'/js/admin/contactform.js', array('jquery-ui-draggable'), filemtime( THEME_DIR.'/js/admin/contactform.js' ), true);
    }

}
