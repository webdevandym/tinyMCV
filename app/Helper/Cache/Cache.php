<?php

namespace app\Helper\Cache;

use app\Helper\Logger\logHTMLAdv;
use Memcache as Memcache;

// use connector\fastConnect;
// use core\logSystem\logHTMLAdv;

class Cache
{
    protected $valid;
    protected $hash;
    private static $memcache = null;
    private static $localhost = 'localhost';
    private static $port = 11211;

    public static function get($key)
    {
        try {
            self::initConn();

            $res = self::$memcache->get(md5((string) $key));
            self::log($key, $res);
            if (!empty($res)) {
                return json_decode($res);
            }

            self::close();

            return false;
        } catch (\MemcachedException $e) {
            return false;
        }
    }

    public static function set($key, $time, $res)
    {
        if (empty($res)) {
            return false;
        }

        try {
            self::initConn();

            self::$memcache->add(md5((string) $key), json_encode($res), 0, $time);

            self::close();

            return $res;
        } catch (\MemcachedException $e) {
            return false;
        }
    }

    public static function has($key)
    {
        return (bool) self::$memcache->get(md5((string) $key));
    }

    public static function delete($key, $time = 0)
    {
        return self::$memcache->delete(md5((string) $key), $time);
    }

    protected static function initConn()
    {
        if (self::$memcache === null) {
            self::$memcache = new Memcache();
        }

        if (!self::$memcache->connect(self::$localhost, self::$port)) {
            exit();
        }
    }

    protected static function close()
    {
        return self::$memcache->close();
    }

    protected static function log($key, $res)
    {
        $logPath = __ROOT__.'/Log/';
        $context = $key.(is_object(self::$memcache) && !empty($res) ? ' - success' : ' - run SQL query!').'<br>'.$res;
        $log = new logHTMLAdv($logPath.'cache.html', $context, false, $logPath.'template.txt');
        $log->writeLog();
    }
}
