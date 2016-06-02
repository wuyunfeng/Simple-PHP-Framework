<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   UnitTest.php
 * Author: wuyunfeng
 * Date: 16/5/31
 * Time: 下午2:27
 * Email: wuyunfeng@126.com
 */
define('UNIT_BASE_PATH', dirname(__DIR__) . "/");

class UnitTest
{
    function testPHPInfo()
    {
        phpinfo();
    }

    function testDatabase()
    {
        require_once UNIT_BASE_PATH . 'component/' . 'DB.class.php';
        $db = DB::getInstance();
        $ret = $db->select('books', array('isbn', 'author'),
            array('title' => "Android Experts", "price" => 30.00));
        var_dump($ret);
        $ret1 = $db->insert('books', array('isbn' => '0-672-31427-6', 'author' => 'wuyunfeng\'s'));
        var_dump($ret1);
        $ret2 = $db->delete('books', array('isbn' => '0-672-31427-6'));
        var_dump($ret2);
        $ret3 = $db->update('books', array('title' => 'Test', 'price' => 19.99), array('isbn' => '0-672-31427-6'));
        var_dump($ret3);
        $ret4 = $db->selectCount('books');
        var_dump($ret4);
        print_r(get_loaded_extensions());
    }

    public function testRedisCache()
    {
        require_once UNIT_BASE_PATH . 'include/' . 'class/' . 'RunException.class.php';
        require_once UNIT_BASE_PATH . 'component/' . 'Cache.class.php';
        $cache = Cache::getInstance();
        $cache->set('eTest', 'expireTest', 30);
        $cache->set('testArray', array('data1', 'data2', 'data3'));
        $cache->set('testHash', array('key1' => 'data1', 'key2' => 'data2', 'key3' => 'data3'));
        var_dump($cache->get('testHash', 'key4'));
    }
}

$unitTest = new UnitTest();
$unitTest->testRedisCache();