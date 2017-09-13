<?php
/**
 * @var \yii\web\View $this
 * @var \app\modules\news\models\News $model
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @noinspection PhpUnhandledExceptionInspection */
echo \app\widgets\FlashAlert::widget([
    "flashKey" => \app\modules\news\models\News::FLASH_KEY__UPDATE_STATUS,
    "htmlOptions" => [
        "class" => "alert-info"
    ]
]);

if ($model->isNewRecord === true) {
    $actionUrl = Url::to(["/news/admin/create"]);
    $action = "create";
} else {
    $actionUrl = Url::to([
        "/news/admin/update",
        "id" => $model->id
    ]);
    $action = "update";
}

$form = ActiveForm::begin([
    "method" => "post",
    "action" => $actionUrl,
    "options" => [
        "data" => [
            "model-id" => $model->id
        ],
        "id" => "update-form--" . date("His"),
        "class" => "news-update-form"
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
    $action,
    [
        "class" => "btn btn-success",
    ]
);

ActiveForm::end();

$js = <<<ENDJS
if ($("body").data("news-{$action}-form-handler") !== true) {
    $("body").on("submit", ".news-update-form", function (e) {
        e.preventDefault();
        
        var \$form = $(this);
        var fd = new FormData(this);
        
        $.ajax(
            \$form.attr("action"),
            {
                type: "post",
                data: fd,
                processData: false,
                contentType: false
            }
        )
        .done(function (data) {
            \$form.parent().html(data);
        });
    });
    $("body").data("news-update-form-handler", true);
}
ENDJS;

$this->registerJs($js);