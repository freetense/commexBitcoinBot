<?php

namespace app\controllers;

use app\models\form\OrdersForm;
use app\models\Statistics;
use app\models\Orders;
use PhpParser\Node\Scalar\String_;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class OrdersController extends ActiveController
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
            $countries = Statistics::find()->where(['products' => 'all'])->orderBy(['id' => SORT_DESC])->all();

            return $countries;
        } else {
            return ['admin' => false];
        }
    }
    public function actionAllhard() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $countries = Statistics::find()->where(['products' => 'hard'])->orderBy(['id' => SORT_DESC])->all();

            return $countries;
        } else {
            return ['admin' => false];
        }
    }
    public function actionAlls() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $countries = Orders::find()->orderBy(['id' => SORT_DESC])->all();

            return $countries;
        } else {
            return ['admin' => false];
        }
    }
    public function actionUpdates() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new OrdersForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->update()) {
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
            $result = new OrdersForm();
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
            $result = new OrdersForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->send()) {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
    public function actionActivate() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new OrdersForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->activate()) {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
    public function actionGeo() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $countries = Orders::find()->select(['geo']) ->orderBy(['id' => SORT_DESC])->all();

            return $countries;
        } else {
            return ['admin' => false];
        }
    }
    public function actionSumm() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $date_start = strtotime(date("Y-m-d 0:0:1", strtotime("-1 month")))* 1000;
            $date_end = strtotime(date("Y-m-d 23:59:59"))* 1000;
            $countries = Orders::find()->where(['status' => 4])->orderBy(['id' => SORT_DESC])->all();
            $count = [];
            foreach ($countries as $key => $value) {
                $date_sec = strtotime($value['update'])* 1000;
                if($date_sec >= $date_start && $date_sec <= $date_end) {
                    $old_date_timestamp = strtotime($value['update']);
                    $new_date = date('Y-m-d', $old_date_timestamp);
                    if(isset($count[$new_date])) {
                        $count[$new_date] += (int) $value['price'];
                    } else {
                        $count[$new_date] = (int) $value['price'];
                    }

                }
            }
            return $count;
        } else {
            return ['admin' => false];
        }
    }
    public function actionZakaz() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $date_start = strtotime(date("Y-m-d 0:0:1", strtotime("-1 month")))* 1000;
            $date_end = strtotime(date("Y-m-d 23:59:59"))* 1000;
            $countries = Orders::find()->where(['status' => 4])->orderBy(['id' => SORT_DESC])->all();
            $count = [];
            foreach ($countries as $key => $value) {
                $date_sec = strtotime($value['update'])* 1000;
                if($date_sec >= $date_start && $date_sec <= $date_end) {
                    $old_date_timestamp = strtotime($value['update']);
                    $new_date = date('Y-m-d', $old_date_timestamp);
                    $cou = 0;
                    if(isset($count[0][$new_date])) {
                        foreach (json_decode($value['id_goods'])[0]->all as $channel) {
                            $cou++;
                        }
                        $count[0][$new_date] += $cou;
                    } else {
                        foreach (json_decode($value['id_goods'])[0]->all as $channel) {
                            $cou++;
                        }
                        $count[0][$new_date] = $cou;
                    }
                    $cou = 0;
                    if(isset($count[1][$new_date])) {
                        foreach (json_decode($value['id_goods'])[0]->hard as $channel) {
                            $cou++;
                        }
                        $count[1][$new_date] += $cou;
                    } else {
                        foreach (json_decode($value['id_goods'])[0]->hard as $channel) {
                            $cou++;
                        }
                        $count[1][$new_date] = $cou;
                    }
                }
            }
            return $count;
        } else {
            return ['admin' => false];
        }
    }
}
