<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%locale}}`.
 */
class m191220_074725_create_locale_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%locale}}', [
            'en' => $this->string(),
            'uk' => $this->string(),
            'ru' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%locale}}');
    }
}
