<?php

namespace app\modules\news\models;


use app\helpers\RbacHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NewsSearch extends News
{
    public $date_from;
    public $date_to;

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [
            ["date_from", "date_to"],
            "datetime"
        ];

        return $rules;
    }

    public function search($params)
    {
        $query = News::find();
        $user = \Yii::$app->user;
        if (RbacHelper::checkUserRole(RbacHelper::ROLE__ADMIN)) {
            $query->andWhere(["author_id" => $user->id]);
        }

        $dataProvider = new ActiveDataProvider(["query" => $query]);

        if (!($this->load($params)) && $this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(["id" => $this->id]);
        $query->andFilterWhere(["status" => $this->status]);
        $query->andFilterWhere(["like", "title", $this->title]);

        $query->andFilterWhere([">=", "date_added", $this->date_from]);
        $query->andFilterWhere(["<=", "date_added", $this->date_to]);

        return $dataProvider;
    }
}