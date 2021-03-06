<?php

    $arr = array(
        "head" => array("ردیف", "کد", "شرح", "مبلغ", "تاریخ", "وضعیت", "جزییات"),
        "format" => array("int", "int", "char", "amount", "date", "char", "link"),
        "body" => array(
            "row01" => array(1, 2563, "مضاربه", 256000002560, 13950121, "بررسی", "دارد"),
            "row02" => array(2, 2563, "مضاربه", -18523900000, 13950121, "انجام شده", "دارد"),
            "row03" => array(3, 2563, "مشارکت مدنی", 256000002560, 13950121, "معوق", "دارد"),
            "row04" => array(4, 2563, "جعاله", 256000002560, 13950121, "بررسی", "دارد"),
            "row05" => array(5, 2563, "تضارب", -65400000000, 13950121, "انجام شده", "دارد"),
            "row06" => array(6, 2563, "فروش اقساطی", 256000002560, 13950121, "انجام شده", "دارد"),
            "row07" => array(7, 2563, "مضاربه", 256000002560, 13950121, "بررسی", "دارد"),
            "row08" => array(8, 2563, "قرض الحسنه", -18523900000, 13950121, "معوق", "دارد"),
            "row09" => array(9, 2563, "سلف", 256000002560, 13950121, "معوق", "دارد"),
            "row10" => array(10, 2563, "اجاره به شرط تملیک", 256000002560, 13950121, "بررسی", "دارد"),
            "row11" => array(11, 2563, "خرید دین", -65400000000, 13950121, "معوق", "دارد"),
            "row12" => array(12, 2563, "کالای معاملات سلف", 256000002560, 13950121, "انجام شده", "دارد"),
            "row13" => array(13, 2563, "وام اشخاص", 256000002560, 13950121, "بررسی", "دارد"),
            "row14" => array(14, 2563, "مطالبات مشکوک الوصول", -18523900000, 13950121, "انجام شده", "دارد"),
            "row15" => array(15, 2563, "مرابحه", 256000002560, 13950121, "انجام شده", "دارد"),
            "row16" => array(16, 2563, "مشارکت حقوقی", 256000002560, 13950121, "انجام شده", "دارد"),
            "row17" => array(17, 2563, "سرمایه گذاری مستقیم", -65400000000, 13950121, "بررسی", "دارد"),
            "row18" => array(18, 2563, "کار در جریان جعاله", 256000002560, 13950121, "انجام شده", "دارد"),
            "row19" => array(19, 2563, "پیش پرداخت خرید اموال", 256000002560, 13950121, "انجام شده", "دارد")
        )
    );

    echo json_encode($arr);

?>