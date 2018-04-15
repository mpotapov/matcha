<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = include (ROOT . '/config/routess.php');
    }

    public static function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI']))
            return trim($_SERVER['REQUEST_URI'], '/');
    }

    public function run()
    {
        $uri = $this->getURI();
        $isPage = 0;

        foreach ($this->routes as $URIpattern => $path)
        {
            if (preg_match("~$URIpattern~", $uri))
            {
                $router = preg_replace("~$URIpattern~", $path, $uri);

                $parts = explode('/', $router);

                $controllerName = ucfirst(array_shift($parts).'Controller');
                $actionName = 'action'.ucfirst(array_shift($parts));

                $parametrs = $parts;

                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    include_once ($controllerFile);
                }

                $controllerObj = new $controllerName;
                $result = call_user_func_array(array($controllerObj, $actionName), $parametrs);

                if ($result != null || $actionName == 'actionParseMarkers') {
                    $isPage = 1;
                    break;
                }
            }
        }

        if ($isPage == 0 && !empty($_SESSION))
            header('location: /profile/' . $_SESSION['userName'] . '/');
        else if ($isPage == 0)
            header('location: /authorization/login/');
    }
}