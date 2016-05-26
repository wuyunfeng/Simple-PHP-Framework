<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 15/12/25
 * Time: 下午9:27
 */
date_default_timezone_set('Asia/Shanghai');
require_once "../include/class/PlainException.php";

try {
    APP::run();
} catch (\BMException $exp) {
    echo json_encode($exp);
}

class APP
{
    static function run()
    {
        $uri = ltrim($_SERVER['REQUEST_URI'], '/');
        $uri = strtok($uri, '?');
        $paths = explode('/', $uri);
        if ($paths[0] !== "v1") {
            throw new BMException(9000, 404, "Request Version is wrong!");
        }
        unset($paths[0]);
        if (count($paths) != 2) {
            throw new BMException(9001, 400, "Please Check Your Request Path :" . $_SERVER['REQUEST_URI']);
        }
        $routes = include("../config/route.php");
        $method = $_SERVER['REQUEST_METHOD'];
        //bug fixed in future !  get Get GET
        if (!array_key_exists($method, $routes)) {
            throw new BMException(9001, 400, "Please Check Your Request Method :" . $_SERVER['REQUEST_METHOD']);
        }
        $methodRoutes = $routes[$method];
        if (array_key_exists($paths[1], $methodRoutes)) {
            $firstLevel = $methodRoutes[$paths[1]];
            if (array_key_exists($paths[2], $firstLevel)) {
                $targetRoute = $firstLevel[$paths[2]];
                $targetRoutine = explode("@", $targetRoute);
                if (empty($targetRoutine)) {
                    throw new BMException(9001, 500, "Server Internal Error");
                }
                if (is_array($targetRoutine) && count($targetRoutine) == 2) {
                    require_once "../include/controller/$targetRoutine[0].php";
//                    header("Content-Type:application/json");
                    call_user_func(array(new $targetRoutine[0], $targetRoutine[1]));
                } else {
                    throw new BMException(9001, 500, "Server Internal Error");
                }

            } else {
                throw new BMException(9001, 404, "Request path $paths[1]/$paths[2] not exists!");
            }
        } else {
            throw new BMException(9001, 404, "Request path $paths[1] not exists!");
        }
    }
}

function __autoload($classname)
{
    $classpath = "include/" . "controller/" . $classname . ".php";
    if (file_exists($classpath)) {
        require_once $classpath;
        return;
    }
    $classpath = "include/" . "class/" . $classname . ".php";
    if (file_exists($classpath)) {
        require_once $classpath;
        return;
    }
    throw new BMException(9002, 500, "Server Error: autoload class failure!");
}