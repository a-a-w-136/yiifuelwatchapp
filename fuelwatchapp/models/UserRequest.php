<?php
namespace app\models;
use Yii;
use yii\base\Model;

class UserRequest {
    private $log = "/var/www/yiifuelwatchapp/log/fuelwatchapp.log";
    public $user;
    
    function processuserrequest() {
        try {
            if($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_SESSION['username'])) {
                $this->user = new FWAUser;
                $this->user->homepageget($this->log);
                $this->user->cheapest_fuel = true;
            }
            else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $this->user = new FWAUser;
                $this->user->homepagepost($this->log);
            }
            else if(isset($_SESSION['username']) && $_SERVER["REQUEST_METHOD"] == "GET") {
                $this->user = new FWARegisteredUser;
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
}

?>