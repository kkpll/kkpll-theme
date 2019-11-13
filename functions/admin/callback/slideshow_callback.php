<?php

class SlideshowCallback {

    static function render_page(){
        global $twig;
        echo $twig->render( 'admin/slideshow_page.html' );
    }

    static function render_section(){
        global $twig;
        echo $twig->render( 'admin/slideshow_section.html' );
    }

    static function render_field(){
        global $twig;
        echo $twig->render( 'admin/slideshow_field.html' );
    }

    static function sanitize($input){
        return $input;
    }


}
