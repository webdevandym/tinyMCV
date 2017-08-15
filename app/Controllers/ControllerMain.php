<?php

namespace app\Controllers;

use app\core\Controller;
use app\core\View;
use app\Helper\Http\Request;
use app\Helper\Http\Response;
use app\Models\ModelMain;

class ControllerMain extends Controller
{
    public function __construct()
    {
        $this->model = new ModelMain();
        $this->view = new View();
    }

    public function actionIndex(Request $request)
    {
        $data = $this->model->get_data();

        $this->{$data->page}($data, $request);
    }

    public function actionLogout(Request $request)
    {
        return Response::json(\app\Helper\Users\User::destroySESSION());
    }

    private function main(\stdClass $data)
    {
        $this->view->generate('main.php', 'maintemplate.php', $data);
    }

    private function view(\stdClass $data, $request)
    {
        if (!$request->statusRequest) {
            $this->view->generate('view.php', 'viewtemplate.php', $data);
        } else {
            $page = $this->view->pageLoader($request->page);

            return Response::json($page);
        }
    }
}
