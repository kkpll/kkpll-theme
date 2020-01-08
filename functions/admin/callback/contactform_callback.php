<?php

class ContactformCallback {

    static function render_page(){
        global $twig;
        echo $twig->render( 'admin/contactform_page.html' );
    }

    static function render_section(){
        global $twig;
        echo $twig->render( 'admin/contactform_section.html' );
    }

    static function render_field( $args ){

        //delete_option('fnsk_contactform');
        // $info = get_option( 'fnsk_contactform' );

        $contactform = $args ? $args['contactform'] : array();

        global $twig;
        echo $twig->render( 'admin/contactform_field.html', array( 'info' => $contactform ) );
    }

    static function sanitize($input){

        // $count = 0;
        //
        // foreach($input as $key => $value){
        //
        //     foreach($value as $k => $v){
        //
        //         if( $k==='key' && $v==='' ){
        //             $input[$key]['key'] = $count;
        //         }else{
        //             $input[$key][$k] = esc_attr($v);
        //         }
        //
        //     }
        // 
        //     $count ++;
        //
        // }

        return $input;

    }


}
