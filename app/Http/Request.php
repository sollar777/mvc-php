<?php

namespace App\Http;

class Request{

    //metodo http da requisição
    private $httpMethod;

    //uri da pagina
    private $uri;

    //parametros da url ($_GET)
    private $queryParams = [];

    //variaveis recebida no post ($_POST)
    private $postVars = [];

    //cabechalho da requisição
    private $headers = [];

    public function __construct()
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->header = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    }

    public function getHttpMethod(){
        return $this->httpMethod;
    }

    public function getUri(){
        return $this->uri;
    }

    public function getHeaders(){
        return $this->headers;
    }

    public function getQueryParams(){
        return $this->queryParams;
    }

    public function getPostVars(){
        return $this->postVars;
    }

}