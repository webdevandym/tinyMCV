<?php

namespace app\Helper\Http;

class Render
{
    private $conn;
    private $page;
    private $query;
    private $path;
    private $jsonPath;

    public function __construct($query)
    {
        $this->query = $query;
        $this->path = __ROOT__.(getenv('PAGE_DEF_PATH') !== null ? getenv('PAGE_DEF_PATH') : '/app/Views/site/');
        $this->jsonPath = __ROOT__.(getenv('PAGE_JSON_PATH') !== null ? getenv('PAGE_JSON_PATH') : '/app/Views/site/jsonData/');
    }

    public function getPage()
    {
        $this->page = stripslashes(htmlentities(strip_tags($this->query)));
        $this->page = ($this->page !== 'null' && !empty($this->page)) ? $this->page : 'timeSheet';

        $jsFile = $this->jsonPath."{$this->page}.json";
        $phpFile = $this->path."{$this->page}.php";

        if (file_exists($jsFile) && !$this->pageCheckModDate($jsFile, $phpFile)) {
            $con = json_decode(file_get_contents($jsFile));

            return $con;
        }

        return $this->createJSONContent($phpFile, $jsFile);
    }

    private function createJSONContent($phpFile, $jsFile)
    {
        $content = file_get_contents($phpFile);

        if (stripos($content, '?php') !== false) {
            ob_start();
            include $phpFile;
            $content = ob_get_clean();
        }

        file_put_contents($jsFile, str_replace(['  ', '\n', '\t', '\r'], '', json_encode($content)));
        touch($jsFile, filemtime($phpFile));

        return $content;
    }

    private function pageCheckModDate($fileStatic, $fileDynamic)
    {
        if (filemtime($fileStatic) !== filemtime($fileDynamic)) {
            unlink($fileStatic);

            return true;
        }

        return false;
    }
}
