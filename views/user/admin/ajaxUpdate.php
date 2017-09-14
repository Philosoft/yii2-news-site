<?php

use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(
    [
        'layout' => 'horizontal',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]
);
echo $this->render("/admin/_user", ["user" => $user]);

$form::end();