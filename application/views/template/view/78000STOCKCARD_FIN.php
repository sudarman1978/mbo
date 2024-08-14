<?
foreach($viewdata as $rvdata):
endforeach;
$headerrs = $viewrs;
$header = array_shift($headerrs);
$fld_periode = $header->periode;
$rvdata->fld_viewnm='78000STOCK_PART_FINANCE';
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
    <td rowspan=2 nowrap>Item Code</td>
    <td rowspan=2 nowrap>Item Name</td>
    <td colspan=2 nowrap>Balance</td>
    <td colspan=2 nowrap>Inbound</td>
    <td colspan=2 nowrap>Outbound</td>
    <td colspan=2 nowrap>Correction</td>
    <td colspan=2 nowrap>Current Stock</td>
   </tr>
   <tr bgcolor="#CDCBBF" align="center">
     <td>Qty</td>
     <td>Price</td>
     <td>Qty</td>
     <td>Price</td>
     <td>Qty</td>
     <td>Price</td>
    <td>Qty</td>
     <td>Price</td>
     <td>Qty</td>
     <td>Price</td>
   </tr>
  
<?
	$fld_bticd=$_GET['fld_bticd'];	
	$periode = isset($_GET['periode']) ? strval($_GET['periode']) : date("Y-m");
	$sql="select Code, Name, fld_btiid,
	sum(case
		when date_format(date,'%Y-%m')<'$periode' then inbound+(-1*Outbound)+correction
		else 0
	end) saldo,
	sum(case
		when date_format(date,'%Y-%m')='$periode' then inbound
		else 0
	end) Inbound, 
	sum(case
		when date_format(date,'%Y-%m')='$periode' then Outbound
		else 0
	end) Outbound,
	sum(case
		when date_format(date,'%Y-%m')='$periode' then correction
		else 0
	end) correction,
	sum(inbound)+sum(-1*Outbound)+sum(correction) stock
	from(
	select 
	t1.fld_btiid,
	t0.fld_btdt date,
	t0.fld_btno,
	t2.fld_bticd 'Code',
	t2.fld_btinm 'Name',
	sum(t1.fld_btqty01) 'inbound',
	0 'OutBound',
	0 correction
	from 
	tbl_bth t0 
	left join tbl_btd_purchase t1 on t1.fld_btidp=t0.fld_btid 
	left join tbl_bti t2 on t2.fld_btiid=t1.fld_btiid
	where t0.fld_bttyid in (3,45) 
	and t0.fld_btstat=3
	and if ('$fld_bticd'!='',t2.fld_bticd='$fld_bticd',1=1)
	and date_format(t0.fld_btdt,'%Y-%m')<='$periode'
	group by t2.fld_bticd, date_format(t0.fld_btdt,'%Y-%m-%d'), t0.fld_btno
	union all
	select 
	t1.fld_btiid,
	t0.fld_btdt date,
	t0.fld_btno,
	t2.fld_bticd 'Code',
	t2.fld_btinm 'Name',
	0 inbound,
	sum(case
		when t0.fld_bttyid=6 then t1.fld_btqty01
		else 0
	end) 'OutBound',
	sum(case
		when t0.fld_bttyid=12 then t1.fld_btqty01
		else 0
	end) 'correction'
	from 
	tbl_bth t0 
	left join tbl_btd_wo_part t1 on t1.fld_btidp=t0.fld_btid 
	left join tbl_bti t2 on t2.fld_btiid=t1.fld_btiid
	where t0.fld_bttyid in (6,12) 
	and t0.fld_btstat=3
	and if ('$fld_bticd'!='',t2.fld_bticd='$fld_bticd',1=1)
	and date_format(t0.fld_btdt,'%Y-%m')<='$periode'
	group by t2.fld_bticd, date_format(t0.fld_btdt,'%Y-%m-%d'), t0.fld_btno
	)t
	-- where if ('$fld_bticd'!='',Code='$fld_bticd',1=1)
	group by Code";
	$no=0;
	$query=$this->db->query($sql);
	foreach ($query->result() as $rviewrs) {
          $price = $this->db->query("select t0.fld_btuamt01 'price' from tbl_btd_purchase t0
                                     left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                     where 
                                     t1.fld_bttyid = 2
                                     and t0.fld_btiid  = $rviewrs->fld_btiid
                                     and t1.fld_btdt < '2017-01-01 00:00:00'
                                     order by t0.fld_btid desc
                                     limit 1");
          $price = $price->row();
		$no=$no+1;
		if ($no==1)
		{
			$saldo=$rviewrs->stock;
			$stock=$saldo;
		}
		else {
			$saldo=$stock;
			$stock=$saldo+$rviewrs->stock;
		}
		
		if ($no % 2 == 1)
		{
			$bgcolor="#FFFFFF";
		}
		else
		{
			$bgcolor="#F5F5F5";
		} 
		//if ($rviewrs->Inbound!=0 && $rviewrs->Outbound!=0 && $rviewrs->Outbound!=0)
		//{
			echo "<tr bgcolor=$bgcolor>";
			echo "<td>" .  $rviewrs->Code . "</td>";
			echo "<td>" .  $rviewrs->Name . "</td>";
			echo "<td align='right'>" .  $rviewrs->saldo . "</td>";
                        echo "<td align='right'>" .  number_format(($rviewrs->saldo * $price->price),2,',','.')  . "</td>";
			echo "<td align='right'>" . $rviewrs->Inbound . "</td>";
                         echo "<td align='right'>" . 0 . "</td>";
			echo "<td align='right'>" . $rviewrs->Outbound	 . "</td>";
                         echo "<td align='right'>" . 0 . "</td>";
			echo "<td align='right'>" . $rviewrs->correction . "</td>";
                         echo "<td align='right'>" . 0 . "</td>";
			echo "<td align='right'>" . $rviewrs->stock . "</td>";
                         echo "<td align='right'>" . 0 . "</td>";
			echo "</tr>";
		//}
	}
?>
</table>
<br>
</form>
