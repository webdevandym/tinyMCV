<?php

namespace app\Models\ClassStore;

use app\core\Model;
use app\Helper\Cache\Cache;
use app\Helper\Database\DB;
use app\Helper\Logger\logHTMLAdv;
use app\Helper\Users\User;
use stdClass as stdClass;

abstract class QueryModel extends Model
{
    protected $db = null;
    protected $logDir = __ROOT__.'/Log/';
    protected $queryOutFormat = \PDO::FETCH_OBJ;

    abstract public function runVisiter();

    public function clr($val)
    {
        $this->initConn();

        return $this->db->clrStr($val);
    }

    public function existsTable($table, $type)
    {
        User::checkSESSION();

        $this->initConn();

        if ((string) $_SESSION[$table] !== 'cheked') {
            $res = $this->db->existsTable($table, $type);
        } else {
            return true;
        }

        if ($res) {
            $_SESSION[$table] = 'cheked';
        }

        return $res;
    }

    public function createSQLProc($table, $body)
    {
        if (!$this->existsTable($table, 'P')) {
            $this->returnQuery($body, false);
        }

        return true;
    }

    public function returnQuery($query)
    {
        $this->initConn();

        $res = $this->db->queryMysql($query);
        $result = $res->fetchAll($this->queryOutFormat);

        return $result;
    }

    public static function printResult($result)
    {
        foreach ($result as $val) {
            echo $val;
        }
    }

    public function cacheData($timeCh, $sql, $addKey = null)
    {
        $key = debug_backtrace()[1]['function'].$addKey;

        $c = Cache::get($key);
        if ($c) {
            return $c;
        }

        $c = $this->returnQuery($sql);
        try {
            return Cache::set($key, $timeCh, $c);
        } catch (\MemcachedException $e) {
            return $c;
        }
    }

    public function chkProp($val, $prop)
    {
        if (!is_array($prop) && !is_object($prop)) {
            $store[] = $prop;
        } else {
            $store = $prop;
        }
        $a = [];
        if (is_object($val) || is_array($val)) {
            foreach ($store as $value) {
                if (property_exists($val, $value)) {
                    $a[] = $this->clr($val->$value);
                } else {
                    $a[] = '';
                }
            }

            return count($a) > 1 ? $a : $a[0];
        }

        return false;
    }

    public function changeCharSet($val, $in = 'cp1251', $out = 'utf-8')
    {
        if (is_array($val) || is_object($val)) {
            foreach ($val as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $this->changeCharSet($value);
                } else {
                    if (is_object($val)) {
                        $val->$key = mb_convert_encoding($val->$key, $out, $in);
                    } else {
                        $val[$key] = mb_convert_encoding($val[$key], $out, $in);
                    }
                }
            }

            return $val;
        }

        return $val = mb_convert_encoding($val, $out, $in);
    }

    protected function log($key, $res)
    {
        if (isset($this->logDir)) {
            if (is_object($res) || is_array($res)) {
                $run = function ($res) {
                    $output = '';
                    foreach ($res as $key => $value) {
                        if ($value instanceof stdClass || is_array($value)) {
                            $run($value);
                        }
                        $output .= $key.' => '.$value.'<br>';
                    }

                    return $output;
                };

                $output = $run($res);
            } else {
                $output = $res;
            }

            $context = 'Key: '.$key.'<br>'.$output;
            $log = new logHTMLAdv($this->logDir.'logRuner.html', $context, false, $this->logDir.'template.txt', 'Europe/Kiev');
            $log->writeLog();
        }
    }

    private function initConn()
    {
        if ($this->db === null) {
            $this->db = DB::getInst()->conn();
        }
    }
}
