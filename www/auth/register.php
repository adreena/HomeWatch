<?php
  ///
	/// User (resident) registration page.
  ///
  /// The registration form posts back to itself so that it can keep around the posted information
  /// in the event there is an error.
  ///

  require_once 'User.php';

  // Error codes
  // TODO: these should be combined
  $reg_err = 0;
  $reg_code = 0;

  // Set any existing form variables.
  $username = isset($_POST['username']) ? $_POST['username'] : "";

  // Handle any outstanding registration requests
  if (!empty($_POST['submit'])) {
    $password = $_POST['password'];
    $conf_password = $_POST['conf-password'];

    // Check that the passwords match
    if ($password != $conf_password) {
      $reg_err = 1;
    }

    // Try to register the new user
    $reg_code = User::RegisterNewUser($username, $password);
    if ($reg_code == User::$REG_SUCCESS) {
      User::LogoutSessionUser(); // Logout the current user, if any
      header('Location: login.php'); // Indicate that the new user should login
      exit();
    }

    // There was an error registering the user
    $reg_err = 1;
  }
?>

<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/default.css" />
  <script src="js/gen_validatorv4.js" type="text/javascript"></script>
</head>

<body>

  <div id="content-div" >

    <form id="register-form" action="register.php" method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" maxLength="30" autofocus="autofocus" value="<?php echo $username; ?>">
      <div id="register-form_username_errorloc" class="error-string"></div>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password">
      <div id="register-form_password_errorloc" class="error-string"></div>

      <label for="conf-password">Confirm Password:</label>
      <input type="password" id="conf-password" name="conf-password">
      <div id="register-form_conf-password_errorloc" class="error-string"></div>

      <input type="submit" id="submit" name="submit" value="Register">
      <div class="error-string"><?php if ($reg_err != 0) echo "Error registering user. (Error Code: $reg_code)"; ?></div>
    </form>

    <div><a href="login.php">Login</a></div>

    <script type="text/javascript">
      var v = new Validator("register-form");
      v.EnableOnPageErrorDisplay();
      v.EnableMsgsTogether();
      v.addValidation("username", "req", "Please enter a username.");
      v.addValidation("username", "maxlen=30", "Max length for username is 30.");
      v.addValidation("password", "req", "Please enter a password.");
      v.addValidation("conf-password", "req", "Please confirm your password.");
      v.addValidation("conf-password","eqelmnt=password","Passwords do not match.");
    </script>

  </div>

</body>

</html>
