<?php


namespace app\modules\notification\models;


use Da\User\Model\User;
use yii\db\ActiveRecord;

/**
 * Class Notification
 * @package app\modules\notification\models
 *
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property boolean $is_read
 *
 * relations:
 * @property \app\models\User $user
 */
class Notification extends ActiveRecord
{
    public static function tableName()
    {
        return "{{notification}}";
    }

    public function rules()
    {
        return [
            [
                ["id", "user_id"],
                "integer"
            ],
            [
                ["text"],
                "string"
            ],
            [
                ["is_read"],
                "boolean"
            ]
        ];
    }

    public function getUser()
    {
        return $this->hasOne(
            User::class,
            ["id" => "user_id"]
        );
    }
}