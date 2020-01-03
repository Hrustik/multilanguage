<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%game}}`.
 */
class m200103_120154_create_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%game}}', [
            'id' => $this->primaryKey(),
            'user_1' => $this->integer(10),
            'user_2' => $this->integer(10)->defaultValue(null),
            'status' => $this->string(10)->defaultValue('active'),
            'created_at' => $this->string()->defaultValue(''),
            'updated_at' => $this->string()->defaultValue(''),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%game}}');
    }
}
