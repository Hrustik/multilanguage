<?php

use common\models\products\Products;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Products */
?>

<div class="products-form">

    <br>
    <?php $form = ActiveForm::begin(['action'=>'/backend/web/index.php?r=products%2Fupdate&id='.$model->id]); ?>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>
<!--            --><?//= "Trans Ua:".print_r($model->translation); ?>

            <?php

            foreach (Yii::$app->params['locale'] as $key => $locale): ?>
<!--            --><?php //var_dump($model->i18n); ?>
<!--                --><?//= $form->field($model, "i18n")->textInput(); ?>
                <?= $form->field($model, "i18n[$key][name]")->textInput()->label(
                        Yii::t('app', 'Name') . ' (' . $key . ')'); ?>
            <?php endforeach; ?>

        </div>


            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
