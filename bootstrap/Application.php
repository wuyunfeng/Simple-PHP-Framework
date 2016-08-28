<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   Application.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:32
 * Email: wuyunfeng@126.com
 */
class Application
{
    static function run()
    {
        $uri = ltrim($_SERVER['REQUEST_URI'], '/');
        $uri = strtok($uri, '?');
        $paths = explode('/', $uri);
        if ($paths[0] !== "api") {
            throw new RunException(9000, 404, "RequestUri /api/*/*");
        }
        unset($paths[0]);
        if (count($paths) != 2) {
            throw new RunException(9001, 400, "Please Check Your Request Path :" . $_SERVER['REQUEST_URI']);
        }
        $routes = static::defaultRoute();
        $method = $_SERVER['REQUEST_METHOD'];
        if (!array_key_exists($method, $routes)) {
            throw new RunException(9001, 400, "Please Check Your Request Method :" . $_SERVER['REQUEST_METHOD']);
        }
        $methodRoutes = $routes[$method];
        if (array_key_exists($paths[1], $methodRoutes)) {
            $firstLevel = $methodRoutes[$paths[1]];
            if (array_key_exists($paths[2], $firstLevel)) {
                $targetRoute = $firstLevel[$paths[2]];
                $targetRoutine = explode("@", $targetRoute);
                if (empty($targetRoutine)) {
                    throw new RunException(9001, 500, "Server Internal Error");
                }
                if (is_array($targetRoutine) && count($targetRoutine) == 2) {
                    call_user_func(array(new $targetRoutine[0], $targetRoutine[1]));
                } else {
                    throw new RunException(9001, 500, "Server Internal Error");
                }

            } else {
                throw new RunException(9001, 404, "Request path $paths[1]/$paths[2] not exists!");
            }
        } else {
            throw new RunException(9001, 404, "Request path $paths[1] not exists!");
        }
    }

    private static function defaultRoute()
    {
        return include(BASE_PATH . "config/route.php");
    }
}