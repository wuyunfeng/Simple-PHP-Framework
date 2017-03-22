<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   autoload.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:36
 * Email: wuyunfeng@126.com
 */
class autoload
{
    static function classMap()
    {
        return array(
            BASE_PATH . 'bootstrap/',
            BASE_PATH . 'controller/',
            BASE_PATH . 'include/',
            BASE_PATH . 'include/class/',
            BASE_PATH . 'view/',
            BASE_PATH . 'model/',
            BASE_PATH . 'component/'
        );
    }

    static function loadClass($className)
    {
        $fileName = $className . '.php';
        foreach (static::classMap() as $path) {
            if (file_exists($path . $fileName)) {
                include_once $path . $fileName;
                // Check to see whether the include declared the class
                if (!class_exists($className, false)) {
                    include_once BASE_PATH . 'include/class/' . 'RunException.php';
                    throw new RunException(9002, 500, "Server Error: autoload class failure!");
                }
                return;
            }
        }
        include_once BASE_PATH . 'include/class/' . 'RunException.php';
        throw new RunException(9002, 500, "Server Error: autoload class failure!");
    }

    public static function exceptionHandler($exception)
    {
        echo json_encode($exception);
    }
}