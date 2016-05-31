<?php

/**
 * ***********************************
 * ***** simple-php-webapp *****
 * ***********************************
 *   DB.class.php
 * Author: wuyunfeng
 * Date: 16/5/31
 * Time: 下午3:41
 * Email: wuyunfeng@126.com
 */
class DB
{
    private static $instance;
    private $config;
    private $db;

    const FETCH_ASSOC = 0x01;
    const FETCH_ARRAY = 0x02;

    private function __construct()
    {
        $this->config = @include_once(UNIT_BASE_PATH . "config/" . "database.php");
        if (!$this->config) {
            throw new RunException(9001, 500, "Server Internal Error");
        }
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
            self::$instance->getConnection();
        }
        return self::$instance;
    }

    private function getConnection($config = [])
    {
        $charSet = 'utf-8';
        if ($config) {
            $this->db = mysqli_connect($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
            isset($config['charset']) && ($charSet = $config['charset']);
        } else {
            $this->db = mysqli_connect($this->config['host'],
                $this->config['user'], $this->config['password'],
                $this->config['database'], $this->config['port']);
            isset($this->config['charset']) && ($charSet = $this->config['charset']);

        }

        if (!$this->db) {
            throw new RunException(9001, 500, "connect mysql failure" . __CLASS__ . __LINE__);
        }
        if (mysqli_connect_errno()) {
            throw new RunException(9001, 500, "connect mysql failure errno = " . mysqli_connect_errno()
                . " error" . mysqli_connect_error());
        };
        mysqli_query($this->db, $charSet);
    }

    public function __clone()
    {
        throw new RunException(9001, 500, "singlton forbidden clone" . __CLASS__ . __LINE__);
    }

    public function insert($table, $row, $option = '')
    {

    }

    /**
     *
     * ```php
     * // tables数组用于构建FROM子句，每个元素是一张表
     * $tables = array(
     *              'user as a',
     *              'order as b'
     *          );
     * //fields数组用于构建SELECT子句，每个元素是一个字段
     * $fields = array(
     *              'a.id as uid',
     *               'a.name as uname',
     *               'b.id as oid'
     *           );
     * //conds数组用于构建WHERE子句，每个元素是一个条件，使用AND进行拼接。如果某元素是key-value型，则会根据value的类型进行自动转码
     * $conds = array(
     *              'a.name = ' => 'Robin Li', // 字符串，自动转码并加引号
     *              'b.id >' => 1000,          // 数字，不作处理
     *              'b.isok != ' => NULL,      // NULL
     *              'b.count > 100'            // 非key-value型
     *          );
     *  //options数组用于设置SQL的前置选项，具体选项参见MySQL手册
     * $option = array(
     *               'DISTINCT',
     *               'SQL_NO_CACHE'
     *           );
     *  //appends数组用于设置SQL的后置操作，具体选项参见MySQL手册
     * $appends = array(
     *              'ORDER BY b.id',
     *              'LIMIT 5'
     *           );
     * appends数组用于设置SQL的后置操作，具体选项参见MySQL手册
     * @param mixed $table 数据表列表，可以是数组或者字符串
     * @param mixed $fields 字段列表，可以是数组或者字符串
     * @param null $cons 条件列表，可以是数组或者字符串
     * @param null $option 选项列表，可以是数组或者字符串
     * @param null $append 结尾操作列表，可以是数组或者字符串
     *
     * @return mixed  成功返回个数,失败返回false
     *
     */
    public function select($table, $fields = '*', $cons = null, $option = null,
                           $append = null, $fetchType = DB::FETCH_ASSOC)
    {
        $assembleFields = $this->assembleParameter($fields);
        $executeQuery = 'select ';
        $executeQuery .= $assembleFields;

        $assembleTable = $this->assembleParameter($table);
        $executeQuery .= ' from ' . $assembleTable;
        if (isset($cons)) {
            $assembleCons = $this->assembleCondition($cons);
            if (strlen($assembleCons)) {
                $executeQuery .= ' where ' . $assembleCons;
            }
        }
        if (isset($option)) {
            $assembleOptions = $this->assembleOptions($option);
            if (strlen($assembleOptions)) {
                $executeQuery .= $assembleOptions;
            }
        }
        if (isset($append)) {
            $assembleAppend = $this->assembleOptions($append);
            if (strlen($assembleAppend)) {
                $executeQuery .= $assembleAppend;
            }
        }
        $mysqliResult = mysqli_query($this->db, $executeQuery, MYSQLI_STORE_RESULT);
        if (!$mysqliResult) {
            return false;
        } else {
            switch ($fetchType) {
                case DB::FETCH_ASSOC:
                    return $mysqliResult->fetch_assoc();
                default:
                    return $mysqliResult->fetch_array();
            }
        }
    }


    private function assembleParameter($param)
    {
        if (is_string($param)) {
            $assembleParam = $param;
        } elseif (is_array($param)) {
            $assembleParam = implode(', ', $param);
        } elseif (is_callable($param)) {
            $assembleParam = call_user_func($param);
        } else {
            throw new RunException(9001, 500, "the format of table incorrect" . __CLASS__ . __LINE__);
        }
        return $assembleParam;
    }

    private function assembleCondition($cons)
    {
        $result = '';
        if (is_string($cons)) {
            $result .= $cons;
        } elseif (is_array($cons)) {
            foreach ($cons as $key => $value) {
                $result .= $key;
                $result .= ' = ';
                //需要特殊处理为String类型
                if (is_string($value)) {
                    $result .= '"';
                    $result .= $value;
                    $result .= '"';
                } else {
                    $result .= $value;
                }
                unset($cons[$key]);
                if (count($cons)) {
                    $result .= ' and ';
                }
            }
        }
        return $result;
    }

    private function assembleOptions($option)
    {
        if (is_string($option)) {
            $assembleOption = $option;
        } elseif (is_array($option)) {
            $assembleOption = implode(' ', $option);
        } elseif (is_callable($option)) {
            $assembleOption = call_user_func($option);
        } else {
            throw new RunException(9001, 500, "the option of table incorrect" . __CLASS__ . __LINE__);
        }
        return $assembleOption;
    }
}