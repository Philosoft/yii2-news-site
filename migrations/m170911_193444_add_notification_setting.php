<?php

use app\models\Profile;
use yii\db\Migration;

class m170911_193444_add_notification_setting extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            Profile::tableName(),
            "notification_type",
            $this->integer()->defaultValue(Profile::NOTIFICATION_TYPE__ALERT)
        );
    }

    public function safeDown()
    {
        $this->dropColumn(
            Profile::tableName(),
            "notification_type"
        );
    }
}
