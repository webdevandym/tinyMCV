<?php

namespace app\core;

use app\Helper\Http\Render;

/**
 *Main View Class.
 *generate() return complex page
 *pageLoader() dynamic page switcher, best use with ajax.
 */

class View
{
    public function generate($contentView, $templateView, $data = null)
    {
        $url = new \app\Helper\Http\UrlHelper();
        //$url avaliable in tepmlate and page views
        include 'app/Views/'.$templateView;
    }

    public function pageLoader($page)
    {
        //dynamic page render
        $rending = new Render($page);
        $page = $rending->getPage();

        return $page;
    }
}
