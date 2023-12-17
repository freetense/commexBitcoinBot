<?php

namespace app\controllers;

use app\models\form\SettingsForm;
use app\models\Settings;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class SettingsController extends ActiveController
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
            $countries = Settings::find()->orderBy(['id' => SORT_DESC])->all();

            return $countries;
        } else {
            return ['admin' => false];
        }
    }
    public function actionUpdates() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new SettingsForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->update()) {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
    public function actionStop() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new SettingsForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->stop()) {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
}
