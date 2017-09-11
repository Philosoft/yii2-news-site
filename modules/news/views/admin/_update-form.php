<?php
/**
 * @var \yii\web\View $this
 * @var \app\modules\news\models\News $model
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin(["enablePushState" => false]);

/** @noinspection PhpUnhandledExceptionInspection */
echo \app\widgets\FlashAlert::widget([
    "flashKey" => \app\modules\news\models\News::FLASH_KEY__UPDATE_STATUS,
    "htmlOptions" => [
        "class" => "alert-info"
    ]
]);

if ($model->isNewRecord === true) {
    $actionUrl = Url::to(["/news/admin/create"]);
} else {
    $actionUrl = Url::to([
        "/news/admin/update",
        "id" => $model->id
    ]);
}

$form = ActiveForm::begin([
    "method" => "post",
    "action" => $actionUrl,
    "options" => [
        "data" => [
            "pjax" => true
        ],
        "id" => "update-form--" . date("His")
    ]
]);

echo $form->field($model, "status")->checkbox();
echo $form->field($model, "title");
if (!empty($model->image)) {
    echo Html::img(
        $model->getImage(),
        [
            "style" => "max-width: 250px;"
        ]
    );
}
echo $form->field($model, "imageFile")->fileInput();
echo $form->field($model, "announce")->textarea();
echo $form->field($model, "content")->textarea();

echo Html::submitButton(
    "update",
    [
        "class" => "btn btn-success"
    ]
);

ActiveForm::end();
Pjax::end();