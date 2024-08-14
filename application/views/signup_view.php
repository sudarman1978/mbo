<!DOCTYPE html>
<html lang="en" dir="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dunex - Portal</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url();?>assets/styles/css/themes/lite-purple.min.css">
    <style type="text/css">
        #message {
          display:none;
          background: #f1f1f1;
          color: #000;
          position: relative;
          padding: 20px;
          margin-top: 10px;
        }

        #message p {
          padding: 0px 35px;
          font-size: 11px;
        }

        /* Add a green text color and a checkmark when the requirements are right */
        .valid {
          color: green;
        }

        .valid:before {
          position: relative;
          left: -35px;
          content: "✔";
        }

        /* Add a red text color and an "x" when the requirements are wrong */
        .invalid {
          color: red;
        }

        .invalid:before {
          position: relative;
          left: -35px;
          content: "✖";
        }
    </style>
    
</head>
<body>
    <div class="auth-layout-wrap" style="background-image: url(<?=base_url();?>assets/images/pic-dunex1.jpeg)">
        <div class="auth-content">
            <div class="card o-hidden">
                <div class="row">
                    <div class="col-md-6 text-center" style="background-size: cover;background-image: url(<?=base_url();?>assets/images/photo-long-3.jpg)">
                        <div class="pl-3 auth-right">
                            <div class="auth-logo text-center mt-4"><img src="<?=base_url();?>assets/images/logo.png" alt=""></div>
                            <div class="flex-grow-1"></div>
                            <div class="flex-grow-1"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4">
                            <h1 class="mb-3 text-18">Sign Up</h1>
                            <form id="form" >
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input class="form-control form-control-rounded" name="username" id="username" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input class="form-control form-control-rounded" name="email" id="email" type="email">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input class="form-control form-control-rounded" name="password" id="password" type="password">
                                </div>
                                <div class="form-group">
                                    <label for="repassword">Retype password</label>
                                    <input class="form-control form-control-rounded" name="repassword" id="repassword" type="password">
                                </div>
                                <a onclick="savedata()" class="btn btn-primary btn-block btn-rounded mt-3" style="color: white;">Sign Up</a>
                                <div id="message">
                                  <span>Password must contain the following:</span>
                                  <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                                  <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                                  <p id="number" class="invalid">A <b>number</b></p>
                                  <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?=base_url();?>assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="<?=base_url();?>assets/js/es5/script.min.js"></script>
</body>
<!-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script> -->
<script type="text/javascript">
    var myInput = document.getElementById("password");
    var myInput2 = document.getElementById("repassword");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");
    var criteria_1=0;
    var criteria_2=0;
    var criteria_3=0;
    var criteria_4=0;
    var criteria_5=0;
    var criteria=0;
    myInput.onfocus = function() {
      document.getElementById("message").style.display = "block";
    }

    // When the user clicks outside of the password field, hide the message box
    myInput.onblur = function() {
      document.getElementById("message").style.display = "none";
    }


    // When the user starts to type something inside the password field
    myInput.onkeyup = function() {
      // Validate lowercase letters

      var lowerCaseLetters = /[a-z]/g;
      if(myInput.value.match(lowerCaseLetters)) {  
        letter.classList.remove("invalid");
        letter.classList.add("valid");
        criteria_1=1;
      } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
        criteria_1=0;
      }
      
      // Validate capital letters
      var upperCaseLetters = /[A-Z]/g;
      if(myInput.value.match(upperCaseLetters)) {  
        capital.classList.remove("invalid");
        capital.classList.add("valid");
        criteria_2=1;
      } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
        criteria_2=0;
      }

      // Validate numbers
      var numbers = /[0-9]/g;
      if(myInput.value.match(numbers)) {  
        number.classList.remove("invalid");
        number.classList.add("valid");
        criteria_3=1;
      } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
        criteria_3=0;
      }
      
      // Validate length
      if(myInput.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
        criteria_4=1;
      } else {
        length.classList.remove("valid");
        length.classList.add("invalid");
        criteria_4=0;
      }
      
      criteria=criteria_1+criteria_2+criteria_3+criteria_4;
      console.log(criteria);
    }
  function savedata(){
    if($('#repassword').val()==$('#password').val()){
        criteria_5=1;
    }else{
        criteria_5=0;
    }
    if(criteria<4){
        alert("Please check Password Criteria.");
    }else if(criteria_5<1){
        alert("Retype Password and Password is different.");
    }else if($('#username').val()==''||$('#email').val()==''){
        alert("Please Complete Username & Email.");
    }else{
        // console.log($("#form").serialize());
        var settings = {
          "async": true,
          "crossDomain": true,
          "url": "<?=base_url();?>index.php/Login/signup",
          "data":$("#form").serialize(),
          "method": "POST",
          "headers": {
            "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
            "Content-Type": "application/x-www-form-urlencoded",
            "Accept": "application/json",
            "cache-control": "no-cache",
            "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
          }
        }

        $.ajax(settings).done(function (response) {
          // console.log(response);
          if(response==1){
            alert('User has been created.');

          }else{
            alert('Signup failed.');        
          }
          window.open("<?=base_url();?>", "_self");
        });
    }




  }
</script>
</html>