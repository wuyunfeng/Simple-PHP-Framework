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
        $classMap = array(
            BASE_PATH . 'bootstrap/',
            BASE_PATH . 'controller/',
            BASE_PATH . 'include/',
            BASE_PATH . 'include/class/',
            BASE_PATH . 'view/',
            BASE_PATH . 'model/'
        );
        $fileName = $className . '.class.php';
        foreach ($classMap as $path) {
            if (file_exists($path . $fileName)) {
                include_once $path . $fileName;
                return;
            }
        }
        include_once BASE_PATH . 'include/class/' . 'RunException.class.php';
        throw new RunException(9002, 500, "Server Error: autoload class failure!");
    }
}