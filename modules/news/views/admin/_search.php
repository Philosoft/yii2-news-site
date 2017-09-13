<?php
/**
 * @var yii\web\View $this
 * @var app\modules\news\models\NewsSearch $model
 */

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" href="#news-search" aria-expanded="true" aria-controls="collapseOne">
                <span class="glyphicon glyphicon-filter"></span> Filters
            </a>
        </h4>
    </div>
    <div id="news-search" class="panel-collapse collapse in">
        <div class="panel-body">
            <h3>Search form</h3>
            <?php
            $form = ActiveForm::begin([
                "action" => ["index"],
                "method" => "get"
            ]);

            echo $form->field($model, "id");
            echo $form->field($model, "status");
            echo $form->field($model, "title");
            echo $form->field($model, "date_from")->widget(
                DatePicker::class,
                [
                    "type" => DatePicker::TYPE_COMPONENT_PREPEND,
                    "pluginOptions" => [
                        "autoclose" => true,
                        "format" => "yyyy-mm-dd"
                    ]
                ]
            );
            echo $form->field($model, "date_to")->widget(
                DatePicker::class,
                [
                    "type" => DatePicker::TYPE_COMPONENT_PREPEND,
                    "pluginOptions" => [
                        "autoclose" => true,
                        "format" => "yyyy-mm-dd"
                    ]
                ]
            );
            echo $form->field($model, "announce");
            ?>

            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                <?= Html::submitButton('Reset', ['class' => 'btn btn-default']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>