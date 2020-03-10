<?php


/*{'result':'success','token':'64 chars'}*/
/*{'result':'failure','message':''}*/

sleep(0.5);
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();
include 'functions.php';
include 'hadfj_connect.php';

register_shutdown_function('sendOutput');



$database_result = explode(":", $db_status);

if ('failure' == $database_result[0]){
	$final_output = ['result'=>'failure','message'=>'There was a problem connecting to the server.'];
	die();
}

/*handshake, check ip in table and block if nessecary*/

if (!isset($_POST) || '' == $_POST['email'] || '' == $_POST['password'] ){
	$final_output = ['result'=>'failure','message'=>'Your email or password was blank.'];
	die();
}

/*if username or password containers illegal chars*/

/*	$final_output = ['result'=>'failure','message'=>'Your ']
	die();

}*/

$email = $_POST['email'];
$submitted_password = $_POST['password'];

$get_user_query = "SELECT rqipo_id, dfpcc_validated_email, vnaik_email, oifgh_password, btnyv_salt, btasd_reg_datetime, tgrrq_login_attempts, hhyyi_locked FROM cqeiq_users WHERE vnaik_email = ?";

$stmt = $sql_conn->prepare($get_user_query);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows === 0) {
	$final_output = ['result'=>'failure','message'=>'Your email or password was incorrect.'];
	die();
}

if ($result->num_rows === 1) {

	$intended_user = $result->fetch_assoc();

	$user_id = $intended_user['rqipo_id'];
	$validated_email = $intended_user['dfpcc_validated_email'];
	$password_hash = $intended_user['oifgh_password'];
	$salt = $intended_user['btnyv_salt'];
	$reg_datetime = $intended_user['btasd_reg_datetime'];
	$login_attempts = (int) $intended_user['tgrrq_login_attempts'];
	$locked = $intended_user['hhyyi_locked'];

	$login_attempts++;

	$date = new DateTime($reg_datetime);
	$reg_month = (int) $date->format('m');

	$hash_repeat = 1 + ($reg_month * 2) ;

	/*update set login attempts*/

	if (1 == $locked){
		$final_output = ['result'=>'failure','message'=>'Your account is currently locked.'];
		die();
	}

	if (5 < $login_attempts){
		$final_output = ['result'=>'failure','message'=>'Your account has been locked due to too many incorrect login attempts.'];

		/*UPDATE SET LOCKED=1 WHERE ID = ''*/

		die();
	}

	if (0 == $validated_email){
		$final_output = ['result'=>'failure','message'=>'Your email has not been validated. Please confirm your email address by clicking on the link in the welcome email. Let us know if you didn&#39;t receive this.'];
		die();
	}


	/*	for ($i=1; $i < $hash_repeat; $i++) { */
	$submitted_password = hash('sha512', $submitted_password . $salt);
	/*	}*/

	if (strtoupper($submitted_password) == $password_hash){

		/*clear previous token*/

		$your_new_token = getToken(54) . strval(time());
		$inserted_start_dt = date("Y/m/d H:i:s", strtotime("now"));
		$inserted_expire_dt = date("Y/m/d H:i:s", strtotime("+60 minutes"));

		$user_agent = '';
		$ip_address = '';

		$stmt = $sql_conn->prepare("INSERT INTO asdfz_sessions (fgyua_start,fgyua_expires,vrbty_session_token,jweoz_belongs_to,plzxa_user_agent,inuaq_ip_address) VALUES (?,?,?,?,?,?)");

		$stmt->bind_param("ssssss", $inserted_start_dt, $inserted_expire_dt,$your_new_token,$user_id,$user_agent,$ip_address);
		$stmt->execute();

		$token_id = $sql_conn->insert_id;

		/*check 1 row created, otherwise problem*/

		$_SESSION['logged_user_id'] = $user_id;
		$_SESSION['pepper'] = $salt;
		$_SESSION['token_id'] = $token_id;
		$_SESSION['token'] = $your_new_token;
		$_SESSION['expires'] = $inserted_expire_dt;

		session_write_close();
		
		$final_output = ['result'=>'success','token'=>$your_new_token];
		die();

	}
	else {

		$final_output = ['result'=>'failure','message'=>'Your email or password was incorrect.'];
		die();

	}

}

/*if more than one or two rows, duplicate user, defo a problem.*/

?>