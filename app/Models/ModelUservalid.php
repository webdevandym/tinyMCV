<?php

namespace app\Models;

use app\core\Model;
use app\Helper\Users\User;

class ModelUservalid extends Model
{
    private $response = [
      'correct' => '<i class="fa fa-check" aria-hidden="true"></i>',
      'incorrect' => '<i class="fa fa-times" aria-hidden="true" style = "color:#e51212;text-shadow: 0px 2px 8px  rgba(201, 12, 34, 0.8);"></i>',
  ];

    public function get_data($userInfo)
    {
        if (isset($userInfo->user)) {
            $user = User::ValidUser($userInfo->user);
            $userN = property_exists($user, 'name') ? $user->name : '';

            if (!isset($userInfo->sendResult)) {
                if (!$userN) {
                    return $response['incorrect'];
                }

                return $response['correct'];
            }

            if (!empty($userN)) {
                User::setUser(['user', 'userName'], $user);

                return true;
            }
        }

        return false;
    }
}
