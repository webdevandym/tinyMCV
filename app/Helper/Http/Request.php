<?php

namespace app\Helper\Http;

class Request
{
    public function __construct()
    {
        $data = $this->loadRequest();

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->{$key} = $this->clearString($value);
            }
        }
    }

    private function loadRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $_GET;
        }

        return null;
    }

    private function clearString($str)
    {
        return stripslashes(htmlentities(strip_tags($str)));
    }
}
