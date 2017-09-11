<?php


namespace app\modules\notification;


use yii\base\Module;

class NotificationModule extends Module
{
    public $template = "New post - '{postName}' - is out! Check it out {postUrl}";
    public $flashKey = "user-notification";
}