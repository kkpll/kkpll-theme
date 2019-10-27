<?php

namespace App\Callbacks;

use App\Base;

class Template extends Base{

    public $template;

    public function __construct(){

        parent::__construct();

        //// for twig2.x
        // $loader = new \Twig\Loader\FilesystemLoader( $this->plugin_path . '/template' );
        // $options = array(
        //     'strict_variables' => false,
        //     'debug' => false,
        //     'cache'=> false
        // );
        // $this->template = new \Twig\Environment( $loader, $options );

        $loader = new \Twig\Loader\FilesystemLoader( get_template_directory().'/admin/template' );
        $options = array(
            'strict_variables' => false,
            'debug' => false,
            'cache'=> false
        );
        $this->template = new \Twig\Environment($loader,$options);

        $function = new \Twig\TwigFunction( 'settings_fields', array( $this, 'settings_fields' ) );
        $this->template->addFunction( $function );

        $function = new \Twig\TwigFunction( 'do_settings_sections', array( $this, 'do_settings_sections' ) );
        $this->template->addFunction( $function );

        $function = new \Twig\TwigFunction( 'submit_button', array( $this, 'submit_button' ) );
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
