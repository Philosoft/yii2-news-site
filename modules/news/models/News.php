<?php

namespace app\modules\news\models;


use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\UploadedFile;

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
 * @property int $author_id
 *
 * @property UploadedFile $imageFile
 *
 * relation:
 *
 * @property \Da\User\Model\User $user
 */
class News extends ActiveRecord
{
    const PERMISSION__MANAGE = "news__manage";
    const PERMISSION__UPDATE = "news__update-post";
    const PERMISSION__UPDATE_OWN_POST = "news__update-own-post";
    const PERMISSION__READ_POST = "news__read-post";
    const PERMISSION__CREATE_POST = "news__create-post";
    const PERMISSION__DELETE_POST = "news__delete-post";

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const FLASH_KEY__UPDATE_STATUS = "news-update-status";

    public $imageFile;

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
                ["author_id"],
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
                ["imageFile"],
                "image",
                "skipOnEmpty" => true,
                "extensions" => "png, jpg"
            ],
            [
                [
                    "announce", "content"
                ],
                "string",
            ],
            [
                "status",
                "boolean"
            ],
            [
                ["date_added", "date_modified"],
                "datetime",
                "format" => "php:Y-m-d H:i:s"
            ]
        ];
    }

    public function behaviors()
    {
        return [
            [
                "class" => TimestampBehavior::class,
                "createdAtAttribute" => "date_added",
                "updatedAtAttribute" => "date_modified",
                "value" => new Expression("CURRENT_TIMESTAMP")
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            "status" => Yii::t("app", "Publication status")
        ];
    }

    public function upload()
    {
        if ($this->validate(["imageFile"]) && $this->imageFile !== null) {
            $fileName = "{$this->imageFile->baseName}.{$this->imageFile->extension}";
            $this->imageFile->saveAs(Yii::getAlias("uploads/{$fileName}"));
            $this->image = $fileName;
            $this->imageFile = null;
            return true;
        } else {
            return false;
        }
    }

    public function getImage()
    {
        if (!empty($this->image)) {
            return "/uploads/{$this->image}";
        } else {
            return "http://placehold.it/150x150";
        }
    }

    public function getAuthor()
    {
        return $this->hasOne(
            \Da\User\Model\User::class,
            [
                "id" => "author_id"
            ]
        );
    }
}