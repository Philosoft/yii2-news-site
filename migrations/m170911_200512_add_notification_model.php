<?php

use app\modules\notification\models\Notification;
use yii\db\Migration;

class m170911_200512_add_notification_model extends Migration
{
    const FK_USER = "fk-user";

    public function safeUp()
    {
        $this->createTable(
            Notification::tableName(),
            [
                "id" => $this->primaryKey(),
                "user_id" => $this->integer(),
                "text" => $this->text(),
                "is_read" => $this->boolean()->defaultValue(0)
            ]
        );

        $this->addForeignKey(
            self::FK_USER,
            Notification::tableName(),
            "user_id",
            \Da\User\Model\User::tableName(),
            "id"
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(self::FK_USER, Notification::tableName());
        $this->dropTable(Notification::tableName());
    }
}
