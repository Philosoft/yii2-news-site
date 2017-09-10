<?php

/**
 * @var \yii\web\View $this
 * @var \app\modules\news\models\News $model
 */

?>

<div class="row">
    <div class="col-md-12 news-item">
        <h1 class="news-item__title"><?= $model->title ?></h1>
        <div class="news-item__content">
            <div class="media">
                <div class="media-left">
                    <img src="<?= $model->getImage() ?>" class="media-object">
                </div>
                <div class="media-body">
                    <?= $model->content ?>
                </div>
            </div>
        </div>
    </div>
</div>
