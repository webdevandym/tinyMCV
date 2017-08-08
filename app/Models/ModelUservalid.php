<?php

namespace app\Models;

use app\core\Model;
use app\Helper\Users\User;

class ModelUservalid extends Model
{
    public function get_data($userInfo)
    {
        if (isset($userInfo->user)) {
            $user = User::ValidUser($userInfo->user);
            $userN = is_object($user) ? $user->name : '';

            if (!isset($userInfo->sendResult)) {
                if (!$userN) {
                    return '<i class="fa fa-times" aria-hidden="true" style = "color:#e51212;text-shadow: 0px 2px 8px  rgba(201, 12, 34, 0.8);"></i>';
                }

                return '<i class="fa fa-check" aria-hidden="true"></i>';
            }

            if (!empty($userN)) {
                User::setUser(['user', 'userName'], $user);

                return true;
            }
        }

        return false;
    }
}
