<?php

	$reportconfig = array(
		"01" => array(
			"select" = "NAME, AMOUNT01, AMOUNT02, AMOUNT03, AMOUNT04, AMOUNT05, AMOUNT06, AMOUNT07, (AMOUNT01 + AMOUNT02 + AMOUNT03 + AMOUNT04 + AMOUNT05 + AMOUNT06 + AMOUNT07) as AMTSUM",
			"from" = "SATA.VMNT84401",
			"where" = array("UPDATEDATE"),
			"order" = "",
			"format" = array("link", "link", "ebcdic", "amount", "amount", "amount", "amount", "amount", "amount", "amount", "amountsum"),
		),
	);

?>