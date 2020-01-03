<?php

namespace common\models\products;

use Yii;
use common\components\LocaleTrait;

/**
 * This is the model class for table "products_i18n".
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $locale
 */
class ProductsI18n extends \yii\db\ActiveRecord
{
    use LocaleTrait;
    protected static $relationColumnName = 'product_id';
    /**
     * @param $name
     * @param $locale
     * @return static
     */



    public static function create($name, $locale)
    {
        $model = new static();
        $model->name = $name;
        $model->locale = $locale;
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products_i18n';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'name', 'locale'], 'required'],
            [['product_id'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['locale'], 'string', 'max' => 11],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'province_id' => Yii::t('app', 'Province ID'),
            'name' => Yii::t('app', 'Name'),
            'locale' => Yii::t('app', 'Locale'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * @param $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}
