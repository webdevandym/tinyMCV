<?php

namespace app\Controllers;

use app\core\Controller;
use app\Helper\Http\Request;
use app\Helper\Http\Response;
use app\Models\ModelUservalid;

class ControllerUservalid extends Controller
{
    public function __construct()
    {
        $this->model = new ModelUservalid();
    }

    public function actionValidate(Request $request)
    {
        $result = $this->model->get_data($request);

        return Response::json($result);
    }
}
