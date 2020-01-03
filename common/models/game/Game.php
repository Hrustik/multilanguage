<?php

namespace common\models\game;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "game".
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
    }

    public function behaviors()
    {
        return [
            [

                'class' => TimestampBehavior::className(),

                'value' => new Expression('NOW()'),

            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_1'], 'integer'],
            [['user_2'], 'safe'],
            ['status', 'string'],
            //[['user_2'], 'integer'],
            [['created_at', 'updated_at'], 'date'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
        ];
    }
}
