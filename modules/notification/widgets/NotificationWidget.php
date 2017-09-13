<?php


namespace app\modules\notification\widgets;


use app\modules\notification\models\Notification;
use Yii;
use yii\base\Widget;

class NotificationWidget extends Widget
{
    public $flashKey;

    public function init()
    {
        if (empty($this->flashKey)) {
            $this->flashKey = Yii::$app->getModule("notification")->flashKey;
        }
        parent::init();
    }

    public function run()
    {
        $user = Yii::$app->user;
        if (!$user->isGuest) {
            $notifications = Notification::find()->where(
                [
                    "user_id" => $user->getId(),
                    "is_read" => 0
                ]
            )->all();

            return $this->render(
                "notification",
                ["notifications" => $notifications]
            );
        }
    }
}