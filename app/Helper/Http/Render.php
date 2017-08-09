<?php

namespace app\Helper\Http;

class Render
{
    private $conn;
    private $page;
    private $query;
    private $path = __ROOT__.'/app/Views/site/';
    private $jsonPath = __ROOT__.'/app/Views/site/jsonData/';

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function getPage()
    {
        $this->page = stripslashes(htmlentities(strip_tags($this->query)));
        $this->page = ($this->page !== 'null' && !empty($this->page)) ? $this->page : 'timeSheet';

        $jsFile = $this->jsonPath."{$this->page}.json";
        $phpFile = $this->path."{$this->page}.php";

        if (file_exists($jsFile)) {
            $con = json_decode(file_get_contents($jsFile));

            return $con;
        }

        return $this->createJSONContent($phpFile, $jsFile);
    }

    private function createJSONContent($phpFile, $jsFile)
    {
        $con = file_get_contents($phpFile);

        if (stripos($con, '?php') !== false) {
            ob_start();
            include $phpFile;
            $content = ob_get_clean();
        } else {
            $content = file_get_contents($phpFile);
        }

        file_put_contents($jsFile, str_replace(['  ', '\n', '\t', '\r'], '', json_encode($content)));

        return $content;
    }
}
