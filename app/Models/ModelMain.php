<?php

namespace app\Models;

use app\core\Model;
use app\Helper\Users\User;

class ModelMain extends Model
{
    public function get_data()
    {
        $userInfo = User::checkLoggedUser();

        $userInfo->page = empty($userInfo->user) ? 'main' : 'view';

        return $userInfo;
    }
}
