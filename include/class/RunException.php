<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   RunException.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:25
 * Email: wuyunfeng@126.com
 */
class RunException extends RuntimeException implements JsonSerializable
{
    protected $message;
    protected $code;
    protected $statusCode;

    function __construct($statusCode, $code, $message)
    {
        $this->message = $message;
        $this->code = $code;
        $this->statusCode = $statusCode;
    }

    function jsonSerialize()
    {
        return array(
            "code" => $this->code,
            "statusCode" => $this->statusCode,
            "message" => $this->message
        );
    }
}