<?php

namespace app\Helper\Database;

use app\Helper\Cache\Cache;

// require_once __DIR__.'/cached.php';

class messStore
{
    protected static $inst;
    private static $lang = 'EN';
    private static $langUse = true;
    private $keepConnect;
    private static $linkStore = '';
    private static $level;
    private static $file = __ROOT__.'/app/Store/messageStore';

    private function __construct()
    {
    }

    public static function instance($key = null, $path = '', $langUse = true)
    {
        if (!self::$inst) {
            self::$inst = new self();
        }

        self::$file = $path ? $path : self::$file;
        self::$langUse = $langUse;

        return self::$inst->get($key);
    }

    public static function genLinks($val, $path = '', $level = false)
    {
        if (!$val) {
            return;
        }

        if (!is_array($val)) {
            $valSt[] = $val;
        } else {
            $valSt = $val;
        }

        self::$level = $level;
        self::$linkStore = new \stdClass();

        foreach ($valSt  as $key => $value) {
            $variable = preg_match('/\D/', $key) ? $key : (is_array($value) ? end($value) : $value);

            if ($level) {
                self::$linkStore = self::instance($value ? $value : $key, $path);
            } else {
                self::$linkStore->$variable = self::instance($value ? $value : $key, $path);
            }
        }

        return self::$linkStore;
    }

    private function get($key)
    {
        $res = self::$inst->cacheFile(self::$file, (self::$langUse ? self::$lang : ''));

        if ($key) {
            if (is_array($key)) {
                foreach ($key as  $value) {
                    if (!empty($value)) {
                        $res = $res->$value;
                    }
                }
            } else {
                if (!empty($key)) {
                    $res = $res->{$key};
                }
            }
        }

        return $res;
    }

    private function cacheFile($path, $lang)
    {
        preg_match('/[\w\d]+$/i', $path, $match);
        $key = 'json'.$match[0].$lang;

        $c = Cache::get($key);
        if ($c) {
            return $c;
        }

        $res = json_decode(file_get_contents($path.$lang.'.json'));
        Cache::set($key, 24 * 60 * 60, $res);

        return $res;
    }
}
