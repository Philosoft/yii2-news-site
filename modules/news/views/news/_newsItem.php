<?php

/**
 * @var News $model
 */

use app\modules\news\models\News;

?>

<div class="col-md-6 news-item">
    <div class="media">
        <div class="media-left">
            <img src="<?= $model->getImage() ?>" class="media-object" style="max-width: 250px;">
        </div>
        <div class="media-body">
            <div class="media-heading news-item__title"><?= $model->title ?></div>
            <div class="news-item__announce"><?= $model->announce ?></div>
            <?php if (\Yii::$app->user->can(News::PERMISSION__READ_POST)): ?>
            <div class="news-item__read-more">
                <?= \yii\helpers\Html::a(
                    "read more",
                    [
                        "/news/news/show",
                        "id" => $model->id
                    ]
                ) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
