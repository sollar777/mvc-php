<?php

namespace App\Http;

use Closure;
use Exception;
use \App\Utils\Debug;
use ReflectionFunction;

class Router{

    private $url = '';

    private $prefix = '';

    private $routes = [];

    private $request;

    public function __construct($url)
    {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    private function setPrefix(){
        $parseUrl = parse_url($this->url);

        $this->prefix = $parseUrl['path'] ?? '';
    }

    private function addRoute($method, $route, $params = []){
        // validação dos parametros
        foreach($params as $key=>$value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // variaveis passado pela rota no url
        $params['variables'] = [];

        //padrao de validacao das variaveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable,'(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        // padrao de validação de url com regex
        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';

        //adiciona a rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;
    }

    public function get($route, $params = []){
        return $this->addRoute('GET', $route, $params);
    }

    public function post($route, $params = []){
        return $this->addRoute('POST', $route, $params);
    }

    public function put($route, $params = []){
        return $this->addRoute('PUT', $route, $params);
    }

    public function delete($route, $params = []){
        return $this->addRoute('DELETE', $route, $params);
    }


    private function getUri(){
        //uri da request
        $uri = $this->request->getUri();

        //fatia a uri com o prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];

        //retorna o ultimo resultado da array que é o valor sem o prefixo da uri
        return end($xUri);
    }

    private function getRoute(){
        $uri = $this->getUri();

        $httpMethod = $this->request->getHttpMethod();

        foreach($this->routes as $patternRoute=>$methods){
            if(preg_match($patternRoute,$uri,$matches)){
               if($methods[$httpMethod]){
                    //remover a primeira posição do array
                    unset($matches[0]);

                    //chves das variaveis
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys,$matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;


                    return $methods[$httpMethod];
               }

               throw new Exception("Método não permitido", 405);
            }
        }

        throw new Exception("URL não encontrada", 404);

    }

    public function run(){
        try{
            $route = $this->getRoute();
            
            // verifica se o controlador existe
            if(!isset($route['controller'])){
                throw new Exception("A URL não pode ser processada", 500);
            }

            $args = [];

            //reflection php
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter){
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return call_user_func_array($route['controller'], $args);

        }catch(Exception $e){
            return new Response($e->getCode(), $e->getMessage());
        }
    }

}