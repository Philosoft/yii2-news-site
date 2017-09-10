<?php
/**
 * @var \yii\web\View $this
 * @var \app\modules\news\models\News $model
 */

$this->title = "Updating news item # {$model->id}";

echo $this->render("_update-form", ["model" => $model]);