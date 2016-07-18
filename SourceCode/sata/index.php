<?php
	session_start();
	
	if(!isset($_SESSION["SATA-USERNAME"]))
	{
		header('Location: login.html');
		return;
	}
	
	$date = explode( " ", "چهارشنبه ۱۲ خـرداد ۱۳۹۵ ه.ش." );
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8" />
		<title>ساتا :: سامانه اداره تسهیلات و اعتبارات</title>
		<link rel="shortcut icon" href="images/favicon.png" type="image/icon">
		<link rel="icon" href="images/favicon.png" type="image/icon"> 
        <link rel="stylesheet" type="text/css" href="styles/style.css" />
        <link rel="stylesheet" type="text/css" href="styles/navigation.css" />
        <script src="js/jquery-1.12.3.min.js"></script>
        <script src="js/jquery.table2excel.js"></script>
        <script src="js/script.js"></script>
    </head>

    <body>
		<div id="export-excel"></div>
		<div id="filter-form-sarfasl">
			<div class="report-form">
				<select name="aghd" id="_ID_aghd">
					<option value="000" selected>انتخاب نوع عقد ...</option>
					<option value="001">مضاربه</option>
					<option value="002"> مشاركت مدني</option>
					<option value="002"> فروش اقساطي</option>
					<option value="002"> اجاره به شرط تمليك</option>
					<option value="002"> معاملات سلف</option>
					<option value="002"> جعاله</option>
					<option value="002"> مشاركت حقوقي</option>
					<option value="002"> سرمايه گزاري مستقيم</option>
					<option value="002"> اوراق مشاركت</option>
					<option value="002"> خريد دين</option>
					<option value="002"> قرض الحسنه نرخ ارز فاينانس</option>
					<option value="002"> فروش اقساطي تفاوت نرخ ارز فاينانس</option>
					<option value="002"> بدهي مشتريان بابت نرخ ارز فاينانس</option>
					<option value="002"> بدهي مشتريان بابت نرخ ارز معاف</option>
					<option value="002"> وام اشخاص بنظام قديم ج</option>
					<option value="002"> قرض الحسنه</option>
					<option value="002"> مطالبات از بانك ها</option>
					<option value="002"> مطالبات معوق نظام قديم</option>
					<option value="002"> مطالبات معوق معاملات اسلامي</option>
					<option value="002"> مطالبات سر رسيد گذشت معاملات اسلامي</option>
					<option value="002"> سود معوق معاملات اسلامي</option>
					<option value="002"> سود و كارمزد معوق قديم</option>
					<option value="002"> بدهكاران بابت اعتبارات اسنادي پرداخت شده</option>
					<option value="002"> بدهكاران بابت ضمانتنامه هاي پرداخت شده</option>
					<option value="002"> اموال منقول فروش اقساطي</option>
					<option value="002"> پيش پرداخت خريد اموال</option>
					<option value="002"> اموال منقول اجاره به شرط تمليك</option>
					<option value="002"> كالاي معاملات سلف</option>
					<option value="002"> كار در جريان جعاله</option>
					<option value="002"> مضاربه وجوه دريافتي</option>
					<option value="002"> سود سالهاي اينده فروش اقساطي</option>
					<option value="002"> سود سالهاي اينده جعاله</option>
					<option value="002"> قرض الحسنه تفاوت نرخ ارز غير معاف</option>
					<option value="002"> سود و كارمزد سالهاي اينده</option>
					<option value="002"> سود سالهاي اينده تسهلات خريد دين</option>
					<option value="002"> مطالبات مشكوك الوصول تسهيلات تا پنج سال</option>
					<option value="002"> مطالبات مشكوك الوصول تسهيلات بالاي پنج سال</option>
					<option value="002"> مطالبات مشكوك الوصول بابت اعتبارات اسنادي پرداخت شده تا پنج سال</option>
					<option value="002"> مطالبات مشكوك الوصول بابت اعتبارات اسنادي پرداخت شده بالاي پنج سال</option>
					<option value="002"> مطالبات مشكوك الوصول بابت ضمانتنامه هاي پرداخت شده تا پنج سال</option>
					<option value="002"> بدهكاران بابت كارتهاي خريد اعتباري</option>
					<option value="002"> مرابحه</option>
					<option value="002"> اموال خدمات خريداري شده براي مرابحه</option>
					<option value="002"> سود سالهاي اينده مرابحه</option>
				</select>
				<select name="bakhsh" id="_ID_bakhsh">
					<option value="000" selected>انتخاب بخش ...</option>
					<option value="001">بازرگاني داخلي </option>
					<option value="001">بازرگاني واردات </option>
					<option value="001">بازرگاني صادرات </option>
					<option value="001">صنعت و معدن </option>
					<option value="001">كشاورزي</option>
					<option value="001">خدمات </option>
					<option value="001">مسكن انفرادي كاركنان اموزش و پرورش </option>
					<option value="001">مسكن مجتمع سازي تعاوني اموزش و پرورش </option>
					<option value="001">مسكن انفرادي سايركاركنان دولت و كارگران </option>
					<option value="001">مسكن مجتمع سازي تعاوني كاركنان دولت و كارگران </option>
					<option value="001">مسكن انفرادي ساير</option>
					<option value="001">مسكن مجتمع سازي ساير افراد</option>
					<option value="001">مسكن ازادگان </option>
					<option value="001">مسكن كاركنان بانك ملي ايران </option>
					<option value="001">مسكن كاركنان بانك مركزي </option>
					<option value="001">ساختمان كشاورزي </option>
					<option value="001">ساختمان خدمات </option>
					<option value="001">ساختمان صنعت </option>
					<option value="001">مدارس غيرانتفاعي </option>
					<option value="001">دانشگاه ازاد</option>
					<option value="001">بنگاه هاي كوك اقتصادي زودبازه </option>
					<option value="001">ت مسكن بند د تبصره 6 بودجه 68 كشور</option>
					<option value="001">تسهيلات مشاركت مدني طرح مسكن مهر</option>
					<option value="001">تسهيلات مشاركت مدني مسكن نخبگان </option>
					<option value="001">تسهيلات مسكن-بافت فرسوده </option>
					<option value="001">مشاركت مدني غير دولتي حوادث </option>
				</select>
				<input type="button" name="show" id="_ID_show" data-key="" value="نمایش" />
			</div>
		</div>
		<div id="filter-form">
			<div class="report-form">
				<select class="level" name="level" id="_ID_level">
					<option value="0" selected disabled>لطفا مبنا را انتخاب نمایید ...</option>
					<option value="sar">بر اساس اداره امور</option>
					<option value="zone">بر اساس منطقه</option>
				</select>
				<select name="sars" id="_ID_sars">
					<option value="-1" selected="">همه ادارات &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</option>
					<option value="290">290 - ادارات مرکزی</option>
					<option value="293">293 - بانک کارگشایی</option>
					<option value="7900">7900 - مناطق ازاد</option>
					<option value="" disabled>------------------------------شعب مستقل-----------------</option>
					<option value="60">60 - شعبه مرکزی</option>
					<option value="63">63 - شعبه بازار</option>
					<option value="64">64 - شعبه مهرملی</option>
					<option value="69">69 - شعبه سعدی</option>
					<option value="137">137 - شعبه فردوسی</option>
					<option value="271">271 - شعبه اسکان</option>
					<option value="304">304 - شعبه حج و زیارت</option>
					<option value="310">310 - شعبه صندوق قرض الحسنه پس انداز</option>
					<option value="695">695 - شعبه بورس اوراق بهادار</option>
					<option value="" disabled>-----------------------------ادارات امور---------------------</option>
					<option value="4400">4400 - اذربايجان شرقي</option>
					<option value="5100">5100 - اذربايجان غربي</option>
					<option value="4850">4850 - اردبيل</option>
					<option value="3000">3000 - اصفهان</option>
					<option value="5980">5980 - ايلام</option>
					<option value="7700">7700 - بوشهر</option>
					<option value="350">350 - تهران (مرکز)</option>
					<option value="351">351 - تهران (شمال)</option>
					<option value="352">352 - تهران (جنوب)</option>
					<option value="353">353 - تهران (شرق)</option>
					<option value="354">354 - تهران (غرب)</option>
					<option value="3270">3270 - چهارمحال بختياري</option>
					<option value="8700">8700 - خراسان جنوبي</option>
					<option value="8500">8500 - خراسان رضوي</option>
					<option value="8600">8600 - خراسان شمالي</option>
					<option value="6500">6500 - خوزستان</option>
					<option value="4300">4300 - زنجان</option>
					<option value="2500">2500 - سمنان</option>
					<option value="8300">8300 - سيستان وبلوچستان</option>
					<option value="2620">2620 - البــرز</option>
					<option value="7200">7200 - فارس</option>
					<option value="2670">2670 - قزوين</option>
					<option value="2700">2700 - قم</option>
					<option value="5600">5600 - كردستان</option>
					<option value="8000">8000 - كرمان</option>
					<option value="5700">5700 - كرمانشاه</option>
					<option value="7100">7100 - كهكيلويه وبويراحمد</option>
					<option value="9200">9200 - گلستان</option>
					<option value="3700">3700 - گيلان</option>
					<option value="6400">6400 - لرستان</option>
					<option value="9600">9600 - مازندران</option>
					<option value="2800">2800 - مركزي</option>
					<option value="7800">7800 - هرمزگان</option>
					<option value="6100">6100 - همدان</option>
					<option value="3500">3500 - يزد</option>
				</select>
				<select name="brs" id="_ID_brs">
					<option value="-1" selected >شعبه مورد نظر را انتخاب نمایید ...</option>
				</select>
				<span>سال <input type="number" min="85" max="95" step="1" value="94" name="year" id="_ID_year" /></span>
				<span>
					ماه
					<select name="moon" id="_ID_moon">
						<option value="0"> </option>
						<option value="01">فروردین</option>
						<option value="02">اردیبهشت</option>
						<option value="03">خرداد</option>
						<option value="04">تیر</option>
						<option value="05">مرداد</option>
						<option value="06">شهریور</option>
						<option value="07">مهر</option>
						<option value="08">آبان</option>
						<option value="09">آذر</option>
						<option value="10">دی</option>
						<option value="11" selected>بهمن</option>
						<option value="12">اسفند</option>
					</select>
				</span>
				<span>
					مقیاس
					<select name="moon" id="_ID_scale">
						<option value="1">ریال</option>
						<option value="1000">هزار ریال</option>
						<option value="1000000" selected>میلیون ریال</option>
						<option value="1000000000">میلیارد ریال</option>
					</select>
				</span>
				<input type="button" name="show" id="_ID_show" data-key="" value="نمایش" />

				<span>
					<img id="_ID_excelExport" class="excel-export" data-key="" src="images/excel.png" />
				</span>
			</div>
		</div>

        <header>
            <div class="horizontal-line-orange"></div>
            <div class="header">
                <div class="header-right">
                    <img src="images/bank-logo.png" />
                    <img src="images/sata-logo.png" />
                    <div id="app-title">سامانه اداره تسهیلات و اعتبارات</div>
                </div>
                <div class="header-left">
                    <div id="current-date"><?php echo $date[0]." ".$date[1]." ".$date[2]." ".$date[3]." ".$date[4]; ?></div>
                    <div id="extra-links">
                        <a href="#" id="link-requests">درخواستها</a>
                        <a href="#" id="link-profile">پروفایل</a>
                        <a href="php/logout.php" id="link-exit">خــروج</a>
                    </div>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="#">گزارشات مانده ناخالص و کسورات براساس عقد و بخش</a>
                        <ul>
                            <li><a class="report-link" data-report="rep-01" href="#">گزارش یک - ناخالص تسهیلات غیردولتی</a></li>
                            <li><a class="report-link" data-report="rep-02" href="#">گزارش دو - کسورات غیردولتی</a></li>
                            <li><a class="report-link" data-report="rep-03" href="#">گزارش سه - ناخالص تسهیلات دولتی</a></li>
                            <li><a class="report-link" data-report="rep-04" href="#">گزارش چهار - کسورات دولتی</a></li>
                            <li><a class="report-link" data-report="rep-05" href="#">گزارش پنج - خالص مانده غیر دولتی</a></li>
                            <li><a class="report-link" data-report="rep-06" href="#">گزارش شش - خالص مانده دولتی</a></li>
                            <li><a class="report-link" data-report="rep-07" href="#">گزارش هفت - مجموع مانده ناخالص غیردولتی و دولتی</a></li>
                            <li><a class="report-link" data-report="rep-08" href="#">گزارش هشت - مجموع کسورات دولتی و غیردولتی</a></li>
                            <li><a class="report-link" data-report="rep-09" href="#">گزارش نه - مجموع خالص مانده دولتی و غیر دولتی</a></li>
                            <li><a class="report-link" data-report="rep-844" href="#">گزارش 844</a></li>
                            <li><a class="report-link" data-report="rep-mt01" href="#">جدول مغایرت مطالبات از دولت</a></li>
                        </ul>
                    </li>
                    <li><a href="#">گــزارش های سرفصل</a>
                        <ul>
                            <li><a class="report-link" data-report="rep-sarfasl" href="#">گزارش لیست سرفصل‌ها</a></li>
                            <li><a class="report-link" data-report="rep-kol" href="#">گزارش کل بانک</a></li>
                            <li><a class="report-link" data-report="rep-comp" href="#">گزارش مقایسه‌ای  کل بانک</a></li>
                        </ul>
                    </li>
                    <li style="display: none;"><a href="#">گزارشات مانده ناخالص و کسورات براساس اداره امور و بخش</a>
                        <ul>
                            <li><a class="report-link" data-report="rep-01" href="#">مجموع ناخالص تسهیلات غیردولتی</a></li>
                            <li><a class="report-link" data-report="rep-02" href="#">مجموع کسورات غیردولتی</a></li>
                            <li><a class="report-link" data-report="rep-03" href="#">مجموع ناخالص تسهیلات دولتی</a></li>
                            <li><a class="report-link" data-report="rep-04" href="#">مجموع کسورات دولتی</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
        
        <main>
            <div id="reports-tab">
                <!--	TABs     -->
            </div>
            <div id="reports-body">
				<!--	Divs of Reports		-->
            </div>
        </main>
        
        <footer>
            <img src="images/sadad-logo.png" />
            تمامی حقوق این نرم افزار متعلق به شرکت داده ورزی سداد می باشد. 
            &copy;
            ۱۳۹۵
        </footer>

        
    </body>
</html>