<?php

/*{'result':'success'}*/
/*{'result':'failure','message':''}*/

sleep(0.5);
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();
include 'functions.php';
include 'hadfj_connect.php';
register_shutdown_function('sendOutput');

/*functionise this*/


if (isset($_SESSION['token_id'])){

	$logging_out_token = $_SESSION['token_id'];

	$stmt = $sql_conn->prepare("DELETE FROM asdfz_sessions WHERE sadkp_id = ?");

	$stmt->bind_param("s", $logging_out_token );
	$stmt->execute();

	session_unset();

	/*clear session with python anywhere*/
			
	$final_output = ['result'=>'success'];
	die();

}
else
{
	$final_output = ['result'=>'failure','message'=>'No valid session exists.'];
}