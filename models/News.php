<?php

namespace app\models;


use yii\db\ActiveRecord;

/**
 * Class News
 * @package app\models
 *
 * @property int $id
 * @property int $status
 * @property string $title
 * @property string $announce
 * @property string $content
 * @property string $image
 * @property string $date_added
 * @property string $date_modified
 */
class News extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function tableName()
    {
        return "{{news}}";
    }

    public function rules()
    {
        return [
            [
                "id",
                "integer",
                "min" => 1
            ],
            [
                [
                    "announce", "content", "status", "title", "image"
                ],
                "safe"
            ],
            [
                [
                    "announce", "content"
                ],
                "string",
                "min" => 10
            ],
            [
                "status",
                "boolean"
            ],
            [
                ["date_added", "date_modified"],
                "datetime"
            ]
        ];
    }
}