<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   StackTrace.php
 * Author: wuyunfeng
 * Date: 16/5/26
 * Time: 下午2:03
 * Email: wuyunfeng@126.com
 */
class StackTrace
{
    static function printStackTrace()
    {
        $array = debug_backtrace();
        unset($array[0]);
        $stackTrace = '';
        foreach ($array as $row) {
            $stackTrace .= $row['file'] . ':' . $row['line'] . '行,调用方法:' . $row['function'] . "<p>";
        }
        return $stackTrace;
    }
}