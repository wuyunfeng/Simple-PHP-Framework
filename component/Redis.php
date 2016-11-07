<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   Redis.php
 * Author: wuyunfeng
 * Date: 16/5/31
 * Time: 下午3:42
 * Email: wuyunfeng@126.com
 */
class Cache extends Component
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
     * @param string $key
     * @param string $type
     * @return boolean
     */
    public function ttl($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->ttl($key);
    }

    /**
     * @param string $key
     * @param string $type
     * @return boolean
     */
    public function exists($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->exists($key);
    }

    /**
     * 设置过期时间，使用时间戳
     * @param string $key
     * @param string $timestamp
     * @return boolean
     */
    public function expireAt($key, $timestamp)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->expireAt($key, $timestamp);
    }

    /**
     * 设置过期时间
     * @param string $key
     * @param string $seconds
     * @return boolean
     */
    public function expire($key, $seconds)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->expire($key, $seconds);
    }

    /**
     * 删除指定的keys
     * @param array $keys
     * @return boolean/int
     */
    public function del($keys)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->del($keys);
    }

    /**
     * 移除给定key的过期时间，使其永不过期
     * @param string $key
     * @param string $type
     * @return
     */
    public function persist($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->persist($key);
    }

    //string 相关
    /**
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->get($key);
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @return boolean, true: 设置成功  false:设置失败
     */
    public function set($key, $value)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->set($key, $value);
    }

    /**
     *
     * @param string $key
     * @param int $value
     * @return boolean/int
     */
    public function incr($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->incr($key);
    }

    /**
     *
     * @param string $key
     * @param int $value
     * @return boolean/int
     */
    public function incrBy($key, $value)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->incrBy($key, $value);
    }

    /**
     * 批量设置redis string类型变量
     * @param array $datas : array("k0"=>"v0", "k1"=>"v1");
     * @return boolean
     */
    public function mset($datas)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->mset($datas);
    }

    /**
     * 批量获取redis string类型变量
     * @param array $datas : array( 123, 456, 2324);
     * @return string
     */
    public function mget($datas)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->mget($datas);
    }

    /**
     *
     * @param string $key
     * @param string $time
     * @param string $value
     * @return boolean
     */
    public function setex($key, $time, $value)
    {
        //TODO add log
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->setex($key, $time, $value);
    }


    //集合相关
    /**
     * 向指定集合key中写入元素
     * @param string $key
     * @param string $value
     * @return string
     */
    public function sAdd($key, $value)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->sAdd($key, $value);
    }

    /**
     * 从指定集合key中删除元素
     * @param string $key
     * @param string $value
     * @return string
     */
    public function sRem($key, $value)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->srem($key, $value);
    }

    /**
     * 获取名称为key的set的所有元素
     * @param string $key
     * @return array
     */
    public function sMembers($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->sMembers($key);
    }

    /**
     * 获取指定key中的元素个数
     * @param string $key
     * @return boolean
     */
    public function sCard($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->scard($key);
    }

    /**
     * 从集合中随机pop出一个元素
     * @param string $key
     * @return string $value
     */
    public function sPop($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->sPop($key);
    }

    //有序集合
    /**
     * @param string $key
     * @param string $score
     * @param string $member
     * @return int
     */
    public function zAdd($key, $score, $member)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->zAdd($key, $score, $member);
    }

    /**
     *
     * @param string $key
     * @param string $score
     * @return string
     */
    public function zRem($key, $member)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->zRem($key, $member);
    }

    /**
     *
     * @param string $key
     * @param string $start
     * @param string $stop
     * @param boolean $withscores
     * @return string
     */
    public function zRange($key, $start, $stop, $withscores)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->zRange($key, $start, $stop, $withscores);
    }

    /**
     *
     * @param string $key
     * @return interger
     */
    public function zScore($key, $member)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->zScore($key, $member);
    }

    /**
     *
     * @param string $key
     * @param string $start
     * @param string $stop
     * @param boolean $withscores
     * @return string
     */
    public function zRevRange($key, $start, $stop, $withscores)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->zrevrange($key, $start, $stop, $withscores);
    }

    /**
     *
     * @param string $key
     * @param string $start
     * @param string $stop
     * @param boolean $withscores
     * @return string
     */
    public function zRangeByScore($key, $start, $stop, $offset, $count, $withscores)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->zrangebyscore($key, $start, $stop, array('limit' => array($offset, $count), 'withscores' => $withscores));
    }

    /**
     *
     * @param string $key
     * @param string $start
     * @param string $stop
     * @param boolean $withscores
     * @return string
     */
    public function zRevRangeByScore($key, $start, $stop, $offset, $count, $withscores)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->zrevrangebyscore($key, $stop, $start, array('limit' => array($offset, $count), 'withscores' => $withscores));
    }

    //队列相关
    /**
     * 在key对应list的尾部pop字符串元素
     *
     * @param
     * @return
     */
    public function mpop($key, $cnt)
    {
        if ($this->redis == false) {
            return false;
        }
        $datas = array();
        for ($i = $cnt; $i > 0; $i--) {
            $data = $this->redis->rPop($key);
            if ($data) {
                $datas[] = $data;
            } else {
                break;
            }
        }
        return $datas;
    }

    /**
     * 在key对应list的头部添加字符串元素$value
     *
     * @param
     * @return
     */
    public function push($key, $value)
    {
        if ($this->redis == false) {
            return false;
        }
        $rs = $this->redis->lPush($key, $value);
        if (!$rs) {
        }
        return $rs;
    }

    /**
     * lLen
     *
     * @param
     * @return
     */
    public function lLen($key)
    {
        if ($this->redis == false) {
            return false;
        }
        $rs = $this->redis->lSize($key);
        if (!$rs) {
        }
        return $rs;
    }

    //hash相关
    /**
     * @param string $key
     * @param string $filed
     * @param string $value
     * @return int/boolean: 1/0->均为成功，false->失败(网络异常等)
     */
    public function hSet($key, $field, $value)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->hSet($key, $field, $value);
    }

    /**
     * @param string $key
     * @param string $field
     * @return string/boolean: string->指定域的值   false->网络异常或该域不存在，根据errno来区分
     */
    public function hGet($key, $field)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->hGet($key, $field);
    }

    /**
     *
     * @param string $key
     * @return array/boolean: string->指定hash的值   false->网络异常或该域不存在，根据errno来区分
     */
    public function hgetall($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->hGetAll($key);
    }

    /**
     * @param string $key
     * @param array $datas
     * @return boolean true->设置成功  false->网络异常
     */
    public function hmset($key, $datas)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->hMset($key, $datas);
    }

    /**
     * @param string $key
     * @param array $fields
     * @return array/boolean array->返回正常  false->网络异常或$fields非数组
     */
    public function hmget($key, $fields)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->hMget($key, $fields);
    }

    /**
     * @param string $key
     * @param string $field
     * @param int $increment
     * @return int/boolean : int->field对应的值 ; false->网络异常或$field对应的类型非数值
     */
    public function hIncrBy($key, $field, $increment)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->hIncrBy($key, $field, $increment);
    }

    /**
     * @param string $key
     * @return array/boolean : array->key中所有的field,key无存在时为空数组; false->网络异常
     */
    public function hKeys($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->hKeys($key);
    }

    /**
     * @param string $key
     * @return array/boolean : array->key中所有的value,key无存在时为空数组; false->网络异常
     */
    public function hVals($key)
    {
        if ($this->redis == false) {
            return false;
        }
        return $this->redis->hVals($key);
    }

//    /**
//     *
//     * redis缓存数据
//     *
//     * @param string $key
//     * @param mixed $value
//     * @param int $timeout
//     *
//     * @return bool
//     */
//    public function set($key, $value, $timeout = 7200)
//    {
//        if (is_string($value)) {
//            $this->redis->set($key, $value, $timeout);
//        } elseif (is_array($value)) {
//            if ($this->isAssoc($value)) {
//                $this->redis->hMset($key, $value);
//                $this->redis->expire($key, $timeout);
//            } else {
//                foreach ($value as $val) {
//                    $this->redis->rPush($key, $val);
//                }
//                $this->redis->expire($key, $timeout);
//            }
//        }
//    }
//
//    /***
//     *
//     * 获取redis缓存的key对应的value
//     *
//     * @param int|string $key
//     * @param int|string $pKey 要查询hashmap的key值
//     * @param int $start
//     * @param int $end
//     * @return array|bool|string
//     */
//    public function get($key, $pKey = null, $start = 0, $end = -1)
//    {
//        $type = $this->redis->type($key);
//        switch ($type) {
//            case Redis::REDIS_STRING:
//                return $this->redis->get($key);
//            case Redis::REDIS_LIST:
//                if (isset($pKey)) {
//                    return $this->redis->lIndex($key, intval($pKey));
//                }
//                return $this->redis->lRange($key, $start, $end);
//            case Redis::REDIS_SET:
//                return $this->redis->sMembers($key);
//            case Redis::REDIS_ZSET:
//                return $this->redis->zRange($key, $start, $end);
//            case Redis::REDIS_HASH:
//                if (isset($pKey)) {
//                    return $this->redis->hGet($key, $pKey);
//                } else {
//                    return $this->redis->hGetAll($key);
//                }
//            default:
//                return false;
//        }
//    }
//
//    public function getZSortScores() {
//        $result = $this->redis->zRevRangeByScore("zzz", 6, -1,
//            array('withscores' => TRUE, 'limit' => array(0, 2)));
//        var_dump($result);
//    }
//
//    /**
//     * @param mixed $key 完整删除key对应内容
//     * @param null $pKey hashMap 对应的key值
//     * @param null $value list\zset\set对应的值
//     * @param int $count list 存在同样的 $value 删除 $count 个, 0 为全部删除
//     */
//    public function delete($key, $pKey = null, $value = null, $count = 0)
//    {
//        $type = $this->redis->type($key);
//        switch ($type) {
//            case Redis::REDIS_STRING:
//                $this->redis->delete($key);
//                break;
//            case Redis::REDIS_LIST: {
//                if (isset($value)) {
//                    $this->redis->lRemove($key, $value, $count);
//                } else {
//                    $this->redis->delete($key);
//                }
//                break;
//            }
//            case Redis::REDIS_SET: {
//                if (isset($value)) {
//                    $this->redis->sRemove($key, $value);
//                } else {
//                    $this->redis->delete($key);
//                }
//                break;
//            }
//            case Redis::REDIS_ZSET: {
//                if ($value) {
//                    $this->redis->zRem($key, $value);
//                } else {
//                    $this->redis->delete($key);
//                }
//                break;
//            }
//            case Redis::REDIS_HASH: {
//                if (isset($pKey)) {
//                    $this->redis->hDel($key, $pKey);
//                } else {
//                    $this->redis->delete($key);
//                }
//                break;
//            }
//            default:
//                break;
//        }
//    }
//
//    /**
//     * @param array $arr 代测试array
//     * @return bool  如果是关联数组返回true,否则返回false
//     */
//    private function isAssoc($arr)
//    {
//        return array_keys($arr) !== range(0, count($arr) - 1);
//    }


}