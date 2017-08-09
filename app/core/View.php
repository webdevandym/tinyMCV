<?php

namespace app\core;

use app\Helper\Http\Render;

/**
 *Main View Class.
 */
class View
{
    public function generate($contentView, $templateView, $data = null)
    {
        $url = new \app\Helper\Http\UrlHelper();

        include 'app/Views/'.$templateView;
    }

    public function pageLoader($page)
    {
        $rending = new Render($page);
        $page = $rending->getPage();

        return $page;
    }
}
