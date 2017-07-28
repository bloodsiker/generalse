<?php

namespace Umbrella\components;

/**
 * Class Router
 */
class Router
{
    private $routes;
    private $uri;

    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    /**
     * Returns request string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        // Получить строку запроса
        if(empty($this->getURI())){
            $this->uri = 'main';
        } else {
            $this->uri = $this->getURI();
        }

        // Проверить наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path) {

            // Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $this->uri)) {
                
                // Получаем внутренний путь из внешнего согласно правилу.
                $internalRoute = preg_replace("~$uriPattern~", $path, $this->uri);
                                
                // Определить контроллер, action, параметры

                $segments = explode('@', $internalRoute);

                $arrayControllerName = explode('/', array_shift($segments));

                $controllerName = implode('/', $arrayControllerName) . 'Controller';


                $stringSegments = implode($segments);

                $actionSegments = explode('/', $stringSegments);
                $actionName = 'action' . ucfirst(array_shift($actionSegments));

                $parameters = $actionSegments;


                // Подключить файл класса-контроллера
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                $controllerName = str_replace('/', '\\', $controllerName);
                $newControllerName = 'Umbrella\\controllers\\' . $controllerName;

                // Создать объект, вызвать метод (т.е. action)
                $controllerObject = new $newControllerName();

                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                
                if ($result != null) {
                    break;
                }
            }
        }
    }

}
