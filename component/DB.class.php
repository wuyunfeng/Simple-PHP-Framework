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
     *              'a.name = ' => 'john', // 字符串，自动转码并加引号
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
     * ```
     * appends数组用于设置SQL的后置操作，具体选项参见MySQL手册
     * @param mixed $table 数据表列表，可以是数组或者字符串
     * @param mixed $fields 字段列表，可以是数组或者字符串
     * @param null $cons 条件列表，可以是数组或者字符串
     * @param null $option 选项列表，可以是数组或者字符串
     * @param null $append 结尾操作列表，可以是数组或者字符串
     * @param $option 返回结果选项:数组或关联数组
     * @return mixed  成功返回结果,失败返回false
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
        $errno = mysqli_errno($this->db);
        if ($errno) {
            $errmsg = mysqli_error($this->db);
            $mysqliResult->free();
            return ['errno' => $errno, 'errormsg' => $errmsg];
        }
        if (!$mysqliResult) {
            return false;
        } else {
            switch ($fetchType) {
                case DB::FETCH_ASSOC:
                    $result = $mysqliResult->fetch_all(MYSQLI_ASSOC);
                    var_dump($mysqliResult->num_rows);
                    break;
                default:
                    $result = $mysqliResult->fetch_all(MYSQLI_NUM);
            }
            @$mysqliResult->free();
            return $result;
        }
    }

    /**
     * @param mixed $table
     * @param string $fields
     * @param null $cons
     * @param null $option
     * @param null $append
     * @param int $fetchType
     * @return array|bool|int
     */
    public function selectCount($table, $fields = '*', $cons = null, $option = null,
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
        $errno = mysqli_errno($this->db);
        if ($errno) {
            $errmsg = mysqli_error($this->db);
            $mysqliResult->free();
            return ['errno' => $errno, 'errormsg' => $errmsg];
        }
        if (!$mysqliResult) {
            return false;
        } else {
            $ret = $mysqliResult->num_rows;
            $mysqliResult->free();
            return $ret;
        }
    }

    /**
     *
     * INSERT INTO table (a,b,c) VALUES (1,2,4) ON DUPLICATE KEY UPDATE c=values(c);
     *
     * @param string $table 数据库表
     * @param mixed $row 插入的数据3
     * @param string $option reserverd paramter
     * @param string $onDup ON DUPLICATE KEY UPDATE
     *
     * @return array if success return [result, id], or return [errno, errormsg]
     *
     */
    public function insert($table, $row, $option = '', $onDup = null)
    {
        $executeSQLInsert = 'insert into ';
        if (is_string($row)) {
            $executeSQLInsert .= $row;
        } elseif (is_array($row)) {
            $executeSQLInsert .= $this->buildInsertParams($table, $row);
        }

        if (isset($onDup)) {
            $executeSQLInsert .= ' ON ' . $onDup;
        }

        $isSuccess = mysqli_query($this->db, $executeSQLInsert);
        $errno = mysqli_errno($this->db);
        if ($errno) {
            $errmsg = mysqli_error($this->db);
            return ['errno' => $errno, 'errormsg' => $errmsg];
        }
        $result['result'] = $isSuccess;
        if ($isSuccess) {
            $insertId = mysqli_insert_id($this->db);
            $result['id'] = $insertId;
        }
        return $result;
    }

    /**
     *```php
     * $table = 'a'
     * $conds = array(
     *              'a.name = ' => 'Robin Li', // 字符串，自动转码并加引号
     *              'a.id >' => 1000,          // 数字，不作处理
     *              'a.count > 100'            // 非key-value型
     *          );
     *```
     * @param string $table 目标数据库表
     * @param mixed $cond 条件语句
     * @return array if success [result, count], or [errno, errmsg]
     */
    public function delete($table, $cond = null)
    {
        $deleteSQLStatement = 'delete from ' . $table;
        $assembleCond = $this->assembleCondition($cond);
        $deleteSQLStatement .= ' where ' . $assembleCond;
        $isSuccess = mysqli_query($this->db, $deleteSQLStatement);
        $errno = mysqli_errno($this->db);
        if ($errno) {
            $errmsg = mysqli_error($this->db);
            return ['errno' => $errno, 'errormsg' => $errmsg];
        }
        $result['result'] = $isSuccess;
        if ($isSuccess) {
            $result['count'] = mysqli_affected_rows($this->db);
        }
        return $result;
    }

    /**
     *```php
     *
     * $table = 'a';
     *
     * $rows = array(
     *              'a.name' => 'john'
     *          );
     *
     * $conds = array(
     *              'a.name = ' => 'john', // 字符串，自动转码并加引号
     *              'a.id >' => 1000,          // 数字，不作处理
     *              'a.count > 100'            // 非key-value型
     *          );
     * ```
     * @param string $table 数据库表名
     * @param array $rows update key => value
     * @param mixed $conds where 条件
     * @return mixed array if success return [result, id], or return [errno, errormsg]
     */
    public function update($table, $rows, $conds = null)
    {
        if (!$rows || !is_array($rows)) {
            return false;
        }
        $updateSQLStatement = 'update ' . $table;
        if ($rows) {
            $updateSQLStatement .= ' SET ';
            $updateSQLStatement .= $this->buildUpdateParams($rows);
        }

        if (isset($conds) && count($conds) > 0) {
            $updateSQLStatement .= ' where ';
            $updateSQLStatement .= $this->assembleCondition($conds);
        }
        $isSuccess = mysqli_query($this->db, $updateSQLStatement);
        $errno = mysqli_errno($this->db);
        if ($errno) {
            $errmsg = mysqli_error($this->db);
            return ['errno' => $errno, 'errormsg' => $errmsg];
        }
        $result['result'] = $isSuccess;
        if ($isSuccess) {
            $result['count'] = mysqli_affected_rows($this->db);
        }
        return $result;
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
                $result .= '=';
                //需要特殊处理为String类型
                if (is_string($value)) {
                    $result .= '"';
                    $result .= @mysqli_escape_string($this->db, $value);
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

    private function buildInsertParams($table, $row)
    {
        $builder = $table . ' (';
        $builderValue = '(';
        foreach ($row as $key => $value) {
            $builder .= $key;
            if (is_string($value)) {
                $builderValue .= '"';
                $builderValue .= mysqli_escape_string($this->db, $value);
                $builderValue .= '"';
            } else {
                $builderValue .= $value;
            }
            unset($row[$key]);
            if (count($row)) {
                $builder .= ', ';
                $builderValue .= ', ';
            } else {
                $builder .= ' )';
                $builderValue .= ' )';
            }
        }
        $sqlPart = $builder . ' VALUES ' . $builderValue;
        return $sqlPart;
    }

    private function buildUpdateParams($rows)
    {
        $builder = '';
        foreach ($rows as $key => $value) {
            $builder .= $key;
            $builder .= '=';
            if (is_string($value)) {
                $builder .= '"';
                $builder .= mysqli_escape_string($this->db, $value);
                $builder .= '"';
            } else {
                $builder .= $value;
            }
            unset($rows[$key]);
            if (count($rows) > 0) {
                $builder .= ', ';
            }
        }
        return $builder;
    }
}