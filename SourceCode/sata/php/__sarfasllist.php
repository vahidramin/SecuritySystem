<?php
	
    require_once("include.php");

    $db2conn = db2connect();

	$query = "SELECT SARFASL, NAME FROM INSN.SARFASL WHERE SARFASL BETWEEN 1200000 AND 26200000";
	$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

	$sarfasls = array();

	while(odbc_fetch_row($result))
	{
		$sarfasls[odbc_result($result, "SARFASL")] = iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "NAME")));
	}

	odbc_close($db2conn);
    echo json_encode($sarfasls);

?>