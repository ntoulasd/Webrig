<?php
include ('config.php');

if ((exec('rigctl  -m 2 -r '.HOST.' t'))==0) {
$ptt = exec('rigctl  -m 2 -r '.HOST.' T 1');
	if ((exec('rigctl  -m 2 -r '.HOST.' t'))==1) {
	echo "<strong><font color='yellow'>PTT ON</font></strong>";
	} else {
	echo "PTT error";
	}
} else {
$ptt = exec('rigctl  -m 2 -r '.HOST.' T 0');
echo "PTT";
}
?>
