<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   autoload.class.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:36
 * Email: wuyunfeng@126.com
 */
class autoload
{

    static function loadClass($className)
    {
        $fileName = $className . '.class.php';
        $bootstrap_dir = __DIR__ . "/";
        $config_dir = BASE_PATH . 'config/';
        $controller_dir = BASE_PATH . 'controller/';
        $include_dir = BASE_PATH . 'include/';
        $include_class_dir = BASE_PATH . 'include/' . 'class/';
        if (file_exists($bootstrap_dir . $fileName)) {
            include_once($bootstrap_dir . $fileName);
        } elseif (file_exists($config_dir . $fileName)) {
            include_once($config_dir . $fileName);
        } elseif (file_exists($controller_dir . $fileName)) {
            include_once $controller_dir . $fileName;
        } elseif (file_exists($include_dir . $fileName)) {
            include_once $include_dir . $fileName;
        } elseif (file_exists($include_class_dir . $fileName)) {
            include_once $include_class_dir . $fileName;
        } else {
            include_once $include_class_dir . 'RunException.class.php';
            throw new RunException(9002, 500, "Server Error: autoload class failure!");
        }

    }
}