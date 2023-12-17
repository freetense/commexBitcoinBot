<?php

namespace app\controllers;

use app\models\form\PushmessageForm;
use app\models\Pushmessage;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class PushmessageController extends ActiveController
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
            $countries = Pushmessage::find()->orderBy(['id' => SORT_DESC])->all();

            return $countries;
        } else {
            return ['admin' => false];
        }
    }
    public function actionSaves() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new PushmessageForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->send()) {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
}
