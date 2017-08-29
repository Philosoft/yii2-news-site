<?php

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

echo \yii\widgets\ListView::widget([
    "dataProvider" => $dataProvider,
    "itemView" => "_newsItem"
]);