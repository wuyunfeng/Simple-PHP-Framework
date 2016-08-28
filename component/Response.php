<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   Response.php
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

    /**
     *  根据FORMAT选择响应客户端格式
     *
     * @param string $response 返回客户端响应
     */
    static function make($response)
    {
        ob_start();
        $responseContent = '';
        if (is_array($response)) {
            if (isset($response['format'])) {
                if ($response['format'] === Response::FORMAT_JSON) {
                    header("Content-Type: application/json");
                    if (isset($response['response'])) {
                        $content['error_no'] = 10000;
                        $content['error_msg'] = 'success';
                        $content = $response['response'];
                        if (is_array($content)) {
                            $responseContent = json_encode($content);
                        } elseif (is_object($content) && ($content instanceof JsonSerializable)) {
                            $responseContent = $response;
                        }
                    }
                }
            }
        } elseif (is_string($response)) {
            header("Content-Type: text/plain");
            $responseContent = $response;
        } elseif (is_callable($response)) {
            header("Content-Type: text/plain");
            $responseContent = call_user_func($response);
        }
        header("Content-Length: " . strlen($responseContent));
        echo $responseContent;
        ob_end_flush();
    }
}