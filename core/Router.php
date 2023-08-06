<?php

namespace Core;

use Exception;

/**
 *
 */
class Router
{
    /**
     * Routes array.
     *
     * @var array
     */
    private static $routes = [];

    /**
     * Route
     *
     * @var array
     */
    private static $route = [];

    /**
     * @param $regExp
     * @param $route
     * @return void
     */
    public static function add($regExp, $route = [])
    {
        self::$routes[$regExp] = $route;
    }

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * @return array
     */
    public static function getRoute()
    {
        return self::$route;
    }

    /**
     * Match url to route
     *
     * @param $url
     * @return bool
     */
    public static function matchRoute($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if(preg_match("#$pattern#i", $url, $matches)){
                foreach($matches as $key => $value){
                    if (is_string($key)) {
                        $route[$key] = $value;
                    }
                }
                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }
                if (!isset($route['prefix']))$route['prefix'] = '';
                else $route['prefix'] .= '\\';
                $route['controller'] = upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * Create controller object
     *
     * @throws Exception
     */
    public static function dispatch($url)
    {

        $url = self::removeQueryString($url);
        if(self::matchRoute($url)){
            $controller = 'App\Controllers\\' . self::$route['prefix'] . upperCamelCase(self::$route['controller'] . 'Controller');
            if (class_exists($controller)) {
                $cObj = new $controller(self::$route);
                $action = lowerCamelCase(self::$route['action']).'Action';
                if (method_exists($cObj, $action)) {
                    $cObj->$action();
                } else {
                    throw new Exception("Action <b>$controller::$action</b> not found", 404);
                }
            } else {
                throw new Exception("Controller <b>$controller</b> not found", 404);
            }
        } else {

            throw new Exception("Page not found", 404);
        }
    }

    /**
     * @param $url
     * @return mixed|string
     */
    private static function removeQueryString($url)
    {
        if ($url) {
            $params = explode('?', $url, 2);
            return !strpos($params[0], '=') ? rtrim($params[0], '/') : '';
        }
        return $url;
    }
}