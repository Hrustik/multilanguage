<?php


namespace common\models\game_actions;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "game_actions".
 */
class GameActions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_actions';
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
            [['user_id'], 'integer'],
            [['game_id'], 'safe'],
            ['number', 'integer'],
            [['created_at'], 'date'],
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
