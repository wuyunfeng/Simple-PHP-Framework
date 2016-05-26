<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   MobileAPIController.class.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:19
 * Email: wuyunfeng@126.com
 */
class MobileAPIController extends BaseController
{

    //do request request log
    function __construct()
    {
        parent::__construct();
    }

    function executePrintGetAction()
    {
        Response::make(array(
            'format' => Response::FORMAT_JSON,
            'response' => $_GET
        ));
    }

    function executePrintPostAction()
    {
        Response::make(array(
            'format' => Response::FORMAT_JSON,
            'response' => $_GET
        ));
    }

}