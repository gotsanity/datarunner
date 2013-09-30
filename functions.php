<?php

function debug($var, $msg = null) {
	print '<pre>';
	if (!is_null($msg)) {
		echo '<div>------------------'.$msg.'---------------------</div>';
	}
	print_r($var);
	print '</pre>';
}

?>
