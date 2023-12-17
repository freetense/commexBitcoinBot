<?php

namespace app\controllers;

use app\models\form\UserForm;
use app\models\User;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'only' => [
                'save',
                'update'
            ],
            'tokenParam' => 'key'
        ];
        return $behaviors;
    }
    protected function verbs()
    {
        return [
            'updates' => ['POST']
        ];
    }
    public function actionAll() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $countries = User::find()
                ->select(['user.*, COUNT(orders.user_id) AS count'])  // make sure same column name not there in both table
                ->leftJoin('orders', 'orders.user_id = user.id')
                ->asArray()
                ->groupBy('user.id')
                ->orderBy(['user.id' => SORT_DESC])
                ->all();

            return $countries;
        } else {
            return ['admin' => false];
        }
    }
    public function actionUpdates() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new UserForm();
            $result->load(Yii::$app->request->bodyParams, '');
            $rez = $result->update();
            if(isset($rez['error'])) {
                return $rez;
            } else {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
    public function actionDeletes() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $result = new UserForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->delete()) {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
    public function actionSaves() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new UserForm();
            $result->load(Yii::$app->request->bodyParams, '');
            $rez =$result->send();
            if(isset($rez['error'])) {
                return $rez;
            } else {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
    public function actionBan() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new UserForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->activate()) {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
}
