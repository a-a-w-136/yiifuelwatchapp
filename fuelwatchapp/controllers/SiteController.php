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
    private $log = "/var/www/yiifuelwatchapp/log/fuelwatchapp.log";
    public $user;
    public $register_status;
    public $login_status;
    public $profile_status;

    public function actionIndex() {
        $this->processuserrequest();
        return $this->render('search', ['user' => $this->user]);
    }
    public function actionRegister() {
        $this->processuserregisterrequest();
        if($this->user == null) {
            return $this->render('register', ['register_status' => $this->register_status]);
        }
        else {
            $this->redirect('index');
        }
    }
    public function actionLogin() {
        $this->processuserloginrequest();
        if($this->user == null) {
            return $this->render('login', ['login_status' => $this->login_status]);
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
        $this->processuserprofilerequest();
        if($this->user == null) {
            $this->redirect('index');
        }
        else {
            return $this->render('profile', ['user' => $this->user]);
        }
    }
    public function actionUnregister() {
        if(isset($_SESSION['username'])) {
            $user = new \app\models\FWARegisteredUser;
            $user->deleteaccount("/var/www/yiifuelwatchapp/log/fuelwatchapp.log");
        }
        $this->redirect('index');
    }
    function processuserrequest() {
        try {
            if($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_SESSION['username'])) {
                $this->user = new \app\models\FWAUser;
                $this->user->homepageget($this->log);
                $this->user->cheapest_fuel = true;
            }
            else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $this->user = new \app\models\FWAUser;
                $this->user->homepagepost($this->log);
            }
            else if(isset($_SESSION['username']) && $_SERVER["REQUEST_METHOD"] == "GET") {
                $this->user = new \app\models\FWARegisteredUser;
                $favourite_status = $this->user->profileget($this->log);
                if(!($this->user->favourite())) {
                    $this->user->cheapest_fuel = true;
                }
                else {
                    $this->user->favourite = true;
                }
            }
        }
        catch (Exception $e) {
            header("Location: index.php"); 
            exit;
        }
    }

    function processuserregisterrequest() {
           try {
                if($_SERVER['REQUEST_METHOD'] == "POST") {
                    $user = new \app\models\FWAUser;
                    $this->register_status = $user->register($this->log);
                    if(isset($_SESSION['username'])) {
                        $this->user = new \app\models\FWARegisteredUser;
                        $this->user->profileget($this->log);
                    }
                }
               else {
                   $user = null;
               }
            }
            catch (Exception $e) {
                exit;
            }
    }

    function processuserloginrequest() {
       try {
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                $this->user = new \app\models\FWAUser;
                $this->login_status = $this->user->login($this->log);
                if(!(isset($_SESSION['username']))) {
                    $this->user = null;
                }
                else {
                    $this->user = new \app\models\FWARegisteredUser($this->log);
                    $this->user->profileget($this->log);
                }
            }
            else {
                $this->user = null;
            }
        }
        catch (Exception $e) {
            exit;
        }
    }
    
    function processuserprofilerequest() {
           try {
                if(!isset($_SESSION['username'])) {
                    $this->user = null;
                }
                else if($_SERVER['REQUEST_METHOD'] == "POST"){
                    $this->user = new \app\models\FWARegisteredUser;
                    $this->profile_status = $this->user->profilepost($this->log);
                }
                else {
                    $this->user = new \app\models\FWARegisteredUser;
                    $this->profile_status = $this->user->profileget($this->log);
                }
            }
            catch (Exception $e) {
                exit;
            }
    }
    
    
}
