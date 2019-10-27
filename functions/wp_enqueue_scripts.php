<?php

/*
 *
 * CSSとJAVASCRIPTファイルの読み込み
 *
 */

add_action( 'wp_enqueue_scripts', 'my_wp_enqueue_scripts' );
function my_wp_enqueue_scripts(){

    // //CSS
    wp_enqueue_style( 'style.css', get_stylesheet_uri() );
    // wp_enqueue_style( 'bootstrap.css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css' );
    // wp_enqueue_style( 'fontawesome.css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    // wp_enqueue_style( 'my-style.css', get_template_directory_uri() . '/assets/css/style.css', array('bootstrap.css','fontawesome.css'), filemtime(get_template_directory() . '/assets/css/style.css'));
    // wp_enqueue_style( 'o-1910.css', get_template_directory_uri() . '/assets/css/o-1910.css');

    //JAVASCRIPT
    global $wp_scripts;
    $jquery = $wp_scripts->registered['jquery-core'];
    $jq_ver = $jquery->ver;
    $jq_src = $jquery->src;
    wp_deregister_script( 'jquery' );
    wp_deregister_script( 'jquery-core' );
    wp_register_script( 'jquery', false, array('jquery-core'), $jq_ver, true );
    wp_register_script( 'jquery-core', $jq_src, array(), $jq_ver, true );
    // wp_enqueue_script('popper.min.js',"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js",array('jquery'),$jq_ver,true);
    // wp_enqueue_script('bootstrap.min.js',"https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js",array('popper.min.js'),$jq_ver,true);
    // wp_enqueue_script('sliderPro.min.js',get_template_directory_uri()."/assets/js/jquery.sliderPro.min.js",array('bootstrap.min.js'),$jq_ver,true);
    // wp_enqueue_script('grace.js',get_template_directory_uri()."/assets/js/grace.js",array('bootstrap.min.js'),filemtime(get_template_directory()."/assets/js/grace.js"),true);
    // wp_enqueue_script('sidemenu.js',get_template_directory_uri()."/assets/js/sidemenu.js",array('jquery'),filemtime(get_template_directory()."/assets/js/sidemenu.js"),true);

}
