<?php

namespace app\controllers;


use app\models\News;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            "query" => News::find()->where(["status" => News::STATUS_ACTIVE]),
            "pagination" => [
                "pageSize" => 10
            ],
            "sort" => [
                "defaultOrder" => [
                    "date_added" => SORT_DESC,
                ]
            ],
        ]);

        return $this->render(
            "index",
            [
                "dataProvider" => $dataProvider
            ]
        );
    }

    public function actionShow($id = 0)
    {
        $newsItem = News::findOne($id);

        if ($newsItem !== null) {
            return $this->render(
                "show",
                [
                    "model" => $newsItem
                ]
            );
        } else {
            throw new NotFoundHttpException("there are no such news item");
        }
    }
}