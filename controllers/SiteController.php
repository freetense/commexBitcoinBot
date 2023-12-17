<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\form\UploadForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'upload' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('lenastar');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('lenastar');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogouts()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionLenastar()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('star');
        } else {
            $this->goHome();
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

                    if(empty($errors)==true) {
                        move_uploaded_file($file_tmp,"img/uploads/".str_replace(" ", "_", $file_name));
                        return ['img' => "img/uploads/".str_replace(" ", "_", $file_name)];
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
