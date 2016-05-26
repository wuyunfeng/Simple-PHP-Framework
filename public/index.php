<?php
/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   index.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:33
 * Email: wuyunfeng@126.com
 */
date_default_timezone_set('Asia/Shanghai');
define('BASE_PATH', dirname(__DIR__) . "/");
define('CONTROLLER_PATH', BASE_PATH . "controller" . "/");
require_once BASE_PATH . "bootstrap/autoload.class.php";
spl_autoload_register(array('autoload', 'loadClass'));

try {
    Application::run();
} catch (\RunException $exception) {
    echo json_encode($exception);
}