<?php

namespace App\Callbacks;

class Field extends Template{

    public function dashboard(){
        $template = $this->template;
        echo $template->render( 'dashboard_field.html' );
    }

    public function cpt(){

        $fnsk_cpt_value = get_option('fnsk_cpt_name');

        $template = $this->template;
        echo $template->render( 'cpt_field.html', array('fnsk_cpt_value' => $fnsk_cpt_value) );

    }

    public function redirect(){

        $redirects = get_option( 'fnsk_redirect_name' );

        $template = $this->template;
        echo $template->render( 'redirect_field.html', array( 'redirects' => $redirects ) );
    }

    public function csv(){
        $template = $this->template;
        echo $template->render( 'csv_field.html' );
    }



}
