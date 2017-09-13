<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\modules\news\models\News;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\helpers\RbacHelper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Simple news',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $links = [
        ['label' => 'Home', 'url' => ['/']],
        ['label' => 'About', 'url' => ['/site/about']],
        Yii::$app->user->isGuest ? (
        ['label' => 'Login', 'url' => ['/user/security/login']]
        ) : (
            '<li>'
            . Html::beginForm(['/user/security/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>'
        )
    ];
    if (
        Yii::$app->user->can(News::PERMISSION__UPDATE)
        || RbacHelper::checkUserRole(RbacHelper::ROLE__MANAGER)
    ) {
        $links[] = [
            "label" => "News CRUD",
            "url" => ["/news/admin/index"]
        ];
    }

    if (!Yii::$app->user->isGuest) {
        $links[] = [
            "label" => "my account",
            "url" => ["/user/settings/profile"]
        ];
    }

    if (RbacHelper::checkUserRole(RbacHelper::ROLE__ADMIN)) {
        $links[] = [
            "label" => "Users CRUD",
            "url" => ["/user/admin/index"]
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $links,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=
                /** @noinspection PhpUnhandledExceptionInspection */
                \app\modules\notification\widgets\NotificationWidget::widget();
                ?>
            </div>
        </div>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Simple news <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
