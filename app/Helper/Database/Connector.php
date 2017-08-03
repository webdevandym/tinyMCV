<?php

namespace app\Helper\Database;

use app\core\Helper\crypt;

abstract class Connector
{
    private static $instance = null;
    private $db = null;

    public static function getInst()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    abstract public function getPDO()
    {
    }

    protected function conn()
    {
        if (empty($this->db)) {
            $file = __DIR__.'/config.xml';

            $xml = simplexml_load_file($file);
            $pass = (string) $xml->dbpass->item;

            require_once __DIR__.'./../core/openssl_encrypt_decrypt.php';
            $crypt = new crypt($xml->cryptKey, $xml->secret_iv);

            if (!$crypt->validMd5($pass)) {
                $cpass = $crypt->encrypt_decrypt('encrypt', $pass);
                $xml->dbpass->item = $cpass;
                file_put_contents($file, $xml->asXML());
                unset($cpass);
            } else {
                $pass = $crypt->encrypt_decrypt('decrypt', $pass);
            }

            $this->db = $this->getConnection($xml->dbhost->item, $xml->dbname->item, $xml->dbuser->item, $pass);
        }
    }

    protected function getConnection($server, $database, $user, $pass)
    {
        try {
            if (!($this->db instanceof \PDO)) {
                $dns = sprintf("dblib:host={$this::$server};dbname={$this::$database};ConnectionPooling=0;LoginTimeout=5");
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
        if (!empty($db)) {
            echo <<<__END
    <head>
         <link rel="stylesheet" type="text/css" href="{$_COOKIE['curPath']}/../assets/css/mincss/siteStyle-min.css">
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
