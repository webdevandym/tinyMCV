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

        if (!empty($data)) {
            $this->statusRequest = 1;
        } else {
            $this->statusRequest = 0;
        }
    }

    private function loadRequest()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
          case 'POST':
            return $_POST;
          case 'GET':
            return $_GET;
          default:
            return null;
        }
    }

    private function clearString($str)
    {
        return stripslashes(htmlentities(strip_tags($str)));
    }
}
