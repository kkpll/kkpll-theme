<?php

namespace App\Callbacks;

class Page extends Template{

    public function dashboard(){
         $template = $this->template;
         echo $template->render( 'dashboard.html' );
    }

    public function cpt(){
        $template = $this->template;
        echo $template->render( 'cpt.html' );
    }

    public function redirect(){
        $template = $this->template;
        echo $template->render( 'redirect.html' );
    }




}
