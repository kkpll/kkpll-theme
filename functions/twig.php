<?php

/*
 *
 * テンプレート初期化
 *
 */

class Template{

    static $twig;

    static function register(){

        if( !self::$twig ){
            require_once( THEME_DIR.'/lib/twig/lib/Twig/Autoloader.php' );
            Twig_Autoloader::register();
        }

        $loader = new Twig_Loader_Filesystem( THEME_DIR . '/template' );

        self::$twig = new Twig_Environment($loader);

        //定数アクセス無効化
        $function = new Twig_SimpleFunction( 'constant', function() { return false; } );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'wp_head', 'wp_head' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'wp_footer', 'wp_footer' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'pagination', 'pagination' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'settings_fields', 'settings_fields' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'do_settings_sections', 'do_settings_sections' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'submit_button', 'submit_button' );
        self::$twig->addFunction( $function );

        return self::$twig;

    }

}

$twig = Template::register();
