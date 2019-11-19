<?php

class InfoCallback {

    static function render_page(){
        global $twig;
        echo $twig->render( 'admin/info_page.html' );
    }

    static function render_section(){
        global $twig;
        echo $twig->render( 'admin/info_section.html' );
    }

    static function render_field( $args ){

        //delete_option('fnsk_info');
        // $info = get_option( 'fnsk_info' );

        $info = $args ? $args['info'] : array();

        global $twig;
        echo $twig->render( 'admin/info_field.html', array( 'info' => $info ) );
    }

    static function sanitize($input){

        $count = 0;

        foreach($input as $key => $value){

            foreach($value as $k => $v){

                if( $k==='key' && $v==='' ){
                    $input[$key]['key'] = $count;
                }else{
                    $input[$key][$k] = esc_attr($v);
                }

            }

            $count ++;

        }

        return $input;

    }


}
