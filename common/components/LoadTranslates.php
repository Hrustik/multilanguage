<?php

namespace common\components;

trait LoadTranslates
{
    public $i18n;

    public function loadI18n($model)
    {
        foreach ($model->translation as $item){
            $this->i18n[] = $item;
        }
    }

    public function loadTranslate($model)
    {
        $result = [];
        foreach ($model->translation as $item) {
            $result[$item->locale] = $item->attributes;
        }
        $this->i18n = $result;
    }

    public function loadTranslateLanguange($model)
    {
        $result = [];
        foreach ($model->translation as $item) {
            $result[$item->language] = $item->attributes;
        }
        $this->i18n = $result;
    }
}