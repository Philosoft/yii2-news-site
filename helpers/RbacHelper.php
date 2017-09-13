<?php

namespace app\helpers;

use Yii;

class RbacHelper
{
    const ROLE__MANAGER = "manager";
    const ROLE__REGISTERED_USER = "ordinary-user";
    const ROLE__ADMIN = "admin";

    const USER_ID__ADMIN = 1;
    const USER_ID__MANAGER = 2;

    public static function checkUserRole($roleName = "")
    {
        return array_key_exists(
            $roleName,
            Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())
        );
    }
}