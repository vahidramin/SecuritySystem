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
	
	$hirarchy = "rep-844_{$level}_{$zone}_{$sar}_{$br}_{$aghd}_{$fasl}_{$year}{$moon}";
	
	
	if($level == "zone")
	{
		if($zone == -1)
		{
			$query = "
			SELECT 
				ZONECODE,
				NAME,
				AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04
			FROM 
				SATA.VMNT84400
			WHERE
				UPDATEDATE={$year}{$moon}
			";

			$arr = array(
				"hirarchy" => $hirarchy,
				"subtitle" => "به تفکیک منطقه",
				"head" => array("جزییات", "شرح", "منطقه", "غیردولتی - غیرتبصره‌ای", "غیردولتی - تبصره‌ای", "دولتی - غیرتبصره‌ای", "دولتی - تبصره‌ای", "جمع"),
				"format" => array("link", "link", "ebcdic", "amount", "amount", "amount", "amount", "amountsum"),
				"body" => array(),
				"rowsum" => false,
				"query" => $query
			);
			
			$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

			while(odbc_fetch_row($result))
			{
				$newline = array(
					array("جزییات عقود", "{report:\"rep-844\", level:\"zone\", sar:{$sar}, br:{$br}, year:{$year}, moon:{$moon}, zone:".odbc_result($result, "ZONECODE").", aghd:1}"),
					array("ادارات امور", "{report:\"rep-844\", level:\"zone\", sar:{$sar}, br:{$br}, year:{$year}, moon:{$moon}, zone:".odbc_result($result, "ZONECODE").", aghd:0}"),
					iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "NAME"))), 
					odbc_result($result, "AMOUNT01"), 
					odbc_result($result, "AMOUNT02"), 
					odbc_result($result, "AMOUNT03"), 
					odbc_result($result, "AMOUNT04"), 
					(   odbc_result($result, "AMOUNT01") + 
						odbc_result($result, "AMOUNT02") + 
						odbc_result($result, "AMOUNT03") + 
						odbc_result($result, "AMOUNT04"))
				);
				array_push($arr["body"], $newline);
			}
		}
		else
		{
			if($aghd == 0)
			{
				if($sar == -1)
				{
					$query = "
					SELECT 
						ZONECODE,
						SAR,
						AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04
					FROM 
						SATA.VSAR84400
					WHERE
						ZONECODE = {$zone} AND
						UPDATEDATE={$year}{$moon}
					";
					

					$arr = array(
						"hirarchy" => $hirarchy,
						"subtitle" => "ادارات امور {$zoneList[$zone]}",
						"head" => array("", "", "اداره امور", "غیردولتی - غیرتبصره‌ای", "غیردولتی - تبصره‌ای", "دولتی - غیرتبصره‌ای", "دولتی - تبصره‌ای", "جمع"),
						"format" => array("link", "link", "sar", "amount", "amount", "amount", "amount", "amountsum"),
						"body" => array(),
						"rowsum" => array(0, 0, 0, 0, 0),
						"query" => $query
					);
					$len = count($arr["format"])-5;
					
					$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

					while(odbc_fetch_row($result))
					{
						$newline = array(
							array("مشاهده عقود", "{report:\"rep-844\", level:\"zone\", sar:".odbc_result($result, "SAR").", br:{$br}, year:{$year}, moon:{$moon}, zone:{$zone}, aghd:1}"),
							array("نمایش شعب", "{report:\"rep-844\", level:\"zone\", sar:".odbc_result($result, "SAR").", br:{$br}, year:{$year}, moon:{$moon}, zone:{$zone}, aghd:0}"),
							odbc_result($result, "SAR"), 
							odbc_result($result, "AMOUNT01"), 
							odbc_result($result, "AMOUNT02"), 
							odbc_result($result, "AMOUNT03"), 
							odbc_result($result, "AMOUNT04"), 
							(   odbc_result($result, "AMOUNT01") + 
								odbc_result($result, "AMOUNT02") + 
								odbc_result($result, "AMOUNT03") + 
								odbc_result($result, "AMOUNT04"))
						);
						array_push($arr["body"], $newline);
						
						for($i = 0; $i<5; $i++)
							$arr["rowsum"][$i] += $newline[$i+$len];
					}
				}
				else
				{
					$query = "
					SELECT
						BR,
						AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04
					FROM 
						SATA.VBR84400
					WHERE
						SAR = {$sar} AND
						UPDATEDATE={$year}{$moon}
					ORDER BY
						BR
					";
					

					$arr = array(
						"hirarchy" => $hirarchy,
						"subtitle" => "شعب اداره امور {$sarList[$sar]}",
						"head" => array("", "شعبه", "غیردولتی - غیرتبصره‌ای", "غیردولتی - تبصره‌ای", "دولتی - غیرتبصره‌ای", "دولتی - تبصره‌ای", "جمع"),
						"format" => array("link", "br", "amount", "amount", "amount", "amount", "amountsum"),
						"body" => array(),
						"rowsum" => array(0, 0, 0, 0, 0),
						"query" => $query
					);
					$len = count($arr["format"])-5;
					
					$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

					while(odbc_fetch_row($result))
					{
						$newline = array(
							array("مشاهده عقود", "{report:\"rep-844\", level:\"zone\", sar:{$sar}, br:".odbc_result($result, "BR").", year:{$year}, moon:{$moon}, zone:{$zone}, aghd:1}"),
							odbc_result($result, "BR"), 
							odbc_result($result, "AMOUNT01"), 
							odbc_result($result, "AMOUNT02"), 
							odbc_result($result, "AMOUNT03"), 
							odbc_result($result, "AMOUNT04"), 
							(   odbc_result($result, "AMOUNT01") + 
								odbc_result($result, "AMOUNT02") + 
								odbc_result($result, "AMOUNT03") + 
								odbc_result($result, "AMOUNT04"))
						);
						array_push($arr["body"], $newline);

						for($i = 0; $i<5; $i++)
							$arr["rowsum"][$i] += $newline[$i+$len];
					}

				}
			}
			else
			{
				if($sar == -1)
				{
					$query = "
					SELECT 
						(ROW-INDX) AS TYPE,
						ROW,
						DESCRIPTION,
						AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04
					FROM 
						SATA.VDMNT84400
					WHERE
						ZONCODE = {$zone} AND
						UPDATEDATE={$year}{$moon}
					ORDER BY
						INDX, ROW
					";
					
					
					$arr = array(
						"hirarchy" => $hirarchy,
						"subtitle" => "عقود {$zoneList[$zone]}",
						"head" => array("شرح", "غیردولتی - غیرتبصره‌ای", "غیردولتی - تبصره‌ای", "دولتی - غیرتبصره‌ای", "دولتی - تبصره‌ای", "جمع"),
						"format" => array("ebcdic", "amount", "amount", "amount", "amount", "amountsum"),
						"body" => array(),
						"rowsum" => array(0, 0, 0, 0, 0),
						"query" => trim($query)
					);
					$len = count($arr["format"])-5;
					
					$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());
					while(odbc_fetch_row($result))
					{
						$newline = array(
							"type" => false,
							iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
							odbc_result($result, "AMOUNT01"), 
							odbc_result($result, "AMOUNT02"), 
							odbc_result($result, "AMOUNT03"), 
							odbc_result($result, "AMOUNT04"), 
							(   odbc_result($result, "AMOUNT01") + 
								odbc_result($result, "AMOUNT02") + 
								odbc_result($result, "AMOUNT03") + 
								odbc_result($result, "AMOUNT04"))
						);
				
						if(odbc_result($result, "TYPE") == 0)
						{
							$newline["type"] = "title";
						}
				
						array_push($arr["body"], $newline);
						
						for($i = 0; $i<5; $i++)
							$arr["rowsum"][$i] += $newline[$i+$len];
					}
				}
				else
				{
					if($br == -1)
					{
						$query = "
						SELECT 
							(ROW-INDX) AS TYPE,
							ROW,
							DESCRIPTION,
							AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04
						FROM 
							SATA.VDSAR84400
						WHERE
							ZONCODE = {$zone} AND
							SAR = {$sar} AND
							UPDATEDATE={$year}{$moon}
						ORDER BY
							INDX, ROW
						";
						
						$arr = array(
							"hirarchy" => $hirarchy,
							"subtitle" => "عقود اداره امور {$sarList[$sar]}",
							"head" => array("شرح", "غیردولتی - غیرتبصره‌ای", "غیردولتی - تبصره‌ای", "دولتی - غیرتبصره‌ای", "دولتی - تبصره‌ای", "جمع"),
							"format" => array("ebcdic", "amount", "amount", "amount", "amount", "amountsum"),
							"body" => array(),
							"rowsum" => array(0, 0, 0, 0, 0),
							"query" => trim($query)
						);
						$len = count($arr["format"])-5;
						
						$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());
						while(odbc_fetch_row($result))
						{
							$newline = array(
								"type" => false,
								iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
								odbc_result($result, "AMOUNT01"), 
								odbc_result($result, "AMOUNT02"), 
								odbc_result($result, "AMOUNT03"), 
								odbc_result($result, "AMOUNT04"), 
								(   odbc_result($result, "AMOUNT01") + 
									odbc_result($result, "AMOUNT02") + 
									odbc_result($result, "AMOUNT03") + 
									odbc_result($result, "AMOUNT04"))
							);
				
							if(odbc_result($result, "TYPE") == 0)
							{
								$newline["type"] = "title";
							}
				
							array_push($arr["body"], $newline);
							
							for($i = 0; $i<5; $i++)
								$arr["rowsum"][$i] += $newline[$i+$len];
						}
					}
					else
					{
						$query = "
						SELECT 
							(ROW-INDX) AS TYPE,
							ROW,
							DESCRIPTION,
							AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04
						FROM 
							SATA.VDBR84400
						WHERE
							SAR = {$sar} AND
							BR = {$br} AND
							UPDATEDATE={$year}{$moon}
						ORDER BY
							INDX, ROW
						";
						
						$arr = array(
							"hirarchy" => $hirarchy,
							"subtitle" => "عقود شعبه {$br}",
							"head" => array("شرح", "غیردولتی - غیرتبصره‌ای", "غیردولتی - تبصره‌ای", "دولتی - غیرتبصره‌ای", "دولتی - تبصره‌ای", "جمع"),
							"format" => array("ebcdic", "amount", "amount", "amount", "amount", "amountsum"),
							"body" => array(),
							"rowsum" => array(0, 0, 0, 0, 0),
							"query" => trim($query)
						);
						$len = count($arr["format"])-5;
						
						$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());
						while(odbc_fetch_row($result))
						{
							$newline = array(
								"type" => false,
								iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
								odbc_result($result, "AMOUNT01"), 
								odbc_result($result, "AMOUNT02"), 
								odbc_result($result, "AMOUNT03"), 
								odbc_result($result, "AMOUNT04"), 
								(   odbc_result($result, "AMOUNT01") + 
									odbc_result($result, "AMOUNT02") + 
									odbc_result($result, "AMOUNT03") + 
									odbc_result($result, "AMOUNT04"))
							);
				
							if(odbc_result($result, "TYPE") == 0)
							{
								$newline["type"] = "title";
							}
				
							array_push($arr["body"], $newline);
							
							for($i = 0; $i<5; $i++)
								$arr["rowsum"][$i] += $newline[$i+$len];
						}
					}
				}
			}
		}
	} 
	else
	{
		if($sar == -1)
		{
			$query = "
			SELECT 
				(ROW-INDX) AS TYPE,
				ROW,
				DESCRIPTION,
				AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04
			FROM 
				SATA.VDKOL84400
			WHERE
				UPDATEDATE={$year}{$moon}
			ORDER BY 
				INDX, ROW
			";
			$arr = array(
				"hirarchy" => $hirarchy,
				"subtitle" => "عقود کل بانک",
				"head" => array("شرح", "غیردولتی - غیرتبصره‌ای", "غیردولتی - تبصره‌ای", "دولتی - غیرتبصره‌ای", "دولتی - تبصره‌ای", "جمع"),
				"format" => array("ebcdic", "amount", "amount", "amount", "amount", "amountsum"),
				"body" => array(),
				"rowsum" => array(0, 0, 0, 0, 0),
				"query" => $query
			);
			$len = count($arr["format"])-5;
			$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

			while(odbc_fetch_row($result))
			{
				$newline = array(
					"type" => false,
					iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
					odbc_result($result, "AMOUNT01"), 
					odbc_result($result, "AMOUNT02"), 
					odbc_result($result, "AMOUNT03"), 
					odbc_result($result, "AMOUNT04"),
					(   odbc_result($result, "AMOUNT01") + 
						odbc_result($result, "AMOUNT02") + 
						odbc_result($result, "AMOUNT03") + 
						odbc_result($result, "AMOUNT04"))
				);
				
				if(odbc_result($result, "TYPE") == 0)
				{
					$newline["type"] = "title";
				}
				
				array_push($arr["body"], $newline);
				for($i = 0; $i<5; $i++)
					$arr["rowsum"][$i] += $newline[$i+$len];
			}
		}
		elseif($br == -1)
		{

			$query = "
			SELECT 
				(ROW-INDX) AS TYPE,
				ROW,
				DESCRIPTION,
				AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04
			FROM 
				SATA.VDSAR84400
			WHERE
				UPDATEDATE={$year}{$moon} AND 
				SAR={$sar}
			ORDER BY 
				INDX, ROW
			";
			$arr = array(
				"hirarchy" => $hirarchy,
				"subtitle" => "عقود اداره امور {$sarList[$sar]}",
				"head" => array("شرح", "غیردولتی - غیرتبصره‌ای", "غیردولتی - تبصره‌ای", "دولتی - غیرتبصره‌ای", "دولتی - تبصره‌ای", "جمع"),
				"format" => array("ebcdic", "amount", "amount", "amount", "amount", "amountsum"),
				"body" => array(),
				"rowsum" => array(0, 0, 0, 0, 0),
				"query" => $query
			);
			$len = count($arr["format"])-5;
			$result = odbc_exec($db2conn, $query) or die(odbc_errormsg()."<br/>".$query);

			while(odbc_fetch_row($result))
			{
				$newline = array(
					"type" => false,
					iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
					odbc_result($result, "AMOUNT01"), 
					odbc_result($result, "AMOUNT02"), 
					odbc_result($result, "AMOUNT03"), 
					odbc_result($result, "AMOUNT04"),
					(   odbc_result($result, "AMOUNT01") + 
						odbc_result($result, "AMOUNT02") + 
						odbc_result($result, "AMOUNT03") + 
						odbc_result($result, "AMOUNT04"))
				);
				
				if(odbc_result($result, "TYPE") == 0)
				{
					$newline["type"] = "title";
				}
				
				array_push($arr["body"], $newline);
				for($i = 0; $i<5; $i++)
					$arr["rowsum"][$i] += $newline[$i+$len];
			}

		}
		else
		{

				$query = "
				SELECT 
					(ROW-INDX) AS TYPE,
					ROW,
					DESCRIPTION,
					AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04
				FROM 
					SATA.VDBR84400
				WHERE
					UPDATEDATE={$year}{$moon} AND SAR={$sar} AND BR={$br}
				ORDER BY 
					INDX, ROW
				";
				$arr = array(
					"hirarchy" => $hirarchy,
					"subtitle" => "عقود شعبه {$br}",
					"head" => array("شرح", "غیردولتی - غیرتبصره‌ای", "غیردولتی - تبصره‌ای", "دولتی - غیرتبصره‌ای", "دولتی - تبصره‌ای", "جمع"),
					"format" => array("ebcdic", "amount", "amount", "amount", "amount", "amountsum"),
					"body" => array(),
					"rowsum" => array(0, 0, 0, 0, 0),
					"query" => $query
				);
				$len = count($arr["format"])-5;
				$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

				while(odbc_fetch_row($result))
				{
					$newline = array(
						"type" => false,
						iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
						odbc_result($result, "AMOUNT01"), 
						odbc_result($result, "AMOUNT02"), 
						odbc_result($result, "AMOUNT03"), 
						odbc_result($result, "AMOUNT04"),
						(   odbc_result($result, "AMOUNT01") + 
							odbc_result($result, "AMOUNT02") + 
							odbc_result($result, "AMOUNT03") + 
							odbc_result($result, "AMOUNT04"))
					);
				
					if(odbc_result($result, "TYPE") == 0)
					{
						$newline["type"] = "title";
					}
					
					array_push($arr["body"], $newline);
					for($i = 0; $i<5; $i++)
						$arr["rowsum"][$i] += $newline[$i+$len];
				}

		}
	}


	odbc_close($db2conn);
    $json = json_encode($arr);
	
	if($json)
		echo $json;
	else
	{
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				echo ' - No errors';
			break;
			case JSON_ERROR_DEPTH:
				echo ' - Maximum stack depth exceeded';
			break;
			case JSON_ERROR_STATE_MISMATCH:
				echo ' - Underflow or the modes mismatch';
			break;
			case JSON_ERROR_CTRL_CHAR:
				echo ' - Unexpected control character found';
			break;
			case JSON_ERROR_SYNTAX:
				echo ' - Syntax error, malformed JSON';
			break;
			case JSON_ERROR_UTF8:
				echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
			break;
			default:
				echo ' - Unknown error';
			break;
		}
		
	}

?>