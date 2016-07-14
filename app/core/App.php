<?php

    class App
    {
        protected $controller = "Home"; /*Valor padrao 'home' significa se a url for vazia, ele assumi
                                        o valor padrão para controller'*/
        protected $method = "index"; /*Mostra qual metodo será executado e o método
                                     padrão será 'index', se for a url for vazia ou não*/
        protected $param = []; /*Mostrará quais os parâmetros que serão passados para função que será executada*/

        public function __construct()
        {
            $url = self::ParseUrl($_GET["url"]);
            if(file_exists("app/controllers/" .$url[0]. ".php")){
               $this->controller = $url[0];
               unset($url[0]);
            }

            require_once "app/controllers/" .$this->controller. ".php";
            $this->controller = new $this->controller; //atualizando  controller
            if(isset($url[1]) && method_exists($this->controller, $url[1]))
            {
                $this->method = $url[1];
                unset($url[1]);
            }
            $this->param = $url ? array_values($url): [];
            call_user_func_array([$this->controller, $this->method], $this->param);
        }

        private function ParseUrl($url)
        {
            return explode("/", filter_var(rtrim($url), FILTER_SANITIZE_URL)); //rtrim: remove espaços em branco
        }
    }