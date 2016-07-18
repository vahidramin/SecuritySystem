<?php

	$sarList = array(
		290  => "ادارات مرکزی",
		293  => "بانک کارگشایی",
		7900 => "مناطق ازاد",
		60   => "شعبه مرکزی",
		63   => "شعبه بازار",
		64   => "شعبه مهرملی",
		69   => "شعبه سعدی",
		137  => "شعبه فردوسی",
		271  => "شعبه اسکان",
		304  => "شعبه حج و زیارت",
		310  => "شعبه صندوق قرض الحسنه پس انداز",
		695  => "شعبه بورس اوراق بهادار",
		4400 => "اذربايجان شرقي",
		5100 => "اذربايجان غربي",
		4850 => "اردبيل",
		3000 => "اصفهان",
		5980 => "ايلام",
		7700 => "بوشهر",
		350  => "تهران (مرکز)",
		351  => "تهران (شمال)",
		352  => "تهران (جنوب)",
		353  => "تهران (شرق)",
		354  => "تهران (غرب)",
		3270 => "چهارمحال بختياري",
		8700 => "خراسان جنوبي",
		8500 => "خراسان رضوي",
		8600 => "خراسان شمالي",
		6500 => "خوزستان",
		4300 => "زنجان",
		2500 => "سمنان",
		8300 => "سيستان وبلوچستان",
		2620 => "البــرز",
		7200 => "فارس",
		2670 => "قزوين",
		2700 => "قم",
		5600 => "كردستان",
		8000 => "كرمان",
		5700 => "كرمانشاه",
		7100 => "كهكيلويه وبويراحمد",
		9200 => "گلستان",
		3700 => "گيلان",
		6400 => "لرستان",
		9600 => "مازندران",
		2800 => "مركزي",
		7800 => "هرمزگان",
		6100 => "همدان",
		3500 => "يزد",	
		9999 => "سایر بانکها"		
	);
	$zoneList = array(
		1=> "منطقه 1",
		2=> "منطقه 2",
		3=> "منطقه تهران",
		4=> "منطقه سایر"
	);


/*

    FUNCTION: db2connect();
    DESC: Connect to DB2 on Z/OS

*/

function db2connect() {
	$dbname = "DB2ALOC";
	$username = "mehr";
	$password = "mehr123";
	$dbconn = odbc_connect($dbname, $username, $password, SQL_CUR_USE_ODBC)or die(odbc_errormsg()) ;
	if ($dbconn == 0) 
	{
		return 0;
	}
	return($dbconn);
}


/*

    FUNCTION: main2win();
    DESC: Convert EBCDIC strings to ANSI

*/

function main2win($winString)
{
    $mainStr = " .<(+|$)*¬;%,?>#@'=¢ABCDEFHG!IJLKMNPOQR_TSUWVXY\"&/: -Z";
    $windStr = " اابپتثججچچححخخدذرزژسشصضطظععغغفققكگلللممننوههييئ*   -- 0123456789";
    $bigsStr = ";,>GIKO_SVY*";
    $tempLatin = "";
    $newString = " ";
    $length = strlen($winString);
    $winString = $winString . " ";
    for ($i=($length-1); $i>=0; $i--)
    {
        $myChar = substr($winString, $i, 1);
        $myCharPosition = strpos($mainStr, $myChar);
        if ($myCharPosition)
        {
            if (strlen($tempLatin) > 0)
            {
                $newString = $newString . $tempLatin . " ";
                $tempLatin = "";
            }
            if ($myChar == "Q"){
                $newString = $newString . "لا";
            }
            else
            {
                $newString = $newString . substr($windStr, $myCharPosition, 1);
                if (strpos($bigsStr, $myChar))
                    $newString = $newString . " ";
            }
        }
        else
        {
            $tempLatin = $myChar . $tempLatin;
        }
        $myOldChar = $myChar;
        $myOldCharPosition = $myCharPosition;
    }

    return $newString;
}

?>