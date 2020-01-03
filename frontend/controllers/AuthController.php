<?php
namespace frontend\controllers;

use common\models\game\Game;
use Yii;
use yii\rest\ActiveController;
use common\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\Profile;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\ContentNegotiator;

class AuthController extends ActiveController
{
    public $modelClass = 'common\models\User';

    public function behaviors()
    {
        return [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'login' => ['post'],
                        'register'  => ['post'],
                        'refresh' => ['post'],
                        'logout' => ['post'],
                    ],
            ],
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return "Already logged";
        }



        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return "success";
        } elseif ($model->load(Yii::$app->request->post()) && !$model->login()) {
            return "user not found";
        }else{
            return $model->load(Yii::$app->request->post());
        }
    }

    public function actionRegister()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return "register success";
                }
            }else{
                return $model->errors;
            }
        }else{
            return "wrong data";
        }
    }

    public function actionRefresh(){
        if (Yii::$app->user->isGuest) {
            return "Not logged";
        }

        $model = new Profile();

        if($model->load(Yii::$app->request->post()) && $model->profileSave()){
            return "User successfully updated";
        } else {
            return $model->errors;
        }


    }

    public function actionGuest(){
        if (!\Yii::$app->user->isGuest) {
            return "Already logged";
        }else{
            return "Guest";
        }
    }

    public function actionGetUsername(){
        if (!\Yii::$app->user->isGuest) {
            return Yii::$app->user->identity->username;
        }else{
            return "Not logged";
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        $active_games = Game::find()->where('user_1 = '.Yii::$app->user->identity->id.' and status = "active"')->all();
        if($active_games){
            foreach ($active_games as $game){
                $game->status = 'closed';
                $game->save();
            }
        }
        return "Logout";
    }
}