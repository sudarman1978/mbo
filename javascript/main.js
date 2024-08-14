function form_action (fnm,act) {
  if (act == 'copy') {
    document.getElementById('act').value = 'copy';
  }

  /*if(act == 'edit' || act == 'add'){
  	if (fnm == '78000EXT_JOB_ORDER') {
		  if(document.getElementById('fld_bttax').value == 1 && document.getElementById('fld_btp17').value == ""){
			  alert("Please... select Active POI Depot Container ");
		  	  exit;
		  }
  }
	if(fnm == '78000JOB_ORDER_IMP'){
		if(document.getElementById('fld_bttax').value == 1 && document.getElementById('fld_btp47').value == 0){
			  alert("Please... select Active POI Depot Container ");
		  	  exit;
		  }
		}
  }*/
  var err= "";
  var x = document.getElementsByTagName('input');
  var y = document.getElementsByTagName('select');
  for (i = 0; i < x.length; i++) {
    if (x[i].className == 'mandatory') {
      if (x[i].value == "" || x[i].value == "0000-00-00" || x[i].value == "0000-00-00 00:00:00"){
        alert("Mandatory Field Cannot be Blank");
        x[i].focus();
        err="error";
        exit ;
      }
    }
  }
                 console.log("Hello Panic!");
 console.log(y);
  for (u = 0; u < y.length; u++) {
    if (y[u].className == 'mandatory') {
      console.log("Hello world!");
      console.log(y[u]);
      if (y[u].value == "0" || y[u].value == ""){
        var aa = y[u].name;
        alert(aa);
        alert("Mandatory Field Cannot be Blank1");
        y[u].focus();
        err="error";
        exit ;
      }
    }
  }

  if (err == ""){
    document[fnm].submit();
  }
}


function formatNumber(myElement) {
        var myVal = "";
        var myDec = "";
	var ffname = myElement.name.substr(0,myElement.name.lastIndexOf("_dsc"));

        var parts = myElement.value.toString().split(".");

        parts[0] = parts[0].replace(/[^-0123456789]/g,"");

        if ( parts[1] ) { myDec = "."+parts[1] }

        while ( parts[0].length > 3 ) {
            myVal = ","+parts[0].substr(parts[0].length-3, parts[0].length )+myVal;
            parts[0] = parts[0].substr(0, parts[0].length-3)
        }
        myElement.value = parts[0]+myVal+myDec;

        var val = myElement.value;
	var ori = val.replace(/,/g, "");
	document.getElementById(ffname).value = ori;

    }


function fup_action (btid,tynextid,tynextform,nextformid,fnm,act) {
  document.getElementById('act').value = 'fup';
  document.getElementById('btid').value = btid;
  document.getElementById('tynextid').value = tynextid;
  document.getElementById('tynextform').value = tynextform;
  document.getElementById('nextformid').value = nextformid;
  document[fnm].submit();
}


function cekMandatory() {
  var erorr = "";
  var x = document.getElementsByTagName('input');
  var y = document.getElementsByTagName('select');
  for (i = 0; i < x.length; i++) {
    if (x[i].className == 'mandatory') {
      if (x[i].value == ""){
        alert("Mandatory Field Cannot be Blank");
        x[i].focus();
        err="error";
        exit ;
      }
    }
  }

  for (u = 0; u < y.length; u++) {
    if (y[u].className == 'mandatory') {
      if (y[u].value == "0"){
        alert("Mandatory Field Cannot be Blank");
        y[u].focus();
        err="error";
        exit ;
      }
    }
  }

  var answer = confirm('Are you sure want to set Approve this record ?');
  if (answer) {
    var w = window.open(burl + '/index.php/page/setApproval/'+btid,'CategorySearch','width=1,height=1,left=1,top=1,scrollbars=1,location=no');
    w.hide;
    return false;
  }


}

/*
function cekMandatory(url,message) {
  if (confirm(message)) {
	  var erorr = "";
	  var err="";
	  var x = document.getElementsByTagName('input');
	  var y = document.getElementsByTagName('select');
	  for (i = 0; i < x.length; i++) {
		if (x[i].className == 'mandatory') {
		  if (x[i].value == ""){
			alert("Mandatory Field Cannot be Blank");
			x[i].focus();
			err="error";
			exit ;
		  }
		}
	  }

	  for (u = 0; u < y.length; u++) {
		if (y[u].className == 'mandatory') {
		  if (y[u].value == "0"){
			alert("Mandatory Field Cannot be Blank");
			y[u].focus();
			err="error";
			exit ;
		  }
		}
	  }

	  document.location = url;
  }
}
*/

function print_action (fnm,act) {
  var url = window.location.href;
  var print_url = url.replace('/view/','/printout/')
  if (act == 'all') {
    var print_url = print_url + '?&all=1'
  }
  if (act == 'page') {
    var print_url = print_url + '?&page=1'
  }
  window.location = print_url;
}

function setApproval(btid,burl) {
  var answer = confirm('Are you sure want to set Approve this record ?');
  if (answer) {
    var w = window.open(burl + '/index.php/page/setApproval/'+btid,'CategorySearch','width=1,height=1,left=1,top=1,scrollbars=1,location=no');
    w.hide;
    return false;
  }
}

function logout() {
var base_url = document.getElementById('base_url').value;
if (window.confirm('Are you sure to log out?'))
  {
    window.location = base_url + "index.php/login/logout";
  };
}

function CurrencyFormatted(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function delTotal(obj,fnm) {
  if (fnm == '78000QTY_DETAIL') {
    var inputs = obj.getElementsByTagName('input');
    for (i = 0; i < inputs.length; i++) {
    var name = inputs[i].name;
    if (name.match(/fld_btqty/gi)) {
      var totalAmount = parseInt(document.getElementById('fld_btqty').value);
      var value = parseInt(inputs[i].value);
      document.getElementById('fld_btqty').value = totalAmount - value;
    }
    }
  }

  if (fnm == '78000PAYMENT_DETAIL') {
    var inputs = obj.getElementsByTagName('input');
    for (i = 0; i < inputs.length; i++) {
    var name = inputs[i].name;
    if (name.match(/fld_btqty/gi)) {
      var totalAmount = parseInt(document.getElementById('fld_btp01').value);
      var value = parseInt(inputs[i].value);
      document.getElementById('fld_btp01').value = totalAmount - value;
    }
    }
 }

// if (fnm == '78000PURCHASE_ORDER_DETAIL' || fnm == '78000CAEXPENSE' || fnm == '78000INVOICE_DTL' || fnm == '78000PAYMENT_DETAIL') {
//    var inputs = obj.getElementsByTagName('input');
//    for (i = 0; i < inputs.length; i++) {
//    var name = inputs[i].name;
//    if (name.match(/fld_btamt/gi)) {
//      var totalAmount = parseInt(document.getElementById('fld_btamt').value);
//      var value = parseInt(inputs[i].value);
//      document.getElementById('fld_btamt').value = totalAmount - value;
//    }
//    }
// }
  if (fnm == '78000PURCHASE_ORDER_DETAIL' || fnm == '78000CAEXPENSE' || fnm == '78000INVOICE_DTL' || fnm == '78000PAYMENT_DETAIL') {
    var inputs = obj.getElementsByTagName('input');
    for (i = 0; i < inputs.length; i++) {
    var name = inputs[i].name;
    if (name.match(/fld_btamt/gi)) {
      if (!name.match(/dsc/gi)) {
      var totalAmount = parseInt(document.getElementById('fld_btamt').value);
      var value = parseInt(inputs[i].value);
      document.getElementById('fld_btamt').value = totalAmount - value;
      var delamt = totalAmount - value;
      var myVal = "";
      var myDec = "";
      var parts = delamt.toString().split(".");
      parts[0] = parts[0].replace(/[^-0123456789]/g,"");
      if ( parts[1] ) { myDec = "."+parts[1] }
        while ( parts[0].length > 3 ) {
          myVal = ","+parts[0].substr(parts[0].length-3, parts[0].length )+myVal;
          parts[0] = parts[0].substr(0, parts[0].length-3)
        }
        var delamt_p = parts[0]+myVal+myDec;
        document.getElementById('fld_btamt_dsc').value = delamt_p;
    }
    }
    }
 }


}


function change(div,count,fnm,countField) {

  var div = document.getElementById(div);
  var elms = div.getElementsByTagName("*");
  var subTotal = 0;
  var row = document.getElementById(countField).value;
  for(var i = 0, maxI = elms.length; i < maxI; ++i) {
    var elm = elms[i];
      if(elm.type == "text" || elm.type == "hidden") {
	  if (elm.name == fnm + 'fld_btqty01' + count || elm.name == fnm + 'fld_btuamt01' + count) {
	    var subTotal = parseFloat(document.getElementById(fnm + 'fld_btqty01' + count).value * document.getElementById(fnm + 'fld_btuamt01' + count).value);
            document.getElementById(fnm + 'fld_btamt01' + count).value = subTotal;
            var myVal = "";
            var myDec = "";
            var parts = subTotal.toString().split(".");
            parts[0] = parts[0].replace(/[^-0123456789]/g,"");
            if ( parts[1] ) { myDec = "."+parts[1] }
            while ( parts[0].length > 3 ) {
              myVal = ","+parts[0].substr(parts[0].length-3, parts[0].length )+myVal;
              parts[0] = parts[0].substr(0, parts[0].length-3)
            }
            var sbt = parts[0]+myVal+myDec;
            document.getElementById(fnm + 'fld_btamt01' + count + '_dsc').value = sbt;
	  }
	  if (elm.name == fnm + 'fld_btamt01' + count) 	{
	    var totalAmount = 0;
            var inputs = div.getElementsByTagName('input');
	    for (m = 0; m < inputs.length; m++) {
              var name = inputs[m].name;
	      if (name.match(/fld_btamt01/gi) && !name.match(/_dsc/gi)) {
	        var subAmount = parseFloat(inputs[m].value);
		totalAmount = parseFloat(totalAmount + subAmount);
 	      }
	    }
	    var tax = parseFloat(totalAmount * 0.1) ;
	    totalAmount = totalAmount.toFixed(2);
	    tax = tax.toFixed(2);
            if(fnm == '78000CASH_DTL') {
	      document.getElementById('fld_btamt').value = totalAmount;
              document.getElementById('fld_btamt_dsc').value = totalAmount;
	    }
	  }
    }
  }
}

function popup_selector2(ffval, burl, qname, ffname, bindfield1, bindfield2, bindfield3, bindfield4, bindfield5, bindfield6, bindfield7, bindfield8, bindfield9, ctval) {
console.log("AUOOO");
var bindval1 = (bindfield1 != '') ? document.getElementById(bindfield1).value : 999;
var bindval2 = (bindfield2 != '') ? document.getElementById(bindfield2).value : 999;
var bindval3 = (bindfield3 != '') ? document.getElementById(bindfield3).value : 999;
var bindval4 = (bindfield4 != '') ? document.getElementById(bindfield4).value : 999;
var bindval5 = (bindfield5 != '') ? document.getElementById(bindfield5).value : 999;
var bindval6 = (bindfield6 != '') ? document.getElementById(bindfield6).value : 999;
var bindval7 = (bindfield7 != '') ? document.getElementById(bindfield7).value : 999;
var bindval8 = (bindfield8 != '') ? document.getElementById(bindfield8).value : 999;
var bindval9 = (bindfield9 != '') ? document.getElementById(bindfield9).value : 999;
var w = window.open(burl + 'index.php/popup/selector?val=' + ffval + '&qname=' + qname + '&ffname=' + ffname + '&bindval1=' + bindval1 + '&bindval2=' + bindval2 + '&bindval3=' + bindval3 + '&bindval4=' + bindval4 + '&bindval5=' + bindval5 + '&bindval6=' + bindval6 + '&bindval7=' + bindval7 + '&bindval8=' + bindval8 + '&bindval9=' + bindval9 + '&popupid=' + ctval, 'CategorySearch', 'width=550,height=350,left=50,top=50,scrollbars=1,location=no');
  w.focus();
  return false;
}

function popup_selector(fnm,ctval,ffval,burl,qname,ffname,bindfield1,bindfield2,bindfield3,bindfield4,bindfield5) {
  var res1 = bindfield1.substring(0, 4);
  if(res1 == 'fld_') { // Add Field Value from Header form
    var bindval1 = document.getElementById(bindfield1).value;
  } else if (res1 == '@fld') { // Add Field Value from Subform
    bindfield1 = bindfield1.substring(1);
    var bindval1 = document.getElementById(fnm+bindfield1+ctval).value;
  }

  var res2 = bindfield2.substring(0, 4);
  if(res2 == 'fld_') { // Add Field Value from Header form
    var bindval2 = document.getElementById(bindfield2).value;
  } else if (res2 == '@fld') { // Add Field Value from Subform
    bindfield2 = bindfield2.substring(1);
    var bindval2 = document.getElementById(fnm+bindfield2+ctval).value;
  }

  var res3 = bindfield3.substring(0, 4);
  if(res3 == 'fld_') { // Add Field Value from Header form
    var bindval3 = document.getElementById(bindfield3).value;
  } else if (res3 == '@fld') { // Add Field Value from Subform
    bindfield3 = bindfield3.substring(1);
    var bindval3 = document.getElementById(fnm+bindfield3+ctval).value;
  }

  var res4 = bindfield4.substring(0, 4);
  if(res4 == 'fld_') { // Add Field Value from Header form
    var bindval4 = document.getElementById(bindfield4).value;
  } else if (res4 == '@fld') { // Add Field Value from Subform
    bindfield4 = bindfield4.substring(1);
    var bindval4 = document.getElementById(fnm+bindfield4+ctval).value;
  }

  var res5 = bindfield5.substring(0, 4);
  if(res5 == 'fld_') { // Add Field Value from Header form
    var bindval5 = document.getElementById(bindfield5).value;
  } else if (res5 == '@fld') { // Add Field Value from Subform
    bindfield5 = bindfield5.substring(1);
    var bindval5 = document.getElementById(fnm+bindfield5+ctval).value;
  }
  var w = window.open(burl + 'index.php/popup/selector?val='+ffval+'&qname='+qname+'&ffname='+ffname+'&bindval1='+bindval1+'&bindval2='+bindval2+'&bindval3='+bindval3+'&bindval4='+bindval4+'&bindval5='+bindval5,'CategorySearch','width=550,height=350,left=50,top=50,scrollbars=1,location=no');
  w.focus();
  return false;
}

function keyhandler(obj,e,max) {
  e = e || event;
  max = max || 50;
  var keycode = e.keyCode
  , len     = 0
  , This    = keyhandler
  , currlen = obj.value.length;
  if (currlen == 4) {
    var currtext = document.getElementById('fld_btp29').value;
    document.getElementById('fld_btp29').value = currtext + " ";
  }
  if (!('countfld' in This)) {
        This.countfld = document.getElementById('counter');
  }
  if (keycode === 13) {
    return document.forms[0].submit();
  }
  if ((keycode == 32 || keycode>46) && currlen >= max) {
    This.countfld.innerHTML = 'maximum input length reached';
    return false;
  }
  len = (keycode==8 || (keycode===46) && obj.value.length<=max && currlen > 0)
  ? currlen-1
  : keycode <= 46
  ? currlen
  : currlen+1;
  This.countfld.innerHTML = (currlen <1 ? max : max-len) + ' characters left';
  return true;
}

function convertToUpper(obj) {
  obj.value=obj.value.toUpperCase();
}

function showProtect(val) {
  if (val.value == 1) {
    document.getElementById('fld_btp21').style.display = "block";
    document.getElementById('fld_btp21-trigger').style.display = "block";
    document.getElementById('fld_btp22').style.display = "block";
  }
  else {
    document.getElementById('fld_btp21').style.display = "none";
    document.getElementById('fld_btp21-trigger').style.display = "none";
    document.getElementById('fld_btp22').style.display = "none";
  }
}

function showProtectPlb(val) {
  if (val.value == 5 || val.value == 6) {
    document.getElementById('fld_btp35').style.display = "block";
    document.getElementById('fld_btp35-trigger').style.display = "block";
    document.getElementById('fld_btp34').style.display = "block";
    document.getElementById('fld_btp36').style.display = "block";
  }
  else {
    document.getElementById('fld_btp35').style.display = "none";
    document.getElementById('fld_btp35-trigger').style.display = "none";
    document.getElementById('fld_btp34').style.display = "none";
    document.getElementById('fld_btp36').style.display = "none";
  }
}

// AJAX

function cekContainer() {
  var ajaxRequest;  // The variable that makes Ajax possible!
  try {
    // Opera 8.0+, Firefox, Safari
	ajaxRequest = new XMLHttpRequest();
  }
  catch (e) {
  // Internet Explorer Browsers
    try {
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
	}
    catch (e) {
	  try {
	    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
	  }
      catch (e) {
	    // Something went wrong
		alert("Your browser broke!");
		return false;
	  }
	}
  }
  // Create a function that will receive data sent from the server
  ajaxRequest.onreadystatechange = function() {
	if(ajaxRequest.readyState == 4) {
	var ajaxDisplay = document.getElementById('message');
	ajaxDisplay.innerHTML = ajaxRequest.responseText;
	if (ajaxRequest.responseText == '' ) {
	  document.getElementById("message").style.visibility = "hidden";
	}
	else {
	  document.getElementById("message").style.visibility = "visible";
	  document.getElementById('fld_btp29').value = '';
	  document.getElementById('fld_btp29').focus();
	}
  }
}
	var contnum = document.getElementById('fld_btp29').value;
	var queryString = "fld_btp29=" + contnum;
	var base_url = document.getElementById('base_url').value;
	ajaxRequest.open("GET", base_url + "index.php/page/message/?" + queryString, true);
 	ajaxRequest.send(null);
}

function cekBLOut(){
	var ajaxRequest;  // The variable that makes Ajax possible!

	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server

	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			var ajaxDisplay = document.getElementById('message');

			ajaxDisplay.innerHTML = ajaxRequest.responseText;
			if (ajaxRequest.responseText == '' ) {
			  document.getElementById("message").style.visibility = "hidden";
			}
			else {
			  document.getElementById("message").style.visibility = "visible";
			  document.getElementById('fld_btp29').value = '';
			  document.getElementById('fld_btp29').focus();
			}
		}
	}
	var book = document.getElementById('fld_btnoreff').value;
	var queryString = "fld_btnoreff=" + book;
	var base_url = document.getElementById('base_url').value;
	ajaxRequest.open("GET", base_url + "index.php/page/message_out/?" + queryString, true);
 	ajaxRequest.send(null);
}

function form_search() {
var search_value = document.getElementById('form_search_value').value;
var base_url = document.getElementById('base_url').value;
window.location = base_url + "index.php/page/view/78000EIR/?fld_btp29=" + search_value + "&search=Search";
}

function countDays() {
var startTime=document.getElementById('fld_btdtsa').value;
var endTime=document.getElementById('fld_btdtso').value;
var startTime_pisah=startTime.split('-');
var endTime_pisah=endTime.split('-');
var objek_tgl=new Date();
var startTime_leave=objek_tgl.setFullYear(startTime_pisah[0],startTime_pisah[1],startTime_pisah[2]);
var endTime_leave=objek_tgl.setFullYear(endTime_pisah[0],endTime_pisah[1],endTime_pisah[2]);
var hasil=(endTime_leave-startTime_leave)/(60*60*24*1000);
document.getElementById('fld_btqty').value=(hasil)+1;
}

function unHideFile(ffname,mode) {
  var divid = ffname + '_attach';
  var ele = document.getElementById(divid);
  ele.style.display = "block";
  if (mode == 'delete') {
    document.getElementById(ffname).value = '';
    alert("Please save the form to completely remove attachment file(s)");
  }
}

function invoiceCancelReason(fld_btid){
  let base_url = document.getElementById("base_url").value;

  Swal.fire({
  title: 'Submit your Purpose',
  input: 'text',
  inputAttributes: {
    autocapitalize: 'off'
  },
  showCancelButton: true,
  confirmButtonText: 'Submit',
  showLoaderOnConfirm: true,
  preConfirm: (desc) => {
    let send_data = {
      desc: desc,
      fld_btid: fld_btid,
    }

    $.ajax({
      type: 'POST',
      url: base_url + 'index.php/page/cancelinv',
      data: send_data,
      success: function (res) {
        console.log(res);
        if(res.error == false){
          alert('Sorry in advance, please fill in the description !.');
        }else {
          alert('Submit, Successfully.');
          window.location.reload();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
          swal('Oopss!','Something Wrong!!.','error');
      }
    });
  },
  allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    // console.log(result);
  });
}

function reviseInvoice(fld_btid){
  let base_url = document.getElementById("base_url").value;

  Swal.fire({
  title: 'Submit your Purpose',
  input: 'text',
  inputAttributes: {
    autocapitalize: 'off'
  },
  showCancelButton: true,
  confirmButtonText: 'Submit',
  showLoaderOnConfirm: true,
  preConfirm: (desc) => {
    let send_data = {
      desc: desc,
      fld_btid: fld_btid,
    }

    $.ajax({
      type: 'POST',
      url: base_url + 'index.php/page/reviseInvoice',
      data: send_data,
      success: function (res) {
        console.log(res);
        if(res.error == false){
          alert('Sorry in advance, please fill in the description !.');
        }else {
          alert('Submit, Successfully.');
          window.location.reload();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
          swal('Oopss!','Something Wrong!!.','error');
      }
    });
  },
  allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    // console.log(result);
  });
}


function reviseInvoiceToday(fld_btid){
  let base_url = document.getElementById("base_url").value;

  Swal.fire({
  title: 'Submit your Purpose',
  input: 'text',
  inputAttributes: {
    autocapitalize: 'off'
  },
  showCancelButton: true,
  confirmButtonText: 'Submit',
  showLoaderOnConfirm: true,
  preConfirm: (desc) => {
    let send_data = {
      desc: desc,
      fld_btid: fld_btid,
    }

    $.ajax({
      type: 'POST',
      url: base_url + 'index.php/page/reviseInvoiceToday',
      data: send_data,
      success: function (res) {
        console.log(res);
        /*res = JSON.parse(res);*/
        if(res.error == false){
          alert('Sorry in advance, please fill in the description !.');
        }else {
          alert('Submit, Successfully.');
          window.location.reload();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
          swal('Oopss!','Something Wrong!!.','error');
      }
    });
  },
  allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    // console.log(result);
  });

}
