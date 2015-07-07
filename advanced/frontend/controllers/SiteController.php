<?php

namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\Ambilpostgre;
use frontend\models\test;
use frontend\models\Mapgenerate;
use frontend\models\Cmantest;
use frontend\models\Buatpetakoordinat;
use frontend\models\Ambilnamatabeldanatribut;
use frontend\models\Tentukanpulau;
use frontend\models\Ambillistkoordinat;
use frontend\models\Cariprovinsikabupaten;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
     * @inheritdoc
     */
    public function actions() {
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

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionLogin() {
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

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }

    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    public function actionHallo() {
        $sql = 'SELECT * FROM data_makan';
        $connection = Yii::$app->db;

        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        $rows = $dataReader->readAll();
        $namevalue = array();
        foreach ($rows as $max) {
            $namevalue = $max['jumlah_miskin'];
            echo $namevalue + "<br/>";
        }
    }

    public function actionAmbil1() {
        $post = Yii::$app->request->get();
        $ambil = $post['ambil'];


        $mf = new test();
        $a = $mf->ditest($ambil);
        echo $a[1];
    }

    public function actionAmbilnamatabeldanatribut() {
   
        $post = Yii::$app->request->post();
        $ambil = $post['ambil'];
        $mf = new Ambilnamatabeldanatribut();
        if ($ambil == "nama_tabel") {
            $mf->ambilNamaTabel();
        } else if ($ambil == "nama_kolom") {
            $namaTabel = $post['namaTabel'];
            $mf->ambilNamaKolom($namaTabel);
        } else if ($ambil == "nama_di") {
            $namaTabel = $post['namaTabel'];
            $namaMenurut = $post['namaMenurut'];
            $mf->ambilNamaDi($namaTabel, $namaMenurut);
        } else if ($ambil == "nama_kabupaten") {
            $namaTabel = $post['namaTabel'];
            $namaMenurut = $post['namaMenurut'];
            $namaDi = $post['namaDi'];
            $mf->ambilNamaKab($namaTabel, $namaMenurut, $namaDi);
        }
    }
public function actionAa(){
     $json_provinsi =array("status"=>"OK");
 echo json_encode($json_provinsi, JSON_PRETTY_PRINT);

} 
    public function actionAmbilpostgre() {
        $post = Yii::$app->request->post();
        $latlng = $post['latlng'];
        $modetematik = $post['modetematik'];
        $latlng = $post['latlng'];
        $idp = $post['idp'];
        $nama_kolom = $post['nama_kolom'];
        $nama_menurut = $post['nama_menurut'];
        $nama_kabupaten = $post['nama_kabupaten'];
        $nama_di = $post['nama_di'];
        $tipe_data = $post['tipe_data'];
        $idpulau = $post['idpulau'];
        $mf = new Ambilpostgre();
        $mf->ambil($latlng, $modetematik, $idp, $nama_kolom, $nama_menurut, $nama_di, $nama_kabupaten, $tipe_data,$idpulau);
    }

    public function actionAmbillistkoordinat() {
        $mf = new Ambillistkoordinat();
        $mf->ambilNamaTabel();
    }

    public function actionKoordinat() {
        $post = Yii::$app->request->post();
        $nama_tabel_koordinat = $post['nama_tabel_koordinat'];
        $mf = new Buatpetakoordinat();
        $mf->ambilKoordinat($nama_tabel_koordinat);
    }

    public function actionMapgenerate() {
        $post = Yii::$app->request->post();
        $idp = $post['idp'];
        $nama_kolom = $post['nama_kolom'];
        $nama_menurut = $post['nama_menurut'];
        $nama_di = $post['nama_di'];
        $nama_kabupaten = $post['nama_kabupaten'];
        $tipe_data = $post['tipe_data'];
        $pilihwarna = $post['pilihwarna'];
        $kelasLegend = $post['kelasLegend'];
        $idpulau = $post['idpulau'];
        $datalegendcanggih =$post['datalegendcanggih'];
        $modelegendcanggih =$post['modelegendcanggih'];
        //$idp ="kepadatan_penduduk_yog";
        //$nama_kolom="tingkat_kepadatan";
        //$nama_menurut="Kabupaten";
        //$nama_di="Indonesia";
//$tipe_data="2";
       
          //$idp ="data_makan";
          //$nama_kolom="jumlah_miskin";
          //$nama_menurut="Provinsi";
          //$nama_di="Indonesia";
         // $tipe_data="1";

      // $idp ="data_pasar_seb_aceh";
       //  $nama_kolom="jumlah_pasar";
        //$nama_menurut="Kabupaten";
         //$nama_di="11";
         //$tipe_data="1";
          //$idpulau=3;
        //$pilihwarna="1";
         //$kelasLegend=3;
        // $modelegendcanggih="ya";
        $mf = new Mapgenerate();
        $mf->generate($idp, $nama_kolom, $nama_menurut, $nama_di, $tipe_data, $nama_kabupaten, $pilihwarna, $kelasLegend,$idpulau,$modelegendcanggih,$datalegendcanggih);
    }

    public function actionRedirect() {
        $post = Yii::$app->request->get();
        $bbox = $post['BBOX'];
        $clonemapserver = Yii::$app->message->clonemapserver();
        return $this->redirect($clonemapserver . '&SERVICE=WMS&REQUEST=GetMap&VERSION=1.1.1&LAYERS=provinsi&STYLES=&FORMAT=image%2Fpng&TRANSPARENT=true&HEIGHT=256&WIDTH=256&SRS=EPSG%3A3857&BBOX=' . $bbox);
    }

    public function actionCariprovinsikabupaten() {
        $post = Yii::$app->request->post();
        $yangDiketik = $post['yangDiketik'];
        $yangDicari = $post['yangDicari'];
        $mf = new Cariprovinsikabupaten();
        $mf->cari($yangDiketik, $yangDicari);
    }

    public function actionCarititiktengah() {
        $post = Yii::$app->request->post();
        $yangDiketik = $post['yangDiketik'];
        $yangDicari = $post['yangDicari'];
        $mf = new Cariprovinsikabupaten();
        $mf->cariTitikTengah($yangDiketik, $yangDicari);
    }

    public function actionExect() {
        $sql = file_get_contents('C:/a.sql');
        $sql = str_replace(array('`'), '', $sql);
        Yii::$app->db->createCommand($sql)->execute();

    }
    public function actionTentukanpulau(){
        $post = Yii::$app->request->post();
        $namaTabel = $post['namaTabel'];
        $kodewilayahtersedia = $post['kodewilayahtersedia'];
         //$namaTabel = "data_pasar_seb_aceh";
       // $kodewilayahtersedia = "Provinsi";
        $mf = new Tentukanpulau();
        $mf->pulau($namaTabel,$kodewilayahtersedia );

    }

}