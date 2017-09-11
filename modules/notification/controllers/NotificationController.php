<?php


namespace app\modules\notification\controllers;


use app\modules\notification\models\Notification;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class NotificationController extends Controller
{
    const STATUS__OK = "ok";
    const STATUS__ERROR = "error";

    public function behaviors()
    {
        return [
            [
                "class" => AccessControl::class,
                "rules" => [
                    [
                        "allow" => true,
                        "actions" => ["dismiss"],
                        "roles" => ["@"]
                    ]
                ]
            ]
        ];
    }

    public function actionDismiss($id = 0)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $status = self::STATUS__ERROR;

        $model = Notification::findOne([
            "id" => $id,
            "user_id" => Yii::$app->user->getId()
        ]);

        if ($model !== null) {
            $model->is_read = true;
            if ($model->save()) {
                $status = self::STATUS__OK;
            }
        }

        return ["status" => $status];
    }
}