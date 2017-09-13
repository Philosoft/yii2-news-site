<?php

namespace app\modules\news\controllers;


use app\modules\news\models\News;
use app\modules\news\models\NewsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::class,
                "rules" => [
                    [
                        "allow" => true,
                        "actions" => ["index"],
                        "roles" => [News::PERMISSION__MANAGE]
                    ],
                    [
                        "allow" => true,
                        "actions" => ["create"],
                        "roles" => [News::PERMISSION__CREATE_POST]
                    ],
                    [
                        "allow" => true,
                        "actions" => ["update", "toggle-status"],
                        "roles" => [News::PERMISSION__UPDATE],
                        "roleParams" => function ($rule) {
                            return [
                                "post" => News::findOne([
                                    "id" => Yii::$app->request->get("id")
                                ])
                            ];
                        }
                    ],
                    [
                        "allow" => true,
                        "actions" => ["delete"],
                        "roles" => [News::PERMISSION__DELETE_POST]
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $filterModel = new NewsSearch();
        $dataProvider = $filterModel->search(Yii::$app->request->get());

        return $this->render(
            "index",
            [
                "dataProvider" => $dataProvider,
                "filterModel" => $filterModel
            ]
        );
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id = 0)
    {
        $model = News::findOne(["id" => $id]);

        if ($model === null) {
            throw new NotFoundHttpException("model with id {$id} cannot be found");
        }

        $request = Yii::$app->request;
        $renderMethod = $request->isAjax ? "renderAjax" : "render";

        if ($request->isPost) {
            $model->load($request->post());
            $model->imageFile = UploadedFile::getInstance($model, "imageFile");
            $model->upload();

            if ($model->save()) {
                Yii::$app->session->setFlash(
                    News::FLASH_KEY__UPDATE_STATUS,
                    "successful update"
                );
            } else {
                $errors = [];
                if (!empty($model->errors)) {
                    $errors = array_map(
                        function ($attributeErrors) {
                            return implode("\n", $attributeErrors);
                        },
                        array_values($model->errors)
                    );
                    $errors[] = $model->date_modified;
                }

                if (!empty($errors)) {
                    Yii::$app->session->setFlash(
                        News::FLASH_KEY__UPDATE_STATUS,
                        "errors: " . Html::ul($errors)
                    );
                }
            }
        }

        return $this->$renderMethod("update", ["model" => $model]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new News();
        $renderMethod = $request->isAjax ? "renderAjax" : "render";
        if (!$request->isGet) {
            $model->load($request->post());
            $model->author_id = Yii::$app->user->getId();
            if ($model->save()) {
                $message = "successful save";
            } else {
                $message = "error while saving";
            }

            Yii::$app->session->setFlash(
                News::FLASH_KEY__UPDATE_STATUS,
                $message
            );

        }

        return $this->$renderMethod(
            "_update-form",
            ['model' => $model]
        );
    }

    public function actionToggleStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ["status" => 0];

        $modelId = Yii::$app->request->get("id");
        if (Yii::$app->request->isAjax && $modelId !== null) {
            $model = News::findOne(["id" => $modelId]);

            if ($model !== null) {
                $model->status = !$model->status;
                $model->save();
                $result["status"] = $model->status;
            }
        }

        return $result;
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id = 0)
    {
        $model = News::findOne(["id" => $id]);

        if ($model === null) {
            throw new NotFoundHttpException("model with id {$id} not found");
        }

        $model->delete();
        $this->redirect(["/news/admin/index"]);
    }
}