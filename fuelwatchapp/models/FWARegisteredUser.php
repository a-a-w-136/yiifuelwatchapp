<?php
namespace app\models;
use Yii;
use yii\base\Model;

class FWARegisteredUser extends FWAUser {
    
    public $favourite = false;
    public $favourite_status = "";
    
    function profilepost($log) {
        $this->homepagepost($log);
        try {
            $this->verifyposteddata($_POST["locality_name"], $_POST["fuel"], $_POST["distance"]);
            return $this->createfavourite();
        }
        catch (Exception $e) {
            throw $e;
        }
        
    }
    function profileget($log) {
        $this->homepageget($log);
        try {
            $this->getfavourite();
            if($this->favourite) {
                $this->fuel_search_view = new LinkedList();
                $this->homepagefavourite();
            }
        }
        catch (Exception $e) {
            throw $e;
        }
        
    }
    function homepagefavourite() {
        try {
            $this->getstationsinrange();
            $this->dbparametersearch(); 
        }
        catch (Exception $e) {
            throw $e;
        }
        
    }
    function favourite() {
        return $this->favourite;
    }
    function createfavourite() {
        if($this->verified_input) {
            $sql_exists_fav = "SELECT username FROM favourite WHERE username = '".$_SESSION['username']."'";
            $result = Yii::$app->db->createCommand($sql_exists_fav)->queryOne();
            if($result) {
                $sql_delete_favourite = "DELETE FROM favourite WHERE username = '".$_SESSION['username']."'";
                $result = Yii::$app->db->createCommand($sql_delete_favourite)->execute();
                if($result == 0) {
                    $this->favourite_status = "2:Error Creating Favourite";
                }
                else {
                    $sql_insert_favourite = "INSERT INTO favourite (username, locality_name, distance, product) VALUES ('".$_SESSION['username']."', '".$_POST['locality_name']."', '".$_POST['distance']."', '".$_POST['fuel']."')";
                    $result = Yii::$app->db->createCommand($sql_insert_favourite)->execute();
                    if($result) {
                        $this->favourite_status = "Favourite Saved";
                    }
                    else {
                        $this->favourite_status = "3:Error Creating Favourite";
                    }
                }
            }
            else {
                $sql_insert_favourite = "INSERT INTO favourite (username, locality_name, distance, product) VALUES ('".$_SESSION['username']."', '".$_POST['locality_name']."', '".$_POST['distance']."', '".$_POST['fuel']."')";
                $result = Yii::$app->db->createCommand($sql_insert_favourite)->execute();
                if($result) {
                    $this->favourite_status = "Favourite Saved";
                }
                else {
                    $this->favourite_status = "3:Error Creating Favourite";
                }
            }
        }
        $this->favourite_status = "5:Error Creating Favourite";
        
    }
    function getfavourite() {
        $sql_favourite = "SELECT username, locality_name, distance, product FROM favourite WHERE username = '".$_SESSION['username']."'";
        $result = Yii::$app->db->createCommand($sql_favourite)->queryOne();
        if($result) {
                $this->verifyposteddata($result['locality_name'], $result['product'], $result['distance']);
                $this->favourite = true;
        }
    }
    function deleteaccount($log) {
        $sql_delete_account = "DELETE FROM users WHERE username = '".$_SESSION['username']."'";
        $sql_delete_favourite = "DELETE FROM favourite WHERE username = '".$_SESSION['username']."'";
        $profile_log = fopen($log, 'a');
        $result = Yii::$app->db->createCommand($sql_delete_account)->execute();
        if($result) {
            $_SESSION['username'] = null;
            if($profile_log) {
                fwrite($profile_log, $sql_delete_account. "\n");
            }
        }
        $result = Yii::$app->db->createCommand($sql_delete_favourite)->execute();
        if($result) {
            if($profile_log) {
                fwrite($profile_log, $sql_delete_favourite. "\n");
            }
        }
    }
}

?>