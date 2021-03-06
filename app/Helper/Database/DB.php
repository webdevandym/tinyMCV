<?php

namespace app\Helper\Database;

use app\Helper\Logger\colorSyntax;
use app\Helper\Logger\logHTMLAdv;
use Exception as Exception;

class DB extends Connector
{
    private $logPath = __DIR__.'/../../../Log/';

    protected function __construct()
    {
        $this->conn();
    }

    public function getPDO()
    {
        return $this->db;
    }

    public function queryMysql($query, $shadow = false)
    {
        try {
            $result = $this->db->query($query);
        } catch (\PDOException $e) {
            $this->close();
            echo $e->getMessage();
        }

        $this->log($query, $result, 'query.html');

        if (empty($result)) {
            die;
        }

        return $result;
    }

    public function existsTable($table, $type)
    {
        $sql = " (select count(*) as cnt from sysobjects where type = '$type' and name = '$table')";
        try {
            $res = $this->queryMysql($sql);
        } catch (Exception $e) {
            echo '<div id = "SQLerror"><span>'.$e->getMessage().'</span></div>';
        }

        foreach ($res as  $value) {
            if (!empty($value['cnt'])) {
                return true;
            }
        }
    }

    public function clrStr($var)
    {
        if (!is_object($var)) {
            if (!empty($this->db)) {
                $var = stripslashes(htmlentities(strip_tags($var)));

                return substr($this->db->quote($var), 1, -1);
            }
            throw new Exception('Check you connect to DB!');
        }

        return $var;
    }

    public function rowCounter($array)
    {
        $rowCounter = 0;

        foreach ($array as $value) {
            ++$rowCounter;
        }

        return $rowCounter;
    }

    private function log($query, $res, $fileName, $templateName = 'template.txt')
    {
        $flink = $this->logPath.$fileName;
        $templFile = $this->logPath.$templateName;

        $query = htmlentities(preg_replace('/[\s\t\n]+/', ' ', $query));

        $syntax = new colorSyntax('sql', $query);
        $newquery = $syntax->clrSyntax();

        $context = '<span>'.((!empty($res)) ? '<span class = "logSucc">success' : '<span class = "logFail">failure').' => </span>'.$newquery.((strlen($query) > 200) ? '...' : '').'</span>';

        $context = preg_replace('/(name|pass|password)[= \']+\S+\'/', '***', $context);

        $log = new logHTMLAdv($flink, $context, true, $templFile, getenv('CUR_LOCATION'));
        $log->writeLog();
    }
}
