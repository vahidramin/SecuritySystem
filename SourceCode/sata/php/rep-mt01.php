<?php
	header('Content-Type: text/html; charset=utf-8');
    require_once("include.php");

    $db2conn = db2connect();

	$level 	= (isset($_GET["level"])) 	? $_GET["level"] : "zone";
	$sar 	= (isset($_GET["sar"])) 	? $_GET["sar"] : -1;
	$br 	= (isset($_GET["br"])) 		? $_GET["br"] : -1;
	$year 	= (isset($_GET["year"]))	? $_GET["year"] : "95";
	$moon 	= (isset($_GET["moon"])) 	? $_GET["moon"] : "01";
	$zone 	= (isset($_GET["zone"])) 	? $_GET["zone"] : -1;
	$aghd 	= (isset($_GET["aghd"])) 	? $_GET["aghd"] : 0;
	$fasl 	= (isset($_GET["fasl"])) 	? $_GET["fasl"] : 0;
	
	$hirarchy = "rep-mt01_{$level}_{$zone}_{$sar}_{$br}_{$aghd}_{$fasl}_{$year}{$moon}";

	if($sar == -1)
	{
		$query = "
		SELECT 
			SAR,
			SUM(AMOUNT01) AS AMOUNT01,
			MIN(UPDATEDATE) AS UPDATEDATE
		FROM 
			SATA.REPMT01
		WHERE
			UPDATEDATE={$year}{$moon}
		GROUP BY
			SAR
		ORDER BY
			SAR
		";
		
		$arr = array(
			"hirarchy" => $hirarchy,
			"subtitle" => "به تفکیک اداره امور",
			"head" => array("", "اداره امور", "مغایرت", "تاریخ"),
			"format" => array("link", "sar", "amount", "date"),
			"body" => array(),
			"rowsum" => array(0, ""),
			"query" => $query
		);
		
		$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

		while(odbc_fetch_row($result))
		{
			$newline = array(
				array("نمایش شعب", "{report:\"rep-mt01\", level:\"zone\", sar:".odbc_result($result, "SAR").", br:{$br}, year:{$year}, moon:{$moon}, zone:0, aghd:0}"),
				odbc_result($result, "SAR"), 
				odbc_result($result, "AMOUNT01"), 
				odbc_result($result, "UPDATEDATE")
			);
			array_push($arr["body"], $newline);
			$arr["rowsum"][0] += $newline[2];
		}
	}
	else
	{
		$query = "
		SELECT 
			SAR,
			BR,
			AMOUNT01,
			UPDATEDATE
		FROM 
			SATA.REPMT01
		WHERE
			SAR = {$sar} AND
			UPDATEDATE={$year}{$moon}
		ORDER BY
			BR
		";
		
		$arr = array(
			"hirarchy" => $hirarchy,
			"subtitle" => "شعب اداره امور {$sarList[$sar]}",
			"head" => array("اداره امور", "شعبه", "مغایرت", "تاریخ"),
			"format" => array("sar", "br", "amount", "date"),
			"body" => array(),
			"rowsum" => array(0, ""),
			"query" => $query
		);
		
		$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

		while(odbc_fetch_row($result))
		{
			$newline = array(
				odbc_result($result, "SAR"), 
				odbc_result($result, "BR"), 
				odbc_result($result, "AMOUNT01"), 
				odbc_result($result, "UPDATEDATE")
			);
			array_push($arr["body"], $newline);
			$arr["rowsum"][0] += $newline[2];
		}
	}

	odbc_close($db2conn);
    echo json_encode($arr);

?>