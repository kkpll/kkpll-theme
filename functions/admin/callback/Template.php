<?php

class Template{

    public $template;

    public function __construct(){

        require_once( THEME_DIR . '/twig/lib/Twig/Autoloader.php');

        Twig_Autoloader::register();

        $loader = new Twig_Loader_Filesystem( THEME_DIR . '/template' );

        $this->template = new Twig_Environment($loader);

        $function = new Twig_SimpleFunction( 'constant', function() { return false; } );
        $this->template->addFunction( $function );

        $function = new Twig_SimpleFunction( 'wp_head', 'wp_head' );
        $this->template->addFunction( $function );

        $function = new Twig_SimpleFunction( 'wp_footer', 'wp_footer' );
        $this->template->addFunction( $function );

        $function = new Twig_SimpleFunction( 'pagination', 'pagination' );
        $this->template->addFunction( $function );

    }

    public function settings_fields( $group ){
        settings_fields( $group );
    }

    public function do_settings_sections( $page ){
        do_settings_sections( $page );
    }

    public function submit_button( $args=array() ){
        submit_button( $args );
    }

}

$twig = new Template();
