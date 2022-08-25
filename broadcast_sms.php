<?php

include_once 'lib/core/Core.php';
include_once 'lib/sms/MtSMSSender.php';
include_once 'db/DbOperations.php';
include_once 'log/log.php';

ini_set('error_log', 'err/broadcast_sms_error.log');

define("APP_ID", "APP_068024");
define("APP_PASSWORD", "770e4fd4b01277c9596fba8035afa38b");

$operations = new DbOperations();
$sms_server_url = "https://developer.bdapps.com/sms/send";

date_default_timezone_set('Asia/Dhaka');
$today_date = date('d.m.Y');
$today_time = date('H:i:s');

$sender = new SMSSender($sms_server_url, APP_ID, APP_PASSWORD);

$is_error = false;

$val = $operations->is_broadcast_message_given_for_the_date($today_date);
echo "$val";

if($operations->is_broadcast_message_given_for_the_date($today_date)){
    try{
        $is_error = false;
        $message = $operations->read_from_broadcast_sms($today_date);
        $sender->broadcast($message);
    } catch(SMSServiceException $ecception){
        $is_error = true;
        $broadcast_sms_id = $operations->read_from_broadcast_sms_id($today_date);
        $is_sent = "false";
        $error_code = $ex->getErrorCode();
		$error_message = $ex->getErrorMessage();
        $operations->write_broadcast_sms_log($broadcast_sms_id, $today_date, $today_time, $is_sent, $error_code, $error_message);
    }
} else{
    $is_error = true;
    $broadcast_sms_id = "NA";
    $is_sent = "false";
    $error_code = "self";
    $error_message = "broadcast message was not provided for the date";
    $operations->write_broadcast_sms_log($broadcast_sms_id, $today_date, $today_time, $is_sent, $error_code, $error_message);
}

if(!$is_error){
    $broadcast_sms_id = $operations->read_from_broadcast_sms_id($today_date);
    $is_sent = "true";
    $error_code = "NA";
    $error_message = "NA";
    $operations->write_broadcast_sms_log($broadcast_sms_id, $today_date, $today_time, $is_sent, $error_code, $error_message);
}

?>