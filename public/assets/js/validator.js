// JavaScript Document

function isNumbers(elem, errContainer, helperMsg){
	$('body').append('<div id="'+ errContainer +'Info" class="info"></div>');
	elem = grab_append('#'+ errContainer +'Info', elem, 3, 15);
	if(isNaN(elem.val())){
		errorToShow(elem, $('#'+ errContainer +'Info'), '&larr; '+helperMsg);
		elem.focus();// elem.effect("shake", { times:3 }, 50);
		return false;	
	}
	successToShow(elem, $('#'+ errContainer +'Info'), '&radic;');
	return true;
}
function isEmailValid(elem, errContainer, helperMsg, allowEmpty){
	var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	allowEmpty = allowEmpty || false;
	if(allowEmpty == true){
		if(elem.val() != "" && (!ck_email.test(elem.val()) || elem.val().length < 9 )){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}else{
		if(!ck_email.test(elem.val()) || elem.val().length < 9 ){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}
	successToShow(elem, $('#'+ errContainer +'Info'), '&radic;');
	return true;
}
function isPhone(elem, errContainer, helperMsg, allowEmpty){
	var ck_phone = /^((\(\d{1,4}\))?|\d{1,4})?(([-\s\.])?[0-9]{6,12}){1,2}$/;
	allowEmpty = allowEmpty || false;
	//alert(ck_phone.test(elem.val()));return;
	if(allowEmpty == true){
		if(elem.val() != "" && (!ck_phone.test(elem.val()) || elem.val().length < 6 )){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}else{
		if(!ck_phone.test(elem.val()) || elem.val().length < 8 ){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}
	successToShow(elem, $('#'+ errContainer +'Info'), '&radic;');
	return true;
}
function isPhoneCode(elem){
	var result = 0;
	$( elem ).each(function(){
		var codeNm = $(this).attr('name');													  
		var phoneNm = codeNm.substr(0, codeNm.indexOf('_')+1);
		$('body').append('<div id="'+ codeNm +'Info" class="info"></div>');
		elem = grab_append('#'+ codeNm +'Info', $(this), 3, 15);
		//alert($(this).val())
		if($("input[name="+phoneNm+"]:not(:disabled)").val() != "" && $(this).val() == ""){
			errorToShow(elem, $('#'+ codeNm +'Info'), '&larr; Dialling code invalid');
			elem.focus(); 
			result ++;
			return;
		}
	})
	if(result > 0) return false;
	else return true;
}
function notEmpty(elem, errContainer, helperMsg){
	$('body').append('<div id="'+ errContainer +'Info" class="info"></div>');
	elem = grab_append('#'+ errContainer +'Info', elem, 3, 15);
	if(elem.val() == ""){
		errorToShow(elem, $('#'+ errContainer +'Info'), '&larr; '+helperMsg);
		elem.focus(); //elem.effect("shake", { times:3 }, 50);
		return false;
	} 
	successToShow(elem, $('#'+ errContainer +'Info'), '&radic;');
	return true;
}
function validName(elem, errContainer, helperMsg, allowEmpty, length){
	length = length || 3;
	var ck_name = "^[A-Za-z0-9' -]{" + length + ",20}$";
	ck_name = new RegExp(ck_name, "g");
	allowEmpty = allowEmpty || false;
	if(allowEmpty == true){
		if(elem.val() != "" && !ck_name.test(elem.val())){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}else{
		if(!ck_name.test(elem.val())){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}
	successToShow(elem, $('#'+ errContainer +'Info'), '&radic;');
	return true;
}
function fullName(elem, errContainer, helperMsg, allowEmpty, length){
	length = length || 1;
	var ck_name = "^[A-Za-z0-9'.&/\ -]{" + length + ",150}$";
	ck_name = new RegExp(ck_name, "g");
	allowEmpty = allowEmpty || false;
	if(allowEmpty == true){
		if(elem.val() != "" && !ck_name.test(elem.val())){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}else{
		if(!ck_name.test(elem.val())){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}
	successToShow(elem, $('#'+ errContainer +'Info'), '&radic;');
	return true;
}
function checkedRad(elem, errContainer, helperMsg){
	$('body').append('<div id="'+ errContainer +'Info" class="info"></div>');
	elem = grab_append('#'+ errContainer +'Info', elem, 3, 15);
	if(elem.filter(':checked').val() == ''){
		errorToShow(elem, $('#'+ errContainer +'Info'), '&larr; '+helperMsg);
		elem.focus(); // elem.effect("shake", { times:3 }, 50);
		return false;
	} 
	successToShow(elem, $('#'+ errContainer +'Info'), '&radic;');
	return true;
}
function checkDate(elem, errContainer, helperMsg, allowEmpty){
	var ck_date = /^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\-\d{2}\-\d{4}$/;
	allowEmpty = allowEmpty || false;
	if(allowEmpty == true){
		if(elem.val() != "" && !ck_date.test(elem.val())){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}else{
		if(!ck_date.test(elem.val())){
			drawErr(elem, errContainer, helperMsg)
			return false;
		} 
	}
	successToShow(elem, $('#'+ errContainer +'Info'), '&radic;');
	return true;
}

function grab_append(appended, ele, s_top, s_width){
	var pos = $(ele).offset();
	$( "#search_results, #dialog-pending" ).scrollTop();
	setTimeout(function(){
		$(appended).css({
			top: pos.top-s_top,
			left: pos.left+$(ele).width()+s_width
		});
	}, 500)
	return $(ele);
}
function drawErr(elem, errContainer, helperMsg){
	$('body').append('<div id="'+ errContainer +'Info" class="info"></div>');
	elem = grab_append('#'+ errContainer +'Info', elem, 3, 15);
	errorToShow(elem, $('#'+ errContainer +'Info'), '&larr; '+helperMsg);
	elem.focus(); //elem.effect("shake", { times:3 }, 50);
}
function errorToShow(ele, aboutInfo, errorInfo){
	aboutInfo.removeClass('correct').addClass('error').html(errorInfo).show()
	setTimeout(function(){aboutInfo.fadeOut()},5000);
	ele.removeClass('normal').addClass('wrong').css({'font-weight': 'normal'});
}
function successToShow(ele, aboutInfo, errorInfo){
	aboutInfo.removeClass('error').addClass('correct').html('&radic;').show().fadeOut(3000);
	ele.removeClass('wrong').addClass('normal');	
}
