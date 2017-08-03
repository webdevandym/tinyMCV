<?php
namespace app\Controllers;

use app\core\Controller;
use app\Models\ModelPortfolio;
use app\core\View;

class ControllerPortfolio extends Controller
{
    public function __construct()
    {
        $this->model = new ModelPortfolio();
        $this->view = new View();
    }

    public function actionIndex()
    {
        $data = $this->model->get_data();
        $this->view->generate('portfolio.php', 'template.php', $data);
    }
}
