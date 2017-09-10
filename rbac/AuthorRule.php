<?php


namespace app\rbac;


use app\models\News;
use yii\rbac\Rule;

class AuthorRule extends Rule
{
    public $name = "author-rule";

    public function execute($user, $item, $params)
    {
        $result = false;

        if (
            isset($params["post"])
            && $params["post"] instanceof News
            && $params["post"]->author === $user
        ) {
            $result = true;
        }

        return $result;
    }
}