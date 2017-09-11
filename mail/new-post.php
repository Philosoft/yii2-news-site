<?php
/**
 * @var \app\modules\news\models\News $post
 * @var \Da\User\Model\User $user
 */
?>

Hi, <?= $user->username ?>. We'v got new shiny-thingy for ya: <?= \yii\helpers\Html::a($post->title, \yii\helpers\Url::to([
    "/news/news/show",
    "id" => $post->id
])) ?>
