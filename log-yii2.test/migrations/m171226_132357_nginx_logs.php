<?php

use yii\db\Migration;

/**
 * Class m171226_132357_nginx_logs
 */
class m171226_132357_nginx_logs extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%nginx_logs}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string()->notNull(),
            'file' => $this->string()->notNull(),
            'time' => $this->integer()->notNull(),
            'request' => $this->string()->notNull(),
            'status' => $this->integer()->notNull(),
            'sentBytes' => $this->integer()->notNull(),
            'referer' => $this->string(),
            'user_agent' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }
    public function down()
    {
        $this->dropTable('{{%nginx_logs}}');
    }
}
