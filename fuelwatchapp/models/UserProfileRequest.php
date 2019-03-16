<?php
namespace app\models;
use Yii;
use yii\base\Model;

class UserProfileRequest {
    private $log = "/var/www/yiifuelwatchapp/log/fuelwatchapp.log";
    public $user;
    public $profile_status;
    
    function processuserprofilerequest() {
           try {
                if(!isset($_SESSION['username'])) {
                    $this->user = null;
                }
                else if($_SERVER['REQUEST_METHOD'] == "POST"){
                    $this->user = new FWARegisteredUser;
                    $this->profile_status = $this->user->profilepost($this->log);
                }
                else {
                    $this->user = new FWARegisteredUser;
                    $this->profile_status = $this->user->profileget($this->log);
                }
            }
            catch (Exception $e) {
                exit;
            }
    }
}

?>