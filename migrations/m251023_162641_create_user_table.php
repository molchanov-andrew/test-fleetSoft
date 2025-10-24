<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m251023_162641_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string(100)->notNull()->unique(),
            'email' => $this->string(150)->notNull()->unique(),
            'password' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
