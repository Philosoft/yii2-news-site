<?php

/**
 * @var array $htmlOptions
 * @var array|string $messages
 */

foreach ($messages as $message) {
    echo \yii\bootstrap\Alert::widget([
        "options" => $htmlOptions,
        "body" => $message
    ]);
}
