<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var NewsSearch $filterModel
 */


use app\modules\news\models\News;
use app\modules\news\models\NewsSearch;
use kartik\daterange\DateRangePicker;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;

echo Html::a(
    "<span class=\"glyphicon glyphicon-plus\"></span> create",
    [
        "/news/admin/create"
    ],
    [
        "class" => "btn btn-success",
        "id" => "news-create"
    ]
);

/** @noinspection PhpUnhandledExceptionInspection */
echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel" => $filterModel,
    "columns" => [
        "id",
        [
            "class" => 'app\components\CheckboxColumn',
            "attribute" => "status",
            "action" => "/news/admin/toggle-status",
            "ajaxMethod" => "GET",
            "filter" => Html::activeDropDownList(
                $filterModel,
                "status",
                $filterModel->getStatusSelection(),
                [
                    "class" => "form-control"
                ]
            )
        ],
        "title",
        [
            "attribute" => "date_added",
            "filter" => DateRangePicker::widget([
                "model" => $filterModel,
                "attribute" => "date",
                "pluginOptions" => [
                    "locale" => [
                        "format" => "YYYY-MM-DD",
                        "separator" => NewsSearch::DATE_SEPARATOR
                    ]
                ]
            ])
        ],
        "announce",
        [
            "class" => 'yii\grid\ActionColumn',
            "buttons" => [
                "update" => function ($url, $model, $key) {
                    $button = "";
                    if (Yii::$app->user->can(News::PERMISSION__UPDATE, ["post" => $model])) {
                        $button = Html::a(
                            "<span class=\"glyphicon glyphicon-pencil\"></span>",
                            $url,
                            [
                                "class" => ["ajax-update"],
                                "data-id" => $model->id
                            ]
                        );
                    }

                    return $button;
                },
                "view" => function ($url, $model, $key) {
                    return Html::a(
                        "<span class=\"glyphicon glyphicon-eye-open\"></span>",
                        [
                            "/news/news/show",
                            "id" => $model->id
                        ]
                    );
                }
            ]
        ]
    ]
]);

$this->registerJs(<<<ENDJS
    $(".ajax-update").click(function (e) {
        e.preventDefault();
        
        var \$this = $(this);
        var modelId = \$this.data("id");

        var modal = $("#modal-update");
        modal.find(".modal-header .post-name").text(modelId);
        modal.find(".modal-body").load(\$this.attr("href"));
        modal.modal("show"); 
    });
    
    $("#news-create").click(function (e) {
        e.preventDefault();
        
        var modal = $("#modal-create");
        modal.find(".modal-body").load("/news/admin/create");
        modal.modal("show");
    });
ENDJS
);

echo Modal::widget([
    "id" => "modal-update",
    "header" => "Updating post #<span class='post-name'></span>"
]);
echo Modal::widget([
    "id" => "modal-create",
    "header" => "Create new post"
]);