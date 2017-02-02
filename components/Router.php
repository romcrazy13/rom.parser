<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    public static function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        // Получить строку запроса
        $uri = $this->getURI();
//addNotes("<br>uri = $uri");
        $segments = preg_split("~/~", $uri);
        $i = 0;
        foreach ($segments as $item) {
            $params[$i] = $item;
            $urlPart[$i] = $item;
//addNotes("urlPart[$i] = " . $urlPart[$i]);
            $i++;
        }

        if ($uri == '') { $uri = "#root"; }

        foreach ($this->routes as $uriPattern => $path) {

            if (preg_match("~$uriPattern~", $uri)) {

//addNotes("if_uri = " . $uri);
//addNotes("if_uriPattern = " . $uriPattern);
//addNotes("if_path = " . $path);
                $segmentsPath = preg_split("~/~", $path);
                $controllerName = ucfirst($segmentsPath[0]) . 'Controller';
//addNotes("controllerName = " . $controllerName . "<br>");
                $actionName = 'action' . ucfirst($segmentsPath[1]);
//addNotes("actionName = " . $actionName . "<br>");

                // Подключаем файл класса Controller
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
//addNotes("controllerFile = " . $controllerFile . "<br>");

                if (file_exists($controllerFile)) {
                    include_once($controllerFile);

                    // Создаем объект, вызываем метод (т.е. action)
                    $controllerObject = new $controllerName;

                    if (isset($params)) {
                        call_user_func_array(array($controllerObject, $actionName), $params);
                    } else {
                        $controllerObject->$actionName();
                    }
                }
//addNotes("actionName = " . $actionName . "<br>");
                break;
            }
        }
    }
}