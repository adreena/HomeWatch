<?php

if (!defined('CUSTOM_LOG')) {
	print "CustomLogging.php enabled but no custom log file defined!";
	die;
}

$loggedErrorHeader = false;
function customError($errno, $errstr,$error_file,$error_line, $context) {
	global $loggedErrorHeader;
	if ($errno == E_DEPRECATED || $errno == E_STRICT)
		return;

	$ignore = array(
			array(realpath(UASMARTHOME_ROOT_DIR) . "/lib/UASmartHome/Auth/User.php", 123),
			array(realpath(UASMARTHOME_ROOT_DIR) . "/lib/UASmartHome/Auth/User.php", 141),
	);

	foreach ($ignore as $ign) {
		if (array(realpath($error_file), $error_line) == $ign)
			return;
	}
	

	if (!$loggedErrorHeader) {
		date_default_timezone_set("America/Edmonton");
		$now = date(DATE_RFC2822);
		$req = $_SERVER['REQUEST_URI'];
		$method = $_SERVER['REQUEST_METHOD'];
		$user = "not logged in";
		$ip = $_SERVER['REMOTE_ADDR'];
		if (isset($_SESSION['user']))
			$user = $_SESSION['user']->getUsername();
		$post = "";
		if ($method == 'POST') {
			$post = "POST: ";
			foreach ($_POST as $k => $v) {
				if (strpos($k, 'password') !== FALSE)
					$v = "<hidden>";
				$post .= "$k=$v&";
			}
		}
			 
		file_put_contents(CUSTOM_LOG, 
				"\n\n\n====================================================================================\n" . 
				"[$now] method=$method user=$user IP=$ip\n$req\n$post\n", FILE_APPEND);
		$loggedErrorHeader = true;
	}

	file_put_contents(CUSTOM_LOG, "[$errno] $errstr ($error_file:$error_line)\n", FILE_APPEND);
}
set_error_handler("customError");
?>
