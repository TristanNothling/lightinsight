<?php

function getToken($length){
	/*used to generate session tokens and salts*/

	$token = "";
	$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	$codeAlphabet.= "0123456789";
	$max = strlen($codeAlphabet);

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
}

function sendOutput(){

	global $final_output;
	global $sql_conn;

	echo json_encode($final_output);

	$sql_conn->close();
	
}

function encrypt($pure_string, $encryption_key) {
        $cipher     = 'AES-256-CBC';
        $options    = OPENSSL_RAW_DATA;
        $hash_algo  = 'sha256';
        $sha2len    = 32;
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($pure_string, $cipher, $encryption_key, $options, $iv);
        $hmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
        return $iv.$hmac.$ciphertext_raw;
    }
    
function decrypt($encrypted_string, $encryption_key) {
        $cipher     = 'AES-256-CBC';
        $options    = OPENSSL_RAW_DATA;
        $hash_algo  = 'sha256';
        $sha2len    = 32;
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($encrypted_string, 0, $ivlen);
        $hmac = substr($encrypted_string, $ivlen, $sha2len);
        $ciphertext_raw = substr($encrypted_string, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $encryption_key, $options, $iv);
        $calcmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
        if(function_exists('hash_equals')) {
            if (hash_equals($hmac, $calcmac)) return $original_plaintext;
        } else {
            if ($this->hash_equals_custom($hmac, $calcmac)) return $original_plaintext;
        }
    }

/**
 * (Optional)
 * hash_equals() function polyfilling.
 * PHP 5.6+ timing attack safe comparison
 */
function hash_equals_custom($knownString, $userString) {
    if (function_exists('mb_strlen')) {
        $kLen = mb_strlen($knownString, '8bit');
        $uLen = mb_strlen($userString, '8bit');
    } else {
        $kLen = strlen($knownString);
        $uLen = strlen($userString);
    }
    if ($kLen !== $uLen) {
        return false;
    }
    $result = 0;
    for ($i = 0; $i < $kLen; $i++) {
        $result |= (ord($knownString[$i]) ^ ord($userString[$i]));
    }
    return 0 === $result;
}

function get_categories($sql_conn){

$types = [0=>'in',1=>'out'];

$final_array= [];

$stmt = $sql_conn->prepare("SELECT * FROM `jwrpa_categories`");
$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows === 0) {
	return [];
}

    while($row = $result->fetch_assoc()) {
    	$final_array[$types[$row['hnccp_type']]][$row['aszcp_id']] = $row['afkvx_name'];
    }

    return $final_array;
}

function get_user_details($sql_conn,$user_id) {

$stmt = $sql_conn->prepare("SELECT * FROM `cqeiq_users` WHERE `rqipo_id` = ?");

$stmt->bind_param("s",$user_id);
$stmt->execute();

$result = $stmt->get_result();
$output = [];

while($row = $result->fetch_assoc()) {
        $output['current_balance'] = $row['etnvc_current_bal'];
    }

return $output;
}

?>