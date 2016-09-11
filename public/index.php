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
define('DEBUG', true);
define('START_TIME', microtime(true));
require_once BASE_PATH . "bootstrap/autoload.php";
spl_autoload_register(array('autoload', 'loadClass'));
set_exception_handler(array('autoload', 'exceptionHandler'));
Application::run();