<?php

namespace app\core;

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
}
