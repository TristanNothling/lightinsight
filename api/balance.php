<?php

/*get session row, */
/*auto logoff thing*/
/*if */
/*$final_result = ['result'=>'expired','message'=>'Your session has expired'];*/
/*category list, as array*/

/*sleep(0.5);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();
include 'functions.php';
include 'hadfj_connect.php';

date_default_timezone_set('UTC');
register_shutdown_function('sendOutput');

/*check balance is a decimal*/

$new_balance = floatval($_POST['balance']);

$user_id = $_SESSION['logged_user_id'];
$pepper = $_SESSION['pepper'];

$stmt = $sql_conn->prepare("UPDATE `cqeiq_users` SET `etnvc_current_bal`= ? WHERE `rqipo_id` = ? AND `btnyv_salt` = ? ");

$stmt->bind_param("sss",$new_balance,$user_id,$pepper);
$stmt->execute();

if ($stmt->affected_rows > 0){
	$final_output= ['result'=>'success','message'=>'Balance updated'];
	die();
}
else{
	$final_output= ['result'=>'failure','message'=>'Balance update failed'];
	die();
}