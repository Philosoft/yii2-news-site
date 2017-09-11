<?php


namespace app\models;

use Da\User\Model\Profile as BaseProfile;

/**
 * Class Profile
 * @package app\models
 *
 * @inheritdoc
 *
 * @property integer $notification_type
 */
class Profile extends BaseProfile
{
    const NOTIFICATION_TYPE__ALERT = 0;
    const NOTIFICATION_TYPE__MAIL = 1;
    const NOTIFICATION_TYPE__BOTH = 2;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            ["notification_type"],
            "integer",
            "min" => 0,
            "max" => 2
        ];

        return $rules;
    }

    public function getNotificationVariants()
    {
        return [
            self::NOTIFICATION_TYPE__ALERT => "alert only",
            self::NOTIFICATION_TYPE__MAIL => "mail only",
            self::NOTIFICATION_TYPE__BOTH => "alert + mail"
        ];
    }
}