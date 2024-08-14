<?
foreach($viewdata as $rvdata):
endforeach;
$headerrs = $viewrs;
$header = array_shift($headerrs);
$fld_periode = $header->periode;
$rvdata->fld_viewnm='78000INSTALLMENT_DRIVER';
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
	<td nowrap>No</td>
    <td nowrap>Driver Name</td>
    <td nowrap>Driver ID</td>
    <td nowrap>Emp ID</td>
    <td nowrap>Driver Status</td>
    <td nowrap>Periode</td>
    <td nowrap>NIP</td>
    <td nowrap>Job Role</td>
    <td nowrap>Beginning Balance</td>
    <td nowrap width='10%'>Debit</td>
    <td nowrap width='10%'>Credit</td>
    <td nowrap width='10%'>Balance</td>

   </tr>
   </tr>
<?
	$fld_bticd=$_GET['fld_bticd'];
	$dtsa = isset($_GET['dtsa']) ? strval($_GET['dtsa']) : date("Y-m-01");
        $dtsa2 = isset($_GET['dtsa']) ? strval($_GET['dtsa']) : date("Y-m");
        $dtso = isset($_GET['dtso']) ? strval($_GET['dtso']) : date("Y-m-31");
        $loc = $this->session->userdata('location');
        $payTp = ($_GET['pay'] == '') ? '%' : $_GET['pay'];
        if ($_GET['dtsa'] == "") {
          $dtsa =  date("Y-m-01");
        }
        if ($_GET['dtso'] == "") {
          $dtso =  date("Y-m-31");
        }

	    if ($payTp == 2)
		{
			$payType = 'Transfer';
		}else
		{
			$payType = 'Cash';
		}
	  $no=0;
	 $dtsa1= date('Y-m-d', strtotime('-1 days', strtotime($dtsa)));
       ##hutang
         /*$debt =$this->db->query("select
                                  t0.fld_driverid,
                                  t0.fld_btamt01 'debt',
                                  date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
                                  t0.fld_empid,
                                  t0.fld_btamt01
                                  from
                                  tbl_btd_driver_loan t0
                                  left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp and t1.fld_bttyid = 120
                                  where
                                  t0.fld_btflag in (1,8) and
                                  t1.fld_btstat = 3
                                  UNION
                                  select
                                  t0.fld_driverid,
                                  t0.fld_btamt01'debt',
                                  date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
                                  t0.fld_empid,
                                  t0.fld_btamt01
                                  from
                                  tbl_btd_accident t0
                                  left join tbl_bth t1 on t1.fld_btid =t0.fld_btidp
                                  where
                                  t1.fld_bttyid =32
                                  and t1.fld_btstat != 5
                                  and t0.fld_btdt > '2019-01-01'
                                  UNION
                                  select
                                  t0.fld_driverid,
                                  t0.fld_btamt 'debt',
                                  date_format(t0.fld_btdtsa,'%Y-%m-%d') 'date',
                                  t0.fld_btp01 'fld_empid',
                                  t0.fld_btamt 'fld_btamt01'
                                  from
                                  tbl_btd_tilang t0
                                  where
                                  t0.fld_btcharged = 3

                                  ");
     ## bayar hutang
         $credit =$this->db->query("select
                                    if(t1.fld_bttyid in(47,48,109,53),t0.fld_btamt01*-1,0) 'credit',
                                    date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
                                    t0.fld_btamt01,
                                    t0.fld_empid,
                                    t0.fld_driverid,
                                    t1.fld_btno
                                    from
                                    tbl_btd_finance t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                    where
                                    t0.fld_coaid in(1068)
                                    and t1.fld_bttyid in (47,109,110,53,48,50)
                                    and t1.fld_btdt > '2018-06-24'
                                    and t1.fld_btnoalt like '%PDC%'
                                    UNION
                                    select
                                    t0.fld_btamt01 'credit',
                                    date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
                                    t0.fld_btamt01,
                                    t0.fld_empid,
                                    t0.fld_driverid,
                                    t1.fld_btno
                                    from
                                    tbl_btd_driver_loan t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp and t1.fld_bttyid = 120
                                    where
                                    t0.fld_btflag =2 and
                                    t1.fld_btstat = 3 and
                                    t0.fld_btdt > '2017-01-01'
                                    UNION
                                    select
                                    t0.fld_btamt01 'credit',
                                    date_format(t1.fld_btdt,'%Y-%m-%d') 'date',
                                    t0.fld_btamt01,
                                    t0.fld_empid,
                                    t0.fld_driverid,
                                    t1.fld_btno
                                    from
                                    tbl_btd_driver_loan t0
                                    left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp and t1.fld_bttyid = 120
                                    where
                                    t0.fld_btflag = 7 and
                                    t1.fld_btstat = 3 and
                                    t0.fld_btdt > '2017-01-01'
                                    ");
     ##saldo awal
         $balc = $this->db->query("select
                                   t0.fld_empid,
                                   sum(t0.fld_btamt01)'begin',
                                   t0.fld_btdt,
                                   t0.fld_driverid
                                   from tbl_btd_driver_loan t0
                                   left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                   where
                                   t0.fld_btflag = 3
                                   and t1.fld_bttyid = 120
                                   and t1.fld_btp01 =3
                                   and t1.fld_btflag = 1
                                   and t1.fld_btstat =3
                                   group by t0.fld_driverid ");
         */
         ## data hutang 
         $debt = $this->db->query("select res.fld_driverid,sum(res.amount) as amount,date from 
                                   (
					select 
                                   t0.fld_empid,
                                   sum(t0.fld_btamt01)'amount',
                                   date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
                                   t0.fld_driverid
                                   from tbl_btd_driver_loan t0
                                   left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp 
                                   where
                                   t0.fld_btflag = 8 
                                   and t1.fld_bttyid = 120 
                                   and t1.fld_btp01 =8 
                                   and t1.fld_btflag = 1 
                                   and t1.fld_btstat =3                           
                                   group by t0.fld_driverid )
                                   res
                                   group by res.fld_driverid");
         ##bayar cicilan
         $credit = $this->db->query("select res.fld_driverid,sum(res.amount) as amount,date from 
                                   (
					select t0.fld_empid, t0.fld_driverid ,t1.fld_btamt01 'amount',t2.fld_btno,
                                    date_format(t2.fld_btdt,'%Y-%m-%d')'date'
                                    from tbl_btd_driver_insurance  t0 
                                    left join tbl_btd_driver_insurance t1 on t1.fld_btreffid = t0.fld_btid
                                    left join tbl_bth t2 on t2.fld_btid = t0.fld_btidp
                                    where 
                                    t1.fld_btflag = 5
                                    and t2.fld_bttyid = 22
                                    union
                                    select t0.fld_empid, t0.fld_driverid ,t0.fld_btamt01 'amount',t2.fld_btno,
                                    date_format(t2.fld_btdt,'%Y-%m-%d')'date'
                                    from tbl_btd_driver_insurance  t0 
                                    #left join tbl_btd_driver_insurance t1 on t1.fld_btreffid = t0.fld_btid
                                    left join tbl_bth t2 on t2.fld_btid = t0.fld_btidp
                                    where 
                                    t0.fld_btflag = 5
                                    and t2.fld_bttyid = 22
				    ) res
                                  group by res.fld_driverid");

            ## koreksi
           $correct = $this->db->query("select res.fld_driverid,sum(res.amount) as amount,date from 
                                   (
                                        select 
                                   t0.fld_empid,
                                   sum(t0.fld_btamt01)'amount',
                                   date_format(t0.fld_btdt,'%Y-%m-%d') 'date',
                                   t0.fld_driverid
                                   from tbl_btd_driver_loan t0
                                   left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp 
                                   where
                                   t0.fld_btflag = 9
                                   and t1.fld_bttyid = 120 
                                   and t1.fld_btp01 =9 
                                   and t1.fld_btflag = 1 
                                   and t1.fld_btstat =3                           
                                   group by t0.fld_driverid )
                                   res
                                   group by res.fld_driverid");
            $tot_fs = 0;
            $tot_in = 0;
            $tot_out = 0;
            $tot_corr = 0;
            #$tot_balance = 0;
	foreach ($viewrs as $rviewrs) {
          $in = 0;
          $out = 0;
          $balance = 0;
          $fs = 0;
          $fsin = 0;
          $fsout = 0;
          $bfs = 0;
          $begin = 0;
	  $fscorr =0;
          $corr =0;	
        foreach($debt->result() as $rdebt) {
          if($rviewrs->fld_driverid == $rdebt->fld_driverid) {
             if($rdebt->date < $dtsa) {
                $fsin = $fsin + $rdebt->amount;
             }
             if($rdebt->date >= $dtsa && $rdebt->date <= $dtso) {
               $in = $in + $rdebt->amount;
             }
          }
        }
       foreach($credit->result() as $rcredit) {
          if($rviewrs->fld_driverid == $rcredit->fld_driverid) {
             if($rcredit->date < $dtsa) {
               $fsout = $fsout + $rcredit->amount;
             }
             if($rcredit->date >= $dtsa && $rcredit->date <= $dtso) {
                $out = $out + $rcredit->amount;
             }
          }
        }
        foreach($correct->result() as $rcorrect) {
          if($rviewrs->fld_driverid == $rcorrect->fld_driverid) {
             if($rcorrect->date < $dtsa) {
                $fscorr = $fscorr + $rcorrect->amount;
             }
             if($rcorrect->date >= $dtsa && $rcorrect->date <= $dtso) {
               $corr = $corr + $rcorrect->amount;
             }
          }
        }

       /* foreach($balc->result() as $rbalc) {
        if($rviewrs->fld_driverid == $rbalc->fld_driverid){
          $begin = $rbalc->begin;
          }
        }*/

        #$balance = $begin+$in - $out;
        #$ttl_fs = $fs + $bfs;
        $fs = ($fsin - $fsout) - $fscorr;
        $balance = ($fs + $in - $out) - $corr;
	#{
          if(($in + $out +  $fs + $corr) <> 0) {
            $tot_fs = $tot_fs + $fs;
            $tot_in = $tot_in + $in;
            $tot_out = $tot_out + $out;
            $tot_corr = $tot_corr + $corr;
            $tot_balance = $tot_balance + $balance;

           $no = $no + 1;
           if ($no % 2 == 1)
                {
                        $bgcolor="#FFFFFF";
                }
                else
                {
                        $bgcolor="#F5F5F5";
                }

			echo "<tr bgcolor=$bgcolor>";
		        echo "<td>".$no."</td>";
			echo "<td>" .  $rviewrs->fld_empnm . "</td>";
                        echo"<td>" . $rviewrs->fld_driverid . "</td>";
			echo "<td>" .  $rviewrs->fld_empid. "</td>";
                        echo "<td>" .  $rviewrs->driverstat. "</td>";
                        echo "<td>" .  $dtsa . " - " . $dtso . "</td>";
                        echo "<td>" .  $rviewrs->fld_empnip. "</td>";
                        echo "<td>" .  $rviewrs->fld_btinm . "</td>";

                        if($fs != 0){
                        echo '<td align="right"><a href="' . base_url() .  'index.php/page/view/78000DRIVER_BEGIN_INST?fld_empid=' . $rviewrs->fld_driverid . '&dtsa=' . $dtsa . '&dtso=' . $dtso . '">' .  number_format($fs,0,'.',',')  . '</a></td>';
                        }else
                        {
                        echo "<td align ='right'>".number_format(0) ."</td>";
                        }
                        if ($in > 0){
                        echo '<td align="right"><a href="' . base_url() .  'index.php/page/view/78000DRIVER_DEBT_INST?fld_empid=' . $rviewrs->fld_driverid . '&dtsa=' . $dtsa . '&dtso=' . $dtso . '">' .  number_format($in,0,'.',',')  . '</a></td>';
			   }else
			   {
				echo "<td align='right'>" . number_format(0) . "</td>";
		           }
		        if ($out != 0){
                       echo '<td align="right"><a href="' . base_url() .  'index.php/page/view/78000DRIVER_PAYS_INST?fld_empid=' . $rviewrs->fld_driverid . '&dtsa=' . $dtsa . '&dtso=' . $dtso . '">' .  number_format($out,0,'.',',')  . '</a></td>';
			}else
			{
				echo "<td align='right'>" . number_format(0) . "</td>";
			}
			if ($balance >= 0)
			{
                          echo "<td align='right'>" . number_format($balance,2,',','.') . "</td>";
			}else
			{
			  echo "<td align='right' bgcolor='red'>" . number_format($balance,2,',','.') . "</td>";
			}

                        echo "</tr>";
	}
}
                        echo "<tr bgcolor='green'>";
                        echo "<td colspan=8 align = 'center'>Total</td>";
                        echo "<td align='right'>" . number_format($tot_fs,2,'.',',') . "</td>";
                        echo "<td align='right'>" . number_format($tot_in,2,'.',',') . "</td>";
                        echo "<td align='right'>" . number_format($tot_out,2,'.',',') . "</td>";
                        echo "<td align='right'>" . number_format($tot_balance,2,'.',',') . "</td>";
                        echo "</tr>";

?>
</table>
<br>
</form>
