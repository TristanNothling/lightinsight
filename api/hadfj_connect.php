<?php

$username = "npaaykwvxc";
$password = "GvUq924SJK";

$username = "root";
$password = "";

$host = "localhost";
$database = "npaaykwvxc";

$sql_conn = new mysqli($host,$username,$password,$database);

$db_status = '';

if ($sql_conn -> connect_errno) {
	$db_status = "failure: " . $sql_conn -> connect_error;
}
else
{
	$db_status = "success: connected successfully.";
}

?>