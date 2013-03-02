<?php
  ///
  /// The login page.
  ///
  /// The login form posts back to itself so that it can keep around the posted information in the
  /// event that there is an error.
  /// (See http://stackoverflow.com/questions/4281900/php-header-redirect-with-post-variables)
  ///
  
  require_once __DIR__ .  "/../vendor/autoload.php";

  use \UASmartHome\Auth\User;

  $login_error = 0;

  // Set any existing form variables.
  $username = isset($_POST['username']) ? $_POST['username'] : "";

  // Handle any outstanding login requests
  if (!empty($_POST['submit'])) {
    $password = $_POST['password'];

    if (!User::LoginUser($username, $password)) {
      $login_error = 1;
    }
  }

  // If the user is logged in, redirect them to their home page.
  if (User::GetSessionUser()) {
    header('Location: ../index.php');
    exit();
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

    <form id="login-form" name="login-form" action="login.php" method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" autofocus="autofocus" value="<?php echo $username; ?>">
      <div id="login-form_username_errorloc" class="error-string"></div>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password">
      <div id="login-form_password_errorloc" class="error-string"></div>

      <input type="submit" id="submit" name="submit" value="Sign In">
      <div class="error-string"><?php if ($login_error != 0) echo "Incorrect username or password"; ?></div>
    </form>

    <script type="text/javascript">
      var v = new Validator("login-form");
      v.EnableOnPageErrorDisplay();
      v.EnableMsgsTogether();
      v.addValidation("username", "req", "Please enter your username.");
      v.addValidation("username", "maxlen=30", "Max length for username is 30.");
      v.addValidation("password", "req", "Please enter your password.");
    </script>

    <div><a href="register.php">Register</a></div>

  </div>

</body>

