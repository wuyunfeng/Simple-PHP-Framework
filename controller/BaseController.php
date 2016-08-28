<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   BaseController.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:29
 * Email: wuyunfeng@126.com
 */
class BaseController
{
    function __construct()
    {
        SimpleLogger::formatLog();
    }
}