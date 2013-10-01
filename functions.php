<?php

function debug($var, $msg = null) {
	print '<pre>';
	if (!is_null($msg)) {
		echo '<div>------------------'.$msg.'---------------------</div>';
	}
	print_r($var);
	print '</pre>';
}

function is_admin() {
	if (!isset($_SESSION['user']['access'])) {
		return false;
	} else {
		if ($_SESSION['user']['access'] >= 1000) {
			return true;
		} else {
			return false;
		}
	}
}

?>
