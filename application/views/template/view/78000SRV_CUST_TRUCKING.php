<?
foreach($viewdata as $rvdata):
endforeach;
$headerrs = $viewrs;
$header = array_shift($headerrs);
$fld_periode = $header->periode;
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
    <td nowrap>No.</td>
    <td nowrap>Type</td>
    <td nowrap>Customer</td>
    <td nowrap>Registration Date</td>
    <td nowrap>PIC</td>
    <td nowrap>Trucking</td>
    <td nowrap>Export</td>
    <td nowrap>Import</td>
    <td nowrap>Inter Island</td> 
    <td nowrap>Warehouse</td>
    <td nowrap>Depot Container</td>
    <td nowrap>Last Visit</td>
   </tr>
   </tr>
<?
 echo "<tr bgcolor='green'>";
 echo "<td colspan = 12 align = 'center'><b> PERIODE " . date('Y') . "</b></td>";
 echo "</tr>";

$customer = ($_GET['fld_benm'] == '') ? '%' : $_GET['fld_benm'];
$quo = $this->db->query("select
                         t0.fld_baidc,
                         if(t0.fld_btp06 > 0,1,0) 'trucking',
                         if(t0.fld_btp07 + t0.fld_btp08 + t0.fld_btp09 + t0.fld_btp10 + t0.fld_btp11 + t0.fld_btp12 + t0.fld_btp13 + t0.fld_btp14 > 0,1,0) 'custom',
                         if(t0.fld_btp07 + t0.fld_btp08 + t0.fld_btp09 + t0.fld_btp10 > 0,1,0) 'export',
                         if(t0.fld_btp11 + t0.fld_btp12 + t0.fld_btp13 + t0.fld_btp14 > 0,1,0) 'import',
                         if(t0.fld_btp37 > 0,1,0) 'inter',
                         if((t0.fld_btp15 + t0.fld_btp22 + t0.fld_btp16) > 0,1,0) 'whs', 
                         if((t0.fld_btp20 + t0.fld_btp21 + t0.fld_btp28) > 0,1,0) 'depo'
                         from 
                         tbl_bth t0
                         left join tbl_be t1 on t1.fld_beid = t0.fld_baidc
                         where
                         t0.fld_bttyid in (33,86)
                         and
                         t0.fld_btstat =3
                         and
                         t0.fld_baidv = 1 
                         and 
                         if ('$customer' = '%',1,t1.fld_benm like '$customer')
                         ")->result();
foreach ($viewrs as $rviewrs) {
$import=0;
$trucking = 0;
$whs = 0;
$depo = 0;
$export = 0;
$inter = 0;
  foreach ($quo as $rquo) {
    if($rquo->fld_baidc == $rviewrs->crud) {
      $export = $export + $rquo->export;
      $import = $import + $rquo->import;
      $inter = $inter + $rquo->inter;
      $whs = $whs + $rquo->whs;
      $depo = $depo + $rquo->depo;
      $trucking = $trucking + $rquo->trucking;
    }
  }
    $no=$no+1;
    if ($no % 2 == 1) {
      $bgcolor="#FFFFFF";
    } else {
      $bgcolor="#F5F5F5";
    }
   
    if($rviewrs->Periode == date('Y')) { 
    echo "<tr bgcolor=$bgcolor>";
    echo "<td>$no</td>";
    echo "<td>$rviewrs->Prefix</td>";
    echo "<td>$rviewrs->Customer</td>";
    echo "<td>$rviewrs->RegisterDate</td>";
    echo "<td>$rviewrs->PIC</td>";
    if($trucking > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&trucking=1" . "'>$trucking</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }
    if($export > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&custom=1" . "'>$export</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    } 
     if($import > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&custom=1" . "'>$import</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }
     if($inter > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&custom=1" . "'>$inter</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }

    if($whs > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&whs=1'>$whs</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }
    if($depo > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&depo=1'>$depo</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }
    echo "<td>$rviewrs->Visit</td>";
    echo "</tr>";
  }
  }

  echo "<tr bgcolor='green'>";
 echo "<td colspan = 12 align = 'center'><b>PERIODE < " . date('Y') . "</b></td>";
 echo "</tr>";
foreach ($viewrs as $rviewrs) {
$import=0;
$trucking = 0;
$whs = 0;
$depo = 0;
$export = 0;
$inter = 0;
  foreach ($quo as $rquo) {
    if($rquo->fld_baidc == $rviewrs->crud) {
      $export = $export + $rquo->export;
      $import = $import + $rquo->import;
      $inter = $inter + $rquo->inter;
      $whs = $whs + $rquo->whs;
      $depo = $depo + $rquo->depo;
      $trucking = $trucking + $rquo->trucking;
    }
  }
    $no=$no+1;
    if ($no % 2 == 1) {
      $bgcolor="#FFFFFF";
    } else {
      $bgcolor="#F5F5F5";
    }
   
    if($rviewrs->Periode == date('Y')) { 
    echo "";
    } else {
    echo "<tr bgcolor=$bgcolor>";
    echo "<td>$no</td>";
    echo "<td>$rviewrs->Prefix</td>";
    echo "<td>$rviewrs->Customer</td>";
    echo "<td>$rviewrs->RegisterDate</td>";
    echo "<td>$rviewrs->PIC</td>";
    if($trucking > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&trucking=1" . "'>$trucking</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }
    if($export > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&custom=1" . "'>$export</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    } 
     if($import > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&custom=1" . "'>$import</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }
     if($inter > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&custom=1" . "'>$inter</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }

    if($whs > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&whs=1'>$whs</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }
    if($depo > 0) {
      echo "<td align ='center'><a href='" . base_url() . "index.php/page/view/78000QUOTATION_ACTIVE?fld_beid=" . $rviewrs->crud . "&depo=1'>$depo</a></td>";
    } else {
      echo "<td>&nbsp;</td>";
    }
    echo "<td>$rviewrs->Visit</td>";
    echo "</tr>";
  }
  }
	
?>
</table>
<div  style="color:#000000">
<?
echo "Total Record = " . $numrows . "<br>";
/******  build the pagination links ******/
// range of num links to show
$range = 3;

// if not on page 1, don't show shck links
if ($currentpage > 1) {
   // show << link to go shck to page 1
   echo "  <a href=javascript:pgoto(1)><<</a>";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go shck to 1 page
   echo " <a href=javascript:pgoto($prevpage)><</a> ";
} // end if

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href=javascript:pgoto($x)>$x</a>";
      } // end else
   } // end if
} // end for

// if not on last page, show forward and last page links
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page
   echo " <a href=javascript:pgoto($nextpage)>></a> ";
   // echo forward link for lastpage
    echo " <a href=javascript:pgoto($totalpages)>>></a> ";
} // end if
?>
</div>
<br>
</form>
