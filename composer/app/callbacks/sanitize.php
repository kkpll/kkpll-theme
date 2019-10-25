<?php

namespace App\Callbacks;

use App\Base;

class Sanitize extends Base{

    public function __construct(){
    }

    public function tel($input){
        return $input;
    }

    public function cpt($input){
        return $input;
    }

    public function sanitize($input){
        return $input;
    }


}
