<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\products\Products;
//use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="products-index">


    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'name',
            'order',

            ['class' => 'yii\grid\ActionColumn',

//                'visibleButtons' => [
//                    'view' => function ($model) {
//                        return User::isUserAdmin(Yii::$app->user->identity->username);
//                    },
//                    'delete' => function ($model) {
//                        return User::isUserAdmin(Yii::$app->user->identity->username);
//                    },
//                ],
                'template'=>'{view}{update}'
            ]
        ],
    ]); ?>
</div>
