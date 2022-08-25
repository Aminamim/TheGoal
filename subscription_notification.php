<?php

include_once 'lib/subscription/SubscriptionReceiver.php';
include_once 'db/DbOperations.php';
include_once 'log/log.php';

ini_set('error_log', 'err/subscription_error.log');

$operations = new DbOperations();
$receiver = new SubscriptionReceiver();

date_default_timezone_set('Asia/Dhaka');
$today_date = date('d.m.Y');
$today_time = date('H:i:s');

$frequency = $receiver->getFrequency();
$status = $receiver->getStatus();
$application_id = $receiver->getApplicationId();
$address = $receiver->getsubscriberId();
$timestamp = $receiver->getTimestamp();

if($status == "REGISTERED"){
    $operations->insert_into_subscribers($address, $today_date, $today_time);
}
if($status == "UNREGISTERED"){
    $operations->insert_into_unsubscribers($address, $today_date, $today_time);
}

?>