var Config={website:"http://bemyguest.ge/",websiteMainLang:"http://bemyguest.ge/ru/",ajax:"http://bemyguest.ge/ru/ajax/index",mainLang:"ru"};$(document).on("click",".goto-registration-button",function(){var e=$(this).attr("data-url");location.href=e}),$(document).on("click",".searchButton",function(){$(".searchButton i").removeClass("fa-search").addClass("fa-circle-o-notch fa-spin");var e=parseInt(1),t=$("#itemPerPage").val(),a=$("#fsale_type").val(),o=$("#frooms").val(),s=$("#ftype").val(),r=$("#fprice_from").val(),n=$("#fprice_to").val(),i=$("#forderby").val();$("#pn").val(e+1);$.ajax({method:"POST",url:Config.ajax+"/loadmoreitems",data:{from:0,pn:e,itemPerPage:t,fsale_type:a,frooms:o,ftype:s,fprice_from:r,fprice_to:n,forderby:i}}).done(function(e){var t=$.parseJSON(e);$(".searchButton i").removeClass("fa-circle-o-notch fa-spin").addClass("fa-search"),1==t.Success.Code?($("#results").html(t.Success.results),$(this).attr("data-loaded",t.Success.loaded),$(".loadergif").hide(),$(".loadmoreitems").show()):1==t.Error.Code&&($("#results").html(t.Error.Text),$(this).attr("data-loaded",0),$(".loadergif").hide(),$(".loadmoreitems").hide())})}),$(document).on("click",".loadmoreitems",function(){var e=$(this).attr("data-loaded"),t=parseInt($("#pn").val()),a=$("#itemPerPage").val(),o=$("#fsale_type").val(),s=$("#frooms").val(),r=$("#ftype").val(),n=$("#fprice_from").val(),i=$("#fprice_to").val(),l=$("#forderby").val();$(".loadergif").show(),$(".loadmoreitems").hide(),$("#pn").val(t+1);$.ajax({method:"POST",url:Config.ajax+"/loadmoreitems",data:{from:e,pn:t,itemPerPage:a,fsale_type:o,frooms:s,ftype:r,fprice_from:n,fprice_to:i,forderby:l}}).done(function(e){var t=$.parseJSON(e);1==t.Success.Code?($("#results").append(t.Success.results),$(this).attr("data-loaded",t.Success.loaded),$(".loadergif").hide(),$(".loadmoreitems").show()):($(".loadergif").hide(),$(".loadmoreitems").hide())})}),$(document).on("click",".change-order-list",function(){"asc"==$("#forderby").val()?($("#forderby").val("desc"),$("i",this).removeClass("fa-sort-numeric-asc").addClass("fa-sort-numeric-desc")):($("#forderby").val("asc"),$("i",this).removeClass("fa-sort-numeric-desc").addClass("fa-sort-numeric-asc")),$(".searchButton").click()}),setTimeout(function(){document.getElementById("audio").play(),$(".send-me-variants-title .desktop").css("display","none"),$(".send-me-variants-title .mobile").css("display","none"),$(".send-me-variants-title").addClass("opened"),setTimeout(function(){$(window).width()<1024?$(".send-me-variants-title .mobile").css("display","inline-flex"):$(".send-me-variants-title .desktop").css("display","block"),$(".send-me-variants-title").removeClass("opened")},4e3)},2e3),$(document).on("click",".send-me-variants-close",function(e){e.preventDefault(),$(window).width()<1024?$(".send-me-variants-title .mobile").css("display","inline-flex"):$(".send-me-variants-title .desktop").css("display","block"),$(".send-me-variants-title").removeClass("opened")}),$(document).on("click",".showFilter",function(e){e.preventDefault(),$("main .container .row .right .list-group .mobile-list-group-item").css("display","none"),$("main .container .row .right .list-group .desktop-list-group-item").fadeIn("fast")}),$(document).on("click",".addComment",function(e){e.preventDefault(),$(".output-message").hide(),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#usersname").val(),a=$("#comment").val();null==t||""==t||null==a||""==a||0==document.getElementById("uploadbtn").files.length?$(".output-message").html("Все поля обязательны для заполнения!"):document.getElementById("commentForm").submit()}),$(document).on("click",".usefullinformation",function(e){e.preventDefault(),"false"==$(this).attr("data-show")?($("main .container .containerRow .left").css("display","block"),$("main .container .containerRow .right").css("display","none"),$(this).html("Назад"),$(this).attr("data-show","true")):($("main .container .containerRow .left").css("display","none"),$("main .container .containerRow .right").css("display","block"),$(this).html("Полезная информация"),$(this).attr("data-show","false")),$("html, body").stop().animate({scrollTop:0},"500","swing",function(){})}),$(document).on("click","#sendButton",function(e){e.preventDefault(),$(".output-message").hide(),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#checkindate").val(),a=$("#checkoutdate").val(),o=$("#adults").val(),s=$("#children").val(),r=$("#canpay").val(),n=$("#willings").val(),i=$("#firstname").val(),l=$("#phone").val(),u=$("#email").val();$.ajax({method:"POST",url:Config.ajax+"/addWeOffer",data:{checkindate:t,checkoutdate:a,adults:o,children:s,canpay:r,willings:n,firstname:i,phone:l,email:u}}).done(function(e){var t=$.parseJSON(e);1==t.Error.Code?$(".output-message").html(t.Error.Text):1==t.Success.Code?($(".output-message").html(t.Success.Text),setTimeout(function(){location.reload()},1500)):$(".output-message").html("E3"),$(".output-message").fadeIn("show")})}),$(document).on("click",".registrationButton",function(e){e.preventDefault(),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#email").val(),a=$("#firstlastname").val(),o=$("#mobile").val(),s=$("#password").val(),r=$("#comfirmpassword").val();$.ajax({method:"POST",url:Config.ajax+"/newUser",data:{email:t,firstlastname:a,mobile:o,password:s,comfirmpassword:r}}).done(function(e){var t=$.parseJSON(e);1==t.Error.Code?$(".output-message").html(t.Error.Text):1==t.Success.Code?($(".output-message").html(t.Success.Text),setTimeout(function(){location.href="/ru/login"},1500)):$(".output-message").html("E3"),$(".output-message").fadeIn("show")})}),$(document).on("click",".loginButton",function(e){e.preventDefault(),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#usersname").val(),a=$("#password").val();$.ajax({method:"POST",url:Config.ajax+"/loginTry",data:{usersname:t,password:a}}).done(function(e){var t=$.parseJSON(e);1==t.Error.Code?$(".output-message").html(t.Error.Text):1==t.Success.Code?($(".output-message").html(t.Success.Text),setTimeout(function(){location.href="/ru/profile"},1e3)):$(".output-message").html("E3"),$(".output-message").fadeIn("show")})}),$(document).on("click",".recoverButton",function(e){e.preventDefault(),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#usersname").val();$.ajax({method:"POST",url:Config.ajax+"/recoverPassword",data:{usersname:t}}).done(function(e){var t=$.parseJSON(e);1==t.Error.Code?$(".output-message").html(t.Error.Text):1==t.Success.Code?($(".output-message").html(t.Success.Text),$("#first-step").hide(),$("#second-step").show()):$(".output-message").html("E3"),$(".output-message").fadeIn("show")})}),$(document).on("click",".recoverButtonStep2",function(e){e.preventDefault(),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#updatepassword").val(),a=$("#newpass").val(),o=$("#confirmnewpass").val();$.ajax({method:"POST",url:Config.ajax+"/recoverPassword2",data:{updatepassword:t,newpass:a,confirmnewpass:o}}).done(function(e){var t=$.parseJSON(e);1==t.Error.Code?$(".output-message").html(t.Error.Text):1==t.Success.Code?($(".output-message").html(t.Success.Text),location.href="/ru/login"):$(".output-message").html("E3"),$(".output-message").fadeIn("show")})}),$(document).on("click",".updateProfileButton",function(e){e.preventDefault(),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#firstname").val(),a=$("#mobile").val();$.ajax({method:"POST",url:Config.ajax+"/updateProfileButton",data:{firstname:t,mobile:a}}).done(function(e){var t=$.parseJSON(e);1==t.Error.Code?$(".output-message").html(t.Error.Text):1==t.Success.Code?($(".output-message").html(t.Success.Text),setTimeout(function(){location.reload()},1500)):$(".output-message").html("E3")})}),$(document).on("click",".updatePasswordButton",function(e){e.preventDefault(),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#current_password").val(),a=$("#new_password").val(),o=$("#confirm_password").val();$.ajax({method:"POST",url:Config.ajax+"/updatePasswordButton",data:{current_password:t,new_password:a,confirm_password:o}}).done(function(e){var t=$.parseJSON(e);1==t.Error.Code?$(".output-message").html(t.Error.Text):1==t.Success.Code?($(".output-message").html(t.Success.Text),setTimeout(function(){location.reload()},1500)):$(".output-message").html("E3")})}),$(document).on("click",".add-new-item",function(e){e.preventDefault(),$(".add-new-item").css("opacity","0.8"),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#title").val(),a=$("#sale_price").val(),o=$("#rooms").val(),s=$("#type").val(),r=$("#price").val(),n=new Array;$(".pricebymonth").each(function(){var e=$(this).attr("name"),t=$(this).val();void 0!==e&&void 0!==t&&n.push(e+"@@"+t)});serialize(n);var i=new Array;$(".additionalinfos").each(function(){var e=$(this).attr("name"),t=$(this).val();void 0!==e&&void 0!==t&&i.push(e+"="+t)});serialize(i);var l=$("#description").val(),u=$("#address").val();$("#mapCords").val();void 0===t||""==t||void 0===a||""==a||void 0===o||""==o||void 0===s||""==s||void 0===r||""==r||void 0===l||""==l||void 0===u||""==u?($(".output-message").html("Пожалуйста, заполните обязательные поля!"),$(".output-message").fadeIn()):$("#catalogItemForm").submit(),$("html, body").stop().animate({scrollTop:0},"1500","swing",function(){}),$(".add-new-item").css("opacity","1")});var serialize=function(e){var t,a,o,s="",r=0;switch(_getType=function(e){var t,a,o,s,r=typeof e;if("object"===r&&!e)return"null";if("object"===r){if(!e.constructor)return"object";(t=(o=e.constructor.toString()).match(/(\w+)\(/))&&(o=t[1].toLowerCase()),s=["boolean","number","string","array"];for(a in s)if(o==s[a]){r=s[a];break}}return r},type=_getType(e),type){case"function":t="";break;case"boolean":t="b:"+(e?"1":"0");break;case"number":t=(Math.round(e)==e?"i":"d")+":"+e;break;case"string":t="s:"+function(e){var t=0,a=0,o=e.length,s="";for(a=0;a<o;a++)t+=(s=e.charCodeAt(a))<128?1:s<2048?2:3;return t}(e)+':"'+e+'"';break;case"array":case"object":t="a";for(a in e)if(e.hasOwnProperty(a)){if("function"===_getType(e[a]))continue;o=a.match(/^[0-9]+$/)?parseInt(a,10):a,s+=this.serialize(o)+this.serialize(e[a]),r++}t+=":"+r+":{"+s+"}";break;case"undefined":default:t="N"}return"object"!==type&&"array"!==type&&(t+=";"),t};$(document).on("click",".edit-new-item",function(e){e.preventDefault(),$(".edit-new-item").css("opacity","0.8"),$(".output-message").html("Пожалуйста, подождите..."),$(".output-message").fadeIn();var t=$("#title").val(),a=$("#sale_price").val(),o=$("#rooms").val(),s=$("#type").val(),r=$("#price").val(),n=new Array;$(".pricebymonth").each(function(){var e=$(this).attr("name"),t=$(this).val();void 0!==e&&void 0!==t&&n.push(e+"@@"+t)});serialize(n);var i=new Array;$(".additionalinfos").each(function(){var e=$(this).attr("name"),t=$(this).val();void 0!==e&&void 0!==t&&i.push(e+"="+t)});serialize(i);var l=$("#description").val(),u=$("#address").val();$("#mapCords").val();void 0===t||""==t||void 0===a||""==a||void 0===o||""==o||void 0===s||""==s||void 0===r||""==r||void 0===l||""==l||void 0===u||""==u?($(".output-message").html("Пожалуйста, заполните обязательные поля!"),$(".output-message").fadeIn()):$("#catalogItemForm").submit(),$(".edit-new-item").css("opacity","1")}),$(document).on("click",".insertedimageremove",function(e){e.preventDefault();var t=$(this).attr("data-imageid"),a=$(this).attr("data-parent");$.ajax({method:"POST",url:Config.ajax+"/deleteuserphoto",data:{id:t,parent:a}}).done(function(e){var t=$.parseJSON(e);1==t.Error.Code?console.log(t.Error.Text):1==t.Success.Code?console.log(t.Success.Text):console.log("E3")})});