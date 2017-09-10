<?php

use app\modules\news\models\News;
use yii\db\Migration;

class m170829_144643_populate_news extends Migration
{
    public function safeUp()
    {
        $news = [];
        for ($i = 0; $i < 23; $i++) {
            $news[] = [
                "news post #{$i}",
                "announce for news # {$i}",
                "full content for news item #{$i}",
                $this->generateRandomDate(),
                rand(1, 2)
            ];
        }
        $this->batchInsert(
            News::tableName(),
            [
                "title",
                "announce",
                "content",
                "date_added",
                "author_id"
            ],
            $news
        );
    }

    public function safeDown()
    {
        $this->truncateTable(News::tableName());
    }

    protected function generateRandomDate()
    {
        $year = rand(2015, 2017);
        $month = rand(1, 12);
        $day = rand (1, 28);
        $hours = rand(0, 23);
        $minutes = rand (0, 59);
        $seconds = rand(0, 59);

        return "{$year}-{$month}-{$day} {$hours}:{$minutes}:{$seconds}";
    }
}
