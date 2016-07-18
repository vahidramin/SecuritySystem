/****
	File: script.js
		The main script to handle view layer
		@version 2.0
		@author: Vahid Ramin <v.ramin@sadad.co.ir>
		@last modify: 15 May 2016
****/

	var loadedReps = new Array(),
		sarfaslList = new Array(),
		branchList,
		brList;

	var sarList = {
		290  : "ادارات مرکزی",
		293  : "بانک کارگشایی",
		7900 : "مناطق ازاد",
		60   : "شعبه مرکزی",
		63   : "شعبه بازار",
		64   : "شعبه مهرملی",
		69   : "شعبه سعدی",
		137  : "شعبه فردوسی",
		251  : "تمرکز اسناد",
		271  : "شعبه اسکان",
		304  : "شعبه حج و زیارت",
		310  : "شعبه صندوق قرض الحسنه پس انداز",
		695  : "شعبه بورس اوراق بهادار",
		4400 : "اذربايجان شرقي",
		5100 : "اذربايجان غربي",
		4850 : "اردبيل",
		3000 : "اصفهان",
		5980 : "ايلام",
		7700 : "بوشهر",
		350  : "تهران (مرکز)",
		351  : "تهران (شمال)",
		352  : "تهران (جنوب)",
		353  : "تهران (شرق)",
		354  : "تهران (غرب)",
		3270 : "چهارمحال بختياري",
		8700 : "خراسان جنوبي",
		8500 : "خراسان رضوي",
		8600 : "خراسان شمالي",
		6500 : "خوزستان",
		4300 : "زنجان",
		2500 : "سمنان",
		8300 : "سيستان وبلوچستان",
		2620 : "البــرز",
		7200 : "فارس",
		2670 : "قزوين",
		2700 : "قم",
		5600 : "كردستان",
		8000 : "كرمان",
		5700 : "كرمانشاه",
		7100 : "كهكيلويه وبويراحمد",
		9200 : "گلستان",
		3700 : "گيلان",
		6400 : "لرستان",
		9600 : "مازندران",
		2800 : "مركزي",
		7800 : "هرمزگان",
		6100 : "همدان",
		3500 : "يزد",	
		9999 : "سایر بانکها"		
	};
	var zoneList = {
		1: "منطقه 1",
		2: "منطقه 2",
		3: "منطقه تهران",
		4: "منطقه سایر"
	};


/*
	FUNCTION toFaDigit
		add an abbility of persianize! digits by declaring this prototype function to String class
		(1, 2, 3, ...) -> (۱, ۲, ۳, ...)
*/
String.prototype.toFaDigit = function() {
    return this.replace(/\d+/g, function(digit) {
        var ret = '';
        for (var i = 0, len = digit.length; i < len; i++) {
            ret += String.fromCharCode(digit.charCodeAt(i) + 1728);
        }
 
        return ret;
    });
};

/*
	FUNCTION main2win()
		convert EBCDIC format strings to a windows supported persian one
*/
function main2win(inputString)
{
    var mainStr = " .<(+|$)*¬;%,?>#@'=¢ABCDEFHG!IJLKMNPOQR_TSUWVXY\"&/: -Z 0123456789",
		windStr = " اابپتثججچچححخخدذرزژسشصضطظععغغفققكگلللممننوههييئ*   -- 0123456789",
		bigsStr = ";,>GIKO_SVY*",
		tempLatin = "",
		newString = " ",
		length = inputString.length;
		inputString = inputString + " ";
    for (var i=length; i>=0; i--)
    {
        var myChar = inputString.substr(i, 1);
        var myCharPosition = mainStr.indexOf(myChar);
        if (myCharPosition >=0 )
        {
            if (tempLatin.length > 0)
            {
                newString = newString + tempLatin + " ";
                tempLatin = "";
            }
            if (myChar == "Q")
	    {
                newString = newString + "لا";
            }
            else
            {
                newString = newString + windStr.substr(myCharPosition, 1);
                if (bigsStr.indexOf(myChar) > -1)
                    newString = newString + " ";
            }
        }
        else
        {
            tempLatin = myChar + tempLatin;
        }
        myOldChar = myChar;
        myOldCharPosition = myCharPosition;
    }

    return newString;
}

/*
	FUNCTION formatNumber()
		style numbers by ",", "/", ":" etc for mony, amount, date, time and other purpose
*/
function formatNumber(n, count, sign)
{
    return n.toFixed(0).replace(/./g, function(c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % count === 0 ? sign + c : c;
    });
}

/*

	FUNCTION createReportTable()
		get a json-format array of a result report and generate its html table
*/
function createReportTable(data, reportId, filter_scale)
{
		
		var divHtml = "<table id='table_"+data.hirarchy+"' data-scale='"+filter_scale+"'>\
			<tr class='tableHead'>";
		for(key in data.head)
			divHtml += "<td>" + data.head[key] + "</td>";
		
		divHtml += "</tr>";
		
		
		for(var rowid in data.body) {
			var row = data.body[rowid];
			
			if(row.type && row.type == "title")
				divHtml += "<tr class='titleRow'>";
			else if(row.type && row.type == "midSum")
				divHtml += "<tr class='midsumRow'>";
			else
				divHtml += "<tr>";
			
			for(key in row) {
				if(key == "type")
					break;
				var value = 0;
				switch(data.format[key])
				{
					case "int":
						value = "<td>" + row[key].toString().toFaDigit() + "</td>";
						break;
					case "char":
						value = "<td><span class='right'>"+row[key]+"</span></td>";
						break;
					case "ebcdic":
							value = "<span class='right'>"+main2win(row[key])+"</span>";
						value = "<td>" + value + "</td>";
						break;
					case "amount":
						value = (row[key] >= 0)? 
							"<span class='green'> " + (formatNumber(Math.abs(row[key]/filter_scale), 3, ",")).toFaDigit() + " </span>" : 
							"<span class='red'> " + (formatNumber(Math.abs(row[key]/filter_scale), 3, ",")).toFaDigit() + " </span>";
						value = "<td class='amount'>" + value + "</td>";
						break;
					case "amountsum":
						value = (row[key] >= 0)? 
							"<span class='green'> " + (formatNumber(Math.abs(row[key]/filter_scale), 3, ",")).toFaDigit() + " </span>" : 
							"<span class='red'> " + (formatNumber(Math.abs(row[key]/filter_scale), 3, ",")).toFaDigit() + " </span>";
						value = "<td class='amount'><strong>"+ value +"</strong></td>";
						break;
					case "date":
						value = "<td>" + (formatNumber(row[key]%1000000, 2, "/")).toFaDigit() + "</td>";
						break;
					case "zone":
						value = "<td>" + zoneList[row[key]] + "</td>";
						break;
					case "sar":
						if(row.type && row.type == "midSum")
							value = "<td><span class='right'>جمــع "+zoneList[row[key]]+"</span></td>";
						else
							value = "<td><span class='right'>"+sarList[row[key]]+"</span></td>";
						break;
					case "br":
						value = "<td><span class='right'>"+row[key]+" - "+brList[row[key]]+"</span></td>";
						break;
					case "fasl":
						value = "<td><span class='right'>"+row[key]+" - "+sarfaslList[row[key]]+"</span></td>";
						break;
					case "link":
						if(row.type && row.type == "midSum")
							value = "<td> </td>";
						else
							value = "<td><a href='javascript:loadReport(\""+reportId+"\", "+row[key][1]+");' class='report-link'>" + row[key][0] + "</a></td>";
						break;
					default:
						value = "<td>" + row[key] + "</td>";
				}
				
				divHtml += value;
			}
			divHtml += "</tr>";
		}
		
		if(data.rowsum != false)
		{
			var rowSum = data.rowsum;
			var dif = data.head.length - rowSum.length;
			
			divHtml += "<tr class='rowsum'>\
							<td colspan="+dif+">جمــع</td>";
			for(key in rowSum)
			{
				var value = (rowSum[key] === "") ? "" :
											((rowSum[key] >= 0) ? 
												("<span class='green'> "+(formatNumber(Math.abs(rowSum[key]/filter_scale), 3, ",")).toFaDigit()+" </span>") :
												("<span class='red'> "+(formatNumber(Math.abs(rowSum[key]/filter_scale), 3, ",")).toFaDigit()+" </span>")
											);
				divHtml += "<td class='amount'>" + value + "</td>";
			
			}
			divHtml += "</tr>";
		}
		divHtml += "</table>";
		
		return divHtml;
}

/*
	FUNCTION excelExport()
		export report table to excel
*/
function excelExport(reportId, repKey, reportTitle)
{
		var data = loadedReps[repKey],
			filter_scale = $("#"+reportId+"_scale").val();
			colspan = 1,
			headspan = 0;
			headHtml = "";
		var divHtml = "<table>";
		
		for(key in data.head)
			if(data.format[key] != "link")
			{
				headspan++;
				if(data.format[key] == "br")
				{
					headHtml += "\n\t<th style='background-color:#3861ab; color:#fff;'>کد شعبه</th>";
					headHtml += "\n\t<th style='background-color:#3861ab; color:#fff;'>نام شعبه</th>";
					colspan = 2;
					headspan++;
				}
				else
					headHtml += "\n\t<th style='background-color:#3861ab; color:#fff;'>" + data.head[key] + "</th>";
			}
			
		divHtml += "\
	<tr>\
		<th colspan='"+headspan+"' height='80' align='center'>\n\
			<img src='http://bmi.ir/App_Themes/FaResponsive/img/BMILogo.png' /> \n\
			"+reportTitle+"<br/>"+data.subtitle+" ( "+ formatNumber(parseInt(repKey.substr(-4)), 2, "/") +" ) \n\
		</th>\
	</tr>\
	<tr>\
	"+headHtml+"\
	</tr>";		
		
		
		for(var rowid in data.body) {
			var row = data.body[rowid];
			var tdBgColor;
			
			if(row.type && row.type == "title")
				tdBgColor = "background-color:#a3ffb4;";
			else if(row.type && row.type == "midSum")
				tdBgColor = "background-color:#a3ffb4;";
			else if(rowid%2)
				tdBgColor = "background-color:#f1f1f1;";
			else
				tdBgColor = "";

			divHtml += "<tr>";

			for(key in row) {
				if(key == "type")
					break;
				var value = "";
				switch(data.format[key])
				{
					case "int":
						value = "<td style='"+tdBgColor+"'>" + row[key] + "</td>";
						break;
					case "char":
						value = "<td align='right' style='"+tdBgColor+"'>"+row[key]+"</td>";
						break;
					case "ebcdic":
						if(row.type && row.type == "midSum")
							value = "جمــع "+zoneList[row[key]];
						else
							value = main2win(row[key]);
						value = "<td align='right' style='"+tdBgColor+"'>" + value.trim() + "</td>";
						break;
					case "amount":
						value = (row[key] >= 0)? 
							"<td align='right' style='color:#008000; "+tdBgColor+"'>" + formatNumber(Math.abs(row[key]/filter_scale), 3, ",") + "</td>" : 
							"<td align='right' style='color:#b12020; "+tdBgColor+"'>" + formatNumber(Math.abs(row[key]/filter_scale), 3, ",") + "</td>";
						break;
					case "amountsum":
						value = (row[key] >= 0)? 
							"<td align='right' style='color:#008000; font-weight:bold; "+tdBgColor+"'>" + formatNumber(Math.abs(row[key]/filter_scale), 3, ",") + "</td>" : 
							"<td align='right' style='color:#b12020; font-weight:bold; "+tdBgColor+"'>" + formatNumber(Math.abs(row[key]/filter_scale), 3, ",") + "</td>";
						break;
					case "date":
						value = "<td style='"+tdBgColor+"'>" + formatNumber(row[key]%1000000, 2, "/") + "</td>";
						break;
					case "zone":
						value = "<td style='"+tdBgColor+"'>" + zoneList[row[key]] + "</td>";
						break;
					case "sar":
						if(row.type && row.type == "midSum")
							value = "<td align='right' style='"+tdBgColor+"'>جمــع "+zoneList[row[key]]+"</td>";
						else
							value = "<td align='right' style='"+tdBgColor+"'>"+sarList[row[key]]+"</td>";
						break;
					case "br":
						value = "<td style='"+tdBgColor+"'>"+row[key]+"</td>";
						value += "<td align='right' dir='rtl' style='"+tdBgColor+"'>"+brList[row[key]]+"</td>";
						break;
					case "link":
						break;
					default:
						value = "<td style='"+tdBgColor+"'>" + row[key] + "</td>";
						break;
				}
				
				divHtml += "\n\t"+value;
			}
			divHtml += "</tr>";
		}
		
		if(data.rowsum != false)
		{
			var rowSum = data.rowsum;
			
			divHtml += "<tr>\
							\n\t<td colspan="+colspan+" style='background-color:#4bd2ff;' >جمــع</td>";
			for(key in rowSum)
			{
				var value = (rowSum[key] === "") ? "<td style='background-color:#4bd2ff;'> </td>" :
											((rowSum[key] >= 0) ? 
												("<td align='right' style='background-color:#4bd2ff; color:#008000; font-weight:bold;'>"+ formatNumber(Math.abs(rowSum[key]/filter_scale), 3, ",") +"</td>") :
												("<td align='right' style='background-color:#4bd2ff; color:#b12020; font-weight:bold;'>"+ formatNumber(Math.abs(rowSum[key]/filter_scale), 3, ",") +"</td>")
											);
				divHtml += "\n\t"+value;
			
			}
			divHtml += "</tr>";
		}
		divHtml += "</table>";
		
		$("div#export-excel").html(divHtml);
		delete divHtml;
		
		//window.open("data:application/vnd.ms-excel," + $("div#export-excel").html(), "SATA-REP01");
		$("div#export-excel > table").table2excel({
			exclude: ".noExl",
			name: reportId,
			sheetName: "گزارش",
			filename: reportTitle+"-"+data.subtitle+"_"+repKey.substr(-4),
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});
		$("div#export-excel").html("");


}

/*
	FUNCTION loadReport()
		Show report table if it was fetched previously or 
		get its data from backend and call createReportTable() function to show it
*/
function loadReport(reportId, etcData)
{

	var filter_level = $("#"+reportId+"_level").val(),
		filter_sar = $("#"+reportId+"_sars").val(),
		filter_br = $("#"+reportId+"_brs").val(),
		filter_year = $("#"+reportId+"_year").val(),
		filter_moon = $("#"+reportId+"_moon").val(),
		filter_scale = $("#"+reportId+"_scale").val();

	var params;
	var newRep;
	var repKey;

	if(etcData == null)
	{
		params = { report:reportId, level:filter_level, sar:filter_sar, br:filter_br, year:filter_year, moon:filter_moon };
	}
	else
	{
		params = etcData;
	}

	params.zone = (params.zone)? params.zone : -1;
	params.sar = (params.sar)? params.sar : -1;
	params.br = (params.br)? params.br : -1;
	params.aghd = (params.aghd)? params.aghd : 0;
	params.fasl = (params.fasl)? params.fasl : 0;

	repKey = reportId+"_"+params.level+"_"+params.zone+"_"+params.sar+"_"+params.br+"_"+params.aghd+"_"+params.fasl+"_"+(params.year).toString()+(params.moon).toString();
	//console.log($("table#table_"+repKey).length);

	if($("table#table_"+repKey).length)
	{
		newRep = 0;
		var sub = 0;

		$("div#"+reportId+" > div.report-table > table").each(function() {
			if($(this).attr("id") == "table_"+repKey)
				$(this).css("display", "");
			else
				$(this).css("display", "none");
		});

		$("div#"+reportId+" > div.report-title > div a").each(function() {
			if($(this).attr("id") == repKey)
			{
				sub = 1;
			}
			else
			{
				if(sub == 1)
				{
					var rk = $(this).attr("id");
					$(this).remove();
					delete loadedReps[rk];
					$("table#table_"+rk).remove();
				}
			}
		});

		$("select#"+reportId+"_scale").val( $("table#table_"+repKey).attr("data-scale") );
		$("input#"+reportId+"_show").attr("data-key", repKey);
		$("img#"+reportId+"_excelExport").attr("data-key", repKey);
	}
	else
	{
		$("div#"+reportId+" > div.report-table > table").each(function() {
			$(this).css("display", "none");
		});
		$("div#"+reportId+" > div.report-table > img").each(function() {
			$(this).remove();
		});

		$("div#"+reportId+" > div.report-table").append("<img src='images/loading_gif.gif' />");

		newRep = 1;

		$.ajax({
			url: "php/"+reportId+".php", 
			type: "GET", 
			dataType: "json",
			data: params,
			success: function(data){

				$("input#"+reportId+"_show").attr("data-key", data.hirarchy);
				$("img#"+reportId+"_excelExport").attr("data-key", repKey);

				loadedReps[data.hirarchy] = data;

				$("div#"+reportId+" > div.report-title > div > a#"+data.hirarchy).remove();

				if(data.subtitle != "")
					$("div#"+reportId+" > div.report-title > div").append("<a id='"+data.hirarchy+"' href='javascript:loadReport(\""+reportId+"\", "+JSON.stringify(params)+");'>" + data.subtitle + " | </a>");

				var divHtml = createReportTable(data, reportId, filter_scale);

				$("div#"+reportId+" > div.report-table > img ").remove();
				$("div#"+reportId+" > div.report-table").append(divHtml);
				//$("div#"+reportId+" > div.report-query").html(data.query);
				$("div#"+reportId+" > div.report-query").html("");

				delete divHtml;

				$("div#"+reportId+" > div.report-table tr.titleRow").click(function() {
					var subset = true;
					$(this).nextAll().each(function() {
						if($(this).hasClass("titleRow") || $(this).hasClass("rowsum"))
							subset = false;
						else
							if(subset)
								$(this).toggle(200);
					});
				});

				$("div#"+reportId+" > div.report-table tr.midsumRow").click(function() {
					var subset = true;
					$(this).prevAll().each(function() {
						if($(this).hasClass("midsumRow") || $(this).hasClass("tableHead"))
							subset = false;
						else
							if(subset)
								$(this).toggle(200);
					});
				});

			}
		});
	}
}

/*
	FUNCTION setMainSize()
		set page frames instead of browser size
		it applys at start time and window resize event
*/
var setMainSize = function ()
{
    var windowHeight = $( window ).height();
    var windowWidth = $( window ).width();
    var objectsHeight = $("header").height() + $("footer").height();
    var objectsWidth = $("#reports-tab").width() + 10;

    var mainHeight = windowHeight - objectsHeight;
    var bodyWidth = windowWidth - objectsWidth;
    
    $("#reports-tab").height(mainHeight);
    $("#reports-body").height(mainHeight);
    $("#reports-body").width(bodyWidth);
    
    $("main").height(mainHeight);
    
}

/*
	Document ready state
	after loading of document run followings in a sequence:
		1- fit the application size instead of browser width and height
		2- load branches list
		3- load sarfasl list
		4- handle menu item click to show reports
*/
$(function ()
{
	/*
		1- fit the application size instead of browser width and height
	*/
    setMainSize();

	/*
		2- load branches list
	*/
	$.ajax({
		url: "php/__branchlist.php", 
		type: "POST", 
		dataType: "json",
		success: function(data) {
			branchList = data;
			brList = new Array();
			for(s in branchList)
				for(b in branchList[s])
					brList[b] = branchList[s][b];
		}
	});

	/*
		3- load sarfasl list
	*/
	$.ajax({
		url: "php/__sarfasllist.php", 
		type: "POST", 
		dataType: "json",
		success: function(data) {
			for(key in data)
				sarfaslList[key] = main2win(data[key]);
		}
	});

	/*
		4- handle menu item click to show reports
	*/
    $("a.report-link").click(function () {
        var reportId = $(this).attr("data-report");
        var reportTitle = $(this).text();
        
        //console.log(reportId);
        
        $("div.report-item").each(function() {
            $(this).css("display", "none");
        });
        
        $("div.tab-item").each(function () {
            $(this).removeClass("tab-select");
        });
        
        if($('div#'+reportId).length) {
            $('div#'+reportId).css("display", "block");
            
            $("div.tab-item").each(function () {
                if($(this).attr("data-report") == reportId)
                    $(this).addClass("tab-select");
            });
        } else {
            
			var divHtml = "<div class='tab-item tab-select' data-report='" + reportId + "'><img src='images/button-remove.png' />" + reportTitle + "</div>";
			$("#reports-tab").append(divHtml);

			$(".tab-item > img").click(function (){
				var reportId = $(this).parent().attr("data-report");
				$(this).parent().remove();
				$('div#'+reportId).remove();
			});
			
			$(".tab-item").click(function (){
				var reportId = $(this).attr("data-report");
				
				$("div.report-item").each(function() {
					$(this).css("display", "none");
				});
				
				$("div.tab-item").each(function () {
					$(this).removeClass("tab-select");
				});

				$(this).addClass("tab-select");
				$('div#'+reportId).css("display", "block");

			});

			if(reportId == "rep-sarfasl")
			{
				var divHtml = "\
					<div class='report-item' id='" + reportId + "' style='display:block;'>\
						" + ($("#filter-form-sarfasl").html()).replace(/_ID_/g, reportId+"_") + "\
						<div class='report-title'> " + reportTitle + " <div></div></div>\
						<div align='center' class='report-table'></div>\
						<div align='center' class='report-query'></div>\
					</div>";
				
				$("#reports-body").append(divHtml);
				$("div#"+reportId+" > div.report-form").attr("id", reportId+"_filter");
				
				$("div#"+reportId+"_filter > *").css("display", "inline");
			}
			else
			{
				var divHtml = "\
					<div class='report-item' id='" + reportId + "' style='display:block;'>\
						" + ($("#filter-form").html()).replace(/_ID_/g, reportId+"_") + "\
						<div class='report-title'> " + reportTitle + " <div></div></div>\
						<div align='center' class='report-table'></div>\
						<div align='center' class='report-query'></div>\
					</div>";
				
				$("#reports-body").append(divHtml);
				$("div#"+reportId+" > div.report-form").attr("id", reportId+"_filter");
				
				$("div#"+reportId+"_filter select.level").change(function(){
					if($(this).val() == "sar")
					{
						$("div#"+reportId+"_filter > *").css("display", "inline");
					} else {
						$("div#"+reportId+"_filter > select#"+reportId+"_sars").val(-1);
						$("div#"+reportId+"_filter > select#"+reportId+"_brs").val(-1);
						$("div#"+reportId+"_filter > *").css("display", "none");

						$("div#"+reportId+"_filter > select.level").css("display", "inline");
						$("div#"+reportId+"_filter > span").css("display", "inline");
						$("div#"+reportId+"_filter > input").css("display", "inline");
					}
				});
				
				$("select#"+reportId+"_sars").change(function(){
					var sarcode = $(this).val();
					$("select#"+reportId+"_brs").html("<option value='-1' selected >همه شعب</option>");
					for(code in branchList[sarcode])
					{
						$("select#"+reportId+"_brs").append("<option value='"+code+"'>"+code +" - "+ branchList[sarcode][code]+" &nbsp; &nbsp; &nbsp; &nbsp;</option>");
					}
				});
				
				
				if( ["rep-05", "rep-06", "rep-07", "rep-08", "rep-09"].indexOf(reportId) > -1 )
				{
					$("div#"+reportId+"_filter > *").css("display", "none");			
					$("div#"+reportId+"_filter > span").css("display", "inline");
					$("div#"+reportId+"_filter > input").css("display", "inline");
				}
				else if( ["rep-mt01"].indexOf(reportId) > -1 )
				{
					$("div#"+reportId+"_filter > *").css("display", "none");			
					$("div#"+reportId+"_filter > select#"+reportId+"_sars").css("display", "inline").val(-1);
					$("div#"+reportId+"_filter > select#"+reportId+"_brs").css("display", "inline").val(-1);
					$("div#"+reportId+"_filter > span").css("display", "inline");
					$("div#"+reportId+"_filter > input").css("display", "inline");
				}
				
				
				$("input#"+reportId+"_show").click(function(){
					if($("#"+reportId+"_moon").val() == 0 || 
						(
							$("#"+reportId+"_year").val() < $("#"+reportId+"_year").attr("min") ||
							$("#"+reportId+"_year").val() > $("#"+reportId+"_year").attr("max")
						))
					{
						alert("مقادیر سال و ماه را بصورت صحیح انتخاب نمایید");
					}
					else
						loadReport(reportId, null);
				});
				
				$("select#"+reportId+"_scale").change(function(){
					if($("input#"+reportId+"_show").attr("data-key") != "")
					{
						var dataKey = $("input#"+reportId+"_show").attr("data-key");
						var data = loadedReps[dataKey];
						var filter_scale = $(this).val();
						
						var divHtml = createReportTable(data, reportId, filter_scale);
						
						$("div#"+reportId+" > div.report-table > table#table_"+dataKey).remove();
						$("div#"+reportId+" > div.report-table").append(divHtml);

						$("div#"+reportId+" > div.report-table tr.titleRow").click(function() {
							var subset = true;
							$(this).nextAll().each(function() {
								if($(this).hasClass("titleRow") || $(this).hasClass("rowsum"))
									subset = false;
								else
									if(subset)
										$(this).toggle(200);
							});
						});

						$("div#"+reportId+" > div.report-table tr.midsumRow").click(function() {
							var subset = true;
							$(this).prevAll().each(function() {
								if($(this).hasClass("midsumRow") || $(this).hasClass("tableHead"))
									subset = false;
								else
									if(subset)
										$(this).toggle(200);
							});
						});

						
					}
				});
				
				$("img#"+reportId+"_excelExport").click(function(){
					var repKey = $(this).attr("data-key");
					excelExport(reportId, repKey, reportTitle);
				});
			}
		}
    });

});

$( window ).resize(function () {
    setMainSize();
});

