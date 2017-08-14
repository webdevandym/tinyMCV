<?php

namespace app\Controllers;

use app\core\Controller;
use app\Helper\Http\Request;
use app\Helper\Http\Response;

/**
 * Query Controller.
 */
class ControllerQuery extends Controller
{
    public function actionGetdata(Request $request)
    {
        $this->runner('get', $request);
    }

    public function actionEditdata(Request $request)
    {
        $this->runner('edit', $request);
    }

    private function runner($location, Request $request)
    {
        $modelNAme = '\app\Models\ModelQuery'.ucfirst(strtolower($location));
        $this->model = new $modelNAme();
        $this->model->init($request);
        $res = $this->model->runVisiter();

        return Response::json($res);
    }
}
