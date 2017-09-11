<?php

namespace app\models;

use app\modules\notification\models\Notification;
use Da\User\Model\User as BaseUser;

/**
 * Class User
 * @package app\models
 *
 * @inheritdoc
 *
 * @property Notification[] $notifications
 */
class User extends BaseUser
{
    public function getNotifications()
    {
        return $this->hasMany(
            Notification::class,
            ["user_id" => "id"]
        )->where(["is_read" => 0]);
    }
}