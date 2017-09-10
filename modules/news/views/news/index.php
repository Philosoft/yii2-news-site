<?php

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */


use yii\widgets\ListView;

echo $this->render("_perPage-selector");

echo ListView::widget([
    "dataProvider" => $dataProvider,
    "itemView" => "_newsItem"
]);