<?php

namespace app\Helper\Http;

class Response
{
    public static function json($data)
    {
        header('Content-type: applicetion/json');
        echo preg_replace('/ {2,}/', ' ', str_replace(['\n', '\t', '\r'], '', json_encode($data)));
    }
}
