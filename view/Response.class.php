<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   Response.class.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 下午2:39
 * Email: wuyunfeng@126.com
 */
class Response
{
    const FORMAT_JSON = 0x00;
    const FORMAT_PB = 0x01;
    const FORMAT_HTML = 0x02;

    static function make($response)
    {
        if (is_array($response)) {
            if (isset($response['format'])) {
                if ($response['format'] === Response::FORMAT_JSON) {
                    if (isset($response['response'])) {
                        $content = $response['response'];
                        if (is_array($content)) {
                            echo json_encode($content);
                            return;
                        }
                    }
                }
            }
        } elseif (is_string($response)) {
            echo $response;
            return;
        } elseif (is_callable($response)) {
            $resp = call_user_func($response);
            echo $resp;
        }
    }
}