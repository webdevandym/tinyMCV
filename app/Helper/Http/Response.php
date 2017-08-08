<?php

namespace app\Helper\Http;

class Response
{
    public static function json($data)
    {
        header('Content-type: applicetion/json');
        echo json_encode($data);
    }
}
