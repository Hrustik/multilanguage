<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\game\Game;
use common\models\game_actions\GameActions;
use yii\web\Response;

/**
 * game controller
 */
class GameController extends ActiveController
{
    public $lines = array(
        // - - -
        // 0 0 0
        // 0 0 0
        '123',
        '132',
        '213',
        '231',
        '321',
        '312',

//        0 0 0
//        - - -
//        0 0 0
        '456',
        '465',
        '546',
        '564',
        '654',
        '645',

//        0 0 0
//        0 0 0
//        - - -
        '789',
        '798',
        '879',
        '897',
        '987',
        '978',

//        - 0 0
//        - 0 0
//        - 0 0

        '147',
        '174',
        '417',
        '471',
        '741',
        '714',

//        0 - 0
//        0 - 0
//        0 - 0
        '258',
        '285',
        '528',
        '582',
        '852',
        '825',

//        0 0 -
//        0 0 -
//        0 0 -
        '369',
        '396',
        '639',
        '693',
        '963',
        '936',

//        - 0 0
//        0 - 0
//        0 0 -
        '159',
        '195',
        '519',
        '951',
        '591',
        '951',

//        0 0 -
//        0 - 0
//        - 0 0
        '357',
        '375',
        '573',
        '537',
        '735',
        '753'
    );

    public $modelClass = 'common\models\game\Game';


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'create' => ['post'],
                    'join' => ['get'],
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

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['index'], $actions['delete'], $actions['update']);
        //unset($actions['delete'], $actions['update'], $actions['options']);
        return $actions;
    }

    public function actionIndex()
    {
        return Game::find()->all();
//        $this->render('index', [
//
//        ]);
    }

    public function actionCreate()
    {
        if(!Yii::$app->user->isGuest){
            if(Game::find()->where('user_1 = '.Yii::$app->user->identity->id.' and status = "active"')->exists()){
                return "your game actually exists";
            }
            $game = new Game();
            $game->user_1 = Yii::$app->user->identity->id;

            if($game->validate() && $game->save()){
                return "game successfully created!";
            }else{
                return "something wrong! ".$game->errors;
            }
        }else{
            return "not logged!";
        }
    }

    public function actionJoin($id)
    {
        if(!Yii::$app->user->isGuest) {
            $game = Game::find()->where('id = ' . $id . ' and status = "active" and user_2 IS NULL and user_1 != ' . Yii::$app->user->identity->id)->one();
            if ($game) {
                $game->user_2 = Yii::$app->user->identity->id;
                $game->save();
            } else {
                return "Game not found or you are game creator or game is started!";
            }
        }else{
            return "not logged!";
        }
    }

    public function actionAction($game_id, $field_number){
        if($this->checkGameAccess($game_id)){
            if($this->checkGameOrderliness($game_id)){
                if($this->checkFieldAccess($game_id, $field_number)){
                    $action = new GameActions();
                    $action->game_id = $game_id;
                    $action->user_id = Yii::$app->user->identity->id;
                    $action->number = $field_number;
                    $action->save();

                    $this->checkGameResult($game_id);
                }
            }
        }else{
            return "Dont have permission";
        }

        return false;
    }

    public function checkGameAccess($game_id){
        if(!Yii::$app->user->isGuest){
            $game = Game::findOne($game_id);
            if($game){
                if(($game->user_1 !== Yii::$app->user->identity->id && $game->user_2 !== Yii::$app->user->identity->id) || $game->user_2 == null){
                    return false;
                }else{
                    return true;
                }
            }
        }

        return false;
    }

    public function checkGameOrderliness($game_id){
        $action = GameActions::find()->where('game_id = '.$game_id)->orderBy(['created_at' => SORT_DESC])->one();
        if($action){
            if($action->user_id === Yii::$app->user->identity->id){
                return false;
            }
        }else{
            if(Game::findOne($game_id)->user_1 !== Yii::$app->user->identity->id){
                return false;
            }
        }

        return true;
    }

    public function checkFieldAccess($game_id, $field_number){
        $action = GameActions::find()->where('game_id = '.$game_id.' and number = '.$field_number)->one();
        if($action){
            return false;
        }else{
            return true;
        }
    }

    public function checkGameResult($game_id){
        $actions = GameActions::find()->where('user_id = '.Yii::$app->user->identity->id.' and game_id = '.$game_id)->all();
    }


}