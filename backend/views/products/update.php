<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\products\Products */

$this->title = 'Update Product: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'products', 'url' => ['products/index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<style>
    .tresh {
        width: 100% !important;
    }
</style>
<div class="products-update">
<div class="row">

    <div class="col-sm-12">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>



</div>
