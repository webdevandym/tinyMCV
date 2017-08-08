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
            foreach ($user as $key => $value) {
                $_SESSION[$svar[$i++]] = $value;
            }

            // $_SESSION[$svar[0]] = $user->{$svar[0]};
            // $_SESSION[$svar[1]] = $user->{$svar[1]};
        }

        if (!isset($_COOKIE[$svar[0]])) {
            setcookie($svar[0], $_SESSION[$svar[0]], time() + 60 * 60 * 24 * 7, '/');
            setcookie($svar[1], $_SESSION[$svar[1]], time() + 60 * 60 * 24 * 7, '/');
        }
    }

    public static function getUser(&$user, array $svar = ['user', 'userName'])
    {
        if (empty($svar[0]) && empty($svar[1])) {
            throw new \Exception('Check input value in getUser func.!', 1);
        }

        if (isset($_COOKIE[$svar[0]])) {
            $_SESSION[$svar[0]] = $_COOKIE[$svar[0]];
            $_SESSION[$svar[1]] = $_COOKIE[$svar[1]];
        }

        if (isset($_SESSION[$svar[0]])) {
            $user->{$svar[0]} = $_SESSION[$svar[0]];
            $user->{$svar[1]} = $_SESSION[$svar[1]];

            self::setUser($svar, $user);
        } else {
            $user->{$svar[0]} = $user->{$svar[1]} = '';
        }
    }

    public static function checkSESSION()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public static function destroySESSION($excl = '')
    {
        self::checkSESSION();

        if (isset($_SESSION['user']) || isset($_COOKIE['user'])) {
            destroySession($excl);
        }

        echo "<script>window.location.href = '.';</script>";

        function destroySession($excl = '')
        {
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
        }
    }
}
