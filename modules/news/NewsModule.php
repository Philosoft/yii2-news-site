<?php


namespace app\modules\news;


use app\commands\RbacController;
use app\modules\news\models\News;
use Da\User\Event\UserEvent;
use Da\User\Model\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\Module;

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
                        $authManager->getRole(RbacController::ROLE__REGISTERED_USER),
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
                            Yii::$app->mailer->compose("new-post", ["post" => $model, "user" => $user])
                                ->setSubject("New article: {$model->title}")
                                ->setTo($user->email)
                                ->setFrom("noreply@example.com")
                                ->send();
                        }
                    }
                }
            }
        );
    }
}