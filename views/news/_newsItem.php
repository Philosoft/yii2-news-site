<?php

/**
 * @var \app\models\News $model
 */

?>

<div class="col-md-6 news-item">
    <div class="media">
        <div class="media-left">
            <img src="<?= $model->image ?>" class="media-object">
        </div>
        <div class="media-body">
            <div class="media-heading news-item__title"><?= $model->title ?></div>
            <div class="news-item__announce"><?= $model->announce ?></div>
            <div class="news-item__read-more">
                <?= \yii\helpers\Html::a(
                    "read more",
                    [
                        "/news/show",
                        "id" => $model->id
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>
