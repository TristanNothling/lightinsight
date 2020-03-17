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

	$type = intval($_POST['type']); /* in or out*/

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

	if (!ctype_alpha(str_replace(' ', '', $description))){
		$final_output = ['result'=>'failure','message'=>'Invalid description'];
		die();
	}

	if (preg_match("/^\d+$/", $amount)) {
    	$encrypted_amount = base64_encode(encrypt($amount,$encryption_key));
    	$encrypted_description = base64_encode(encrypt($description,$encryption_key));
	} else {
    	$final_output = ['result'=>'failure','message'=>'Invalid amount'];
    	die();
	}

	$repeat_type = intval($_POST['repeat_type']); /*1 date every month, 2 every x days*/
	$repeat = intval($_POST['repeat']); /*date or number of days*/
	$occurences = intval($_POST['occurences']);

	if (!preg_match("/^\d+$/", $repeat) && !preg_match("/^\d+$/", $occurences)) {
    	$final_output = ['result'=>'failure','message'=>'Invalid repeating number or occurences'];
    	die();
	}

	$stmt = $sql_conn->prepare("INSERT INTO `nnbca_recurring_transactions` (tiyrh_value_sig,egtrr_belongs_to,jwena_description,egbvv_start_date,hatrx_category,dbfxv_type,bsdjw_repeat_type,etrhc_repeat,vbzpp_occurences,xcvbl_enabled) VALUES (?,?,?,?,?,?,?,?,?,?)");
	$enabled = 1;
	/*insert into recurring transactions*/

	$stmt->bind_param("sissiiiiii",$encrypted_amount,$_SESSION['logged_user_id'],$encrypted_description,$newformat,$category,$type,$repeat_type,$repeat,$occurences,$enabled);
	$result = $stmt->execute();

	if (!$result)
	{
		$final_output = ['result'=>'failure','message'=>'Could not insert recurring transaction' ];
    	die();
	}

	$parent_id = $sql_conn->insert_id;

	$start_date = new DateTime($newformat); /*starting from this date*/

	if (empty($occurences)  || $occurences == 0 || $occurences>50) {
		$occurences = 50; /*just make 50 for now*/
	}

	
	for ($i=0; $i < $occurences; $i++) { 
		if ($repeat_type==1){

			$unformatted_insert_date = get_monthly_inc_date($start_date,$repeat,$i);
			$insert_date = $unformatted_insert_date->format('Y-m-d');
		}

		if ($repeat_type==2){

			$unformatted_insert_date = get_day_inc_date($start_date,$repeat,$i);
			$insert_date = $unformatted_insert_date->format('Y-m-d');
			
		}


		$stmt = $sql_conn->prepare("INSERT INTO plzna_transactions (askdl_value_sig,vrbtn_belongs_to,wqeok_description,jwecv_date,haasx_category,jkqwe_type,oqwaa_enabled,asdjl_recurring_parent) VALUES (?,?,?,?,?,?,?,?)");
		$enabled = 1;

		$stmt->bind_param("ssssiiis",$encrypted_amount,$_SESSION['logged_user_id'],$encrypted_description,$insert_date,$category,$type,$enabled,$parent_id);
		$stmt->execute();

	}


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

	/*check not recurring */

	$_POST['transaction_id'];

	/*prepared statement, delete from transactions*/

}

$final_result = ['result'=>'failure','message'=>'Invalid method'];

?>