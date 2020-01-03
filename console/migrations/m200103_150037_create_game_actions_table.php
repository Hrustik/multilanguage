<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%game_actions}}`.
 */
class m200103_150037_create_game_actions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%game_actions}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer(10),
            'user_id' => $this->integer(10),
            'number' => $this->integer(1),
            'created_at' => $this->string()->defaultValue(''),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%game_actions}}');
    }
}
