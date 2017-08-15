<?php

namespace app\Helper\Database;

use app\Helper\crypt;

class Connector
{
    protected static $instance = null;
    protected $db = null;

    public static function getInst()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function getPDO()
    {
    }

    public function close($callback = null)
    {
        $this->db = null;
        if (is_callable($callback)) {
            return $callback();
        }
    }

    public function conn()
    {
        if (empty($this->db)) {
            $file = __ROOT__.'/config.xml';

            $xml = simplexml_load_file($file);
            $pass = (string) $xml->dbpass->item;

            $crypt = new crypt($xml->cryptKey, $xml->secret_iv);

            if (!$crypt->validMd5($pass)) {
                $cpass = $crypt->encrypt_decrypt('encrypt', $pass);
                $xml->dbpass->item = $cpass;
                file_put_contents($file, $xml->asXML());
                unset($cpass);
            } else {
                $pass = $crypt->encrypt_decrypt('decrypt', $pass);
            }

            $dbhost = getenv('SQL_SERVER');
            $dbname = getenv('SQL_DATABASE');
            $dbuser = getenv('SQL_LOGIN');

            $this->db = $this->getConnection($dbhost, $dbname, $dbuser, $pass);
        }

        return static::$instance;
    }

    protected function getConnection($server, $database, $user, $pass)
    {
        try {
            if (!($this->db instanceof \PDO)) {
                $dns = sprintf("dblib:host={$server};dbname={$database};ConnectionPooling=0;LoginTimeout=5");
                $db = new \PDO(
                  $dns,
                  $user,
                  $pass,
                  [
                      // PDO::ATTR_PERSISTENT => true,
                      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                      // PDO::ATTR_CASE => PDO::CASE_LOWER,
                  ]
              );
            }
        } catch (\PDOException $e) {
            echo '<div id = "dberrorMessage">'.$e->getMessage().'</div>';
        }

        return $this->checkConnect($db);
    }

    protected function checkConnect($db)
    {
        if (empty($db)) {
            $url = new \app\Helper\Http\UrlHelper();
            $link = $url->assets('css.mincss.siteStyle-min.css', true, true);
            echo <<<__END
    <head>
         <link rel="stylesheet" type="text/css" href="$link">
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    </head>
    <body>
        <div id = 'dbstatus' class ='dberror'>
             <i class="fa fa-database" aria-hidden="true"></i>
        </div>
        <script> document.body.setAttribute("class",'loaded'); </script>
__END;
            die('</body>');
        }

        return $db;
    }
}
