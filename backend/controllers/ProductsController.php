<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\products\Products;
use common\models\products\ProductsSearch;

/**
 * Site controller
 */
class ProductsController extends Controller
{
    /**
     * {@inheritdoc}
     */
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['login', 'error'],
//                        'allow' => true,
//                    ],
//                    [
//                        'actions' => ['logout', 'index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'products' => Products::find()->asArray()->all(),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function findModel($id)
    {
        return Products::findOne($id);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->loadI18n($model);
        
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->save();
            //return var_dump($model->errors);

            Yii::$app->session->setFlash('success', 'Product updated');
            return $this->redirect('/backend/web/index.php?r=products%2Fupdate&id='.$model->id);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}