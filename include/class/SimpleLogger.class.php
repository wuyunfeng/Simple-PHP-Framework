<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   SimpleLogger.class.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:25
 * Email: wuyunfeng@126.com
 */
class SimpleLogger
{
    public static function formatLog()
    {
        $prefix = date("Y_m_d");
        $file = "logs/" . $prefix . "_log.txt";
        $logFile = BASE_PATH . $file;
        if (!file_exists($logFile)) {
            touch($logFile);
            chmod($logFile,0666);
        }
        $method = $_SERVER['REQUEST_METHOD'];
        $post_content = '';
        if (isset($_SERVER['CONTENT_TYPE'])) {
            if (!empty($_POST) & $_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded') {
                $post_content = @file_get_contents("php://input");
            } elseif (strstr($_SERVER['CONTENT_TYPE'], "multipart/form-data")) {
                if (!empty($_POST)) {
                    foreach ($_POST AS $key => $value)
                        $post_content .= $key . "=" . $value . " ";
                }
                if (!empty($_FILES)) {
                    //nothing
                }
            } else {
                $post_content = 'no data';
            }
        }
        $_SERVER['REQUEST_URI'];
        $recordLog = date("Y-m-d H:i:s") . "\t" . $_SERVER['REMOTE_ADDR'] .
            "\t" . $method . ":" . $_SERVER['REQUEST_URI'] . "\t" . $post_content . "\n";
        @file_put_contents($logFile, $recordLog, FILE_APPEND);
    }
}