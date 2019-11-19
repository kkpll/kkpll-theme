<?php

class BreadcrumbCallback {

    static function render_page(){
        global $twig;
        echo $twig->render( 'admin/breadcrumb_page.html' );
    }

    static function render_section(){
        global $twig;
        echo $twig->render( 'admin/breadcrumb_section.html' );
    }

    static function render_field(){

        //delete_option('fnsk_breadcrumb');
        // $breadcrumb = get_option( 'fnsk_breadcrumb' );

        global $twig;
        echo $twig->render( 'admin/breadcrumb_field.html' );
    }

    static function sanitize($input){
        return $input;
    }


}
