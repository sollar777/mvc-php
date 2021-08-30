<?php

namespace App\Utils;

class View{


    private static $vars = [];

    public static function init($vars = []){
        self::$vars = $vars;
    }

    //retornar o conteudo de uma view
    private static function getContentView($view){
        $file = __DIR__.'/../../resources/view/'.$view.'.html';

        return file_exists($file) ? file_get_contents($file) : ''; 
    }

    // responsavel por retornar o conteudo redenrizado da view
    public static function render($view, $vars = []){
        $contentView = self::getContentView($view);

        //merge de variaveis da view
        $vars = array_merge(self::$vars, $vars);

        //descobrir as chaves do array
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        },$keys);

        return str_replace($keys,array_values($vars),$contentView);
    }
}