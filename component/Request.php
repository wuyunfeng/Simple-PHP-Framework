<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   Request.php
 * Author: wuyunfeng
 * Date: 16/6/3
 * Time: 下午4:07
 * Email: wuyunfeng@126.com
 */

/**
 * Class Request
 *
 * 获取用户请求数据
 *
 */
class Request implements ArrayAccess, Iterator
{
    private static $request;

    private $params = [];
    private $index = 0;
    private $datas = [];

    public static function getInstance()
    {
        if (!static::$request) {
            static::$request = new static;
        }
        return static::$request;
    }

    private function __construct()
    {
        $this->index = 0;
        $this->params = array_merge($_GET, $_POST);
        foreach ($this->params as $value) {
            $this->datas[] = $value;
        }
    }

    /**
     *
     * 校验所请求参数
     *
     * @param array $rules
     */
    public function validateParams($rules = [])
    {

    }

    /**
     *
     * 获取请求的CGI参数
     * ```php
     *
     * $_GET = ['key1' => 'value1', 'key2' => 'value2'];
     * $_POST = ['key2' = 'value3', 'key4' => 'value4'];
     * $request = Request::getInstance();
     * $request->getRequestParameter('key1') = value1;
     * $request->getRequestParameter('key2') = value3;
     * $request->getRequestParameter('key4') = value4;
     *
     * ```
     *
     * @param string $key Get 或 Post 请求参数 key 值
     * @return bool|string 返回获取的Get请求或Post请求参数,如果不存在则返回false
     */
    public function getRequestParameter($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return false;
    }

    /**
     * @return array 获取Get 和 Post请求的所有参数
     */
    public function getRequestParameters()
    {
        return $this->params;
    }

    /**
     * @param string $headerKey HTTP请求头key值
     * @return string mixed   HTTP请求头Value值
     */
    public function getRequestHeader($headerKey)
    {
        return $_SERVER[$headerKey];
    }

    /**
     * @return mixed 全部的HTTP请求头
     */
    public function getRequestHeaders()
    {
        return $_SERVER;
    }


    public function offsetExists($offset)
    {
        if (isset($this->params[$offset])) {
            return true;
        }
        return false;
    }


    public function offsetGet($offset)
    {
        if (isset($this->params[$offset])) {
            return $this->params[$offset];
        }
        return false;
    }

    public function offsetSet($offset, $value)
    {
        $this->params[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->params[$offset]);
    }

    public function current()
    {
        return $this->datas[$this->index];
    }


    public function next()
    {
        $this->index++;
    }


    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->datas[$this->index]);
    }

    public function rewind()
    {
        $this->index = 0;
    }
}