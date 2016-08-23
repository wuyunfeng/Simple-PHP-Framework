<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   HttpClient.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 上午11:228
 * Email: wuyunfeng@126.com
 */
class HttpClient
{
    static function executeHttpGet($url, $params = array())
    {
        if (empty($url))
            return false;
//        $trans = array();
//        foreach ($params as $key => $value) {
//            $trans[] = "$key=$value";
//        }
//        $query = implode("&", $trans);
        $query = http_build_query($params);
        if (strlen($query)) {
            $url .= '?' . $query;
        }
        $get_session = curl_init();
        curl_setopt($get_session, CURLOPT_URL, $url);
        curl_setopt($get_session, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($get_session, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($get_session, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($get_session);
        curl_close($get_session);
        return $data;
    }


    static function executeHttpPost($url, $params = array())
    {
        if (empty($url))
            return null;
        $postData = implode("&", $params);
        $urlSession = curl_init();
        curl_setopt($urlSession, CURLOPT_URL, $url);
        curl_setopt($urlSession, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($urlSession, CURLOPT_POST, 1);
        curl_setopt($urlSession, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($urlSession, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($urlSession, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($urlSession);
        curl_close($urlSession);
        return $data;
    }

    static function executeStreamContextPost($url, $params = array())
    {
        if (empty($url))
            return null;
        $postData = implode("&", $params);
        $postContext = array(
            'http' => array
            (
                'method' => 'POST',
                'header' => "Content-Type:  application/x-www-form-urlencoded\r\n",
                'content' => $postData
            )
        );
        $ctx = stream_context_create($postContext);
        $response = file_get_contents($url, false, $ctx);
        return $response;
    }
}