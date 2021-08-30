<?php

namespace App\Controller\Pages;

use App\Model\Entity\Organization;
use App\Utils\View;

class About extends Page{

    public static function getAbout(){

        $organization = new Organization();

        $content = View::render('pages\about', [
            "name" => $organization->name,
            "site" => $organization->site
        ]);

        return parent::getPage('DEV - MVC', $content);

    }

}