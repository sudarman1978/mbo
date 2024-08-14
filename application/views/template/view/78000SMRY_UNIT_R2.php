<?
foreach($viewdata as $rvdata):
endforeach;
$headerrs = $viewrs;
$header = array_shift($headerrs);
$fld_periode = $header->periode;
$rvdata->fld_viewnm='78000SMRY_UNIT_R';
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
<table cellpadding="1"  border ="1" width="100%">
  <tr bgcolor="#CDCBBF" align="center">
    <td nowrap rowspan =1>No</td>
    <td nowrap rowspan =1>Vehicle Type</td>
    <td nowrap rowspan =1>Periode</td>
    <td nowrap rowspan =1>Service Ringan</td>
    <td nowrap rowspan =1>Service Berat</td>
    <td nowrap rowspan =1>Total</td>
    </tr>

<?    
foreach ($viewrs as $rviewrs) {
$test = $rviewrs->periode;

} 
        $loc = $this->session->userdata('location');
        $periode = isset($_GET['periode']) ? strval($_GET['periode']) : date("Y-m");
$year = isset($_GET['year']) ? strval($_GET['year']) : date("Y");
 $month = date('n');
        $no=0;
        $import = 0;
        $export = 0;
        $local = 0;
$data = $this->db->query("select
t0.fld_btid 'crud',
t0.fld_btno 'WO Number',
date_format(t0.fld_btdtso,'%Y-%m') 'Periode',
date_format(t0.fld_btdt,'%Y-%m-%d %H:%i:%s') 'WO Start',
date_format(t0.fld_btdt,'%H:%i:%s') 'date',
date_format(t0.fld_btdtso,'%Y-%m-%d %H:%i:%s') 'WO Close',
date_format(t0.fld_btdtso,'%H:%i:%s') 'Close',
COUNT(1) 'qty',
TIME_FORMAT(timediff(t0.fld_btdtso, t0.fld_btdt), '%H') 'Diff',
t1.fld_bticd 'VehicleNumber',
t3.fld_tyvalnm 'VehicleType'
from tbl_bth t0
left join tbl_bti t1 on t1.fld_btiid=t0.fld_baidc
left join tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btstat and t2.fld_tyid=2
left join tbl_tyval t3 on t3.fld_tyvalcd=t1.fld_btiflag and t3.fld_tyid=19

where
t0.fld_bttyid=4
and
t3.fld_tyvalnm != ' '
and
t0.fld_btdtso not in (0000-00-00)
and Timestampdiff(Year,t0.fld_btdtso,now()) < 2
and date_format(t0.fld_btdtso,'%Y-%m') = '$periode'
and t0.fld_btloc = $loc
AND TIME_FORMAT(timediff(t0.fld_btdtso, t0.fld_btdt), '%H') >= 24
group by t3.fld_tyvalnm")->result();       
foreach ($viewrs as $rviewrs) {
             
 $no=$no+1;
$qty1 = 0;
$qty2 = 0;
foreach($data as $rdata){
if($rdata->VehicleType == $rviewrs->VehicleType ){
$qty2 = $qty2 + $rdata->qty;
}
}
$ttl_qty = 0;
$ttl_qty = $rviewrs->qty1 + $qty2;
                if ($no % 2 == 1)
                {
                        $bgcolor="#FFFFFF";
                }
else
                {
                        $bgcolor="#F5F5F5";
                }

                if (1==1 )
                {

      echo "<tr bgcolor=$bgcolor>";
      echo "<td>" .$no."</td>";
                        echo "<td>" .  $rviewrs->VehicleType . "<br></td>";
                        echo "<td>" . $rviewrs->Periode ."</td>";
                        echo "<td>" .  $rviewrs->qty1. "</td>";
                        echo "<td> " . $qty2."</td>";
                        echo "<td>" .  $ttl_qty. "</td>";
                        #echo "<td align='right'>" . number_format($balance,2,',','.') . "</td>";

                        echo "</tr>";
    }
    $ttl_qty1 = $ttl_qty1 + $rviewrs->qty1;
    $ttl_qty2 = $ttl_qty2 + $qty2;
    $ttl_avg  = $ttl_avg + $ttl_qty;

  }
                        echo "<tr bgcolor='green'>";
                        echo "<td colspan=3 align = 'left'>Total</td>";
                        echo "<td align='center'>" . number_format($ttl_qty1,0,'.',',') . "</td>";
                        echo "<td align='center'>" . number_format($ttl_qty2,0,'.',',') . "</td>";
                        echo "<td align='center'>" . number_format($ttl_avg,0,'.',',') . "</td>";
                        echo "</tr>";

?>
</table>
<br>
</form>
                                       
                                                       
