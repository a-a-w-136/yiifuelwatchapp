<?php
namespace app\models;
use Yii;
use yii\base\Model;

class UserLoginRequest {
    private $log = "/var/www/yiifuelwatchapp/log/fuelwatchapp.log";
    public $user;
    public $login_status;
    
    function processuserloginrequest() {
       try {
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                $this->user = new FWAUser;
                $this->login_status = $this->user->login($this->log);
                if(!(isset($_SESSION['username']))) {
                    $this->user = null;
                }
                else {
                    $this->user = new FWARegisteredUser($this->log);
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
}

?>