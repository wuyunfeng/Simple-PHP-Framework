<?php

/**
 * Created by PhpStorm.
 * User: wuyunfeng
 * Date: 16/9/11
 * Time: 上午10:06
 */
class Logger
{
    public static function write($level, $type, $logData = array())
    {
        $level = strtoupper($level);
        switch ($level) {
            case 'NOTICE':
                $file_name = Conf::APP_NAME . '.notice' . '.log';
                break;
            case 'DEBUG':
                $file_name = Conf::APP_NAME . '.debug' . '.log';
                break;
            case 'FATAL':
                $file_name = Conf::APP_NAME . '.fatal' . '.log';
                break;
            default:
                return false;
        }
        $log_str = self::format($type, $logData);
        is_dir(Conf::LOG_DIR) || mkdir(Conf::LOG_DIR, 0777, true);
        error_log($log_str, 3, Conf::LOG_DIR . '/' . $file_name . '.' . date("YmdH"));
    }

    private static function format($type, $params)
    {
        $remote = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $cost = intval((microtime(true) - START_TIME) * 1000);
        $msg = date("[Y-m-d H:i:s]") . "[" . Conf::APP_NAME . "][{$remote}][log_type={$type}][time={$cost}]";

        if (is_array($params)) {
            foreach ($params as $k => $v) {
                $v = is_string($v) ? $v : json_encode($v);
                $v = urlencode($v);
                $msg .= "[$k=$v]";
            }
        } else {
            $msg .= urlencode($params);
        }

        $msg .= "\n";

        return $msg;
    }
}