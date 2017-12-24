<?php

use yii\db\Migration;

/**
 * Class m171224_211945_user
 */
class m171224_211945_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->notNull(),
            'password' => $this->string(32)->notNull(),
            'email' => $this->string(32)->notNull(),
            'authKey' => $this->string(32)->notNull(),
            'accessToken' => $this->string(64)->notNull(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->createIndex('idx-user-name', '{{%user}}', 'username', true);
        $this->createIndex('idx-user-email', '{{%user}}', 'email');
        $this->createIndex('idx-user-status', '{{%user}}', 'status');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
