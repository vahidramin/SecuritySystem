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
	
	$hirarchy = "rep-01_{$level}_{$zone}_{$sar}_{$br}_{$aghd}_{$fasl}_{$year}{$moon}";
	
	
	if($level == "zone")
	{
		if($zone == -1)
		{
			$query = "
			SELECT 
				ZONCODE,
				NAME,
				AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
			FROM 
				SATA.VMNT84401
			WHERE
				UPDATEDATE={$year}{$moon}
			";

			$arr = array(
				"hirarchy" => $hirarchy,
				"subtitle" => "به تفکیک منطقه",
				"head" => array("جزییات", "شرح", "منطقه", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
				"format" => array("link", "link", "ebcdic", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
				"body" => array(),
				"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
				"query" => $query
			);
			$len = count($arr["format"])-8;
			
			$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

			while(odbc_fetch_row($result))
			{
				$newline = array(
					array("جزییات عقود", "{report:\"rep-01\", level:\"zone\", sar:{$sar}, br:{$br}, year:{$year}, moon:{$moon}, zone:".odbc_result($result, "ZONCODE").", aghd:1}"),
					array("ادارات امور", "{report:\"rep-01\", level:\"zone\", sar:{$sar}, br:{$br}, year:{$year}, moon:{$moon}, zone:".odbc_result($result, "ZONCODE").", aghd:0}"),
					iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "NAME"))), 
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
					$arr["rowsum"][$i] += $newline[$i+$len];
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
						AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
					FROM 
						SATA.VSAR84401
					WHERE
						ZONECODE = {$zone} AND
						UPDATEDATE={$year}{$moon}
					";
					

					$arr = array(
						"hirarchy" => $hirarchy,
						"subtitle" => "ادارات امور {$zoneList[$zone]}",
						"head" => array("", "", "اداره امور", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
						"format" => array("link", "link", "sar", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
						"body" => array(),
						"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
						"query" => $query
					);
					$len = count($arr["format"])-8;
					
					$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

					while(odbc_fetch_row($result))
					{
						$newline = array(
							array("مشاهده عقود", "{report:\"rep-01\", level:\"zone\", sar:".odbc_result($result, "SAR").", br:{$br}, year:{$year}, moon:{$moon}, zone:{$zone}, aghd:1}"),
							array("نمایش شعب", "{report:\"rep-01\", level:\"zone\", sar:".odbc_result($result, "SAR").", br:{$br}, year:{$year}, moon:{$moon}, zone:{$zone}, aghd:0}"),
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
							$arr["rowsum"][$i] += $newline[$i+$len];
					}
				}
				else
				{
						$query = "
						SELECT
							BR,
							AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
						FROM 
							SATA.VBR84401
						WHERE
							SAR = {$sar} AND
							UPDATEDATE={$year}{$moon}
						ORDER BY
							BR
						";
						

						$arr = array(
							"hirarchy" => $hirarchy,
							"subtitle" => "شعب اداره امور {$sarList[$sar]}",
							"head" => array("", "شعبه", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
							"format" => array("link", "br", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
							"body" => array(),
							"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
							"query" => $query
						);
						$len = count($arr["format"])-8;
						
						$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

						while(odbc_fetch_row($result))
						{
							$newline = array(
								array("مشاهده عقود", "{report:\"rep-01\", level:\"zone\", sar:{$sar}, br:".odbc_result($result, "BR").", year:{$year}, moon:{$moon}, zone:{$zone}, aghd:1}"),
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
								$arr["rowsum"][$i] += $newline[$i+$len];
						}

				}
			}
			else /* */
			{
				if($sar == -1)
				{
					if($fasl == 0)
					{
						$query = "
						SELECT 
							INDX,
							DESCRIPTION,
							AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
						FROM 
							SATA.VDMNT84401
						WHERE
							ZONCODE = {$zone} AND
							UPDATEDATE={$year}{$moon}
						ORDER BY
							INDX
						";
						

						$arr = array(
							"hirarchy" => $hirarchy,
							"subtitle" => "عقود {$zoneList[$zone]}",
							"head" => array("جزییات", "شرح", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
							"format" => array("link", "ebcdic", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
							"body" => array(),
							"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
							"query" => $query
						);
						$len = count($arr["format"])-8;
						
						$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

						while(odbc_fetch_row($result))
						{
							$newline = array(
								array("سرفصلها", "{report:\"rep-01\", level:\"zone\", sar:{$sar}, br:{$br}, year:{$year}, moon:{$moon}, zone:{$zone}, aghd:".odbc_result($result, "INDX").", fasl:1}"),
								iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
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
								$arr["rowsum"][$i] += $newline[$i+$len];
						}
					}
					else
					{
						$query = "
						SELECT 
							FASL,
							AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
						FROM 
							SATA.VFMNT84401
						WHERE
							ZONCODE = {$zone} AND
							INDX = {$aghd} AND
							UPDATEDATE={$year}{$moon}
						ORDER BY
							FASL
						";
						

						$arr = array(
							"hirarchy" => $hirarchy,
							"subtitle" => "سرفصلهای عقود",
							"head" => array("سرفصل", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
							"format" => array("fasl", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
							"body" => array(),
							"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
							"query" => $query
						);
						$len = count($arr["format"])-8;
						
						$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

						while(odbc_fetch_row($result))
						{
							$newline = array(
								odbc_result($result, "FASL"), 
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
								$arr["rowsum"][$i] += $newline[$i+$len];
						}
					}
				}
				else
				{
					if($br == -1)
					{
						if($fasl == 0)
						{
							$query = "
							SELECT 
								INDX,
								DESCRIPTION,
								AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
							FROM 
								SATA.VDSAR84401
							WHERE
								SAR = {$sar} AND
								UPDATEDATE={$year}{$moon}
							ORDER BY
								INDX
							";
							

							$arr = array(
								"hirarchy" => $hirarchy,
								"subtitle" => "عقود اداره امور {$sarList[$sar]}",
								"head" => array("", "شرح", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
								"format" => array("link", "ebcdic", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
								"body" => array(),
								"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
								"query" => $query
							);
							$len = count($arr["format"])-8;
							
							$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

							while(odbc_fetch_row($result))
							{
								$newline = array(
									array("سرفصلها", "{report:\"rep-01\", level:\"zone\", sar:{$sar}, br:{$br}, year:{$year}, moon:{$moon}, zone:{$zone}, aghd:".odbc_result($result, "INDX").", fasl:1}"),
									iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
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
									$arr["rowsum"][$i] += $newline[$i+$len];
							}
						}
						else
						{
							$query = "
							SELECT 
								FASL,
								AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
							FROM 
								SATA.VFSAR84401
							WHERE
								SAR = {$sar} AND
								INDX = {$aghd} AND
								UPDATEDATE={$year}{$moon}
							ORDER BY
								FASL
							";
							

							$arr = array(
								"hirarchy" => $hirarchy,
								"subtitle" => "سرفصلهای عقود",
								"head" => array("سرفصل", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
								"format" => array("fasl", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
								"body" => array(),
								"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
								"query" => $query
							);
							$len = count($arr["format"])-8;
							
							$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

							while(odbc_fetch_row($result))
							{
								$newline = array(
									odbc_result($result, "FASL"), 
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
									$arr["rowsum"][$i] += $newline[$i+$len];
							}
						}
						
					}
					else
					{
						if($fasl == 0)
						{
							$query = "
							SELECT 
								INDX,
								DESCRIPTION,
								AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
							FROM 
								SATA.VDBR84401
							WHERE
								BR = {$br} AND
								UPDATEDATE={$year}{$moon}
							ORDER BY
								INDX
							";
							

							$arr = array(
								"hirarchy" => $hirarchy,
								"subtitle" => "عقود شعبه {$br}",
								"head" => array("", "شرح", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
								"format" => array("link", "ebcdic", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
								"body" => array(),
								"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
								"query" => $query
							);
							$len = count($arr["format"])-8;
							
							$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

							while(odbc_fetch_row($result))
							{
								$newline = array(
									array("سرفصلها", "{report:\"rep-01\", level:\"zone\", sar:{$sar}, br:{$br}, year:{$year}, moon:{$moon}, zone:{$zone}, aghd:".odbc_result($result, "INDX").", fasl:1}"),
									iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
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
									$arr["rowsum"][$i] += $newline[$i+$len];
							}
						}
						else
						{
							$query = "
							SELECT 
								FASL,
								AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
							FROM 
								SATA.VFBR84401
							WHERE
								BR = {$br} AND
								INDX = {$aghd} AND
								UPDATEDATE={$year}{$moon}
							ORDER BY
								FASL
							";
							

							$arr = array(
								"hirarchy" => $hirarchy,
								"subtitle" => "سرفصلهای عقود",
								"head" => array("سرفصل", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
								"format" => array("fasl", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
								"body" => array(),
								"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
								"query" => $query
							);
							$len = count($arr["format"])-8;
							
							$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

							while(odbc_fetch_row($result))
							{
								$newline = array(
									odbc_result($result, "FASL"), 
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
									$arr["rowsum"][$i] += $newline[$i+$len];
							}
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
			if($aghd == 0)
			{
				$query = "
				SELECT 
					INDX,
					DESCRIPTION,
					AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
				FROM 
					SATA.VDKOL84401
				WHERE
					UPDATEDATE={$year}{$moon}
				ORDER BY 
					INDX
				";
				$arr = array(
					"hirarchy" => $hirarchy,
					"subtitle" => "عقود کل بانک",
					"head" => array("جزییات", "شرح", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
					"format" => array("link", "ebcdic", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
					"body" => array(),
					"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
					"query" => $query
				);
				$len = count($arr["format"])-8;
				$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

				while(odbc_fetch_row($result))
				{
					$newline = array(
						array("سرفصلها","{report:\"rep-01\", level:\"sar\", year:{$year}, moon:{$moon}, aghd:".odbc_result($result, "INDX")."}"),
						iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
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
						$arr["rowsum"][$i] += $newline[$i+$len];
				}
			}
			else
			{
				$query = "
				SELECT 
					FASL,
					AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
				FROM 
					SATA.VFKOL84401
				WHERE
					INDX = {$aghd} AND
					UPDATEDATE={$year}{$moon}
				ORDER BY 
					FASL
				";
				$arr = array(
					"hirarchy" => $hirarchy,
					"subtitle" => "سرفصل عقود",
					"head" => array("سرفصل", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
					"format" => array("fasl", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
					"body" => array(),
					"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
					"query" => $query
				);
				$len = count($arr["format"])-8;
				$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

				while(odbc_fetch_row($result))
				{
					$newline = array(
						odbc_result($result, "FASL"), 
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
						$arr["rowsum"][$i] += $newline[$i+$len];
				}
			}
		}
		elseif($br == -1)
		{
			if($aghd == 0)
			{
				$query = "
				SELECT 
					INDX,
					DESCRIPTION,
					AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
				FROM 
					SATA.VDSAR84401
				WHERE
					UPDATEDATE={$year}{$moon} AND 
					SAR={$sar}
				ORDER BY 
					INDX
				";
				$arr = array(
					"hirarchy" => $hirarchy,
					"subtitle" => "عقود اداره امور {$sarList[$sar]}",
					"head" => array("جزییات", "شرح", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
					"format" => array("link", "ebcdic", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
					"body" => array(),
					"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
					"query" => $query
				);
				$len = count($arr["format"])-8;
				$result = odbc_exec($db2conn, $query) or die(odbc_errormsg()."<br/>".$query);

				while(odbc_fetch_row($result))
				{
					$newline = array(
						array("سرفصلها", "{report:\"rep-01\", level:\"sar\", sar:{$sar}, year:{$year}, moon:{$moon}, aghd:".odbc_result($result, "INDX")."}"),
						iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
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
						$arr["rowsum"][$i] += $newline[$i+$len];
				}
			}
			else
			{
				$query = "
				SELECT 
					FASL,
					AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
				FROM 
					SATA.VFSAR84401
				WHERE
					UPDATEDATE={$year}{$moon} AND 
					SAR={$sar} AND
					INDX = {$aghd}
				ORDER BY 
					FASL
				";
				$arr = array(
					"hirarchy" => $hirarchy,
					"subtitle" => "سرفصل های عقود",
					"head" => array("سرفصل", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
					"format" => array("fasl", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
					"body" => array(),
					"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
					"query" => $query
				);
				$len = count($arr["format"])-8;
				$result = odbc_exec($db2conn, $query) or die(odbc_errormsg()."<br/>".$query);

				while(odbc_fetch_row($result))
				{
					$newline = array(
						odbc_result($result, "FASL"), 
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
						$arr["rowsum"][$i] += $newline[$i+$len];
				}
			}
		}
		else
		{
			//echo $level;
			if($aghd == 0)
			{
				$query = "
				SELECT 
					INDX,
					DESCRIPTION,
					AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
				FROM 
					SATA.VDBR84401
				WHERE
					UPDATEDATE={$year}{$moon} AND SAR={$sar} AND BR={$br}
				ORDER BY 
					INDX
				";
				$arr = array(
					"hirarchy" => $hirarchy,
					"subtitle" => "عقود شعبه {$br}",
					"head" => array("جزییات", "شرح", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
					"format" => array("link", "ebcdic", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
					"body" => array(),
					"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
					"query" => $query
				);
				$len = count($arr["format"])-8;
				$result = odbc_exec($db2conn, $query) or die(odbc_errormsg());

				while(odbc_fetch_row($result))
				{
					$newline = array(
						array("مشاهده جزییات", "{report:\"rep-01\", level:\"sar\", sar:{$sar}, br:{$br}, year:{$year}, moon:{$moon}, aghd:".odbc_result($result, "INDX")."}"),
						iconv('UTF-8', 'UTF-8//IGNORE', trim(odbc_result($result, "DESCRIPTION"))), 
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
						$arr["rowsum"][$i] += $newline[$i+$len];
				}
			}
			else
			{
				$query = "
				SELECT 
					FASL,
					AMOUNT01,AMOUNT02,AMOUNT03,AMOUNT04,AMOUNT05,AMOUNT06,AMOUNT07
				FROM 
					SATA.VFBR84401
				WHERE
					UPDATEDATE={$year}{$moon} AND 
					BR={$br} AND
					INDX = {$aghd}
				ORDER BY 
					FASL
				";
				$arr = array(
					"hirarchy" => $hirarchy,
					"subtitle" => "سرفصل های عقود",
					"head" => array("سرفصل", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
					"format" => array("fasl", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
					"body" => array(),
					"rowsum" => array(0, 0, 0, 0, 0, 0, 0, 0),
					"query" => $query
				);
				$len = count($arr["format"])-8;
				$result = odbc_exec($db2conn, $query) or die(odbc_errormsg()."<br/>".$query);

				while(odbc_fetch_row($result))
				{
					$newline = array(
						odbc_result($result, "FASL"), 
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
						$arr["rowsum"][$i] += $newline[$i+$len];
				}
			}
		}
	}

/*
    $query = "
        SELECT 
            ZONECODE ,
            SUM(AMOUNT01) AS AMOUNT01 ,
            SUM(AMOUNT02) AS AMOUNT02 , 
            SUM(AMOUNT03) AS AMOUNT03 , 
            SUM(AMOUNT04) AS AMOUNT04 , 
            SUM(AMOUNT05) AS AMOUNT05 , 
            SUM(AMOUNT06) AS AMOUNT06 , 
            SUM(AMOUNT07) AS AMOUNT07 
        FROM 
            SATA.REP84401 
        WHERE
            UPDATEDATE=9411 
        GROUP BY
            ZONECODE
    ";
*/

	odbc_close($db2conn);
    echo json_encode($arr);

?>