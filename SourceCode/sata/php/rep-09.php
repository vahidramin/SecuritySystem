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
	
	$hirarchy = "rep-09_{$level}_{$zone}_{$sar}_{$br}_{$aghd}_{$fasl}_{$year}{$moon}";

	if($sar == -1)
	{
		$query = "
		SELECT 
			ZONECODE,
			SAR,
			NAME,
			AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
		FROM 
			SATA.REPSAR84409
		WHERE
			UPDATEDATE={$year}{$moon}
		ORDER BY
			ORDERCODE
		";
		

		$arr = array(
			"hirarchy" => $hirarchy,
			"subtitle" => "به تفکیک اداره امور",
			"head" => array("", "اداره امور", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
			"format" => array("link", "sar", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
			"body" => array(),
			"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
			"query" => $query
		);
		$len = count($arr["format"])-8;
		
		$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());
		
		$currentZone = 1;
		$midSum = array("type" => "midSum", "", $currentZone, 0, 0, 0, 0, 0, 0, 0, 0);

		while(odbc_fetch_row($result))
		{
			if(odbc_result($result, "ZONECODE") > $currentZone)
			{
				array_push($arr["body"], $midSum);
				$currentZone = odbc_result($result, "ZONECODE");
				$midSum = array("type" => "midSum", "", $currentZone, 0, 0, 0, 0, 0, 0, 0, 0);
			}
			$newline = array(
				"type" => false,
				array("نمایش شعب", "{report:\"rep-09\", level:\"zone\", sar:".odbc_result($result, "SAR").", br:{$br}, year:{$year}, moon:{$moon}, zone:".odbc_result($result, "ZONECODE").", aghd:0}"),
				odbc_result($result, "SAR"), 
				odbc_result($result, "AMOUNT01"), 
				odbc_result($result, "AMOUNT02"), 
				odbc_result($result, "AMOUNT03"), 
				odbc_result($result, "AMOUNT04"), 
				odbc_result($result, "AMOUNT05"), 
				odbc_result($result, "AMOUNT06"), 
				odbc_result($result, "AMOUNT07"),
				(   odbc_result($result, "AMOUNT01") + 
					odbc_result($result, "AMOUNT02") + 
					odbc_result($result, "AMOUNT03") + 
					odbc_result($result, "AMOUNT04") + 
					odbc_result($result, "AMOUNT05") +
					odbc_result($result, "AMOUNT06") +
					odbc_result($result, "AMOUNT07"))
			);
			array_push($arr["body"], $newline);
			
			for($i = 0; $i<8; $i++)
			{
				$midSum[$i+2] += $newline[$i+$len];
				$arr["rowsum"][$i] += $newline[$i+$len];
			}
		}
		array_push($arr["body"], $midSum);
	}
	else
	{
		$query = "
		SELECT 
			BR,
			AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
		FROM 
			SATA.VBR84409
		WHERE
			SAR = {$sar} AND
			UPDATEDATE={$year}{$moon}
		ORDER BY
			BR
		";
		

		$arr = array(
			"hirarchy" => $hirarchy,
			"subtitle" => "شعب اداره امور {$sarList[$sar]}",
			"head" => array("شعبه", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
			"format" => array("br", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
			"body" => array(),
			"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
			"query" => $query
		);
		$len = count($arr["format"])-8;
		
		$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());
		
		while(odbc_fetch_row($result))
		{
			$newline = array(
				odbc_result($result, "BR"), 
				odbc_result($result, "AMOUNT01"), 
				odbc_result($result, "AMOUNT02"), 
				odbc_result($result, "AMOUNT03"), 
				odbc_result($result, "AMOUNT04"), 
				odbc_result($result, "AMOUNT05"), 
				odbc_result($result, "AMOUNT06"), 
				odbc_result($result, "AMOUNT07"),
				(   odbc_result($result, "AMOUNT01") + 
					odbc_result($result, "AMOUNT02") + 
					odbc_result($result, "AMOUNT03") + 
					odbc_result($result, "AMOUNT04") + 
					odbc_result($result, "AMOUNT05") +
					odbc_result($result, "AMOUNT06") +
					odbc_result($result, "AMOUNT07"))
			);
			array_push($arr["body"], $newline);
			
			for($i = 0; $i<8; $i++)
			{
				$arr["rowsum"][$i] += $newline[$i+$len];
			}
		}
	}

	odbc_close($db2conn);
    echo json_encode($arr);

?>