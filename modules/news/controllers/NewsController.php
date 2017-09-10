<?php

namespace app\modules\news\controllers;


use app\modules\news\helpers\PaginationHelper;
use app\modules\news\models\News;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    const PERMISSION__VIEW_FULL_POST = "views-full-post";

    public function behaviors()
    {
        return [
            [
                "class" => AccessControl::class,
                "rules" => [
                    [
                        "allow" => true,
                        "actions" => ["index", "set-page-size"],
                        "roles" => ["?", "@"]
                    ],
                    [
                        "allow" => true,
                        "actions" => ["show"],
                        "roles" => [News::PERMISSION__READ_POST]
                    ]
                ]
            ]
        ];
    }

    public function actionSetPageSize($pageSize = 10)
    {
        PaginationHelper::setPageSize($pageSize);
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            "query" => News::find()->where(["status" => News::STATUS_ACTIVE]),
            "pagination" => [
                "pageSize" => PaginationHelper::getPageSize()
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

    /**
     * @param int $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionShow($id = 0)
    {
        $newsItem = News::findOne(["id" => $id, "status" => News::STATUS_ACTIVE]);

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