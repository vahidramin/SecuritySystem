<?php
	
	$dbhost = '10.1.1.2';
	$dbuser = 'root';
	$dbpass = '123456';
	$dbname = 'bmiconnect2';
	
	$mysqlconn = mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname);
	mysql_query ('SET NAMES UTF8');
	
	$myquery = "
		SELECT sarcode, brcode, brname FROM bmiconnect2.branchclassification order by sarcode, brcode;
	";
	$result = mysql_query($myquery);
	$brs = array();

	while($row = mysql_fetch_row($result)){
		//echo $row[2]."<br/>";
		$brs[$row[0]][$row[1]] = $row[2];
	}

	mysql_close($mysqlconn);
	
	/*
	echo "{<br/>";
	foreach($brs as $sar => $list)
	{
		echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $sar." : {<br/>";
		foreach($list as $br => $name)
		{
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{" . $br . ":'{$name}'},<br/>";
		}
		echo "},<br/>";
	}
	echo "}";
	*/
	echo json_encode($brs);

?>