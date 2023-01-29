<?php
/* check if session has id, if so redirect */
session_start();
if (isset($_SESSION['user_id'])) {
  header("Location: http://localhost/trackingApp/home.php");
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Pro Tracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>
  <section class="forms-section">
    <h1 class="section-title">Pro. Tracker</h1>
    <div class="forms">
      <div class="form-wrapper is-active">
        <button type="button" class="switcher switcher-login">
          Login
          <span class="underline"></span>
        </button>
        <form class="form form-login" method="POST" action="">
          <fieldset>
            <legend>Please, enter your email and password for login.</legend>
            <div class="input-block">
              <label for="login-email">E-mail</label>
              <input id="login-email" type="email" id="emailLogin" name="emailLogin" required />
            </div>
            <div class="input-block">
              <label for="login-password">Password</label>
              <input id="login-password" type="password" id="passwordLogin" name="passwordLogin" required />
            </div>
          </fieldset>
          <div class="error-message" id="errorMsgLogin" style="display: none">
          </div>
          <button type="submit" value="SubmitLogin" name="SubmitLogin" class="btn-login">Login</button>
        </form>
      </div>
      <div class="form-wrapper">
        <button type="button" class="switcher switcher-signup">
          Sign Up
          <span class="underline"></span>
        </button>
        <form class="form form-signup" method="POST" action="">
          <fieldset>
            <legend>
              Please, enter your email, password and password confirmation for
              sign up.
            </legend>
            <div class="input-block">
              <label for="signup-email">E-mail</label>
              <input id="signup-email" type="email" id="emailRegister" name="emailRegister" required />
            </div>
            <div class="input-block">
              <label for="signup-password">Password</label>
              <input id="signup-password" type="password" id="passwordRegister" name="passwordRegister" required />
            </div>
            <div class="input-block">
              <label for="signup-password-confirm">Confirm password</label>
              <input id="signup-password-confirm" type="password" id="confirmpasswordRegister"
                name="confirmpasswordRegister" required />
            </div>
          </fieldset>
          <div class="error-message" id="errorMsgRegister" style="display: none">
          </div>
          <button type="submit" value="SubmitRegister" name="SubmitRegister" class="btn-signup">Continue</button>
        </form>
      </div>
    </div>
  </section>
  <script>
    const switchers = [...document.querySelectorAll(".switcher")];
    switchers.forEach((item) => {
      item.addEventListener("click", function () {
        const errorMessageDivs = document.getElementsByClassName("error-message");
        for (let i = 0; i < errorMessageDivs.length; i++) {
          errorMessageDivs[i].style.display = "none";
        }
        switchers.forEach((item) =>
          item.parentElement.classList.remove("is-active")
        );
        console.log(item.innerHtml);
        this.parentElement.classList.add("is-active");
      });
    });

    function fillErrorMessageDivRegister(message) {
      document.getElementById("errorMsgRegister").innerText = message;
      document.getElementById("errorMsgRegister").style.display = "block";
    }

    function fillErrorMessageDivLogin(message) {
      document.getElementById("errorMsgLogin").innerText = message;
      document.getElementById("errorMsgLogin").style.display = "block";
    }

    $(document).ready(function () {
      $("input").keydown(function () {
        const errorMessageDivs = document.getElementsByClassName("error-message");
        for (let i = 0; i < errorMessageDivs.length; i++) {
          errorMessageDivs[i].style.display = "none";
        }
      });
      $("form.form-signup").submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
          type: "POST",
          url: "register.php",
          data: formData,
          success: function (response) {
            if (response == "email_exists") {
              fillErrorMessageDivRegister("Email already exists.");
            } else if (response == "success") {
              window.location.href = "home.php";
            }
            else if (response == "email_invalid") {
              fillErrorMessageDivRegister("Please enter a valid email address.");
            }
            else if (response == "password_mismatch") {
              fillErrorMessageDivRegister("Passwords do not match.");
            }
            else if (response == "password_short") {
              fillErrorMessageDivRegister("Password must be at least 6 characters long.");
            }
            else if (response == "password_long") {
              fillErrorMessageDivRegister("Password must be less than 15 characters long.");
            } else {
              fillErrorMessageDivRegister("Something went wrong, try again later.");
            }
          }
        });
      });

      $("form.form-login").submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
          type: "POST",
          url: "login.php",
          data: formData,
          success: function (response) {
            if (response == "email_invalid") {
              fillErrorMessageDivLogin("Please enter valid email.");
            } else if (response == "success") {
              window.location.href = "home.php";
            }
            else if (response == "noAccount") {
              fillErrorMessageDivLogin("No account with that email.");
            }
            else if (response == "password_invalid") {
              fillErrorMessageDivLogin("Passwords is incorrect.");
            } else {
              fillErrorMessageDivLogin("Something went wrong, try again later.");
            }
          }
        });
      });
    });
  </script>
</body>

</html>