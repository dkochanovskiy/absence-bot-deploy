<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'psql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'user_id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100)->notNull()
        ], $tableOptions);

        $this->createTable('{{%telegram_order_home}}', [
            'telegram_order_home_id' => $this->primaryKey()->unsigned(),
            'chat_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned(),
            'date_absence' => $this->string()->defaultValue(''),
            'reason' => $this->string(),
            'created_at' => $this->timestamp(),
        ], $tableOptions);

        $this->createTable('{{%telegram_order_ill}}', [
            'telegram_order_ill_id' => $this->primaryKey()->unsigned(),
            'chat_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned(),
            'sick_leave' => $this->integer()->unsigned(),
            'due_to_what' => $this->integer()->unsigned(),
            'planned_date_of_mining' => $this->string()->defaultValue(''),
            'created_at' => $this->timestamp(),
        ], $tableOptions);

        $this->createTable('{{%telegram_order_vacation}}', [
            'telegram_order_vacation_id' => $this->primaryKey()->unsigned(),
            'chat_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned(),
            'type_id' => $this->integer()->unsigned(),
            'vacation_start' => $this->string()->defaultValue(''),
            'number_of_days' => $this->integer()->unsigned(),
            'created_at' => $this->timestamp(),
        ], $tableOptions);

        $this->createTable('{{%telegram_order_dayoff}}', [
            'telegram_order_dayoff_id' => $this->primaryKey()->unsigned(),
            'chat_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned(),
            'type_id' => $this->integer()->unsigned(),
            'dayoff_date' => $this->string()->defaultValue(''),
            'planned_date_of_mining' => $this->string()->defaultValue(''),
            'created_at' => $this->timestamp(),
        ], $tableOptions);

        $this->addForeignKey('fk-order_user', '{{%telegram_order_home}}', 'user_id',
            '{{%user}}', 'user_id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk-order_user', '{{%telegram_order_ill}}', 'user_id',
            '{{%user}}', 'user_id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk-order_user', '{{%telegram_order_vacation}}', 'user_id',
            '{{%user}}', 'user_id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk-order_user', '{{%telegram_order_dayoff}}', 'user_id',
            '{{%user}}', 'user_id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%telegram_order_home}}');
        $this->dropTable('{{%telegram_order_ill}}');
        $this->dropTable('{{%telegram_order_vacation}}');
        $this->dropTable('{{%telegram_order_dayoff}}');
    }
}
