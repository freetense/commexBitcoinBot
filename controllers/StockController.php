<?php

namespace app\controllers;

use app\models\form\StatisticsForm;
use app\models\form\StockForm;
use app\models\Statistics;
use app\models\Stock;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class StockController extends ActiveController
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
            $countries = Stock::find()->orderBy(['id' => SORT_DESC])->all();

            return $countries;
        } else {
            return ['admin' => false];
        }
    }
    public function actionUpdates() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = new StockForm();
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
            $result = new StockForm();
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
            $result = new StockForm();
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
            $result = new StockForm();
            $result->load(Yii::$app->request->bodyParams, '');

            if($result->activate()) {
                return true;
            }

            return false;
        } else {
            return ['admin' => false];
        }
    }
    public function actionUpload()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->user->isGuest) {

            if (Yii::$app->request->isPost) {
                if(isset($_FILES['imageFile'])){
                    $errors= array();
                    $file_name = $_FILES['imageFile']['name'];
                    $file_size = $_FILES['imageFile']['size'];
                    $file_tmp = $_FILES['imageFile']['tmp_name'];
                    $file_type = $_FILES['imageFile']['type'];
                    $exp = explode('.',$_FILES['imageFile']['name']);
                    $file_ext=strtolower(end($exp));

                    $extensions= array("jpeg","jpg","png");
                    $extensionsImg = array('image/jpeg','image/png');
                    if(in_array($file_ext,$extensions)=== false && in_array($file_type,$extensionsImg)=== false){
                        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                    }
                   $img = mt_rand(1000000, 8888888);
                    if(empty($errors)==true) {
                        move_uploaded_file($file_tmp,"img/stock/".$img.str_replace(" ", "_", $file_name));
                        return ['img' => "img/stock/".$img.str_replace(" ", "_", $file_name)];
                    }else{
                        return ['admin' => false];
                    }
                }
            }

            return ['admin' => false];
        } else {
            return ['admin' => false];
        }
    }
}
