<?php

include_once 'lib/core/Core.php';
include_once 'lib/subscription/Subscription.php';
include_once 'lib/ussd/MoUssdReceiver.php';
include_once 'lib/ussd/MtUssdSender.php';
include_once 'db/DbOperations.php';
include_once 'log/log.php';

ini_set('error_log', 'err/ussd_error.log');

define("APP_ID", "APP_068024");
define("APP_PASSWORD", "770e4fd4b01277c9596fba8035afa38b");

$subscription_server_url = "https://developer.bdapps.com/subscription/send";
$ussd_server_url = "https://developer.bdapps.com/ussd/send";

$receiver = new UssdReceiver();
$sender = new UssdSender($ussd_server_url, APP_ID, APP_PASSWORD);
$subscription = new Subscription($subscription_server_url, APP_PASSWORD, APP_ID);
$operations = new DbOperations();

$responseMsg = array(
    "unsubscriber_menu" => "Submit 1 to subscribe The Goal.\nThanks."
    );

$content = $receiver->getMessage(); // get the message content
$address = $receiver->getAddress(); // get the sender's address
$requestId = $receiver->getRequestID(); // get the request ID
$applicationId = $receiver->getApplicationId(); // get application ID
$encoding = $receiver->getEncoding(); // get the encoding value
$version = $receiver->getVersion(); // get the version
$sessionId = $receiver->getSessionId(); // get the session ID;
$ussdOperation = $receiver->getUssdOperation(); // get the ussd operation

logFile("[ content=$content, address=$address, requestId=$requestId, applicationId=$applicationId, encoding=$encoding, version=$version, sessionId=$sessionId, ussdOperation=$ussdOperation ]");

$status = $subscription->getStatus($address); //When I call this it calls Subscription class method and there error is occuring

date_default_timezone_set('Asia/Dhaka');
$today_date = date('d.m.Y');
$today_time = date('H:i:s');

if($status == "REGISTERED" || $status == "PENDING CHARGE"){
    if($operations->is_broadcast_message_given_for_the_date($today_date)){
        $message = $operations->read_from_broadcast_sms($today_date);
        $sender->ussd($sessionId, $message, $address, "mt-fin");
    } else{
        $message = "In 1997 Bangladesh became a regular ICC member with the right to play ODIs.";
        $sender->ussd($sessionId, $message, $address, "mt-fin");
    }
} else {
    $operations->insert_into_tried_to_subscribe($address, $today_date, $today_time);
    $sender->ussd($sessionId, $responseMsg["unsubscriber_menu"], $address, "mt-fin");
    $subscription->subscribe($address); //When I call this it calls Subscription class method and there error is occuring
}

?>