<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h1>Change Password</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<!-- <p class="text-center">Use the form below to change your password. Your password cannot be the same as your username.</p> -->
			<form method="post" id="passwordForm">
				<input type="password" class="input-lg form-control" name="password1" id="password1" placeholder="New Password" autocomplete="off">
				<div class="row" align="left">
					<div class="col-sm-6">
						<span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Characters Long<br>
						<span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Uppercase Letter
					</div>
					<div class="col-sm-6">
						<span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Lowercase Letter<br>
						<span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Number
					</div>
				</div>
				<input type="password" class="input-lg form-control" name="password2" id="password2" placeholder="Repeat Password" autocomplete="off">
				<div class="row" align="left">
					<div class="col-sm-12">
						<span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Passwords Match
					</div>
				</div>
				<a onclick="changepassword()" class="col-xs-12 btn btn-primary btn-load btn-lg">Change Password</a>
			</form>
		</div><!--/col-sm-6-->
	</div><!--/row-->
</div>
<script type="text/javascript">
	var char=0;
	var lchar=0;
	var uchar=0;
	var numchar=0;
	var repas=0;
	$("input[type=password]").keyup(function(){
	    var ucase = new RegExp("[A-Z]+");
		var lcase = new RegExp("[a-z]+");
		var num = new RegExp("[0-9]+");
		
		if($("#password1").val().length >= 8){
			$("#8char").removeClass("glyphicon-remove");
			$("#8char").addClass("glyphicon-ok");
			$("#8char").css("color","#00A41E");
			char=1;
		}else{
			$("#8char").removeClass("glyphicon-ok");
			$("#8char").addClass("glyphicon-remove");
			$("#8char").css("color","#FF0004");
			char=0;
		}
		
		if(ucase.test($("#password1").val())){
			$("#ucase").removeClass("glyphicon-remove");
			$("#ucase").addClass("glyphicon-ok");
			$("#ucase").css("color","#00A41E");
			uchar=1;
		}else{
			$("#ucase").removeClass("glyphicon-ok");
			$("#ucase").addClass("glyphicon-remove");
			$("#ucase").css("color","#FF0004");
			uchar=0;
		}
		
		if(lcase.test($("#password1").val())){
			$("#lcase").removeClass("glyphicon-remove");
			$("#lcase").addClass("glyphicon-ok");
			$("#lcase").css("color","#00A41E");
			lchar=1;
		}else{
			$("#lcase").removeClass("glyphicon-ok");
			$("#lcase").addClass("glyphicon-remove");
			$("#lcase").css("color","#FF0004");
			lchar=0;
		}
		
		if(num.test($("#password1").val())){
			$("#num").removeClass("glyphicon-remove");
			$("#num").addClass("glyphicon-ok");
			$("#num").css("color","#00A41E");
			numchar=1;
		}else{
			$("#num").removeClass("glyphicon-ok");
			$("#num").addClass("glyphicon-remove");
			$("#num").css("color","#FF0004");
			numchar=0;
		}
		
		if($("#password1").val() == $("#password2").val()){
			$("#pwmatch").removeClass("glyphicon-remove");
			$("#pwmatch").addClass("glyphicon-ok");
			$("#pwmatch").css("color","#00A41E");
			repas=1;
		}else{
			$("#pwmatch").removeClass("glyphicon-ok");
			$("#pwmatch").addClass("glyphicon-remove");
			$("#pwmatch").css("color","#FF0004");
			repas=0;;
		}

	});
	function changepassword(){
		var total =char+lchar+uchar+numchar+repas;
		console.log("<?=$_SERVER['HTTP_HOST']?>/dunex/dunex-apps/page/changepasswordnew");

		var r = confirm("Are you sure change your password ?");
		if (r == true) {
			if(total<5){
				// alert("Please Check Password Criteria.");
			}else{
				// var host="<?=$_SERVER['HTTP_HOST']?>/dunex/dunex-apps";
				var host="<?=$_SERVER['HTTP_HOST']?>";
				console.log(total);
				var settings = {
			      "async": true,
			      "crossDomain": true,
			      "url": "http://"+host+"/index.php/page/changepasswordnew",
			      "data":'userid=<?=$this->session->userdata('userid');?>&password2='+$("#password1").val(),
			      "method": "GET",
			      "headers": {
			        "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
			        "Content-Type": "application/x-www-form-urlencoded",
			        "Accept": "application/json",
			        "cache-control": "no-cache",
			        "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
			      }
			    }

			    $.ajax(settings).done(function (response) {
			      console.log(response);
			      alert('Password has been changed.');
			    });
			}
		}


	}
</script>