<?php
  require_once "User.php";

  User::LogoutSessionUser();

  header("location:login.php");
?>
