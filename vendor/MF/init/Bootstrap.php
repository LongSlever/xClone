<?php
// classe abstrata pra criar uma segurança maior e responsabilizar
// basicamente uma interface, uma assinatura.
namespace MF\Init;

abstract class Bootstrap {
    private $routes;

    abstract protected function initRoutes();

    public function __construct() {
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    /**
     * Get the value of routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Set the value of routes
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    protected function run($url) {
        foreach ($this->getRoutes() as $key => $route) {

            if($url == $route['route'] ) {
                $class = "App\\Controllers\\".ucfirst($route['controller']);
                $controller = new $class;
                $action = $route['action'];
                $controller->$action();
            }
        }
    }

    
    protected function getUrl() {
        // super global service pra ser a URL que estamos, o que queremos para ver
        // a URL é o REQUEST_URI
        return  parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}

?>