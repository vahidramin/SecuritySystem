<?php
	session_start();
	
	$uname = $_GET["satausername"];
	$pass = $_GET["satapass"];
	
	if($uname == "admin" and $pass == "pass")
	{
		$_SESSION["SATA-USERNAME"] = $uname;
		$_SESSION["SATA-PASSWORD"] = $pass;
		echo "ok";
	}
	elseif($uname != "admin")
	{
		echo "ERROR:username";
	}
	else
	{
		echo "ERROR:password";
	}

?>