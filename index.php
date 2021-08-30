<?php

require __DIR__.'/vendor/autoload.php';

use \App\Http\Router;
use App\Utils\View;

define('URL','http://localhost:8081/projeto-mvc');

//define o valor padrao das variaveis
View::init([
    'URL' => URL
]);

$obRouter = new Router(URL);

include __DIR__.'/routes/pages.php';

$obRouter->run()->sendResponse();