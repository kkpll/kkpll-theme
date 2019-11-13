<?php

class DashBoardCallback {

    static function render_page(){
        global $twig;
        echo $twig->render( 'admin/dashboard_page.html' );
    }

    static function render_section(){
        global $twig;
        echo $twig->render( 'admin/dashboard_section.html' );
    }

    static function render_field(){
        global $twig;
        echo $twig->render( 'admin/dashboard_field.html' );
    }

    static function sanitize($input){
        return $input;
    }


}
