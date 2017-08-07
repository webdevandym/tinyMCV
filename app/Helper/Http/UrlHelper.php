<?php

namespace app\Helper\Http;

/* Class UrlHelper
Help to include files, link Assets etc. */

class UrlHelper
{
    protected $url;

    public function __construct()
    {
        $this->url = 'http://'.$_SERVER['HTTP_HOST'].$this->getSubFold().'/';
    }

    public function assets($file, $return = false)
    {
        $file = $this->url.'assets/'.$this->coolLink($file);

        if (!$return) {
            echo $file;
        } else {
            return $file;
        }
    }

    public function url()
    {
        return $this->url;
    }

    public function incView($name)
    {
        // __ROOT__ defined in index.php
        return  __ROOT__.'/app/Views/'.$this->coolLink($name);
    }

    public function getSubFold()
    {
        preg_match('/(\/\w+)\//', $_SERVER['SCRIPT_NAME'], $match);

        return $match[1] ? $match[1] : '';
    }

    private function coolLink($link)
    {
        return preg_replace('/\./', '/', $link, substr_count($link, '.') - 1);
    }
}
