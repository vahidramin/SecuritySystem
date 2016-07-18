<?php

	header('Content-Type: text/html; charset=utf-8');
    require_once("include.php");
    require_once("reports-config.php");

    $db2conn = db2connect();
	
	$report	= $_GET["report"];
	$level 	= $_GET["level"];
	$sar 	= $_GET["sar"];
	$br 	= $_GET["br"];
	$year 	= $_GET["year"];
	$moon 	= $_GET["moon"];
	
	$reportId = substr($report, 3);

	$query = "
        SELECT 
			{$reportconfig[$reportId]["select"]}
        FROM 
			{$reportconfig[$reportId]["from"]}
        WHERE
            {$reportconfig[$reportId]["where"][0]} = {$year}{$moon}
    ";

    $result = odbc_exec($db2conn, $query) or die(odbc_errormsg());



    $arr = array(
        "head" => array("جزییات", "شرح", "منطقه", "کشاورزی", "صنعت و معدن", "ساختمان", "مسکن", "بازرگانی", "خدمات", "صادرات", "جمع"),
        "format" => array("link", "link", "ebcdic", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
        "body" => array()
    );

    while(odbc_fetch_row($result))
    {
        $newline = array(
			"مشاهده جزییات",
			"شرح",
                        odbc_result($result, "NAME"), 
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
    }

    echo json_encode($arr);

?>