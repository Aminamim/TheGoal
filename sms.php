<?php

include_once 'lib/core/Core.php';
include_once 'lib/subscription/Subscription.php';
include_once 'lib/sms/MoSMSReceiver.php';
include_once 'lib/sms/MtSMSSender.php';

ini_set('error_log', 'err/sms_error.log');

define("APP_ID", "APP_068024");
define("APP_PASSWORD", "770e4fd4b01277c9596fba8035afa38b");

$sms_server_url = "https://developer.bdapps.com/sms/send";

try{
    $receiver = new SMSReceiver();
    $sender = new SMSSender($sms_server_url, APP_ID, APP_PASSWORD);

    $message = $receiver->getMessage();
    $address = $receiver->getAddress();

    
    
} catch(SMSServiceException $ecception){

}

?>