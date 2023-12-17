<?php

namespace app\controllers;

use app\models\Products;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use app\models\form\ProductsScladForm;
use Yii;

class ProductsController extends ActiveController
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
        $request = Yii::$app->request;
        if (!Yii::$app->user->isGuest) {
            $countries = Products::find()
                ->orderBy(['id' => SORT_DESC])
                ->all();
            return $countries;
        } else {
            return ['admin' => false];
        }
    }

    public function actionUpdates() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new ProductsScladForm();
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
            $result = new ProductsScladForm();
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
            $result = new ProductsScladForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->send()) {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
    public function actionGraph() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $countries = Products::find()->all();
            $count = [];
            $counts = [];
            foreach ($countries as $key => $value) {
                if(!isset($count[$value['id_name']])) {
                    $counts[$value['id_name']] = 0;
                }
                $count[$value['id_name']][] = [
                    'id' => $value['id_name'],
                    'title' => $value['title'],
                    'date' => $value['update'],
                    'price' => $value['price'],
                    'count' => $counts[$value['id_name']]
                ];
                $counts[$value['id_name']]++;
            }
            $items = [];
            foreach ($count as $key => $value) {
                $your_date = null;
                $datediff = time();
                $coun = (int) (floor((time() - strtotime($value[0]['date'])) / (60 * 60 * 24)));
                $price = $value[0]['price'];
                $priceEnd = 0;
                $date = null;

                foreach ($value as $el => $val) {
                    if($your_date) {
                        $your_date = strtotime($val['date']);
                    } else {
                        $datediff = (int) (floor((time() - strtotime($val['date'])) / (60 * 60 * 24))/count($value));
                        $your_date = strtotime($val['date']);
                    }
                    if($price >= $val['price'] && count($value) - 1 != $val['count']) {
                        $coun = (int)(floor((strtotime($value[$val['count'] + 1]['date']) - strtotime($val['date'])) / (60 * 60 * 24)));

                        $price =  $val['price'];
                    }
                    if(count($value) - 1 != $val['count']) {
                        $priceEnd += $val['price'];
                    }
                    $date = $val['date'];
                }

                if(count($value) > 1) {
                    $items[$val['title']][0] = $datediff;
                    $items[$val['title']][1] = (int)(floor((time() - $your_date) / (60 * 60 * 24)));
                    $items[$val['title']][2] = count($value) >= 1 ? ((($priceEnd-$price)/$price) >= 1 ? (($priceEnd-$price)/$price) * $coun : $coun) : $coun
                        ;
                } else {
                    $items[$val['title']][0] = 0;
                    $items[$val['title']][1] = 0;
                    $items[$val['title']][2] = 0;
                }
            }
            return $items;
        } else {
            return ['admin' => false];
        }
    }
}
