<?php


namespace app\modules\news;


use app\helpers\RbacHelper;
use app\models\Profile;
use app\modules\news\models\News;
use app\modules\notification\models\Notification;
use Da\User\Event\UserEvent;
use Da\User\Model\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\Module;
use yii\helpers\Html;

class NewsModule extends Module implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Event::on(
            User::class,
            UserEvent::EVENT_AFTER_REGISTER,
            function ($event) {
                /** @var User $user */
                $user = $event->sender;
                $authManager = \Yii::$app->authManager;
                try {
                    $authManager->assign(
                        $authManager->getRole(RbacHelper::ROLE__REGISTERED_USER),
                        $user->getId()
                    );
                } catch (\Exception $e) {
                    Yii::error($e->getMessage());
                }
            }
        );

        Event::on(
            News::class,
            News::EVENT_AFTER_INSERT,
            function ($event) {
                /** @var News $model */
                $model = $event->sender;

                if ($model->isActive()) {
                    foreach (User::find()->where(["IS NOT", "confirmed_at", null])->batch() as $userPack) {
                        foreach ($userPack as $user) {
                            /** @var User $user */
                            /** @var Profile $profile */
                            $profile = $user->profile;
                            if ($profile->notification_type === Profile::NOTIFICATION_TYPE__ALERT) {
                                $notification = new Notification();
                                $notification->text = strtr(
                                    Yii::$app->getModule("notification")->template,
                                    [
                                        "{postName}" => $model->title,
                                        "{postUrl}" => Html::a(
                                            "read",
                                            ["/news/news/show", "id" => $model->id]
                                        )
                                    ]
                                );
                                $notification->link("user", $user);
                                $notification->save();
                            }

                            if ($profile->notification_type === Profile::NOTIFICATION_TYPE__MAIL) {
                                Yii::$app->mailer->compose("new-post", ["post" => $model, "user" => $user])
                                    ->setSubject("New article: {$model->title}")
                                    ->setTo($user->email)
                                    ->setFrom("noreply@example.com")
                                    ->send();
                            }
                        }
                    }
                }
            }
        );
    }
}