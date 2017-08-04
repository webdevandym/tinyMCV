<?php

namespace app\Helper\Http;

class UrlHelper
{
    protected $url;

    public function __construct()
    {
        $this->url = 'http://'.$_SERVER['HTTP_HOST'].$this->getSubFold().'/';
    }

    public function assets($file, $return = false)
    {
        $file = $this->url.'assets/'.$this->collLink($file);

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
        return  __ROOT__.'/app/Views/'.$this->collLink($name);
    }

    private function collLink($link)
    {
        return preg_replace('/\./', '/', $link, substr_count($link, '.') - 1);
    }

    private function getSubFold()
    {
        preg_match('/(\/\w+)\//', $_SERVER['SCRIPT_NAME'], $match);

        return $match[1];
    }
}
