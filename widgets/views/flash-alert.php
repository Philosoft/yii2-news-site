<?php

/**
 * @var array $htmlOptions
 * @var string $message
 */

echo \yii\bootstrap\Alert::widget([
    "options" => $htmlOptions,
    "body" => $message
]);