<?php

namespace app\Helper\Http;

class Request
{
    public function __construct()
    {
        $data = $this->loadRequest();
        $this->getRequest($data, $this);
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
        if (is_string($str)) {
            if (preg_match('@^[a-zA-Z0-9%+-_]*$@', $str)) {
                $str = rawurldecode($str);
            }

            return stripslashes(htmlentities(strip_tags($str)));
        }
        throw new \Exception(print_r($str));
    }

    private function getRequest($data, $object)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $object->{$key} = new \StdClass();
                    $this->getRequest($value, $object->{$key});
                } else {
                    $object->{$key} = $this->clearString($value);
                }
            }
        }
    }
}
