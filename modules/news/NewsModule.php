<?php


namespace app\modules\news;


use app\commands\RbacController;
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
    }
}