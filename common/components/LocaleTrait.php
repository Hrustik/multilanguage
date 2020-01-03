<?php


namespace common\components;

/**
 * Class LocaleTrait
 * @package app\components
 */
trait LocaleTrait
{

    /**
     * @var string
     */
    protected static $localeColumnName = 'locale';

    /**
     * @param $id
     * @param $data
     * @param bool $insert
     * @return bool
     */
    public static function saveLanguageData($id, $data, $insert = true)
    {
        if ($insert) {
            foreach ($data as $locale => $localeData) {
                self::_insertLocale($id, $locale, $localeData);
            }
        } else {
            foreach ($data as $locale => $localeData) {

                /**
                 * @var $self \yii\db\ActiveRecord
                 */
                $self = self::findOne([
                    static::$relationColumnName => $id,
                    static::$localeColumnName => $locale
                ]);

                if (!$self) {
                    self::_insertLocale($id, $locale, $localeData);
                } else {
                    $self->load($localeData, '');
                    $self->save();
                }
            }
        }
    }

    /**
     * @param $id
     * @param $locale
     * @param $data
     * @return bool
     */
    private static function _insertLocale($id, $locale, $data)
    {
        /**
         * @var $self \yii\db\ActiveRecord
         */

        $self = new self();

        $self->load($data, '');
        $self->{static::$relationColumnName} = $id;
        $self->{static::$localeColumnName} = $locale;

        return $self->save();
    }

    /**
     * @param $id
     * @return array
     */
    public static function getLocaleData($id)
    {
        $data = self::find()->where([
            static::$relationColumnName => $id
        ])->all();

        $result = [];

        foreach ($data as $item) {
            $result[$item->{static::$localeColumnName}] = $item->attributes;
            foreach (static::$additionalLocaleDataAttributes as $attribute) {
                $result[$item->{static::$localeColumnName}][$attribute] = $item->{$attribute};
            }
        }

        return $result;
    }
}