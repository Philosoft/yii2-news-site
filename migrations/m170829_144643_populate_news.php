<?php

use app\models\News;
use yii\db\Migration;

class m170829_144643_populate_news extends Migration
{
    public function safeUp()
    {
        $news = [];
        for ($i = 0; $i < 23; $i++) {
            $news[] = [
                "news #{$i}",
                "announce for news # {$i}",
                "full content for news item #{$i}"
            ];
        }
        $this->batchInsert(
            News::tableName(),
            [
                "title",
                "announce",
                "content"
            ],
            $news
        );
    }

    public function safeDown()
    {
        $this->truncateTable(News::tableName());
    }
}
