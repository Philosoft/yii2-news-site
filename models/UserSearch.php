<?php


namespace app\models;

use Da\User\Search\UserSearch as BaseSearch;

class UserSearch extends BaseSearch
{
    public $id;

    public function rules()
    {
        $rules = parent::rules();
        $rules["safeFields"][0][] = "id";

        return $rules;
    }

    public function search($params)
    {
        $dp = parent::search($params);

        $dp->query->andFilterWhere(["id" => $this->id]);
        return $dp;
    }


}