<?php

namespace app\modules\news\models;


use app\helpers\RbacHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NewsSearch extends News
{
    const STATUS_ANY = 2;
    const DATE_SEPARATOR = " to ";

    public $date;
    public $date_from;
    public $date_to;

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function rules()
    {
        return [
            [
                ["date_from", "date_to"],
                "datetime"
            ],
            [
                "id",
                "integer"
            ],
            [
                "status",
                "boolean"
            ],
            [
                "announce",
                "string"
            ],
            [
                [
                    "date_from",
                    "date_to",
                    "id",
                    "status",
                    "announce",
                    "title",
                    "date"
                ],
                "safe"
            ]
        ];
    }

    public function getStatusSelection()
    {
        return [
            self::STATUS_ANY => "any",
            self::STATUS_INACTIVE => "disabled",
            self::STATUS_ACTIVE => "enabled",
        ];
    }

    public function search($params)
    {
        $query = News::find();
        $user = \Yii::$app->user;
        if (!RbacHelper::checkUserRole(RbacHelper::ROLE__ADMIN)) {
            $query->andWhere(["author_id" => $user->id]);
        }

        $dataProvider = new ActiveDataProvider(["query" => $query]);

        if (!($this->load($params)) && $this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(["id" => $this->id]);
        if ($this->status != self::STATUS_ANY) {
            $query->andFilterWhere(["status" => $this->status]);
        }
        $query->andFilterWhere(["like", "title", $this->title]);
        $query->andFilterWhere(["like", "announce", $this->announce]);

        if (!empty($this->date)) {
            list($this->date_from, $this->date_to) = explode(self::DATE_SEPARATOR, $this->date);
        }

        $query->andFilterWhere([">=", "date_added", $this->date_from]);
        $query->andFilterWhere(["<=", "date_added", $this->date_to]);

        return $dataProvider;
    }
}