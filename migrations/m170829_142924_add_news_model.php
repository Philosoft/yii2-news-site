<?php

use app\modules\news\models\News;
use yii\db\Expression;
use yii\db\Migration;

class m170829_142924_add_news_model extends Migration
{
    const FK_POST_AUTHOR = 'fk-post-author';

    public function safeUp()
    {
        $this->createTable(
            News::tableName(),
            [
                "id" => $this->primaryKey(),
                "status" => $this->boolean()->defaultValue(1),
                "date_added" => $this->timestamp()->defaultValue(new Expression("NOW()")),
                "date_modified" => $this->timestamp()->defaultValue(new Expression("NOW()")),
                "title" => $this->string(256),
                "announce" => $this->text()->notNull(),
                "content" => $this->text()->notNull(),
                "image" => $this->string(256)->null(),
                "author_id" => $this->integer()->notNull()->defaultValue(1)
            ]
        );

        $this->addForeignKey(
            self::FK_POST_AUTHOR,
            News::tableName(),
            "author_id",
            \Da\User\Model\User::tableName(),
            "id"
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            self::FK_POST_AUTHOR,
            News::tableName()
        );
        $this->dropTable(News::tableName());
    }
}
