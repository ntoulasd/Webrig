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

$row = 1;
if (($handle = fopen("memory.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

	if ($mem==$data[0]) {
	//Freq
	$run = exec('rigctl  -m 2 -r '.HOST.' F '.$data[2]*1000000);
	//Modulation
	If ($data[10]=="NFM") {$data[10]="FM";}
	$run = exec('rigctl  -m 2 -r '.HOST.' M '.$data[10].' 0');
	//Shift
	$run = exec('rigctl  -m 2 -r '.HOST.' O '.$data[4]*1000000);
	//-+Shift
	if ($data[3]) {$run = exec('rigctl  -m 2 -r '.HOST.' R '.$data[3]);}
	//Tone
	if ($data[5]=="Tone") {$run = exec('rigctl  -m 2 -r '.HOST.' C '.$data[6]);}
	}
	$row++;
    }
    fclose($handle);
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
