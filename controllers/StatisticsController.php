<?php

namespace app\controllers;

use app\models\form\StatisticsForm;
use app\models\Statistics;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class StatisticsController extends ActiveController
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
            $countries = Statistics::find()->orderBy(['id' => SORT_DESC])->limit(1)->all();

            return $countries;
        } else {
            return ['admin' => false];
        }
    }
}
