<?php

namespace app\Helper\Http;

/* Class UrlHelper
Help to include files, link Assets etc. */

class UrlHelper
{
    protected $url;
    protected $subfolder;

    public function __construct($subfolder = '/tinyMCV')
    {
        $this->subfolder = $subfolder;
        $this->url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$subfolder.'/';
    }

    public function assets($file, $conv = true, $return = false)
    {
        $file = $this->url.'assets/'.($conv ? $this->coolLink($file) : $file);

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

    public function incView($name, $conv = true)
    {
        // __ROOT__ defined in index.php
        return  __ROOT__.'/app/Views/'.($conv ? $this->coolLink($name) : $name);
    }

    public function getSubFold()
    {
        return $this->subfolder;
    }

    private function coolLink($link)
    {
        $position = strtolower(array_slice(explode('.', $link), -2, 1)[0]) === 'min' ? 2 : 1;

        return preg_replace('/\./', '/', $link, substr_count($link, '.') - $position);
    }
}
