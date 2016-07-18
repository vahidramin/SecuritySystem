
// document ready
$(function () {
	var windowHeight = $(window).height();
	var margin = ( windowHeight - ( $("div#login-box").height() + $("div#copyright").height() ) ) / 2;
	$("div#login-box").css("margin-top", margin-50);
	
	$("input#satausername").keypress(function( event ) {
		if(event.which == 13)
		{
			if( $(this).val().length > 3 )
			{
				$("input#satapass").focus();
			}
			else
			{
				$("span#unamealert").text("! نام کاربری را صحیح وارد نمایید ");
			}
		}
		else
			$("span#unamealert").text("");
	});
	
	$("input#satapass").keypress(function( event ) {
		if(event.which == 13)
		{
			if( $("input#satausername").val().length < 3 )
			{
				$("input#satausername").focus();
				$("span#unamealert").text("! نام کاربری را صحیح وارد نمایید ");
			}
			else
			{
				//todo: ajax request for login
				$.ajax({
					url: "php/login.php", 
					data: {satausername:$("input#satausername").val(), satapass:$("input#satapass").val()},
					type: "GET", 
					dataType: "text",
					success: function(data) {
						switch(data){
							case "ok":
								window.location.assign("index.php");
								break;
							case "ERROR:username":
								$("span#unamealert").text("! این نام کاربری وجود ندارد ");
								break;
							case "ERROR:password":
								$("span#passalert").text("! کلمه عبور اشتباه است ");
								break;
							default:
								console.log(data);
								$("span#passalert").text("! عدم امکان برقراری ارتباط با سرور ");
								break;
						}
						/*
						*/
					}
				});

			}
		}
		else
			$("span#passalert").text("");
	});
	
});