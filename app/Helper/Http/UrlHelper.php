<?php

namespace app\Helper\Http;

class UrlHelper
{
    protected $url;

    public function __construct()
    {
        $this->url = 'http://'.preg_replace("/\/\w+\.php/", '', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']).'/';
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
}
