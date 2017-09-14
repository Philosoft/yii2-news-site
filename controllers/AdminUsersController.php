<?php


namespace app\controllers;

use Da\User\Controller\AdminController as BaseController;
use Da\User\Event\UserEvent;
use Da\User\Factory\MailFactory;
use Da\User\Model\User;
use Da\User\Service\UserCreateService;
use Da\User\Validator\AjaxRequestModelValidator;
use Yii;

class AdminUsersController extends BaseController
{
    public function actionAjaxCreate()
    {
        /** @var User $user */
        $user = $this->make(User::class, [], ['scenario' => 'create']);

        /** @var UserEvent $event */
        $event = $this->make(UserEvent::class, [$user]);

        $this->make(AjaxRequestModelValidator::class, [$user])->validate();

        if ($user->load(Yii::$app->request->post())) {
            $this->trigger(UserEvent::EVENT_BEFORE_CREATE, $event);

            $mailService = MailFactory::makeWelcomeMailerService($user);

            if ($this->make(UserCreateService::class, [$user, $mailService])->run()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('usuario', 'User has been created'));
                $this->trigger(UserEvent::EVENT_AFTER_CREATE, $event);

                return $this->renderAjax("ajaxCreate", ['user' => $user]);
            }
        }

        return $this->renderAjax('ajaxCreate', ['user' => $user]);
    }

    public function actionAjaxUpdate($id)
    {
        $user = $this->userQuery->where(['id' => $id])->one();
        $user->setScenario('update');
        /** @var UserEvent $event */
        $event = $this->make(UserEvent::class, [$user]);

        $this->make(AjaxRequestModelValidator::class, [$user])->validate();

        if ($user->load(Yii::$app->request->post())) {
            $this->trigger(ActiveRecord::EVENT_BEFORE_UPDATE, $event);

            if ($user->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('usuario', 'Account details have been updated'));
                $this->trigger(ActiveRecord::EVENT_AFTER_UPDATE, $event);
            }
        }

        return $this->renderAjax('_account', ['user' => $user]);
    }
}