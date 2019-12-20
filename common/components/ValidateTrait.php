<?php

namespace common\components;

use common\models\hybrids\PdHybridsI18n;
use common\models\microelement\PdMicroelementI18n;
use common\models\products\PdProductI18n;
use yii\validators\EmailValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use Yii;

/**
 * Class ValidateTrait
 * @package app\components
 */
trait ValidateTrait
{
    /**
     * Input validation.
     *
     * @param $attribute
     */
    public function validateUniqueNew($attribute)
    {
        // массив инпутов
        $items = $this->$attribute;
        $values = [];
        foreach ($items as $key => $item) {
            $values[$key] = $item['value'];
        }

        // массив с недублироваными значениями
        $result = [];

        // получаем количество повторений по определенным значениям
        $data = array_count_values($values);


        // отбираем елементы где количество одинаковых елементов больше 1
        foreach ($data as $key => $value) {
            if ($value <= 1) {
                unset($data[$key]);
            } elseif ($value > 1) {
                $result[] = $key;
            }
        }

        // перебор массива инпутов с проверкой если значения с инпута в массиве result
        foreach ($items as $index => $item) {
            foreach ($result as $value) {
                if (intval($value) === intval($item['value'])) {
                    $key = $attribute . '[' . $index . '][value]';
                    $this->addError($key, "Таке значення вже існує");
                }
            }
        }
    }


    /**
     * Input validation.
     *
     * @param $attribute
     */
    public function validateUnique($attribute)
    {
        // массив инпутов
        $items = $this->$attribute;

        // массив с недублироваными значениями
        $result = [];

        // получаем количество повдорений по определенным значениям
        $data = array_count_values($items);


        // отбираем елементы где количество одинаковых елементов больше 1
        foreach ($data as $key => $value) {
            if ($value > 1) {
                unset($data[$key]);
            } elseif ($value == 1) {
                $result[] = $key;
            }
        }

        // перебор массива инпутов с проверкой если значения с инпута в массиве result
        foreach ($items as $index => $item) {
            $key = array_search($item, $result);
            if ($key === false) {
                $key = $attribute . '[' . $index . ']';
                $this->addError($key, "Таке значення вже існує");
            }
        }
    }

    /**
     * Input validation.
     *
     * @param $attribute
     */
    public function validateRecomendation($attribute)
    {
        $array = $this->valid_array;
        $requiredValidator = new RequiredValidator();

        foreach ($this->$attribute as $index => $row) {
            foreach ($this->recomendationSelectNames as $select) {
                if (!array_key_exists($select, $row)) {
                    $keys = $attribute . '[' . $index . ']['.$select.']';
                    $this->addError($keys, "Необхідно заповнити обов'язкові поля" );
                }
            }
            // uncomment to validate all fields

            foreach ($row as $key => $item) {
                if (in_array($key, $array)){
                    $error = null;
                    $requiredValidator->validate($row[$key], $error);
                    if (!empty($error)) {
                        $keys = $attribute . '[' . $index . ']['.$key.']';
                        $this->addError($keys, 'Обов\'язкове значення');
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateRequire($attribute)
    {
        $requiredValidator = new RequiredValidator();

        foreach ($this->$attribute as $index => $row) {
            $error = null;
            $requiredValidator->validate($row['value'], $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][value]';
                $this->addError($key, $error);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateByCompany($attribute)
    {
        $data = $this->$attribute;
        $lang = Yii::$app->params['locale'];
        foreach ($lang as $key => $item) {
            if ($key !== 'uk') continue;     //remove this line to check all languages for uniqueness
            foreach ($data[$key] as $step => $value) {
                if ($step == $this->validation_field) {

                    if ($this->isNewRecord) {
                        $model = PdHybridsI18n::find()
                            ->joinWith('hybrid')
                            ->where([
                                'i18n' => $key,
                                'pd_Hybrids_i18n.hybrids_name' => $value
                            ])
                            ->andWhere([
                                'company_id' => $this->company_id
                            ])
                            ->count();
                    } else {
                        $model = PdHybridsI18n::find()
                            ->joinWith('hybrid')
                            ->where([
                                'i18n' => $key,
                                'pd_Hybrids_i18n.hybrids_name' => $value
                            ])
                            ->andWhere([
                                '!=',
                                $this::relationColumnId(),
                                $this->id
                            ])
                            ->andWhere([
                                'company_id' => $this->company_id
                            ])
                            ->count();
                    }

                    if ($model >= 1) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Поле з такою назвою в такого бренду вже існує (' . $key . ')');
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateProductByCompany($attribute)
    {
        $data = $this->$attribute;
        $lang = [
            'uk' => 'Українська (Україна)',
        ];
        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == $this->validation_field) {

                    if ($this->isNewRecord) {
                        $model = PdProductI18n::find()
                            ->joinWith('product')
                            ->where([
                                'i18n' => $key,
                                'pd_Product_i18n.name' => $value
                            ])
                            ->andWhere([
                                'brandId' => $this->brandId
                            ])
                            ->count();
                    } else {
                        $model = PdProductI18n::find()
                            ->joinWith('product')
                            ->where([
                                'i18n' => $key,
                                'pd_Product_i18n.name' => $value
                            ])
                            ->andWhere([
                                '!=',
                                $this::relationColumnId(),
                                $this->id
                            ])
                            ->andWhere([
                                'brandId' => $this->brandId
                            ])
                            ->count();
                    }
                    if ($model >= 1) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Поле з такою назвою в такого бренду вже існує (' . $key . ')');
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateLanguageRequire($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = Yii::$app->params['locale'];
        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                $error = null;
                $requiredValidator->validate($value, $error);
                if (!empty($error)) {
                    $field = $attribute . '[' . $key . '][' . $step . ']';
                    $this->addError($field, 'Необхідно заповнити це значення ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateLanguageRequireTitle($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = Yii::$app->params['locale'];
        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == 'title') {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Необхідно заповнити це значення ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                    }
                }
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function validateLanguageRequireTitleUkr($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = [
            'uk' => 'Українська (Україна)',
        ];
        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == 'title') {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Необхідно заповнити це значення ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                    }
                } else if($step == 'conditions') {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Необхідно заповнити це значення ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                    }
                }
            }
        }
    }

    public function validateLanguageRequireTitleRus($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = [
            'ru' => 'Русский (РФ)',
        ];
        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == 'title') {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Необходимо заполнить это значение ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                    }
                } else if($step == 'conditions') {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Необхідно заповнити це значення ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                    }
                }
            }
        }
    }

    public function validateLanguageRequireTitleEng($attribute) {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = [
            'en' => 'English (US)',
        ];
        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == 'title') {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Необхідно заповнити це значення ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                    }
                } else if($step == 'conditions') {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Необхідно заповнити це значення ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                    }
                }
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function validateLanguageRequireSimple($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = Yii::$app->params['locale'];
        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == 'name') {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field ='[' . $attribute .'][' . $key . '][' . $step . ']';
                        $this->addError($field, 'Необхідно заповнити це значення ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                    }
                }
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function validateLanguageRequireAdditionCondition($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = Yii::$app->params['locale'];
        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if (in_array($step, ['name','title', 'subtitle', 'url', 'button'])) {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field ='[' . $attribute .'][' . $key . '][' . $step . ']';
                        $this->addError($field, 'Необхідно заповнити це значення ' . Yii::t('app', ucfirst($step)) . '  (' . $key . ')');
                    }
                }
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function validateFeaturesInteger($attribute)
    {
        $numberValidator = new NumberValidator();
        $numberValidator->integerOnly = true;
        $numberValidator->init();

        $data = $this->$attribute;

        foreach ($data as $index => $row) {
            $error = null;
            $numberValidator->validate($row['sort'], $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][sort]';
                $this->addError($key, $error);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateLanguageUnique($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = Yii::$app->params['locale'];
        $model18n = self::tableName18n();
        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == $this->validation_field) {

                    if ($this->isNewRecord) {
                        $model = $model18n::find()
                            ->where([
                                'i18n' => $key,
                                $this->validation_field => $value
                            ])
                            ->count();
                    } else {
                        $model = $model18n::find()
                            ->where([
                                'i18n' => $key,
                                $this->validation_field => $value
                            ])
                            ->andWhere([
                                '!=',
                                $this::relationColumnId(),
                                $this->id
                            ])
                            ->count();
                    }

                    if ($model >= 1) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Поле з такою назвою вже існує (' . $key . ')');
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateLangName($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = Yii::$app->params['locale'];

        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == $this->type) {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $this->type . ']';
                        $this->addError($field, 'Необхідно заповнити це значення');
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateLangTypeName($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        $lang = Yii::$app->params['locale'];

        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == $this->type_name) {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $this->type_name . ']';
                        $this->addError($field, 'Необхідно заповнити це значення');
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateLang($attribute)
    {
        $requiredValidator = new RequiredValidator();

        foreach ($this->$attribute as $index => $row) {
            $error = null;
            $requiredValidator->validate($row['features_name'], $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][features_name]';
                $this->addError($key, $error);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateString($attribute)
    {
        $stringValidator = new StringValidator();
        $stringValidator->length = [1, 256];
        $stringValidator->init();

        foreach ($this->$attribute as $index => $row) {
            $error = null;
            $stringValidator->validate($row['features_name'], $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][features_name]';
                $this->addError($key, $error);
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function validateLanguageUkRequire($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        //$lang = Yii::$app->params['locale'];
        $lang = [
            'uk' => 'Українська (Україна)',
        ];

        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                $error = null;
                $requiredValidator->validate($value, $error);
                if (!empty($error)) {
                    $field = $attribute . '[' . $key . '][' . $step . ']';
                    $this->addError($field, 'Необхідно заповнити це значення (' . $key . ')');
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateLangTypeUkName($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        //$lang = Yii::$app->params['locale'];
        $lang = [
            'uk' => 'Українська (Україна)',
        ];

        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == $this->type_name) {
                    $error = null;
                    $requiredValidator->validate($value, $error);
                    if (!empty($error)) {
                        $field = $attribute . '[' . $key . '][' . $this->type_name . ']';
                        $this->addError($field, 'Необхідно заповнити це значення');
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateLanguageUkUnique($attribute)
    {
        $requiredValidator = new RequiredValidator();
        $data = $this->$attribute;
        //$lang = Yii::$app->params['locale'];
        $lang = [
            'uk' => 'Українська (Україна)',
        ];

        $model18n = self::tableName18n();

        foreach ($lang as $key => $item) {
            foreach ($data[$key] as $step => $value) {
                if ($step == $this->validation_field) {

                    if ($this->isNewRecord) {
                        $model = $model18n::find()
                            ->where([
                                'i18n' => $key,
                                $this->validation_field => $value
                            ])
                            ->count();
                    } else {
                        $model = $model18n::find()
                            ->where([
                                'i18n' => $key,
                                $this->validation_field => $value
                            ])
                            ->andWhere([
                                '!=',
                                $this::relationColumnId(),
                                $this->id
                            ])
                            ->count();
                    }

                    if ($model >= 1) {
                        $field = $attribute . '[' . $key . '][' . $step . ']';
                        $this->addError($field, 'Поле з такою назвою вже існує (' . $key . ')');
                    }
                }
            }
        }
    }

    public function validateEmails($attribute)
    {
        $items = $this->$attribute;

        if (!is_array($items)) {
            $items = [];
        }

        foreach ($items as $index => $item) {
            $validator = new EmailValidator();
            $error = null;
            $validator->validate($item, $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . ']';
                $this->addError($key, $error);
            }
        }
    }
}