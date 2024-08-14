<?
foreach($viewdata as $rvdata):
endforeach;
$headerrs = $viewrs;
$header = array_shift($headerrs);
$fld_periode = $header->periode;

#$days = date('t',strtotime($header->periode));
?>
<script>
var ovp = new Object();
var covp = null;

function hideParBox() {
	window.document.getElementById("parbox").style.visibility="hidden";
	};
function pgoto(page) {
	var pg = page;
	document.getElementById("currentpage").value= pg;
	document.forms["spar"].submit();
	};
function order(ind,sorting) {
	document.getElementById("order").value= ind;
	document.getElementById("sorting").value= sorting;
	document.forms["spar"].submit();
	};
function showParBox() {
	var lb = window.document.getElementById('parbox');
	var ls = lb;
	if (ls.style) { ls = ls.style; };

	var nx = window.document.body.scrollWidth>=0?window.document.body.scrollWidth:window.pageWidth;
	var ww = lb.scrollWidth>=0?lb.scrollWidth:lb.pageWidth;
	var wx = nx/2 - ww/2;

	var ny = window.document.body.scrollHeight>=0?window.document.body.scrollHeight:window.pageHeight;
	var wh = lb.scrollHeight>=0?lb.scrollHeight:lb.pageHeight;
	var wy = ny/2 - wh/2;

	var noPx = window.document.childNodes ? 'px' : 0;

	ls.left = wx+noPx;


	ls.visibility='visible';
	};
	</script>

<div id=parbox class=parbox>
<table class=parbox cellpadding=4 cellspacing=0>
	<tr class=parbox>
		<td align=left class=parbox>
			<b>Search Parameter</b>
			</td>
		<td align=right class=parbox>
			<a href=javascript:hideParBox()>Hide</a>
			</td>
		</tr>
	<tr>
		<td class=parboxsep colspan=2><?  include ("search_view.php"); ?></td>
		</tr>
	</table>
</div>
<?
echo "<form name='spar' id='spar' method='get' action='" . $rvdata->fld_viewnm . "'>";
if (isset($formfield)) {
foreach($formfield as $rff):
echo '<input type="hidden" name="' . $rff->fld_formfieldnm . '" value="' . $this->input->get($rff->fld_formfieldnm) . '">';
endforeach;
}
echo '<input type="hidden" name="currentpage" id="currentpage">';
echo '<input type="hidden" name="order" id="order" value="' . $order . '">';
echo '<input type="hidden" name="sorting" id="sorting" value="' . $sorting . '">';
echo "</form>";
?>

<form>


<table cellpadding="1" cellspacing="1" width="100%">

  <tr bgcolor="#CDCBBF" align="center">

    <td nowrap rowspan=2>No</td>

    <th nowrap rowspan=2>Month</th>

    <td nowrap rowspan=2>Periode</td>

    <td nowrap colspan = 2>CarCarrier</td>

    <td nowrap colspan = 2>Box</td>

    <td nowrap colspan = 2>Trailer</td>

    <td nowrap colspan = 3>Total</td>

  </tr>

​

  <tr bgcolor="#CDCBBF" align="center">

  <td>B</td>

  <td>R</td>

  <td>B</td>

  <td>R</td>

  <td>B</td>

  <td>R</td>

  <td>B</td>

  <td>R</td>

</tr>


 <?
$namaBulan = array("Januari","Februaru","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
    $periode = ($_GET['year'] == 0) ? date('Y') : $_GET['year'];
$orders = $this->db->query("select 
date_format(t0.fld_btdt,'%M') 'Month',
date_format(t0.fld_btdt,'%Y-%m-%d %H:%i:%s') 'WO Start',
date_format(t0.fld_btdtso,'%Y-%m-%d %H:%i:%s') 'WO Close',
date_format(t0.fld_btdt, '%Y-%m') 'Periode',

date_format(t0.fld_btdt,'%e') 'day',
TIME_FORMAT(timediff(t0.fld_btdtso, t0.fld_btdt), '%H') 'Diff',
t1.fld_bticd 'Vehicle Number',
t3.fld_tyvalcfg 'VehicleType'
from tbl_bth t0
left join tbl_bti t1 on t1.fld_btiid=t0.fld_baidc
left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btstat and t2.fld_tyid=2
left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btiflag and t3.fld_tyid=19

where
t0.fld_bttyid=4 
and t3.fld_tyvalcfg in (1,2,3)
and 
t0.fld_btdtso not in (0000-00-00)
and date_format(t0.fld_btdt,'%Y') = date_format(now(),'%Y')
#and TIME_FORMAT(timediff(t0.fld_btdtso, t0.fld_btdt), '%H') > 24
group by t0.fld_btdt")->result();
$month = date('n');
$year = date('Y');
$monthhs = date('M');
$periode = date('Y-m');
$now = date('Y-m-d');
$day = date('w');
$week_start = date('D, M d  Y', strtotime('-'.$day.' days'));
$week_end = date('D, M d  Y', strtotime('+'.(6-$day).' days'));
#for($x=1;$x<=31;$x++) {

for ($a=1; $a<=$month; ++$a) {
  $x = sprintf("%02s", $a);
$periodes = "$year";
$periode = "$year-$x";
$ttl_bT = 0;
$ttl_rT = 0;
$ttl_bC = 0;
$ttl_rC = 0;
$ttl_bB = 0;
${"BCC" . $periode} = 0;
 ${"RCC" . $periode} = 0;
 ${"Bbox" . $periode} = 0;
 ${"rBox" . $periode} = 0;
  ${"BTrailer" . $periode} = 0;
  ${"rTrailer" . $periode} = 0;
${"bTotal". $periode} = 0;
${"rTotal". $periode} = 0;
foreach ($orders as $rtruck) {
  if($rtruck->Periode == $periode) {
   if($rtruck->VehicleType == 1){
if($rtruck->Diff >= 24){
 ${"BTrailer" . $periode} =  ${"BTrailer" . $periode} + 1;  
   }
if($rtruck->Diff < 24 ){
${"rTrailer" . $periode} = ${"rTrailer" . $periode} + 1;
}
}
if($rtruck->VehicleType == 2 ){
	if($rtruck->Diff >= 24){
 ${"BCC" . $periode} =  ${"BCC" . $periode} + 1;
   }
if($rtruck->Diff < 24){
${"RCC" . $periode} = ${"RCC" . $periode} + 1;
}
}

if($rtruck->VehicleType == 3){
   if($rtruck->Diff >= 24){
${"Bbox" . $periode} = ${"Bbox" . $periode} + 1;
}
if($rtruck->Diff < 24){
${"rBox" . $periode} = ${"rBox" . $periode} + 1;
}
}
${"bTotal". $periode} = ${"BTrailer" . $periode} +  ${"BCC" . $periode} + ${"Bbox" . $periode};
${"rTotal". $periode} = ${"rTrailer" . $periode} +  ${"RCC" . $periode} + ${"rBox" . $periode};

}
}

  $no=$no+1;
  echo "<tr bgcolor=$bgcolor>";
  echo "<td width='20'>" .  $no . "</td>";
  echo "<td width='100'>" .$x. "</td>";
  echo "<td width='70'>$periodes</td>";
   
    echo "<td align='center' width='50'>" . number_format(${"BCC" . $periode},0,',','.') . "</td>";
   echo "<td align='center' width='50'>" . number_format(${"RCC" . $periode},0,',','.') . "</td>";
  echo "<td align='center' width='50'>" . number_format(${"Bbox" . $periode},0,',','.') . "</td>";
    echo "<td align='center' width='50'>" . number_format(${"rBox" . $periode},0,',','.') . "</td>";
 echo "<td align='center' width='50'>" . number_format(${"BTrailer" . $periode},0,',','.') . "</td>";
    echo "<td align='center' width='50'>" . number_format(${"rTrailer". $periode},0,',','.') . "</td>";
 echo "<td align='center' width='50'>" . number_format(${"bTotal" . $periode},0,',','.') . "</td>";
    echo "<td align='center' width='50'>" . number_format(${"rTotal" . $periode},0,',','.') . "</td>";
 
  
  echo "</tr>";
}


?>
</table>
<br>
</form>
