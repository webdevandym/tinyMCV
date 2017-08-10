<?php

namespace app\Controllers;

use app\core\Controller;
use app\Helper\Http\Request;
use app\Helper\Http\Response;
use app\Models\ModelQueryresponse;

/**
 * Query Controller.
 */
class ControllerQuery extends Controller
{
    public function __construct()
    {
        $this->model = new ModelQueryresponse();
    }

    public function actionGet(Request $request)
    {
        $this->model->init($request);
        $res = $this->model->runVisiter();

        return Response::json($res);
    }
}
