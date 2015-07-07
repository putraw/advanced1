<?php
namespace backend\controllers;

use Yii;
//use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use backend\models\simpandatabase;
use backend\models\ambilnamatabel;
use backend\models\ambildatatabel;
use backend\models\editdeletebaris;

//use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    /*
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
*/
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
     public function actionUpload()
    {
         return $this->render('index');
    }

    public function actionKirimcsv()
    {
        $post = Yii::$app->request->post();
        $isicsv = $post['isicsv'];
        $namakolomtampil = $post['namakolomtampil'];
        $tipedata = $post['tipedata'];
        $namakolom = $post['namakolom'];
        $mf = new simpandatabase();
        $mf->simpan($isicsv,$namakolom,$namakolomtampil,$tipedata  );
    }

    public function actionAmbilnamatabel()
    {
        $post = Yii::$app->request->post();
        $mf = new ambilnamatabel();
        $mf->ambil();
    }

    public function actionAmbildatatabel()
    {
        $post = Yii::$app->request->post();
        $namatabel = $post['namatabel'];
        $mf = new ambildatatabel();
        $mf->ambil($namatabel);
    }
        public function actionDeletebaris()
    {
        $post = Yii::$app->request->post();
        $namatabel=$post['namatabel'];
        $kolomunik=$post['kolomunik'];
        $nilaikolomunik=$post['nilaikolomunik'];
        $mf = new editdeletebaris();
        $mf->deletebaris($namatabel,$kolomunik,$nilaikolomunik);
    }
     
    public function actionEditbaris()
    {
        $post = Yii::$app->request->post();
               $namatabel=$post['namatabel'];
        $datanamakolom=$post['datanamakolom'];
        $datapembaharuan=$post['datapembaharuan'];
        $kolomunik=$post['kolomunik'];
        $nilaikolomunik=$post['nilaikolomunik'];
        /*
        $namatabel="data_makan";
        $datanamakolom="id_prov,;,jumlah_miskin";
        $datapembaharuan="31,;,1000002";
        $kolomunik="id_prov";
        $nilaikolomunik="31";
*/
        $mf = new editdeletebaris();

        $mf->edit($namatabel,$datanamakolom,$datapembaharuan,$kolomunik,$nilaikolomunik);
    }
     

}
