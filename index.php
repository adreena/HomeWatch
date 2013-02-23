<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/default.css" />
</head>

<body>
  <?php require 'header.php'; ?>

  <div id='content-div'>
    <h1><?php echo "Welcome " . $_SESSION['user']->get_username(); ?></h1>
  </div>

</body>
