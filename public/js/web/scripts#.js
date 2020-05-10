var Config = {
	website: "http://bemyguest.404.ge/",
	websiteMainLang: "http://bemyguest.404.ge/ru/",
	ajax:"http://bemyguest.404.ge/ru/ajax/index", 
	mainLang: "ru"
};

$(document).on("click", ".goto-registration-button", function(){
	var url = $(this).attr("data-url");
	location.href = url;
});

$(document).on("click", ".searchButton", function(){
	$(".searchButton i").removeClass("fa-search").addClass("fa-circle-o-notch fa-spin");
	var from = 0;
	var pn = parseInt(1);
	var itemPerPage = $("#itemPerPage").val();
	var fsale_type = $("#fsale_type").val();
	var frooms = $("#frooms").val();
	var ftype = $("#ftype").val();
	var fprice_from = $("#fprice_from").val();
	var fprice_to = $("#fprice_to").val();
	var forderby = $("#forderby").val();

	var html = "";
	$("#pn").val(pn+1);

	// ajax request
	var ajaxFile = "/loadmoreitems";
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			from: from, 
			pn: pn, 
			itemPerPage: itemPerPage, 
			fsale_type: fsale_type,  
			frooms: frooms,  
			ftype: ftype,  
			fprice_from: fprice_from,  
			fprice_to: fprice_to,  
			forderby: forderby
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		$(".searchButton i").removeClass("fa-circle-o-notch fa-spin").addClass("fa-search");
		if(obj.Success.Code==1){
			$("#results").html(obj.Success.results);
			$(this).attr("data-loaded", obj.Success.loaded); 
			$(".loadergif").hide();
			$(".loadmoreitems").show();
		}else if(obj.Error.Code==1){
			$("#results").html(obj.Error.Text);
			$(this).attr("data-loaded", 0); 
			$(".loadergif").hide();
			$(".loadmoreitems").hide();
		}
	});
});

$(document).on("click", ".loadmoreitems", function(){
	var from = $(this).attr("data-loaded");
	var pn = parseInt($("#pn").val());
	var itemPerPage = $("#itemPerPage").val();
	var fsale_type = $("#fsale_type").val();
	var frooms = $("#frooms").val();
	var ftype = $("#ftype").val();
	var fprice_from = $("#fprice_from").val();
	var fprice_to = $("#fprice_to").val();
	var forderby = $("#forderby").val();

	var html = "";
	
	$(".loadergif").show();
	$(".loadmoreitems").hide();
	$("#pn").val(pn+1);

	// ajax request
	var ajaxFile = "/loadmoreitems";
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			from: from, 
			pn: pn, 
			itemPerPage:itemPerPage,
			fsale_type: fsale_type,  
			frooms: frooms,  
			ftype: ftype,  
			fprice_from: fprice_from,  
			fprice_to: fprice_to,  
			forderby: forderby
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Success.Code==1){
			$("#results").append(obj.Success.results);
			$(this).attr("data-loaded", obj.Success.loaded); 
			
			$(".loadergif").hide();
			$(".loadmoreitems").show();
		}else{
			$(".loadergif").hide();
			$(".loadmoreitems").hide();
		}
	});
});

$(document).on("click", ".change-order-list", function(){
	var forderby = $("#forderby").val();
	if(forderby=="asc")
	{
		$("#forderby").val("desc");	
		$("i", this).removeClass("fa-sort-numeric-asc").addClass("fa-sort-numeric-desc");
	}else{
		$("#forderby").val("asc");	
		$("i", this).removeClass("fa-sort-numeric-desc").addClass("fa-sort-numeric-asc");
	}
	$('.searchButton').click();
});

setTimeout(function(){
	document.getElementById("audio").play();
	$(".send-me-variants-title .desktop").css("display","none");
	$(".send-me-variants-title .mobile").css("display","none");
	$( ".send-me-variants-title" ).addClass("opened");	

	setTimeout(function(){
		if($(window).width()<1024){
			$(".send-me-variants-title .mobile").css("display","inline-flex");
		}else{
			$(".send-me-variants-title .desktop").css("display","block");
		}
		$( ".send-me-variants-title" ).removeClass("opened");	
	}, 4000);

}, 2000);


$(document).on("click", ".send-me-variants-close", function(event){
	event.preventDefault();
	if($(window).width()<1024){
		$(".send-me-variants-title .mobile").css("display","inline-flex");
	}else{
		$(".send-me-variants-title .desktop").css("display","block");
	}
	$( ".send-me-variants-title" ).removeClass("opened");
});

$(document).on("click", ".showFilter", function(event){
	event.preventDefault();
	$("main .container .row .right .list-group .mobile-list-group-item").css("display","none");	
	$("main .container .row .right .list-group .desktop-list-group-item").fadeIn("fast");
});

$(document).on("click", ".addComment", function(event){
	event.preventDefault();
	$(".output-message").hide();
	$(".output-message").html("Пожалуйста, подождите...");
	$(".output-message").fadeIn();
	var usersname = $("#usersname").val();
	var comment = $("#comment").val();

	if(
		(usersname==null || usersname=="") || 
		(comment==null || comment=="") || 
		(document.getElementById("uploadbtn").files.length == 0) 
	){
		$(".output-message").html("Все поля обязательны для заполнения!");
	}else{
		document.getElementById("commentForm").submit();
	}
});

$(document).on("click", ".usefullinformation", function(event){
	event.preventDefault();
	var show = $(this).attr("data-show");
	if(show=="false"){
		$("main .container .containerRow .left").css("display","block");	
		$("main .container .containerRow .right").css("display","none");
		$(this).html("Назад");
		$(this).attr("data-show", "true");
	}else{
		$("main .container .containerRow .left").css("display","none");	
		$("main .container .containerRow .right").css("display","block");
		$(this).html("Полезная информация");
		$(this).attr("data-show", "false");
	}
	var body = $("html, body");
	body.stop().animate({scrollTop:0}, '500', 'swing', function() { });
});

$(document).on("click", "#sendButton", function(event){
	event.preventDefault();

	$(".output-message").hide();
	$(".output-message").html("Пожалуйста, подождите...");
	$(".output-message").fadeIn();
	var ajaxFile = "/addWeOffer";
	var checkindate = $("#checkindate").val();
	var checkoutdate = $("#checkoutdate").val();
	var adults = $("#adults").val();
	var children = $("#children").val();
	var canpay = $("#canpay").val();
	var willings = $("#willings").val();
	var firstname = $("#firstname").val();
	var phone = $("#phone").val();
	var email = $("#email").val();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			checkindate: checkindate, 
			checkoutdate: checkoutdate,  
			adults: adults,  
			children: children,  
			canpay: canpay,  
			willings: willings,  
			firstname: firstname,  
			phone: phone,  
			email: email  
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			$(".output-message").html(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$(".output-message").html(obj.Success.Text);
			let html = "<section class=\"col-md-12\">"+
			"<h4 class=\"text-center text-uppercase\">Дорогой гость!</h4>"+
			"<p class=\"text-center text-uppercase\">В течение 45 минут  я свяжусь с вами и предложу вам лучшие варианты!</p>"+
			"</section>";
			$(".anketadata").html(html);
			setTimeout(function(){
				location.href = "/";
			}, 3500);
		}else{
			$(".output-message").html("E3");
		}

		$(".output-message").fadeIn("show");
	});


});

$(document).on("click", ".registrationButton", function(event){
	event.preventDefault();
	$(".output-message").html("Пожалуйста, подождите...");
	$(".output-message").fadeIn();
	var ajaxFile = "/newUser";
	var email = $("#email").val();
	var firstlastname = $("#firstlastname").val();
	var mobile = $("#mobile").val();
	var password = $("#password").val();
	var comfirmpassword = $("#comfirmpassword").val();

	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			email: email, 
			firstlastname: firstlastname, 			 
			mobile: mobile,  
			password: password,  
			comfirmpassword: comfirmpassword
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			$(".output-message").html(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$(".output-message").html(obj.Success.Text);
			setTimeout(function(){
				location.href = "/ru/login";
			}, 1500);
		}else{
			$(".output-message").html("E3");
		}

		$(".output-message").fadeIn("show");
	});

});

$(document).on("click", ".loginButton", function(event){
	event.preventDefault();
	$(".output-message").html("Пожалуйста, подождите...");
	$(".output-message").fadeIn();
	var ajaxFile = "/loginTry";
	var usersname = $("#usersname").val();
	var password = $("#password").val();
	
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			usersname: usersname, 
			password: password 
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			$(".output-message").html(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$(".output-message").html(obj.Success.Text);
			setTimeout(function(){
				location.href = "/ru/profile";
			}, 1000);
		}else{
			$(".output-message").html("E3");
		}

		$(".output-message").fadeIn("show");
	});

});

$(document).on("click", ".recoverButton", function(event){
	event.preventDefault();
	$(".output-message").html("Пожалуйста, подождите...");
	$(".output-message").fadeIn();
	var ajaxFile = "/recoverPassword";
	var usersname = $("#usersname").val();
	
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			usersname: usersname
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			$(".output-message").html(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$(".output-message").html(obj.Success.Text);
			$("#first-step").hide();
			$("#second-step").show();
		}else{
			$(".output-message").html("E3");
		}

		$(".output-message").fadeIn("show");
	});

});

$(document).on("click", ".recoverButtonStep2", function(event){
	event.preventDefault();
	$(".output-message").html("Пожалуйста, подождите...");
	$(".output-message").fadeIn();
	var ajaxFile = "/recoverPassword2";
	var updatepassword = $("#updatepassword").val();
	var newpass = $("#newpass").val();
	var confirmnewpass = $("#confirmnewpass").val();
	
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			updatepassword: updatepassword, 
			newpass: newpass, 
			confirmnewpass: confirmnewpass 
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			$(".output-message").html(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$(".output-message").html(obj.Success.Text);
			location.href = "/ru/login"; 
		}else{
			$(".output-message").html("E3");
		}

		$(".output-message").fadeIn("show");
	});

});

$(document).on("click", ".updateProfileButton", function(event){
	event.preventDefault();
	$(".output-message").html("Пожалуйста, подождите...");
	$(".output-message").fadeIn();
	var ajaxFile = "/updateProfileButton";
	
	var firstname = $("#firstname").val();
	var mobile = $("#mobile").val();
	
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			firstname: firstname, 
			mobile: mobile
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			$(".output-message").html(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$(".output-message").html(obj.Success.Text);
			setTimeout(function(){
				location.reload();
			}, 1500);
		}else{
			$(".output-message").html("E3");
		}
	});

});

$(document).on("click", ".updatePasswordButton", function(event){
	event.preventDefault();
	$(".output-message").html("Пожалуйста, подождите...");
	$(".output-message").fadeIn();
	var ajaxFile = "/updatePasswordButton";
	
	var current_password = $("#current_password").val();
	var new_password = $("#new_password").val();
	var confirm_password = $("#confirm_password").val();
	
	$.ajax({
		method: "POST",
		url: Config.ajax + ajaxFile,
		data: { 
			current_password: current_password, 
			new_password: new_password,
			confirm_password: confirm_password
		}
	}).done(function( msg ) {
		var obj = $.parseJSON(msg);
		if(obj.Error.Code==1){
			$(".output-message").html(obj.Error.Text);
		}else if(obj.Success.Code==1){
			$(".output-message").html(obj.Success.Text);
			setTimeout(function(){
				location.reload();
			}, 1500);
		}else{
			$(".output-message").html("E3");
		}
	});

});
$(document).on("click", ".add-new-item", function(event){
	event.preventDefault();

	$(".add-new-item").css("opacity", "0.8");

	$(".output-message").html("Пожалуйста, подождите...");
	$(".output-message").fadeIn();
	
	var title = $("#title").val();
	var sale_price = $("#sale_price").val();
	var rooms = $("#rooms").val();
	var type = $("#type").val();
	var price = $("#price").val();

	var pricebymonthtable = new Array();
	$(".pricebymonth").each(function(){
		var name = $(this).attr("name"); 
		var value = $(this).val(); 
		if(typeof name !== "undefined" && typeof value !== "undefined"){
			pricebymonthtable.push(name+"@@"+value);
		}
	});
	var serialPricebymonthtable = serialize(pricebymonthtable);

	var additionalinfotable = new Array();
	$(".additionalinfos").each(function(){
		var name = $(this).attr("name"); 
		var value = $(this).val(); 
		if(typeof name !== "undefined" && typeof value !== "undefined"){
			additionalinfotable.push(name+"="+value);
		}
	});
	var serialAdditionalinfotable = serialize(additionalinfotable);

	var description = $('#description').val();

	var address = $("#address").val();
	var mapCords = $("#mapCords").val();

	if(
		(typeof title === "undefined" || title=="") ||
		(typeof sale_price === "undefined" || sale_price=="") ||
		(typeof rooms === "undefined" || rooms=="") ||
		(typeof type === "undefined" || type=="") || 
		(typeof price === "undefined" || price=="") || 
		(typeof description === "undefined" || description=="") || 
		(typeof address === "undefined" || address=="") 
	){
		$(".output-message").html("Пожалуйста, заполните обязательные поля!");
		$(".output-message").fadeIn();
	}else{
		$("#catalogItemForm").submit();
	}

	$("html, body").stop().animate({scrollTop:0}, '1500', 'swing', function() { });
	$(".add-new-item").css("opacity", "1");

});

var serialize = function(mixed_value) {
	var val, key, okey,
	ktype = '',
	vals = '',
	count = 0,
	_utf8Size = function(str) {
		var size = 0,
		i = 0,
		l = str.length,
		code = '';
		for (i = 0; i < l; i++) {
			code = str.charCodeAt(i);
			if (code < 0x0080) {
				size += 1;
			} else if (code < 0x0800) {
				size += 2;
			} else {
			size += 3;
			}
		}
		return size;
	};
	_getType = function(inp) {
		var match, key, cons, types, type = typeof inp;

		if (type === 'object' && !inp) {
			return 'null';
		}
		if (type === 'object') {
			if (!inp.constructor) {
				return 'object';
			}
			cons = inp.constructor.toString();
			match = cons.match(/(\w+)\(/);
			if (match) {
				cons = match[1].toLowerCase();
			}
			types = ['boolean', 'number', 'string', 'array'];
			for (key in types) {
				if (cons == types[key]) {
					type = types[key];
					break;
				}
			}
		}
		return type;
	};
	type = _getType(mixed_value);

	switch (type) {
		case 'function':
			val = '';
			break;
		case 'boolean':
			val = 'b:' + (mixed_value ? '1' : '0');
			break;
		case 'number':
			val = (Math.round(mixed_value) == mixed_value ? 'i' : 'd') + ':' + mixed_value;
			break;
		case 'string':
			val = 's:' + _utf8Size(mixed_value) + ':"' + mixed_value + '"';
			break;
		case 'array':
			case 'object':
				val = 'a';

				for (key in mixed_value) {
					if (mixed_value.hasOwnProperty(key)) {
						ktype = _getType(mixed_value[key]);
						if (ktype === 'function') {
							continue;
						}
						okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
						vals += this.serialize(okey) + this.serialize(mixed_value[key]);
						count++;
					}
				}
				val += ':' + count + ':{' + vals + '}';
				break;
		case 'undefined':
			default:
				val = 'N';
				break;
	}

	if (type !== 'object' && type !== 'array') {
		val += ';';
	}
	return val;
};