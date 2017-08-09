<?php

namespace app\Helper\Users;

use  app\Helper\Database\DB;

class User
{
    public static function checkLoggedUser()
    {
        self::checkSESSION();

        $user = new \stdClass();
        self::getUser($user);

        return $user;
    }

    public static function ValidUser($user, $table = 'proj_users', $row = ['DISTINCT name ', 'f_name'], $cond = "and disabled = '0'")
    {
        $db = DB::getInst()->conn();

        $user = $db->clrStr($user);
        $rows = implode(',', $row);
        $result = $db->queryMysql("SELECT $rows FROM  $table WHERE name = '$user' $cond", true);
        $res = $result->fetch(\PDO::FETCH_OBJ);

        return is_object($res) ? $res : false;
    }

    public static function setUser($svar, $user)
    {
        self::checkSESSION();

        if (!isset($_SESSION[$svar[0]]) && is_object($user)) {
            $i = 0;
            foreach ($user as  $value) {
                $_SESSION[$svar[$i++]] = $value;
            }
        }

        if (!isset($_COOKIE[$svar[0]])) {
            foreach ($svar as  $value) {
                setcookie($value, $_SESSION[$value], time() + 60 * 60 * 24 * 7, '/');
            }
        }
    }

    public static function getUser(&$user, array $svar = ['user', 'userName'])
    {
        if (empty($svar[0]) && empty($svar[1])) {
            throw new \Exception('Check input value in getUser func.!', 1);
        }

        if (isset($_COOKIE[$svar[0]])) {
            foreach ($svar as $value) {
                $_SESSION[$value] = $_COOKIE[$value];
            }
        }

        if (isset($_SESSION[$svar[0]])) {
            foreach ($svar as $value) {
                $user->$value = $_SESSION[$value];
            }

            self::setUser($svar, $user);
        } else {
            foreach ($svar as $value) {
                $user->$value = '';
            }
        }
    }

    public static function checkSESSION()
    {
        $is_session_started = function () {
            if (PHP_SAPI !== 'cli') {
                if (version_compare(PHP_VERSION, '7.0', '>=')) {
                    return session_status() === PHP_SESSION_ACTIVE ? true : false;
                }

                return session_id() === '' ? false : true;
            }

            return false;
        };

        if (!$is_session_started()) {
            session_start();
        }
    }

    public static function destroySESSION($excl = '')
    {
        self::checkSESSION();

        $destroySession = function ($excl = '') {
            $exl_array[] = [];

            if (is_array($excl)) {
                $exl_array = $excl;
            } else {
                $exl_array[] = $excl;
            }

            $_SESSION = [];
            if (session_id() !== '' || isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 2592000, '/');
                foreach ($_COOKIE as $key => $value) {
                    if (!(in_array($key, $exl_array, true))) {
                        setcookie($key, '', time() - 2592000, '/');
                    }
                }
            }

            try {
                if (session_destroy()) {
                    return true;
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        };

        if ($_SESSION !== null || $_COOKIE !== null) {
            if ($destroySession($excl)) {
                echo "<script>window.location.href = '.';</script>";
            }
        }
    }
}
