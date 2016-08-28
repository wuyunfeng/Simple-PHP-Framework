<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   Cache.php
 * Author: wuyunfeng
 * Date: 16/5/31
 * Time: 下午3:42
 * Email: wuyunfeng@126.com
 */
class Cache
{
    private static $instance;

    /**
     * @var Redis
     */
    private $redis;

    private function __construct()
    {
        $this->redis = new Redis();
        $this->connect();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    private function connect()
    {
        $config = require_once UNIT_BASE_PATH . 'config/' . 'redis.php';
        if (!isset($config['host'])) {
            throw new RunException(9000, 500, 'absence host key');
        }
        $result = $this->redis->connect($config['host'], $config['port'], $config['timeout']);

        if (!$result) {
            throw new RunException(9000, 500, 'connect redis failure!!');
        }
    }

    /**
     *
     * redis缓存数据
     *
     * @param string $key
     * @param mixed $value
     * @param int $timeout
     *
     * @return bool
     */
    public function set($key, $value, $timeout = 7200)
    {
        if (is_string($value)) {
            $this->redis->set($key, $value, $timeout);
        } elseif (is_array($value)) {
            if ($this->isAssoc($value)) {
                $this->redis->hMset($key, $value);
                $this->redis->expire($key, $timeout);
            } else {
                foreach ($value as $val) {
                    $this->redis->rPush($key, $val);
                }
                $this->redis->expire($key, $timeout);
            }
        }
    }

    /***
     *
     * 获取redis缓存的key对应的value
     *
     * @param int|string $key
     * @param int|string $pKey 要查询hashmap的key值
     * @param int $start
     * @param int $end
     * @return array|bool|string
     */
    public function get($key, $pKey = null, $start = 0, $end = -1)
    {
        $type = $this->redis->type($key);
        switch ($type) {
            case Redis::REDIS_STRING:
                return $this->redis->get($key);
            case Redis::REDIS_LIST:
                if (isset($pKey)) {
                    return $this->redis->lIndex($key, intval($pKey));
                }
                return $this->redis->lRange($key, $start, $end);
            case Redis::REDIS_SET:
                return $this->redis->sMembers($key);
            case Redis::REDIS_ZSET:
                return $this->redis->zRange($key, $start, $end);
            case Redis::REDIS_HASH:
                if (isset($pKey)) {
                    return $this->redis->hGet($key, $pKey);
                } else {
                    return $this->redis->hGetAll($key);
                }
            default:
                return false;
        }
    }

    public function getZSortScores() {
        $result = $this->redis->zRevRangeByScore("zzz", 6, -1,
            array('withscores' => TRUE, 'limit' => array(0, 2)));
        var_dump($result);
    }

    /**
     * @param mixed $key 完整删除key对应内容
     * @param null $pKey hashMap 对应的key值
     * @param null $value list\zset\set对应的值
     * @param int $count list 存在同样的 $value 删除 $count 个, 0 为全部删除
     */
    public function delete($key, $pKey = null, $value = null, $count = 0)
    {
        $type = $this->redis->type($key);
        switch ($type) {
            case Redis::REDIS_STRING:
                $this->redis->delete($key);
                break;
            case Redis::REDIS_LIST: {
                if (isset($value)) {
                    $this->redis->lRemove($key, $value, $count);
                } else {
                    $this->redis->delete($key);
                }
                break;
            }
            case Redis::REDIS_SET: {
                if (isset($value)) {
                    $this->redis->sRemove($key, $value);
                } else {
                    $this->redis->delete($key);
                }
                break;
            }
            case Redis::REDIS_ZSET: {
                if ($value) {
                    $this->redis->zRem($key, $value);
                } else {
                    $this->redis->delete($key);
                }
                break;
            }
            case Redis::REDIS_HASH: {
                if (isset($pKey)) {
                    $this->redis->hDel($key, $pKey);
                } else {
                    $this->redis->delete($key);
                }
                break;
            }
            default:
                break;
        }
    }

    /**
     * @param array $arr 代测试array
     * @return bool  如果是关联数组返回true,否则返回false
     */
    private function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}