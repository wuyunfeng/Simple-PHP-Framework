<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   route.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:19
 * Email: wuyunfeng@126.com
 */
return array(
    "GET" => array(
        "list" => array(
            "get" => "MobileAPIController@executePrintGetAction",
        )
    ),
    "POST" => array(
        "list" => array(
            "post" => "MobileAPIController@executePrintPostAction",
        )
    ),
);