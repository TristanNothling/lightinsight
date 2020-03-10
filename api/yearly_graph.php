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

/*ACCEPTED METHODS - GET */

$year_requested = $_GET['year'];

$decryption_key = $_SESSION['logged_user_id'] . $_SESSION['pepper'];

/*get current balance*/

/*create date array of entire year*/

$start = new DateTime(strval($year_requested).'-01-01');

$current_date = $start;

for ($i=0; $i <= 366; $i++) { /*to account for leap year*/
	if ($year_requested==$current_date->format('Y'))
	{
	$date_array[$current_date->format('d-m-Y')] = 0;
	$current_date ->add(new DateInterval('P1D'));
	}
}

$stmt = $sql_conn->prepare("SELECT * FROM plzna_transactions WHERE YEAR(`jwecv_date`) = ? AND `vrbtn_belongs_to` = ? AND `oqwaa_enabled` = 1 ORDER BY `jwecv_date` ASC");

$stmt->bind_param("ss",$year_requested,$_SESSION['logged_user_id']);
$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows === 0) {
$final_output = ['result'=>'success','data'=>[]];
die();
}
$final_output = ['result'=>'success'];

while($row = $result->fetch_assoc()) {
  $real_amount = decrypt(base64_decode($row['askdl_value_sig']),$decryption_key);
  $real_date = date("d-m-Y", strtotime($row['jwecv_date']));
  $real_type = $row['jkqwe_type'];
  $transactions[$real_date][] = ['value'=>$real_amount,'type'=>$real_type]; 
}


$current_date = new DateTime();
$user_details = get_user_details($sql_conn,$_SESSION['logged_user_id']);
$current_balance = $user_details['current_balance'];


$date_array[$current_date->format('d-m-Y')] = $current_balance;

while ($current_date->format('Y') == $year_requested)
{

	if (isset($transactions[$current_date->format('d-m-Y')] ))
		{

			$todays_transactions = $transactions[$current_date->format('d-m-Y')];

			foreach ($todays_transactions as $item) {
				if ($item['type']==1)
				{
					$current_balance+= $item['value'];
				}
				if ($item['type']==0)
				{
					$current_balance-= $item['value'];
				}
			}


		}

	$date_array[$current_date->format('d-m-Y')] = $current_balance;
	$current_date ->add(new DateInterval('P1D'));
}



$current_date = new DateTime();
$current_date ->sub(new DateInterval('P1D'));
$current_balance = $user_details['current_balance'];

while ($current_date->format('Y') == $year_requested)
{

	if (isset($transactions[$current_date->format('d-m-Y')] ))
		{
			$todays_transactions = $transactions[$current_date->format('d-m-Y')];

			foreach ($todays_transactions as $item) {
				if ($item['type']==1)
				{
					$current_balance-= $item['value'];
				}
				if ($item['type']==0)
				{
					$current_balance+= $item['value'];
				}
			}
		}
		$date_array[$current_date->format('d-m-Y')] = $current_balance;
		$current_date ->sub(new DateInterval('P1D'));
	
}

$balance_array = [];
$final_date_array = [];

foreach ($date_array as $date => $value) {
	$balance_array[] = $value;
	$final_date_array[] = $date;
}


$final_output['balance'] = $balance_array;
$final_output['labels'] = $final_date_array;

?>