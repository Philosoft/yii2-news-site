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

        if (Yii::$app->request->isPost) {
            $model->setAttributes(Yii::$app->request->post("News"));
            $model->imageFile = UploadedFile::getInstance($model, "imageFile");
            $model->upload();
            if ($model->save()) {
                Yii::$app->session->setFlash(
                    News::FLASH_KEY__UPDATE_STATUS,
                    "successful update"
                );
                return $this->renderAjax("_update-form", ["model" => $model]);
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
                        "errors: " . Html::ul($errors));
                }

                return $this->renderAjax("_update-form", ['model' => $model]);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax("_update-form", ["model" => $model]);
            } else {
                return $this->render("update", ["model" => $model]);
            }
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new News();
        if ($request->isGet) {
            $method = ($request->isAjax && !$request->isPjax) ? "renderAjax" : "render";
            return $this->$method("_update-form", ['model' => $model]);
        } else {
            $model->setAttributes($request->post("News"), false);
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

            return $this->render(
                "_update-form",
                ['model' => $model]
            );
        }
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