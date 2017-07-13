<?php
include ('config.php');

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//Set rig
$freq = test_input($_GET["freq"]);
$mod = test_input($_GET["mod"]);
$mem = test_input($_GET["mem"]);
$move = test_input($_GET["move"]);

if ($mem) {

switch ($mem) {

	case M1: //RU90
	$run = exec('rigctl  -m 2 -r '.HOST.' F 439150000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M FM 0');
	$run = exec('rigctl  -m 2 -r '.HOST.' O 7600000');
	$run = exec('rigctl  -m 2 -r '.HOST.' R -');
	$run = exec('rigctl  -m 2 -r '.HOST.' C 825');
	break;
	case M2: //RU72
	$run = exec('rigctl  -m 2 -r '.HOST.' F 438700000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M FM 0');
	$run = exec('rigctl  -m 2 -r '.HOST.' O 7600000');
	$run = exec('rigctl  -m 2 -r '.HOST.' R -');
	$run = exec('rigctl  -m 2 -r '.HOST.' C 770');
	break;
	case M3: //Radio
	$run = exec('rigctl  -m 2 -r '.HOST.' F 91600000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M WFM 0');
	break;
	case M4: //7185000
	$run = exec('rigctl  -m 2 -r '.HOST.' F 7185000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M LSB 0');
	break;
	case M5: //Katerini
	$run = exec('rigctl  -m 2 -r '.HOST.' F 144700000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M FM 0');
	break;
	case M6: //SOTA
	$run = exec('rigctl  -m 2 -r '.HOST.' F 145375000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M FM 0');
	break;
	case M7: //ISS
	$run = exec('rigctl  -m 2 -r '.HOST.' F 145825000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M FM 0');
	break;
	case M8: //R0
	$run = exec('rigctl  -m 2 -r '.HOST.' F 145600000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M FM 0');
	$run = exec('rigctl  -m 2 -r '.HOST.' O 600000');
	$run = exec('rigctl  -m 2 -r '.HOST.' R -');
	$run = exec('rigctl  -m 2 -r '.HOST.' C 825');
	break;
	case M9: //R5
	$run = exec('rigctl  -m 2 -r '.HOST.' F 145725000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M FM 0');
	$run = exec('rigctl  -m 2 -r '.HOST.' O 600000');
	$run = exec('rigctl  -m 2 -r '.HOST.' R -');
	$run = exec('rigctl  -m 2 -r '.HOST.' C 885');
	break;
	case M10: //RU-92
	$run = exec('rigctl  -m 2 -r '.HOST.' F 439200000');
	$run = exec('rigctl  -m 2 -r '.HOST.' M FM 0');
	$run = exec('rigctl  -m 2 -r '.HOST.' O 7600000');
	$run = exec('rigctl  -m 2 -r '.HOST.' R -');
	$run = exec('rigctl  -m 2 -r '.HOST.' C 797');
	break;
}

} else {
if ($freq) {
$run = exec('rigctl  -m 2 -r '.HOST.' F '.$freq);
}

if ($mod) {
$run = exec('rigctl  -m 2 -r '.HOST.' M '.$mod." 0");
}

if (!empty($move)) {
$freq = (exec('rigctl  -m 2 -r '.HOST.' f'));
$freq=$freq+($move*1000);
$run = exec('rigctl  -m 2 -r '.HOST.' F '.$freq);
}


}

echo "SET";
?>
