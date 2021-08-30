<?php

namespace App\Controller\Pages;

use \App\Utils\View;


// retornar o conteudo gererico que vai repetir em todas as paginas
class Page{

    //responsavel por redenrizar o header das paginas
    private static function getHeader(){
        return View::render('pages/header');
    }

    private static function getFooter(){
        return View::render('pages/footer');
    }

    //responsavel por retornar a view com as variaveis preenchidas
    public static function getPage($title, $content){
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }

}

