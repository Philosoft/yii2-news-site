<?php


namespace app\widgets;


use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class FlashAlert
 * @package app\widgets
 *
 * @property string $flashKey
 * @property array $htmlOptions
 */
class FlashAlert extends Widget
{
    public $flashKey = null;
    public $htmlOptions = [
        "class" => "alert-success"
    ];

    /**
     * @throws InvalidConfigException
     */
    public function run()
    {
        if ($this->flashKey === null) {
            throw new InvalidConfigException("flashKey must be set");
        }

        $messages = \Yii::$app->session->getFlash($this->flashKey);
        if (!empty($messages)) {
            if (!is_array($messages)) {
                $messages = [$messages];
            }
            return $this->render(
                "flash-alert",
                [
                    "messages" => $messages,
                    "htmlOptions" => $this->htmlOptions
                ]
            );
        } else {
            return "";
        }
    }
}