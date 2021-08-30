<?php

namespace App\Utils;

class Debug{

    public static function debug($variavel){
        echo "<pre>";
        print_r($variavel);
        echo "</pre>";
        exit;
    }
}