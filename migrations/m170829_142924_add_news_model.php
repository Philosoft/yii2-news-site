<?php

use app\models\News;
use yii\db\Expression;
use yii\db\Migration;

class m170829_142924_add_news_model extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            News::tableName(),
            [
                "id" => $this->primaryKey(),
                "status" => $this->boolean()->defaultValue(1),
                "date_added" => $this->dateTime()->defaultValue(new Expression("NOW()")),
                "date_modified" => $this->dateTime()->defaultValue(new Expression("NOW()")),
                "title" => $this->string(256),
                "announce" => $this->text()->notNull(),
                "content" => $this->text()->notNull(),
                "image" => $this->string(256)->null()->defaultValue(new Expression('"https://placehold.it/100x100"'))
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable(News::tableName());
    }
}
