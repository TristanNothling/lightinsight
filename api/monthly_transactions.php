<?php

/*get session row, */
/*auto logoff thing*/
/*if */
/*$final_result = ['result'=>'expired','message'=>'Your session has expired'];*/
/*category list, as array*/

sleep(0.5);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'functions.php';
include 'hadfj_connect.php';

date_default_timezone_set('UTC');
register_shutdown_function('sendOutput');

/*ACCEPTED METHODS - GET*/

$decryption_key = $_SESSION['logged_user_id'] . $_SESSION['pepper'];

$month_requested = $_GET['month'];
$year_requested = $_GET['year'];

$stmt = $sql_conn->prepare("SELECT * FROM plzna_transactions WHERE MONTH(`jwecv_date`) = ? AND YEAR(`jwecv_date`) = ? AND `vrbtn_belongs_to` = ? ");

$stmt->bind_param("sss",$month_requested,$year_requested,$_SESSION['logged_user_id']);
$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows === 0) {
	$final_output = ['result'=>'success','data'=>[]];
	die();
}

$final_output = ['result'=>'success'];

while($row = $result->fetch_assoc()) {
  $real_amount = decrypt(base64_decode($row['askdl_value_sig']),$decryption_key);
  $real_description = decrypt(base64_decode($row['wqeok_description']),$decryption_key);
  $real_date = date("d-m-Y", strtotime($row['jwecv_date']));  
  $real_type = $row['jkqwe_type'];
  $real_id = $row['zeqwe_id'];
  
  $final_output['data'][] = ['id'=>$real_id,'type'=>$real_type,'description'=>$real_description,'value'=>$real_amount,'date'=>$real_date]; 
}


?>