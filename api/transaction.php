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

$categories = get_categories($sql_conn);

/*ACCEPTED METHODS - POST */
/*check logged user in session, otherwise fuck off!!!*/

if ($_POST['method'] == '' || !isset($_POST['method'])){
	$final_output= ['result'=>'failure','message'=>'Invalid method'];
	die();
}

if ($_POST['method'] === 'create') {

	$transaction_date = $_POST['date'];

	$newformat = date('Y-m-d', strtotime(str_replace('/','-',$transaction_date)));
	$type = intval($_POST['type']); /*type 0 = income, type 1 = outgoing*/

	/*checkDate($transaction_date);*/
	/*if $date_check == false {$final_result = ['result'=>'failure','message'=>'Invalid date'];}*/
	$type = intval($_POST['type']);

	if ($type==1){
		$category = $_POST['in_cat'];
	}
	if ($type==0){
		$category = $_POST['out_cat'];
	}

	if (!in_array($category, array_keys($categories['in'])) && !in_array($category, array_keys($categories['out']))){
		$final_result = ['result'=>'failure','message'=>'Invalid category'];
	}

	$amount = strval($_POST['amount']);

	$encryption_key = $_SESSION['logged_user_id'] . $_SESSION['pepper'];

	$description = $_POST['description'];

	/*get max length of description before encryption fails*/

	if (preg_match("/^\d+$/", $amount)) {
    	$encrypted_amount = base64_encode(encrypt($amount,$encryption_key));
    	$encrypted_description = base64_encode(encrypt($description,$encryption_key));
	} else {
    	$final_output = ['result'=>'failure','message'=>'Invalid amount'];
    	die();
	}

	if (!ctype_alpha(str_replace(' ', '', $description))){
		$final_output = ['result'=>'failure','message'=>'Invalid description'];
		die();
	}

	$stmt = $sql_conn->prepare("INSERT INTO plzna_transactions (askdl_value_sig,vrbtn_belongs_to,wqeok_description,jwecv_date,haasx_category,jkqwe_type,oqwaa_enabled) VALUES (?,?,?,?,?,?,?)");
	$enabled = 1;

	$stmt->bind_param("ssssiii",$encrypted_amount,$_SESSION['logged_user_id'],$encrypted_description,$newformat,$category,$type,$enabled);
	$stmt->execute();

	$final_output = ['result'=>'success','message'=>'Inserted transaction'];
	die();

}

if ($_POST['method'] === 'toggle'){
	$_POST['transaction_id'];

	/*get transaction enabled*/
	/*reverse it*/
	/*update*/
}

if ($_POST['method'] === 'modify'){

	$_POST['transaction_id'];
	$type = boolval($_POST['type']); /*type 0 = income, type 1 = outgoing*/
	$_POST['date'];

	/*additional field, passed*/
	/*if changing date to later than current date, and passed is true, reverse change to balance before adjusting.*/

	$_POST['amount'];
	$_POST['description'];
	$category = intval($_POST['category']);

}

if ($_POST['method'] === 'delete'){

	$tran_id = strval($_POST['tid']);
	$user_id = intval($_SESSION['logged_user_id']);

	$stmt = $sql_conn->prepare("DELETE FROM plzna_transactions WHERE `zeqwe_id` = ? AND `vrbtn_belongs_to` = ? ");
	$stmt->bind_param("si",$tran_id,$user_id);
	$result = boolval($stmt->execute());

	if ($result){
		$final_output = ['result'=>'success','message'=>'Deleted Transaction'];
		die();
	}
	else{
		$final_output = ['result'=>'failure','message'=>'Transaction does not belong to you or exist'];
		die();
	}

}

$final_output = ['result'=>'failure','message'=>'Invalid method'];

?>