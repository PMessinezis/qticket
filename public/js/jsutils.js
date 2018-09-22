"use strict";

var $=jQuery;

function msg(s) {
	return alert(s);
}

function myAjax() {
	var ajaxRequest;

	try {
		ajaxRequest = new XMLHttpRequest();
	} catch (e) {
		try {
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert("Your browser broke!");
				return false;
			}
		}
	}
	return ajaxRequest;
}

function myWEB(url, params, asynchronous, onsuccess, onfail, meth) {
	var asynch = ((asynchronous == undefined) ? true : Boolean(asynchronous));
	var p = ((params == undefined) ? "" : params);
	var method = ((meth == undefined) ? "GET" : meth.toUpperCase());
	var outcome="";
	var afunc=function(d){outcome=d; return d;};
	var ajaxRequest = myAjax();
	ajaxRequest.onreadystatechange = function() {
		if (ajaxRequest.readyState == 4) {
			if (ajaxRequest.status==200) {
				if (onsuccess != undefined)
					onsuccess(ajaxRequest.responseText);
				outcome=ajaxRequest.responseText;
				return "OK";
			} else {
				if (onfail != undefined)
					onfail(ajaxRequest.responseText);
				outcome=ajaxRequest.responseText;
				return "error";
			}
		}
	}

//	alert("myWEB | method " + method + " | url " + url + " | asynch " + asynch + " | params " + p);
	if (method == "POST") {
		ajaxRequest.open("post", url, asynch);
		ajaxRequest.setRequestHeader("Content-type",
				"application/x-www-form-urlencoded");
		ajaxRequest.send(p);
		return outcome;
	} else {
		if (p !== "")
			p = "?" + p;
		ajaxRequest.open("GET", url + p, asynch);
		ajaxRequest.send();
		return outcome;
	}
}

function myPOST_JSON(url, params, asynch, onsuccess, onfail) {
var p=params;
var myfunc=function(r){
	var out=r; //.responseText;
//	alert(out);//r.status);
//	if (r.status==200){
		if ((onsuccess!=undefined) && (onsuccess!=null)) onsuccess(out);
//	} else {
		if ((onfail!=undefined) && (onfail!=null)) onfail(out);

//	}
  };
//alert(p);
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({
    url: url,
    type: "POST",
    data: p,
    contentType: "application/json",
    dataType: "html",
    success: myfunc,
    error:myfunc
});
}

function myGET(url, params, asynch, onsuccess, onfail) {
	return myWEB(url, params, asynch, onsuccess, onfail, "GET");
}

function myPOST(url, params, asynch, onsuccess, onfail) {
	return myWEB(url, params, asynch, onsuccess, onfail, "POST");
}

function newRandomID(){
	var anID="";
	var anitem;
	do {
	    anID="~~a_random_id~~"+Math.floor((Math.random() * 99999) + 1);
	    anitem=document.getElementById(anID);
	    if (anitem !=undefined) alert(anID + " exists ?????!!!???");
	} while (anitem!=undefined);myStealthFormSubmit(document.manage_subs_entry);
	
	return(anID);
}
function newHiddenFrame(){
	var ifrm = document.createElement('iframe');
	var id=newRandomID();
	var if_attribs={"height":"0" , "width":"0" , "style":"visibility:hidden;display:none", "frameBorder":"0"};
	ifrm.setAttribute('id', id); // assign an id
	ifrm.setAttribute('name', id); // assign an id
	for(var attr in if_attribs) {
		ifrm.setAttribute(attr , if_attribs[attr]); 
	}
	document.body.appendChild(ifrm); // to place at end of document */
	return ifrm;
}

function reload(){location.reload();}


function setFormSubmitJQ(jqf,onsuccess,onfail,submitSynch=false){
	var action=jqf.attr("action");
	// log(action);
	var logresult=function(reply){log(reply)};
	if (isBlank(onsuccess)) onsuccess=logresult;
	var func=function(event) {
			// jqf.find(':input').prop('disabled',true);
	        event.preventDefault();
	        var F=htmlnode(jqf);
			var formData = new FormData(F);
	        if (jqf.data('image')) formData.set('file',jqf.data('image'));
	        $.ajax({
	            url: action,
	            type: 'POST',
	            data: formData,
	            contentType: false,
    			processData: false,
	            success: onsuccess,
	            error: onfail,
	        	async: ! submitSynch
	            });
	};
	jqf.on("keypress", ":input:not(textarea):not([type=submit], select 	)", function(event) {
    	if (event.keyCode == 13) {
    		log('caught ENTER')
     	  	event.preventDefault();
            var inputs = $(this).parents("form").eq(0).find(":input:visible:not(disabled):not([readonly]) , select , textarea");
        	var idx = inputs.index(this);
        	if (idx == inputs.length - 1) {
           		idx = -1;
       		} else {
           		inputs[idx + 1].focus(); // handles submit buttons
      		}
        	try {
            	inputs[idx + 1].select();
            	}
        	catch(err) {
            // handle objects not offering select
            }
        	return false;
    
        	// event.keyCode = 9
    	}
	});
	jqf.on("submit", func);
}

function log(s){
	console.log(s);
	var dumpster=htmlnode("#dumpster")
	if (isObj(dumpster)){
		var t=dumpster.innerHTML;
		t=t.replace(/^<pre>/,"")
		t=t.replace(/<\/pre>$/,"")
		t=t.trim();
		if (t !="") t= t + "\n"
		t= "<pre>" + t + s + "</pre>"
		dumpster.innerHTML=t
	}
}

function myStealthFormSubmit(F,method,onsuccess,onfail) {
	var jqf = $(F);
	var result;
	var func;
	if(onsuccess != undefined) {
		func=function(d){result=d; onsuccess(d);};
		// log(onsuccess);
	} else {
		func=function(d){result=d};		
	}
	if (method != undefined) jqf.attr("method",method);
    setFormSubmitJQ(jqf,func,onfail);
    jqf.submit();
    return(result);
}

function myStealthFormPOST(F,onsuccess,onfail) {
	return myStealthFormSubmit(F,"POST",onsuccess,onfail); 
}
function myStealthFormGET(F,onsuccess,onfail) {
	return myStealthFormSubmit(F,"GET",onsuccess,onfail); 
}


function add2Dict(Dict,k,v){
	out=null;
	if (v != undefined) if (v!=null) { Dict[k]=v; out=Dict[k]; }
	return out;
}

function var_dump(o,level){
	var dumped_text = "+ ";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	if(typeof(o) == 'object') { //Array/Hashes/Objects 
		for(var item in o) {
			var value = o[item];
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += var_dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Strings/Chars/Numbers etc.
		dumped_text = "===>"+ o +"<===("+typeof(o)+")";
	}
	return dumped_text;
}

function isBlank(o){return isEmpty(o);}
function notBlank(o){return ! isBlank(o);}
function notEmpty(o){return ! isEmpty(o);}

function isObj(o){ 
	var t=typeof(o);
	var e=true;
	var v=o;
	t=t.toLowerCase();
	if (t=="string") {
		e=false
	} else if ((t=="number") || (t=="function")) {
		e=false; 
	} else if (t=="object")  {
		e=true; 
	} else if ((v===null) || (v===undefined) ){
		e=false;
	} else if (! isEmpty(v.length) ){
		e=true;
	} else {
		var c=0;
		for (var x in v){c+=1;}
		e=(c==0);
	}
	return e;
}

function isEmpty(o){
	var t=typeof(o);
	var e=true;
	var v=o;
	t=t.toLowerCase();
	if (t=="string") v=o.trim();
	if ((t=="number") || (t=="function")) {
		e=false; 
	} else if ((v===null) || (v===undefined) ){
		e=true;
	} else if (! isEmpty(v.length) ){
		e=v.length==0;
	} else {
		var c=0;
		for (var x in v){c+=1;}
		e=(c==0);
	}
	return e;
}

// tbl is an html DOM node of type table and o is an array object 
function initTableFromObject(tbl,o){
	var html="";
	var head="";
	var body="";
	var cols=[];
	var row=0;

	// learn table columns
	for (var i = 0; i < o.length; i++){
		var r=o[i];
		for (var col in r) {
			if (cols[col]!=col) cols[col]=col;
		}
	}
	
	// now create html for headers
	for (var col in cols) {
		head += "<th>" + col + "</th>";
	}
	if (head!="") {
		head="<thead><tr>" + head + "</tr></thead>";
		// now the body
		for (var i = 0; i < o.length; i++){
			var r=o[i];
			body += "<tr>";
			for (var col in cols) {
				var v=r[col];
				if (isEmpty(v)) { log(v + " is empty !") ; v="";}
				body += "<td>"+v+"</td>";
			}
			body += "</tr>";
		}
		html=head + "<tbody>" + body + "</tbody>";
	}
	tbl.innerHTML=html;
}


function tableAddRowEvent(tbl,eventName,func){
	var rows=$(tbl).find("tr");
	eventName=eventName.replace(/^on/,"");
	rows.each(function(){
		var th=$(this).find("th");
		if (isEmpty(th)){
			$(this).on(eventName,func);
		} else {
			var f=function(){alert("nothing to do")};
			this.onclick=f;
		}
	});
}

function tableAddRowEvents(tbl,funcs){
	for (var ev in funcs){
		tableAddRowEvent(tbl,ev,funcs[ev]);
	}
}

function getParentOfType(node,ptype){
	if (! isEmpty(node) ) if (! isEmpty(ptype)) {
		ptype=ptype.toLowerCase();
		var p=node.parentNode;
		var t=p.nodeName;
		// log(t);
		t=t.toLowerCase();
		if (t==ptype) {
			return p;
		} else {
			return getParentOfType(p,ptype); 
		}
	} 
	return null;
};

function quote(s){return JSON.stringify(s);}

function htmlnode(arg1,arg2){
	var t1=typeof(arg1);
	var t2=typeof(arg2);
	var has1=notEmpty(arg1);
	var has2=notEmpty(arg2);
	var node=null;
	if (t1=="string" && ! has2){
		node=$(arg1).get(0);
	} else if (t1=="object" && ! has2) {
		node=$(arg1).get(0);		
	} else if (t1=="object" && has2) {
		node=$(arg1).find(arg2).get(0);				
	}
	return node;
}

function date2ddmmyyy(d){
    var month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [day, month, year].join('/');Prev
}

function userAgentHas(s,flags){
	var u=navigator.userAgent;
	var has = false;
	var re= new RegExp(s,flags);
	var out=u.search(re);
//	alert(out);
	return out>0
}

function device_iOS(){
	return userAgentHas("(iPhone|iPad)","i") 
}

var $_GET = {};
location.search.substr(1).split("&").forEach(function(item) {
	var k = item.split("=")[0], v = decodeURIComponent(item.split("=")[1]);
	(k in $_GET ) ? $_GET[k].push(v) : $_GET[k] = [ v, ]
})

function use_jqueryUI_datepicker(){
	if (jQuery.ui) {
		$.datepicker.formatDate( "dd-mm-yyy");
	    $('input[type=date]').each(function (index, element) {
	        /* Create a hidden clone, which will contain the actual value */
	        var clone = $(this).clone();
	        clone.insertAfter(this);
	        clone.hide();

	        /* Rename the original field, used to contain the display value */
	        $(this).attr('id', $(this).attr('id') + '-display');
	        $(this).attr('name', $(this).attr('name') + '-display');
	        $(this).attr('type', "text");


	        /* Create the datepicker with the desired display format and alt field */
	        $(this).datepicker({ dateFormat: "dd/mm/yy", altField: "#" + clone.attr("id"), altFormat: "yy-mm-dd" });

	        /* Finally, parse the value and change it to the display format */
	        if ($(this).attr('value')) {
	            var date = $.datepicker.parseDate("yy-mm-dd", $(this).attr('value'));
	            $(this).attr('value', $.datepicker.formatDate("dd/mm/yy", date));
	        }
	    });
	}
}


function setCookie(name, value, exdays, path, domain, secure){
    var d = new Date();
	var maxage=0;
	if (exdays) {
		if (exdays >0) {
		    maxage= exdays*24*60*60;
		} else if (exdays<0) {
			maxage=0
		}
	}
    document.cookie= name + "=" + encodeURI(value) +
    "; max-age=" + maxage  +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
	var v=""
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return decodeURI(c.substring(name.length,c.length));
    }
    return v;
}


function stringContainsRX (theStr, str2Find, caseSensitive) {
 	var OK=theStr.match(new RegExp(str2Find, ( (caseSensitive) ? "" : "i")));
 	if (OK) return true ;  else return false;
 } 

function dumpCookies() {
    var t
	var name 
    var ca = document.cookie.split(';');
	var ck
	t="";
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        ck=c.split("="); 
        name=ck[0];
		if ( ! stringContainsRX(name,"rf") ) t+="[" + name + "=" +  decodeURI(ck[1]) +"] <br>"
    }
    return t;
}

function toBoolean(o){
var t=typeof(o);
	var e=true;
	t=t.toLowerCase();
	if (t=="string") {
		o=o.trim();
		e=((o.trim().toLowerCase() == "true") || Math.abs(Number(o))==1);
	} else if (t=="number")  {
		e=(t!=0); 
	} else if ( (t=="object") || (t=="function")) {
		e=false; 
	} else if ((o===null) || (o===undefined) ){
		e=false;
	} else if (! isEmpty(v.length) ){
		e=false;
	} else {
		var c=0;
		for (var x in o){c+=1;}
		e=(c==0);
	}
	return e;	
}

jQuery( document ).ready(function() {	
	if (! device_iOS()) {
		use_jqueryUI_datepicker();
	}
});


