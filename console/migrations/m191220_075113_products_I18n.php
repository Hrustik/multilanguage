<?php

use yii\db\Migration;

/**
 * Class m191220_075113_products_I18n
 */
class m191220_075113_products_I18n extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('products_I18n',[
           'id' => $this->primaryKey(),
           'product_id' => $this->integer(),
           'locale' => $this->string(),
           'name' => $this->string(),
        ]);

        $this->createIndex(
            'idx-products_I18n-product_id',
            'products_I18n',
            'product_id'
        );

        $this->addForeignKey(
            'fk-products_I18n-product_id',
            'products_I18n',
            'product_id',
            'products',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-products_I18n-product_id',
            'products_I18n'
        );

        $this->dropIndex(
            'idx-products_I18n-product_id',
            'products_I18n'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191220_075113_products_I18n cannot be reverted.\n";

        return false;
    }
    */
}
