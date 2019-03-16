<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function actionIndex() {
        $user_request = new \app\models\UserRequest;
        $user_request->processuserrequest();
        return $this->render('search', ['user' => $user_request->user]);
    }
    public function actionRegister() {
        $user_request = new \app\models\UserRegisterRequest;
        $user_request->processuserregisterrequest();
        if($user_request->user == null) {
            return $this->render('register', ['register_status' => $user_request->register_status]);
        }
        else {
            $this->redirect('index');
        }
    }
    public function actionLogin() {
        $user_login = new \app\models\UserLoginRequest;
        $user_login->processuserloginrequest();
        if($user_login->user == null) {
            return $this->render('login', ['login_status' => $user_login->login_status]);
        }
        else {
            $this->redirect('index');
        }
    }
    public function actionLogout()
    {
        $_SESSION['username'] = null;
        $this->redirect('index');
    }
    public function actionProfile() {
        $user_request = new \app\models\UserProfileRequest;
        $user_request->processuserprofilerequest();
        if($user_request->user == null) {
            $this->redirect('index');
        }
        else {
            return $this->render('profile', ['user' => $user_request->user]);
        }
    }
    public function actionUnregister() {
        if(isset($_SESSION['username'])) {
            $user = new \app\models\FWARegisteredUser;
            $user->deleteaccount("/var/www/yiifuelwatchapp/log/fuelwatchapp.log");
        }
        $this->redirect('index');
    }
}
