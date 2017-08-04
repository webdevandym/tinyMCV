<?php

namespace app\Helper\Users;

class User
{
    public static function checkLoggedUser()
    {
        self::checkSESSION();

        $user = new \stdClass();

        if (isset($_COOKIE['user'])) {
            $_SESSION['user'] = $_COOKIE['user'];
            $_SESSION['userName'] = $_COOKIE['userName'];
        }

        if (isset($_SESSION['user'])) {
            $user->user = $_SESSION['user'];
            $user->userName = $_SESSION['userName'];
        } else {
            $user->user = $user->userName = '';
        }

        return $user;
    }

    public static function setUser()
    {
    }

    public static function getUser()
    {
    }

    public static function checkSESSION()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public static function destroySESSION()
    {
    }
}
