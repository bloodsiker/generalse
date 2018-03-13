<?php

namespace Umbrella\components;

/**
 * Class Router
 */
class Router
{

    /**
     * @var string
     */
    private $directory;

    /**
     * @var array|mixed
     */
    private $routes = [];

    /**
     * @var
     */
    private $uri;

    /**
     * @var array
     */


    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->directory   = ROOT . '/routes/';
        $arrayRoutes = $this->dirToArray($this->directory);

        foreach ($arrayRoutes as $file){
            $routesPath = $this->directory . $file;
            $this->routes += include($routesPath);
        }
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


    /**
     * run routig
     */
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

                //Если последняя ошибка в файле Router, показываем 404
                $last_error = error_get_last();
                if($last_error){
                    $pathFileError = explode('/', $last_error['file']);
                    $fileError = end($pathFileError);
                    if($last_error['type'] == 2 && $fileError == 'Router.php'){
                        \Josantonius\Url\Url::redirect('/ru/404');
                    }
                }

                if ($result != null) {
                    break;
                }
            }
        }
    }


    /**
     * Scan dir
     * @param $dir
     * @return array
     */
    public function dirToArray($dir) {

        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value)
        {
            if (!in_array($value,array(".","..")))
            {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
                {
                    $result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                }
                else
                {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
}
