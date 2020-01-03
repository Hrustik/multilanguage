<?php

namespace common\components;

use Yii;

/**
 * Class SaveTrait
 * @package app\components
 */
trait SaveTrait
{
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!empty($this->i18n) && array_key_exists(Yii::$app->language, $this->i18n)) {
                foreach ($this->i18n[Yii::$app->language] as $key => $item) {
                    $this->$key = $item;
                }
            }
            return true;
        }
        return false;
    }
}