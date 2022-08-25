<?php

class DbOperations{

    private $con;

    function __construct(){
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect;
        $this->con = $db->connect();
    }

    public function insert_into_unsubscribers($sender_address, $date, $time){
        
        $user_id = $sender_address;
            
        $stmt = $this->con->prepare("INSERT INTO unsubscribers(user_id, unsubscription_date, unsubscription_time) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $sender_address, $date, $time);
        $stmt->execute();
        

    }

    public function insert_into_subscribers($sender_address, $date, $time){
        $stmt = $this->con->prepare("INSERT INTO subscribers(user_id, subscription_date, subscription_time) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $sender_address, $date, $time);
        $stmt->execute();
    }

    public function is_broadcast_message_given_for_the_date($date){
        $stmt = $this->con->prepare("SELECT id FROM broadcast_sms WHERE date = ?");
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function read_from_broadcast_sms($date){
        $stmt = $this->con->prepare("SELECT message FROM broadcast_sms WHERE date = ?");
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $stmt->bind_result($message);
        $stmt->fetch();
        return $message;
    }
    
    public function insert_into_tried_to_subscribe($address, $date, $time){
        $stmt = $this->con->prepare("INSERT INTO tried_to_subscribe(user_id, date, time) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $address, $date, $time);
        $stmt->execute();
    }

    public function read_from_broadcast_sms_id($date){
        $stmt = $this->con->prepare("SELECT id FROM broadcast_sms WHERE date = ?");
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        return $id;
    }

    public function write_broadcast_sms_log($broadcast_sms_id, $date, $time, $is_sent, $error_code,	$error_message){
        $stmt = $this->con->prepare("INSERT INTO broadcast_sms_log(broadcast_sms_id, date, time, is_sent, error_code, error_message) VALUES(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $broadcast_sms_id, $date, $time, $is_sent, $error_code,	$error_message);
        $stmt->execute();
    }
    
}

?>