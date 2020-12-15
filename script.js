function free_for_all_click_me_close(id) {
	document.getElementById(id).remove();
}

function smsLoading() {
	var lightbox = document.createElement("div");
	lightbox.setAttribute('id', 'sms-lightbox-load');
	lightbox.setAttribute('class', 'flex flex-center');
	var loading = document.createElement("p");
	loading.innerText = 'لطفا منتظر بمانید ...';
	lightbox.appendChild(loading);
	document.body.appendChild(lightbox);

}

function popupForm(t){
	smsLoading();
	var url = t.getAttribute("href");
	var method = "GET";
	var data = "";
	var callBack = showPopup;
	myAjax(url, method, data, callBack);
	return false;
}
function showPopup(res) {
	free_for_all_click_me_close("sms-lightbox-load");
	document.body.insertAdjacentHTML('beforeend', res);
	document.getElementById("sms-lightbox").style.opacity = 1;
}

function nextStep(t) {
	smsLoading();
	var url = t.getAttribute("action");
	var method = t.getAttribute("method");
	var data = serialize(t)+"&action=updateForm";

	myAjax(url, method, data, updateForm);
	return false;
}

function serialize(form) {
    var field, s = [];
    if (typeof form == 'object' && form.nodeName == "FORM") {
        var len = form.elements.length;
        for (i=0; i<len; i++) {
            field = form.elements[i];
            if (field.name && !field.disabled && field.type != 'file' && field.type != 'reset' && field.type != 'submit' && field.type != 'button') {
                if (field.type == 'select-multiple') {
                    for (j=form.elements[i].options.length-1; j>=0; j--) {
                        if(field.options[j].selected)
                            s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[j].value);
                    }
                } else if ((field.type != 'checkbox' && field.type != 'radio') || field.checked) {
                    s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value);
                }
            }
        }
    }
    return s.join('&').replace(/%20/g, '+');
}

function myAjax(url, method, data, callBack) {
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == XMLHttpRequest.DONE) {	// XMLHttpRequest.DONE == 4
			if (xmlhttp.status == 200) {
				var res = xmlhttp.responseText;
				return callBack(res);
			} else {
				console.log(xmlhttp.status);
			}
		}
	};

	xmlhttp.open(method, url, true);
	xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
	xmlhttp.send(data);
}

var myCountDown;
function updateForm(res) {
	if (document.getElementById("sms-lightbox-load"))
		free_for_all_click_me_close("sms-lightbox-load");
	if (myCountDown)
		clearInterval(myCountDown);
	
	var res = JSON.parse(res);
	if (res['error']) {
		document.getElementById("sms-error").innerText = res['error'];
		return;
	} else if (res['refresh']) {
		alert("پیامک ارسال شد");
		window.location.href = window.location.href;
	}

	document.getElementById("progress-bar").innerHTML = res['progress-bar'];
	document.getElementById("free_for_all_step_title").innerHTML = res['title'];
	document.getElementById("free_for_all_step_form").innerHTML = res['inner-form'];
	countDown();
}

function countDown() {
	var count_down = document.getElementById("count-down");
	if (count_down) {
		var remind = count_down.getAttribute("data-remind");

		myCountDown = setInterval( function() {
			count_down.innerText = Math.floor(remind/60) + ':' + (remind%60);
			remind --;
			if (remind == 0) {
				clearInterval(myCountDown);
				window.location.href = window.location.href;
			}
		}, 1000);
	}
}

function getTexts(sid) {
	var url = document.getElementById("free_for_all_step_form").getAttribute("action")+"?"+"action=textsOfSubject&sid="+sid;
	var method = "GET";
	var data = "";
	var callBack = updateTexts;
	myAjax(url, method, data, callBack);
}

function updateTexts(res) {
	var res = JSON.parse(res)["texts"];
	var select = document.getElementById("text");
	var options = '';
	for (var i = 0; i < res.length; i++) {
		options += '<option value="'+res[i]["id"]+'">'+res[i]["message"].replaceAll("%s", "-------")+'</option>';
	}
	select.innerHTML = options;
}