<?php

namespace app\Controllers;

use app\core\Controller;
use app\Helper\Http\Request;
use app\Helper\Http\Response;
use app\Models\ModelQueryGet;

/**
 * Query Controller.
 */
class ControllerQuery extends Controller
{
    public function __construct()
    {
        $this->model = new ModelQueryGet();
    }

    public function actionGetdata(Request $request)
    {
        $this->model->init($request);
        $res = $this->model->runVisiter();

        return Response::json($res);
    }
}
