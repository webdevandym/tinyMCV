<?php

namespace app\Helper\Cache;

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
        self::initConn();
        try {
            $res = self::$memcache->get(md5((string) $key));

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

        self::initConn();
        try {
            echo md5((string) $key);
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

    protected function log()
    {
        $context = $key.(is_object($this::$memcache) && !empty($res) ? ' - success' : ' - run SQL query!').'<br>'.$res;
        $log = new logHTMLAdv(__DIR__.'/../log/log.html', $context, false, __DIR__.'/../log/template.txt', 'Europe/Kiev');
        $log->writeLog();
    }
}
//
//
// class cached extends cachedInit
// {
//     public function getCacheSQL($key, $query)
//     {
//         $key = 'query'.$key;
//         $c = $this->getCacheFromStore($key);
//         if ($c) {
//             return $c;
//         }
//
//         $result = $this::$db->queryMysql($query);
//         $r = $result->fetchAll(\PDO::FETCH_ASSOC);
//
//         return $this->setToCache($r);
//     }
//
//     public function getJSONFileToCache($path, $lang)
//     {
//         preg_match('/[\w\d]+$/i', $path, $match);
//         $key = 'json'.$match[0].$lang;
//
//         $c = $this->getCacheFromStore($key);
//         if ($c) {
//             return $c;
//         }
//
//         $res = json_decode(file_get_contents($path.$lang.'.json'));
//
//         return $this->setToCache($res);
//     }
// }
