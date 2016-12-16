<?php
include ('config.php');

$freq = (exec('rigctl  -m 2 -r '.HOST.' f'))/1000;
if ($freq !=0) {
$mod = exec('rigctl  -m 2 -r '.HOST.' m | head -n 1');
$signals = exec('rigctl  -m 2 -r '.HOST.' l STRENGTH');

if (exec('rigctl  -m 2 -r '.HOST.' t') ==0) {
$ptt = '<font color="green">RX</font>';
$tx=0;
} else {
$ptt = '<font color="red">TX</font>';
$tx=1;
}

/*if ($freq < 30000 ) {
$signals=$signals-20;
}*/
//echo $freq." ".$mod."<br>";
echo number_format($freq,2,".",".");
echo " ".$mod."<br>";
echo $signals;
echo "<meter value='$signals' min='-60' max='50' low='-25' high='0' optimum=50>$signals</meter>";
if ($tx==1) {
$lev="  ))) TX (((";
} else {
$lev=$signals." ".str_repeat("|",($signals+54)/6 );
}
?>
<script>
document.title = "Webrig <?php echo $lev; ?>";
</script>
<?php
echo $ptt;
} else {
echo "RIG is OFF";
}
?>
<style>

</style>
