<?php
namespace app\models;
use Yii;
use yii\base\Model;

class UserRegisterRequest {
    private $log = "/var/www/yiifuelwatchapp/log/fuelwatchapp.log";
    public $user;
    public $register_status;
    
    function processuserregisterrequest() {
           try {
                if($_SERVER['REQUEST_METHOD'] == "POST") {
                    $user = new FWAUser;
                    $this->register_status = $user->register($this->log);
                    if(isset($_SESSION['username'])) {
                        $this->user = new FWARegisteredUser;
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
}

?>