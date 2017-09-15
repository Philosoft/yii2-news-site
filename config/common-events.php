<?php


\yii\base\Event::on(
    \Da\User\Model\User::class,
    \Da\User\Model\User::EVENT_AFTER_UPDATE,
    function ($event) {
        if (!empty($event->changedAttributes) && !empty($event->changedAttributes["password_hash"])) {
            \Yii::$app->mailer->compose(
                "@app/mail/user__changed-password"
            )
                ->setFrom(\Yii::$app->params["emailFrom"])
                ->setTo($event->sender->email)
                ->setSubject("Your password has been changed")
                ->send();
        }
    }
);