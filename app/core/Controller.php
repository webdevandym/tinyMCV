<?php

namespace app\core;

/**
 * Main Class Controller;.
 */
class Controller
{
    public $model;
    public $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function actionIndex()
    {
    }

    public function getParams()
    {
        $params = new \StdClass();

        if (isset($_POST)) {
            $store = $_POST;
        } elseif (isset($_GET)) {
            $store = $_GET;
        } else {
            $store = ['11' => '12'];
        }

        foreach ($store as $key => $value) {
            $params->$key = $value;
        }

        return $store;
    }
}
