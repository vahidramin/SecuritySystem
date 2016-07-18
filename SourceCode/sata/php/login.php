<?php
	session_start();
	
	$usenames = array(
		1 => "admin",
		2 => "r930443",
		3 => "n55063"
	);
	$passwords = array(
		1 => "pass",
		2 => "123456",
		3 => "123456"
	);
	
	$uname = $_GET["satausername"];
	$pass = $_GET["satapass"];
	
	if( 
		(in_array($uname, $usenames, TRUE) AND in_array($pass, $passwords, TRUE)) AND
		array_search($uname, $usenames, TRUE) == array_search($pass, $passwords, TRUE)
		)
	{
		$_SESSION["SATA-USERNAME"] = $uname;
		$_SESSION["SATA-PASSWORD"] = $pass;
		echo "ok";
	}
	elseif(!in_array($uname, $usenames, TRUE))
	{
		echo "ERROR:username";
	}
	else
	{
		echo "ERROR:password";
	}

?>