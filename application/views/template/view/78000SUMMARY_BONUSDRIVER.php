<?
foreach($viewdata as $rvdata):
endforeach;
$fld_btdt=isset($_GET['fld_btdt']) ? strval($_GET['fld_btdt']) : date('Y-m-d');
$fld_btnm=isset($_GET['fld_btnm']) ? strval($_GET['fld_btnm']) : '1';
$fld_loc=isset($_GET['fld_loc']) ? strval($_GET['fld_loc']) : '0';
if ($fld_btdt==''){ $fld_btdt=date('Y-m-d'); }
$rvdata->fld_viewnm = '78000SUMMARY_BONUSDRIVER';
//$date = date('Y-m-d');
$dtsa = ($_GET['dtsa'] == '') ? date('Y-m-01') : $_GET['dtsa'];
$dtso = ($_GET['dtso'] == '') ? date('Y-m-31') : $_GET['dtso'];
$group = $this->session->userdata('group');
$group_add = $this->session->userdata('group_add');
$fld_empjob = ($_GET['fld_empjob'] == '') ? '%' : $_GET['fld_empjob'];
$location = $this->session->userdata('location');
$fld_empnm = ($_GET['fld_empnm'] == '') ? '%' : $_GET['fld_empnm']; ;
$sql="
select
driver,
job,
periode,
fld_empjob,
fld_empnm,
fld_btdt,
sum(qtydaily) qtydaily,
sum(qtyweekly) qtyweekly,
sum(qtymonthly) qtymonthly,
sum(daily) daily,
sum(weekly) weekly,
sum(monthly) monthly
from(
select
t2.fld_empnm 'driver',
t3.fld_btinm 'job',
t2.fld_empjob 'fld_empjob',
t2.fld_empnm 'fld_empnm',
t1.fld_btdt 'fld_btdt',
date_format(t1.fld_btdt, '%Y') 'periode',
sum(t0.fld_bonusamt>1) 'qtydaily',
0 'qtyweekly',
0 'qtymonthly',
sum(t0.fld_bonusamt) 'daily',
0 'weekly',
0 'monthly' 
FROM `tbl_bonus` t0
left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
left join hris.tbl_truck_driver t2 on t2.fld_empid=t0.fld_empid
left join hris.tbl_bti t3 on t3.fld_btiid=t2.fld_empjob
where
t1.fld_bttyid=74
and t1.fld_btflag=4
and
if ('$fld_empjob' = '%',1,t2.fld_empjob like '$fld_empjob')
and
if ('$fld_empnm' = '%',1,t2.fld_empnm like '$fld_empnm')
and
date_format(t1.fld_btdt,'%Y-%m-%d') between '$dtsa' and '$dtso'
group by t0.fld_empid
union
select
t2.fld_empnm 'driver',
t3.fld_btinm 'job',
t2.fld_empjob 'fld_empjob',
t2.fld_empnm 'fld_empnm',
t1.fld_btdt 'fld_btdt',
date_format(t1.fld_btdt, '%Y') 'periode',
0 'qtydaily',
sum(t0.fld_bonusamt>1) 'qtyweekly',
0 'qtymonthly',
0 'daily',
sum(t0.fld_bonusamt) 'weekly',
0 'monthly'
FROM `tbl_bonus` t0
left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
left join hris.tbl_truck_driver t2 on t2.fld_empid=t0.fld_empid
left join hris.tbl_bti t3 on t3.fld_btiid=t2.fld_empjob
where
t1.fld_bttyid=74
and t1.fld_btflag=1
and
if ('$fld_empjob' = '%',1,t2.fld_empjob like '$fld_empjob')
and
if ('$fld_empnm' = '%',1,t2.fld_empnm like '$fld_empnm')
and
date_format(t1.fld_btdt,'%Y-%m-%d') between '$dtsa' and '$dtso'
group by t0.fld_empid
union
select
t2.fld_empnm 'driver',
t3.fld_btinm 'job',
t2.fld_empjob 'fld_empjob',
t2.fld_empnm 'fld_empnm',
t1.fld_btdt 'fld_btdt',
date_format(t1.fld_btdt, '%Y') 'periode',
0 'qtydaily',
0 'qtyweekly',
sum(t0.fld_bonusamt>1) 'qtymonthly',
0 'daily',
0 'weekly',
sum(t0.fld_bonusamt) 'monthly'
FROM `tbl_bonus` t0
left join tbl_bth t1 on t1.fld_btid=t0.fld_postingid
left join hris.tbl_truck_driver t2 on t2.fld_empid=t0.fld_empid
left join hris.tbl_bti t3 on t3.fld_btiid=t2.fld_empjob
where
t1.fld_bttyid=74
and t1.fld_btflag=2
and
if ('$fld_empjob' = '%',1,t2.fld_empjob like '$fld_empjob')
and
if ('$fld_empnm' = '%',1,t2.fld_empnm like '$fld_empnm')
and
date_format(t1.fld_btdt,'%Y-%m-%d') between '$dtsa' and '$dtso'
group by t0.fld_empid
order by monthly desc) a
where
a.periode >= '2018'
and
if ('$fld_empjob' = '%',1,a.fld_empjob like '$fld_empjob')
and
if ('$fld_empnm' = '%',1,a.fld_empnm like '$fld_empnm')
and
date_format(a.fld_btdt,'%Y-%m-%d') between '$dtsa' and '$dtso'
group by a.driver
order by a.monthly desc
";
$query=$this->db->query($sql);
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
<table cellpadding="1" cellspacing="1" width="100%" class="listing">
  <tr bgcolor="#CDCBBF" align="center">
    <td rowspan=2>Driver</td>
    <td rowspan=2>Job Title</td>
    <td rowspan=2>Periode</td>
    <td colspan=3>Bonus Qty</td>
    <td colspan=3>Bonus Amount</td>
  </tr>
    <tr bgcolor="#CDCBBF" align="center">
    <td>Daily</td>
    <td>Weekly</td>
    <td>Monthly</td>
    <td>Daily</td>
    <td>Weekly</td>
    <td>Monthly</td>
  </tr>
 <? foreach ($query->result() as $rviewrs) {
        $totalqtydaily=$totalqtydaily+$rviewrs->qtydaily;
        $totalqtyweekly=$totalqtyweekly+$rviewrs->qtyweekly;
        $totalqtymonthly=$totalqtymonthly+$rviewrs->qtymonthly; 
	$totaldaily=$totaldaily+$rviewrs->daily;  
	$totalweekly=$totalweekly+$rviewrs->weekly;
	$totalmonthly=$totalmonthly+$rviewrs->monthly;  
  ?>
  <tbody>
  <tr bgcolor="#DDDDDD">
    <td><? echo $rviewrs->driver ;?></td>
    <td><? echo $rviewrs->job ;?></td>
    <td><? echo $rviewrs->periode ;?></td>
    <td align=right><? echo $rviewrs->qtydaily ;?></td>
    <td align=right><? echo $rviewrs->qtyweekly ;?></td>
    <td align=right><? echo $rviewrs->qtymonthly ;?></td>
    <td align=right><? echo $rviewrs->daily ;?></td>
    <td align=right><? echo $rviewrs->weekly ;?></td>
    <td align=right><? echo $rviewrs->monthly ;?></td>
  </tr>
  </tbody>
  <? } ?>
  <tbody>
  <tr bgcolor="#CDCBBF">
    <td colspan=3>Total</td>
    <td align=right><? echo $totalqtydaily ;?></td>
    <td align=right><? echo $totalqtyweekly ;?></td>
    <td align=right><? echo $totalqtymonthly ; ?></td>
    <td align=right><? echo $totaldaily ;?></td>
    <td align=right><? echo $totalweekly ;?></td>
    <td align=right><? echo $totalmonthly ; ?></td>
  </tr>
 </tbody>
</table>
</form>
<br>
