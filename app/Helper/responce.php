<?php

namespace app\Helper;

class responce
{
    public static function json($data)
    {
        header('Content-type: applicetion/json');
        echo json_encode($data);
    }
}
