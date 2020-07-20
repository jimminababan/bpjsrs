<?php

namespace app\controllers;

use Nsulistiyawan\Bpjs\VClaim\Referensi;
use Nsulistiyawan\Bpjs\VClaim\Sep;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
   public $enableCsrfValidation = false;

   public function actionSep()
   {

      $this->enableCsrfValidation = 'false';


      $vclaim_conf = [
            'cons_id' => '25818',
            'secret_key' => '5xFB853705',
            'base_url' => 'https://dvlp.bpjs-kesehatan.go.id',
            'service_name' => 'vclaim-rest'
      ];

      $referensi = new Sep($vclaim_conf);
      $referensi->insertSEP();
   }



   public function actionCarisep()
   {
      $vclaim_conf = [
            'cons_id' => '25818',
            'secret_key' => '5xFB853705',
            'base_url' => 'https://dvlp.bpjs-kesehatan.go.id',
            'service_name' => 'vclaim-rest'
      ];

      $referensi = new Sep($vclaim_conf);
      var_dump($referensi->cariSEP('0001694551904'));
   }


   /**
    * {@inheritdoc}
    */
   public function behaviors()
   {
      return [
            'access' => [
                  'class' => AccessControl::className(),
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
                  'class' => VerbFilter::className(),
                  'actions' => [
                        'logout' => ['post'],
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

   /**
    * Displays homepage.
    *
    * @return string
    */
   public function actionIndex()
   {
      return $this->render('index');
   }

   /**
    * Login action.
    *
    * @return Response|string
    */
   public function actionLogin()
   {
      if (!Yii::$app->user->isGuest) {
         return $this->goHome();
      }

      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
         return $this->goBack();
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
   public function actionLogout()
   {
      Yii::$app->user->logout();

      return $this->goHome();
   }

   /**
    * Displays contact page.
    *
    * @return Response|string
    */
   public function actionContact()
   {
      $model = new ContactForm();
      if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
         Yii::$app->session->setFlash('contactFormSubmitted');

         return $this->refresh();
      }
      return $this->render('contact', [
            'model' => $model,
      ]);
   }

   /**
    * Displays about page.
    *
    * @return string
    */
   public function actionAbout()
   {
      return $this->render('about');
   }
}
