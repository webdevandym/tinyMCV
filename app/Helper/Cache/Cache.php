<?php

namespace app\Helper\Cache;

use app\Helper\Logger\logHTMLAdv;
use Memcache as Memcache;

// use connector\fastConnect;
// use core\logSystem\logHTMLAdv;

class Cache
{
    private static $memcache = null;

    public static function get($key)
    {
        try {
            self::initConn();

            $res = self::$memcache->get(self::hash($key));
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

            self::$memcache->add(self::hash($key), json_encode($res), 0, $time);

            self::close();

            return $res;
        } catch (\MemcachedException $e) {
            return false;
        }
    }

    public static function has($key)
    {
        return (bool) self::$memcache->get(self::hash($key));
    }

    public static function delete($key, $time = 0)
    {
        return self::$memcache->delete(self::hash($key), $time);
    }

    protected static function initConn()
    {
        if (self::$memcache === null && class_exists('\Memcache')) {
            self::$memcache = new Memcache();

            self::$memcache->connect(getenv('MEMCACHE_HOST') !== null ? getenv('MEMCACHE_HOST') : 'localhost', getenv('MEMCACHE_PORT') !== null ? getenv('MEMCACHE_PORT') : 11211) or die();

            return;
        }

        if (self::$memcache === null) {
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

    protected static function hash($key)
    {
        return md5((string) $key);
    }
}
