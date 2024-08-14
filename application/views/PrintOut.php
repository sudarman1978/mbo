<?
	function rupiah_format($rp) {
		$rupiah = "";
		$rupiah = number_format($rp,2);
		return $rupiah;
	}
	
	$data1=$data->row();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<style>
table.content{
width:29.7cm ;
/*height:13.97 ;*/ 
font-size: 16px;
border-collapse:collapse;
}

table.grid{
width:29.7cm ;
font-size: 16px;
border-collapse:collapse;
}
table.grid th{
	padding:5px;
}
table.grid th{
background: #F0F0F0;
border-top: 0.2mm solid #000;
border-bottom: 0.2mm solid #000;
text-align:center;
border:1px solid #000;
}
table.grid tr td{
	padding:2px;
	border-bottom:0.2mm solid #000;
	border:1px solid #000;
}

table.grid tfoot tr td {
	border:none;
}

.pagebreak {
width:29.7cm ;
page-break-after: always;
margin-bottom:10px;
}
.akhir {
width:29.7cm ;
font-size:13px;
}
.page {
width:21.59cm ;
height:13.97cm ;
font-size:18px;
padding:12px;
}
.Landscape

{

width: 100%;
height: 100%;

margin: 0% 0% 0% 0%; filter: progid:DXImageTransform.Microsoft.BasicImage(Rotation=3);
}


.kbw-signature{
	height: 200px;
	width: 300px;
}
.style1 {font-family: Arial, Helvetica, sans-serif}
</style>
</head>

<body>
      <span class="style1">
      <?
  switch($modul)
  {
    case "SKDO": ?>
      </span>
      <div style="font-size:13px">
	<br/><br/><br/><br/><br/><br/><br/><br/><br/>	
      <table width="100%" border="0" cellpadding="0" cellspacing="0" >
	<tr>
	  <td colspan="2" class="style1"><div align="center" style="font-size:20px"><strong> SURAT KUASA</strong></div></td>
	</tr>
	<tr>
	  <td colspan="2" class="style1"><div align="center" style="font-size:20px"><strong>	    PENGAMBILAN DO </strong></div></td>
	</tr>
	<tr>
	  <td colspan="2" class="style1">&nbsp;</td>
	</tr>
	<tr>
          <td colspan="2" class="style1">&nbsp;</td>
        </tr>
	<tr>
          <td colspan="2" class="style1">&nbsp;</td>
        </tr>
	<tr>
	  <td colspan="2" class="style1">Kami yang bertanda tangan dibawah ini : </td>
	</tr>
	<tr>
	  <td colspan="2" class="style1">&nbsp;</td>
	</tr>
	<tr>
	  <td width="17%" class="style1">Nama</td>
	  <td width="83%" class="style1">: <?=$data1->fld_btp22;?></td>
	</tr>
	<tr>
	  <td class="style1">Jabatan</td>
	  <td class="style1">: Kuasa Direksi</td>
	</tr>
	<tr>
	  <td class="style1">Nama Perusahaan </td>
	  <td class="style1">:
      <?=$data1->CUSTOMER;?></td>
	</tr>
	<tr>
	  <td class="style1">NPWP</td>
	  <td class="style1">:
      <?=$data1->fld_btp21;?></td>
	</tr>
	<tr>
	  <td class="style1">Alamat Perusahaan </td>
	  <td class="style1">:
      <?=$data1->ALAMAT;?></td>
	</tr>
	<tr>
	  <td class="style1">Telp/Fax</td>
	  <td class="style1">: <?=$data1->fld_btp20;?></td>
	</tr>
	<tr>
	  <td class="style1">&nbsp;</td>
	  <td class="style1">&nbsp;</td>
	</tr>
	<tr>
	  <td colspan="2" class="style1">Selanjutnya dalam surat kuasa ini disebut sebagai PEMBERI KUASA dengan ini memberi kuasa kepada :</td>
	</tr>
	<tr>
	  <td colspan="2" class="style1">&nbsp;</td>
	</tr>
	<tr>
	  <td class="style1">Nama</td>
	  <td class="style1">: Firman Dermawan</td>
	</tr>
	<tr>
	  <td class="style1">Jabatan</td>
	  <td class="style1">: Manager Import</td>
	</tr>
	<tr>
	  <td class="style1">Nama Perusahaan </td>
	  <td class="style1">:
      PT. Dunia Express</td>
	</tr>
	<tr>
	  <td class="style1">Alamat</td>
	  <td class="style1">:
      Jl. Agung Karya VII No.1 Jakarta Utara 14340</td>
	</tr>
	<tr>
	  <td class="style1">Telp/Fax</td>
	  <td class="style1">:
      6505603/6511041</td>
	</tr>
	<tr>
	  <td class="style1">&nbsp;</td>
	  <td class="style1">&nbsp;</td>
	</tr>
	<tr>
	  <td colspan="2" class="style1">Untuk mengambil DO asli atas dokumen impor dengan data-data sebagai berikut :</td>
	</tr>
	<tr>
	  <td colspan="2" class="style1">&nbsp;</td>
	</tr>
	<tr>
	  <td class="style1">No BL </td>
	  <td class="style1">:
      <?=$data1->DOKUMEN;?></td>
	</tr>
	<tr>
	  <td class="style1">Nama Kapal </td>
	  <td class="style1">:
      <?=$data1->fld_btp03;?> / <?=$data1->fld_btp04;?></td>
	</tr>
	<tr>
	  <td class="style1">Jumlah Container </td>
	  <td class="style1">:
      	<?
			$JmlCon='';
			if ($data1->fld_btqty > 0 && $data1->fld_btp06 > 0)
			{
				$JmlCon=$data1->fld_btqty."x20, ".$data1->fld_btp06."x40";
			}
			if ($data1->fld_btqty == 0 && $data1->fld_btp06 > 0)
			{
				$JmlCon=$data1->fld_btp06."x40";
			}
			if ($data1->fld_btqty > 0 && $data1->fld_btp06 == 0)
			{
				$JmlCon=$data1->fld_btqty."x20";
			}
			print $JmlCon;
		?>	</td>
	</tr>
	<tr>
	  <td class="style1">&nbsp;</td>
	  <td class="style1">&nbsp;</td>
	</tr>
	<tr>
	  <td colspan="2" class="style1">Demikian surat kuasa ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</td>
	</tr>
	<tr>
	  <td colspan="2" class="style1">&nbsp;</td>
	</tr>
	<tr>
	  <td colspan="2" class="style1"><div align="right">......................, <? print date("d-M-Y"); ?></div></td>
	</tr>
	<tr>
	  <td class="style1">&nbsp;</td>
	  <td class="style1">&nbsp;</td>
	  </tr>
	<tr>
	  <td class="style1"><span style="text-align:left">Penerima Kuasa</span></td>
	  <td class="style1"><div align="right"><span style="text-align:right">Pemberi Kuasa</span></div></td>
	</tr>
	<tr>
	  <td class="style1">&nbsp;</td>
	  <td class="style1">&nbsp;</td>
	</tr>
	<tr>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>
        </tr>
	<tr>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>
        </tr>
	<tr>
	  <td class="style1">&nbsp;</td>
	  <td class="style1">&nbsp;</td>
	</tr>
	<tr>
	  <td class="style1">&nbsp;</td>
	  <td class="style1">&nbsp;</td>
	</tr>
	<tr>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>
        </tr>
	<tr>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>
        </tr>

	<tr>
	  <td class="style1">Firman Dermawan<hr noshade size=1 width=200> </td>
	  <td class="style1"><div align="right"><?=$data1->fld_btp22;?></div> <hr noshade size=1 width=200 align=right></td>
	</tr>
	<tr>
	  <td class="style1">Manager Import</td>
	  <td class="style1"><div align="right">Kuasa Direksi </div></td>
	</tr>
      </table>
      </div>
      <span class="style1">
      <? break;
  	case "CashAdvance": ?>
	<div style="font-family:Georgia, "Times New Roman", Times, serif;">
</span>
      <table width="100%" class="content">
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>ADVANCE REQUEST</strong></div></td>
	    </tr>
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:16px"><strong><? if($data1->usercomp == 1) { echo 'PT. REMA LOGISTIK INDONESIA';}?></strong></div></td>
		  <td colspan="2" class="style1">&nbsp;</td>
	    </tr>
	    <tr>
		  <td width="8%" class="style1">Number</td>
		  <td width="47%" class="style1">: <?=$data1->fld_btno;?></td>
                  <td width="10%" class="style1">Shipping Line</td>
                  <td width="47%" class="style1">: <?=$data1->shipping;?></td> 
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>   
	    </tr>
	    <tr>
	      <td class="style1">Division</td>
	      <td class="style1"> : <?=$data1->fld_bedivnm?></td>
               <td width="10%" class="style1">Bank Name </td>
               <?
                $bank_name = '';

                if($data1->paytype != 3) {
                        $bank_name = '';
                }
                else {

                        $bank_name = $data1->bank_name;
                }
               ?>

               <td width="47%" class="style1">:  <?=$bank_name;?></td>
	      <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	 <tr>
              <td class="style1">Date</td>
              <td class="style1">: <?=$data1->req_date; ?></td>
              <td width="10%" class="style1">Bank Account </td>
               <?
                $bank_acc = '';

                if($data1->paytype != 3) {
                        $bank_acc = '';
                }
                else {

                        $bank_acc = $data1->bank_acc;
                }
               ?>

               <td width="47%" class="style1">: <?=$bank_acc;?></td>
               <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
        <tr>
              <td class="style1">Remark</td>
              <?
                $remark = '';

                if($data1->paytype == 4) {
                        $remark = 'EDC';
                }
                else {

                        $remark = $data1->remark;
                }
              ?>
              <td class="style1">: <?=$remark; ?></td>

              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>

	    <tr>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
        </tr>
</table>
		<table width="100%" class="content">
		  <tr >
		    <th width="3%" class="style1"style="border: 1px solid #000;">No</th>
		    <th width="10%" class="style1" style="border: 1px solid #000;">Job Order</th>
		    <th width="15%" class="style1" style="border: 1px solid #000;"> B/L</th>
		    <th width="14%" class="style1" style="border: 1px solid #000;">Customer</th>
		    <th width="14%" class="style1" style="border: 1px solid #000;">Shipping Line</th>
		    <th width="20%" class="style1" style="border: 1px solid #000;">Desc</th>
	            <th width="5%" class="style1" style="border: 1px solid #000;">UoM</th>
           	    <th width="5%" class="style1" style="border: 1px solid #000;">Curr</th>
		    <th width="8%" class="style1" style="border: 1px solid #000;">Amount</th>
		    <th width="4%" class="style1" style="border: 1px solid #000;">Qty</th>
		    <th width="10%" class="style1" style="border: 1px solid #000;">Total</th>
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; $TransferUsd=0; $TransferIdr=0; $OpIdr=0; $OpUsd=0;  foreach($data->result() as $row) { 
		         $OpIdr=$data1->totalopidr;
                         $OpUsd=$data1->totalopusd;

                	 if ($row->currency=='USD'){
			 	$usd=$usd+$row->total;
				if ($row->payment=='CASH'){
					$CashUsd=$CashUsd+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroUsd=$GiroUsd+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferUsd=$TransferUsd+$row->total;
                                }
			 }
			 elseif ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='CASH'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroIdr=$GiroIdr+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferIdr=$TransferIdr+$row->total;
                                }
			
                        }
                          
                      	
		  ?>
		  <tr>
			<td class="style1" style="border-left: 1px solid #000;border-right: 1px solid #000;"><?=$i;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=substr($row->jo,-11,11);?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->bl;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->customer;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->shipping_line;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><?=$row->desc;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><?=$row->tipe;?></td>
                    <td class="style1" style="border-right: 1px solid #000;"><?=$row->currency;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->amount,2);?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=$row->qty;?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->total,2);?>
		    </div></td>
		  </tr>
		 <? $i++; } ?>
		</table>
		<table width="100%" class="content">

		  <tr>

			<td colspan="2" class="style1" style="border-left: 1px solid #000; border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>	

		    <td class="style1" style="border-top:1px solid #000;">Total Advance</td>

		    <td class="style1" style="border-top:1px solid #000;">USD</td>

		    <td class="style1" style="border-top:1px solid #000;border-right: 1px solid #000;"><div align="right">

		      <?=rupiah_format($usd);?>

		    </div></td>

		  </tr>

		  <tr>

		    <td colspan="2" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>

		   

		    <td class="style1">&nbsp;</td>

		    <td class="style1">&nbsp;</td>

			<td class="style1">&nbsp;</td>

			<td class="style1">&nbsp;</td>

		    <td class="style1">&nbsp;</td>

		    <td class="style1">&nbsp;</td>

                    <td class="style1">IDR</td>

		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">

		      <?=rupiah_format($idr);?>

		    </div></td>

	      </tr>

                   <tr>

                        <td colspan="2" class="style1" style="border-left: 1px solid #000; border-top:1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-top:1px solid #000;">Total Over Payment</td>

                    <td class="style1" style="border-top:1px solid #000;">USD</td>

                    <td class="style1" style="border-top:1px solid #000;border-right: 1px solid #000;"><div align="right">

                      <?=rupiah_format($OpUsd);?>

                    </div></td>

                  </tr>
 
                  
                <tr>

                    <td colspan="2" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>



                    <td class="style1">&nbsp;</td>

                    <td class="style1">&nbsp;</td>

                        <td class="style1">&nbsp;</td>

                        <td class="style1">&nbsp;</td>

                    <td class="style1">&nbsp;</td>

                    <td class="style1">&nbsp;</td>

                    <td class="style1">IDR</td>

                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">

                     <?=rupiah_format($OpIdr);?>

                    </div></td>

              </tr>
                 



		  <tr>

		    <td colspan="10" class="style1" style="border: 1px solid #000;"><div align="center">Payment Detail</div></td>

	       </tr>

		  <tr>

		    <td class="style1" style="border-left: 1px solid #000;">&nbsp;</td>

			

	        <td class="style1">Giro/Chq </td>

	        <td class="style1">&nbsp;</td>

			<td class="style1">&nbsp;</td>

			<td class="style1">:</td>

	        <td colspan="2" class="style1">Rp. <?
                      $TotGiroIdr=0;
                         if($OpIdr>0 && $GiroIdr>0){
                            $TotGiroIdr = $GiroIdr-$OpIdr;
                         }
                         elseif($OpIdr==0) {
                            $TotGiroIdr = $GiroIdr;
                         }
                         print rupiah_format($TotGiroIdr); 
                    ?> 
                </td>
	        <td colspan="2" class="style1">US. <?
                        $TotGiroUsd=0;
                         if($OpUsd>0 && $GiroUsd>0){
                            $TotGiroUsd = $GiroUsd-$OpUsd;
                         }
                         elseif($OpUsd==0) {
                            $TotGiroUsd = $GiroUsd;
                         }
                         print rupiah_format($TotGiroUsd);
                    ?>
                </td>

	        <td class="style1" style="border-right: 1px solid #000;">&nbsp;</td>

		  </tr>

		  <tr>

		    <td class="style1" style="border-left: 1px solid #000;">&nbsp;</td>

		    <td class="style1" >Cash </td>

		    <td class="style1" >&nbsp;</td>

			<td class="style1">&nbsp;</td>

			<td class="style1">:</td>

		    <td colspan="2" class="style1">Rp. <? 
                         $TotCashIdr=0; 
                         if($OpIdr>0 && $CashIdr>0){
                            $TotCashIdr = $CashIdr-$OpIdr;
                         }
                         if($OpIdr==0) {
                            $TotCashIdr = $CashIdr;
                         }
                         print rupiah_format($TotCashIdr);
                     ?>
                           
                    </td>

		    <td colspan="2" class="style1">US. <?
                         $TotCashUsd=0;
                         if($OpUsd>0 && $CashUsd>0){
                            $TotCashUsd = $CashUsd-$OpUsd;
                         }
                         if($OpUsd==0) {
                            $TotCashUsd = $CashUsd;
                         }
                         print rupiah_format($TotCashUsd);
                     ?>

                    </td>
                      

		    <td class="style1" style="border-right: 1px solid #000;">&nbsp;</td>

	       </tr>	

              <tr>

                    <td class="style1" style="border-left: 1px solid #000;border-bottom: 1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-bottom: 1px solid #000;">Transfer </td>

                    <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-bottom: 1px solid #000;">:</td>

                    <td colspan="2" class="style1" style="border-bottom: 1px solid #000;">Rp. <?
                     $TotTransIdr=0;
                         if($OpIdr>0 && $TransferIdr>0){
                            $TotTransIdr = $TransferIdr-$OpIdr;
                         }
                         if($OpIdr==0) {
                            $TotTransIdr = $TransferIdr;
                         }
                         print rupiah_format($TotTransIdr);
                     ?>

                    </td>
                  

                    <td colspan="2" class="style1" style="border-bottom: 1px solid #000;">US. <?
                     $TotTransUsd=0;
                         if($OpUsd>0 && $TransferUsd>0){
                            $TotTransUsd = $TransferUsd-$OpUsd;
                         }
                         if($OpUsd==0) {
                            $TotTransUsd = $TransferUsd;
                         }
                         print rupiah_format($TotTransUsd);
                     ?>

                    </td>

                    <td class="style1" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>

               </tr>

		  </table> 
   
	<span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="25%" class="style1">Prepared By</td>
		    <td width="25%" class="style1">Aproved By</td>
		    <td width="25%" class="style1">Ops.Staff</td>
		    <td width="25%" class="style1" ><div align="right">Cashier</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="36%" class="style1">                      <?=$data1->fld_empnm;?>                    </td>

                    <td width="27%" class="style1">&nbsp;</td>
                    <td width="27%" class="style1">                      <?=$data1->staff;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>
		</table>
        <span class="style1">
	</div>
        <? break;
        
        case "CashAdvanceCO": ?>
	<div style="font-family:Georgia, "Times New Roman", Times, serif;">
</span>
      <table width="100%" class="content">
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>ADVANCE REQUEST (CO)</strong></div></td>
	    </tr>
	    <tr>
		  <td colspan="2" class="style1">&nbsp;</td>
		  <td colspan="2" class="style1">&nbsp;</td>
	    </tr>
	    <tr>
		  <td width="8%" class="style1">Number</td>
		  <td width="47%" class="style1">: <?=$data1->fld_btno;?></td>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>   
	    </tr>
	    <tr>
	      <td class="style1">Division</td>
	      <td class="style1"> : <?=$data1->fld_bedivnm?></td>
	      <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	 <tr>
              <td class="style1">Date</td>
              <td class="style1">: <?=$data1->req_date; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
        <tr>
              <td class="style1">Remark</td>
              <td class="style1">: <?=$data1->remark; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>

	    <tr>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
        </tr>
</table>
		<table width="100%" class="content">
		  <tr >
		    <th width="3%" class="style1"style="border: 1px solid #000;">No</th>
		    <th width="10%" class="style1" style="border: 1px solid #000;">Job Order</th>
		    <th width="15%" class="style1" style="border: 1px solid #000;"> Invoice No</th>
		    <th width="14%" class="style1" style="border: 1px solid #000;">Customer</th>
		    <th width="14%" class="style1" style="border: 1px solid #000;">CO No</th>
		    <th width="20%" class="style1" style="border: 1px solid #000;">Desc</th>
	            <th width="5%" class="style1" style="border: 1px solid #000;">UoM</th>
           	    <th width="5%" class="style1" style="border: 1px solid #000;">Curr</th>
		    <th width="8%" class="style1" style="border: 1px solid #000;">Amount</th>
		    <th width="4%" class="style1" style="border: 1px solid #000;">Qty</th>
		    <th width="10%" class="style1" style="border: 1px solid #000;">Total</th>
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; $TransferUsd=0; $TransferIdr=0; $OpIdr=0; $OpUsd=0;  foreach($data->result() as $row) { 
		         $OpIdr=$data1->totalopidr;
                         $OpUsd=$data1->totalopusd;

                	 if ($row->currency=='USD'){
			 	$usd=$usd+$row->total;
				if ($row->payment=='CASH'){
					$CashUsd=$CashUsd+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroUsd=$GiroUsd+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferUsd=$TransferUsd+$row->total;
                                }
			 }
			 elseif ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='CASH'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroIdr=$GiroIdr+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferIdr=$TransferIdr+$row->total;
                                }
			
                        }
                          
                      	
		  ?>
		  <tr>
			<td class="style1" style="border-left: 1px solid #000;border-right: 1px solid #000;"><?=$i;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=substr($row->jo,-11,11);?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->invoice;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->customer;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->co;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><?=$row->desc;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><?=$row->tipe;?></td>
                    <td class="style1" style="border-right: 1px solid #000;"><?=$row->currency;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->amount,2);?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=$row->qty;?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->total,2);?>
		    </div></td>
		  </tr>
		 <? $i++; } ?>
		</table>
		<table width="100%" class="content">

		  <tr>

			<td colspan="2" class="style1" style="border-left: 1px solid #000; border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>	

		    <td class="style1" style="border-top:1px solid #000;">Total Advance</td>

		    <td class="style1" style="border-top:1px solid #000;">USD</td>

		    <td class="style1" style="border-top:1px solid #000;border-right: 1px solid #000;"><div align="right">

		      <?=rupiah_format($usd);?>

		    </div></td>

		  </tr>

		  <tr>

		    <td colspan="2" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>

		   

		    <td class="style1">&nbsp;</td>

		    <td class="style1">&nbsp;</td>

			<td class="style1">&nbsp;</td>

			<td class="style1">&nbsp;</td>

		    <td class="style1">&nbsp;</td>

		    <td class="style1">&nbsp;</td>

                    <td class="style1">IDR</td>

		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">

		      <?=rupiah_format($idr);?>

		    </div></td>

	      </tr>

                   <tr>

                        <td colspan="2" class="style1" style="border-left: 1px solid #000; border-top:1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-top:1px solid #000;">Total Over Payment</td>

                    <td class="style1" style="border-top:1px solid #000;">USD</td>

                    <td class="style1" style="border-top:1px solid #000;border-right: 1px solid #000;"><div align="right">

                      <?=rupiah_format($OpUsd);?>

                    </div></td>

                  </tr>
 
                  
                <tr>

                    <td colspan="2" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>



                    <td class="style1">&nbsp;</td>

                    <td class="style1">&nbsp;</td>

                        <td class="style1">&nbsp;</td>

                        <td class="style1">&nbsp;</td>

                    <td class="style1">&nbsp;</td>

                    <td class="style1">&nbsp;</td>

                    <td class="style1">IDR</td>

                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">

                     <?=rupiah_format($OpIdr);?>

                    </div></td>

              </tr>
                 



		  <tr>

		    <td colspan="10" class="style1" style="border: 1px solid #000;"><div align="center">Payment Detail</div></td>

	       </tr>

		  <tr>

		    <td class="style1" style="border-left: 1px solid #000;">&nbsp;</td>

			

	        <td class="style1">Giro/Chq </td>

	        <td class="style1">&nbsp;</td>

			<td class="style1">&nbsp;</td>

			<td class="style1">:</td>

	        <td colspan="2" class="style1">Rp. <?
                      $TotGiroIdr=0;
                         if($OpIdr>0 && $GiroIdr>0){
                            $TotGiroIdr = $GiroIdr-$OpIdr;
                         }
                         elseif($OpIdr==0) {
                            $TotGiroIdr = $GiroIdr;
                         }
                         print rupiah_format($TotGiroIdr); 
                    ?> 
                </td>
	        <td colspan="2" class="style1">US. <?
                        $TotGiroUsd=0;
                         if($OpUsd>0 && $GiroUsd>0){
                            $TotGiroUsd = $GiroUsd-$OpUsd;
                         }
                         elseif($OpUsd==0) {
                            $TotGiroUsd = $GiroUsd;
                         }
                         print rupiah_format($TotGiroUsd);
                    ?>
                </td>

	        <td class="style1" style="border-right: 1px solid #000;">&nbsp;</td>

		  </tr>

		  <tr>

		    <td class="style1" style="border-left: 1px solid #000;">&nbsp;</td>

		    <td class="style1" >Cash </td>

		    <td class="style1" >&nbsp;</td>

			<td class="style1">&nbsp;</td>

			<td class="style1">:</td>

		    <td colspan="2" class="style1">Rp. <? 
                         $TotCashIdr=0; 
                         if($OpIdr>0 && $CashIdr>0){
                            $TotCashIdr = $CashIdr-$OpIdr;
                         }
                         if($OpIdr==0) {
                            $TotCashIdr = $CashIdr;
                         }
                         print rupiah_format($TotCashIdr);
                     ?>
                           
                    </td>

		    <td colspan="2" class="style1">US. <?
                         $TotCashUsd=0;
                         if($OpUsd>0 && $CashUsd>0){
                            $TotCashUsd = $CashUsd-$OpUsd;
                         }
                         if($OpUsd==0) {
                            $TotCashUsd = $CashUsd;
                         }
                         print rupiah_format($TotCashUsd);
                     ?>

                    </td>
                      

		    <td class="style1" style="border-right: 1px solid #000;">&nbsp;</td>

	       </tr>	

              <tr>

                    <td class="style1" style="border-left: 1px solid #000;border-bottom: 1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-bottom: 1px solid #000;">Transfer </td>

                    <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-bottom: 1px solid #000;">:</td>

                    <td colspan="2" class="style1" style="border-bottom: 1px solid #000;">Rp. <?
                     $TotTransIdr=0;
                         if($OpIdr>0 && $TransferIdr>0){
                            $TotTransIdr = $TransferIdr-$OpIdr;
                         }
                         if($OpIdr==0) {
                            $TotTransIdr = $TransferIdr;
                         }
                         print rupiah_format($TotTransIdr);
                     ?>

                    </td>
                  

                    <td colspan="2" class="style1" style="border-bottom: 1px solid #000;">US. <?
                     $TotTransUsd=0;
                         if($OpUsd>0 && $TransferUsd>0){
                            $TotTransUsd = $TransferUsd-$OpUsd;
                         }
                         if($OpUsd==0) {
                            $TotTransUsd = $TransferUsd;
                         }
                         print rupiah_format($TotTransUsd);
                     ?>

                    </td>

                    <td class="style1" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>

               </tr>

		  </table> 
   
	<span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="25%" class="style1">Prepared By</td>
		    <td width="25%" class="style1">Aproved By</td>
		    <td width="25%" class="style1">Ops.Staff</td>
		    <td width="25%" class="style1" ><div align="right">Cashier</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="36%" class="style1">                      <?=$data1->fld_empnm;?>                    </td>

                    <td width="27%" class="style1">&nbsp;</td>
                    <td width="27%" class="style1">                      <?=$data1->staff;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>
		</table>
        <span class="style1">
	</div>
        <? break;



        case "CashAdvanceRepo": ?>
	<div style="font-family:Georgia, "Times New Roman", Times, serif;">
</span>
      <table width="100%" class="content">
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>ADVANCE REQUEST REPO</strong></div></td>
	    </tr>
	    <tr>
		  <td colspan="2" class="style1">&nbsp;</td>
		  <td colspan="2" class="style1">&nbsp;</td>
	    </tr>
	    <tr>
		  <td width="8%" class="style1">Number</td>
		  <td width="47%" class="style1">: <?=$data1->fld_btno;?></td>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>   
	    </tr>
	    <tr>
	      <td class="style1">Division</td>
	      <td class="style1"> : <?=$data1->fld_bedivnm?></td>
	      <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	 <tr>
              <td class="style1">Date</td>
              <td class="style1">: <?=$data1->req_date; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	    <tr>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
        </tr>
</table>
		<table width="100%" class="content">
		  <tr >
		    <th width="3%" class="style1"style="border: 1px solid #000;">No</th>
		    <th width="10%" class="style1" style="border: 1px solid #000;">Job Order</th>
		    <th width="15%" class="style1" style="border: 1px solid #000;"> B/L</th>
		    <th width="14%" class="style1" style="border: 1px solid #000;">Customer</th>
		    <th width="14%" class="style1" style="border: 1px solid #000;">Shipping Line</th>
		    <th width="20%" class="style1" style="border: 1px solid #000;">Desc</th>
	            <th width="5%" class="style1" style="border: 1px solid #000;">UoM</th>
           	    <th width="5%" class="style1" style="border: 1px solid #000;">Curr</th>
		    <th width="8%" class="style1" style="border: 1px solid #000;">Amount</th>
		    <th width="4%" class="style1" style="border: 1px solid #000;">Qty</th>
		    <th width="10%" class="style1" style="border: 1px solid #000;">Total</th>
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; $TransferUsd=0; $TransferIdr=0; 
                     
                     foreach($data->result() as $row) { 
			 if ($row->currency=='USD'){
			 	$usd=$usd+$row->total;
				if ($row->payment=='CASH'){
					$CashUsd=$CashUsd+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroUsd=$GiroUsd+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferUsd=$TransferUsd+$row->total;
                                }
			 }
			 elseif ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='CASH'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroIdr=$GiroIdr+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferIdr=$TransferIdr+$row->total;
                                }
			 }			  	
		  ?>
                   <? $i++; } ?>

		  <tr>
			<td class="style1" style="border-left: 1px solid #000;border-right: 1px solid #000;">1</td>
			<td class="style1" style="border-right: 1px solid #000;"></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->bl;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->customer;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->shipping_line;?></td>
		    <td class="style1" style="border-right: 1px solid #000;">REPO</td>
		    <td class="style1" style="border-right: 1px solid #000;"><?=$row->tipe;?></td>
                    <td class="style1" style="border-right: 1px solid #000;">IDR</td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($data1->amount_repo,2);?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      1
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($data1->amount_repo,2);?>
		    </div></td>
		  </tr>
		 

		</table>
		<table width="100%" class="content">

		  <tr>

			<td colspan="2" class="style1" style="border-left: 1px solid #000; border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>	

		    <td class="style1" style="border-top:1px solid #000;">Total</td>

		    <td class="style1" style="border-top:1px solid #000;">USD</td>

		    <td class="style1" style="border-top:1px solid #000;border-right: 1px solid #000;"><div align="right">

		      <?=rupiah_format($usd);?>

		    </div></td>

		  </tr>

		  <tr>

		    <td colspan="2" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>

		   

		    <td class="style1">&nbsp;</td>

		    <td class="style1">&nbsp;</td>

			<td class="style1">&nbsp;</td>

			<td class="style1">&nbsp;</td>

		    <td class="style1">&nbsp;</td>

		    <td class="style1">&nbsp;</td>

                    <td class="style1">IDR</td>

		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">

		      <?=rupiah_format($data1->amount_repo);?>

		    </div></td>

	      </tr>

		  <tr>

		    <td colspan="10" class="style1" style="border: 1px solid #000;"><div align="center">Payment Detail</div></td>

	       </tr>

		  <tr>

		    <td class="style1" style="border-left: 1px solid #000;">&nbsp;</td>

			

	        <td class="style1">Giro/Chq </td>

	        <td class="style1">&nbsp;</td>

			<td class="style1">&nbsp;</td>

			<td class="style1">:</td>

	        <td colspan="2" class="style1">Rp. <?=rupiah_format($GiroIdr);?></td>

	        <td colspan="2" class="style1">US. <?=rupiah_format($GiroUsd);?></td>

	        <td class="style1" style="border-right: 1px solid #000;">&nbsp;</td>

		  </tr>

		  <tr>

		    <td class="style1" style="border-left: 1px solid #000;">&nbsp;</td>

		    <td class="style1" >Cash </td>

		    <td class="style1" >&nbsp;</td>

			<td class="style1">&nbsp;</td>

			<td class="style1">:</td>

		    <td colspan="2" class="style1">Rp. <?=rupiah_format($data1->amount_repo);?></td>

		    <td colspan="2" class="style1">US. <?=rupiah_format($CashUsd);?></td>

		    <td class="style1" style="border-right: 1px solid #000;">&nbsp;</td>

	       </tr>	

              <tr>

                    <td class="style1" style="border-left: 1px solid #000;border-bottom: 1px solid #000;">&nbsp;</td>

                    <td class="style1" style="border-bottom: 1px solid #000;">Transfer </td>

                    <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

                        <td class="style1" style="border-bottom: 1px solid #000;">:</td>

                    <td colspan="2" class="style1" style="border-bottom: 1px solid #000;">Rp. <?=rupiah_format($TransferIdr);?> </td>

                    <td colspan="2" class="style1" style="border-bottom: 1px solid #000;">US. <?=rupiah_format($TransferUsd);?> </td>

                    <td class="style1" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>

               </tr>

		  </table> 
   
	<span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="25%" class="style1">Prepared By</td>
		    <td width="25%" class="style1">Aproved By</td>
		    <td width="25%" class="style1">Ops.Staff</td>
		    <td width="25%" class="style1" ><div align="right">Cashier</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="36%" class="style1">                      <?=$data1->ops_staff;?>                    </td>

                    <td width="27%" class="style1">&nbsp;</td>
                    <td width="27%" class="style1">                      <?=$data1->ops_staff;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>
		</table>
        <span class="style1">
	</div>
        <? break;

    	case "OperatingCost": ?>
		</span>
        <table class="content" width="100%">
		  <tr>
			<td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>ADVANCE REQUEST</strong></div></td>
		  </tr>
		  <tr>
			<td colspan="2" class="style1">&nbsp;</td>
			<td colspan="2" class="style1">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="175" class="style1">Request Number</td>
			<td width="300" class="style1">: <?=$data1->fld_btno;?></td>
			<td width="384" class="style1"><div align="right">Name</div></td>
            <td width="296" class="style1"><div align="left">: 
              <?=$data1->fld_empnm;?>
            </div></td>
		  </tr>
		  <tr>
		    <td class="style1">Customer</td>
		    <td class="style1">: <?=$data1->fld_benm?></td>
		    <td class="style1"><div align="right">Date</div></td>
		    <td class="style1"><div align="left">: <? print date("d-M-Y"); ?></div></td>
	      </tr>
		  <tr>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
	      </tr>
</table>
		<table class="grid" width="95%">
		  <tr>
		    <th width="43" class="style1">No</th>
		    <th colspan="2" class="style1">Desc</th>
		    <th width="96" class="style1">Type</th>
		    <th width="124" class="style1">Currency</th>
		    <th width="172" class="style1">Amount</th>
		    <th width="88" class="style1">Qty</th>
		    <th width="178" class="style1">Total</th>
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; foreach($data->result() as $row) { 
			 if ($row->currency=='USD'){
			 	$usd=$usd+$row->total;
				if ($row->payment=='Cash'){
					$CashUsd=$CashUsd+$row->total;
				}
				if ($row->payment=='Giro/Chq'){
					$GiroUsd=$GiroUsd+$row->total;
				}
			 }
			 elseif ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='Cash'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='Giro/Chq'){
					$GiroIdr=$GiroIdr+$row->total;
				}
			 }			  	
		  ?>
		  <tr>
		    <td class="style1"><?=$i;?></td>
		    <td colspan="2" class="style1"><?=$row->desc;?></td>
		    <td class="style1"><?=$row->tipe;?></td>
		    <td class="style1"><?=$row->currency;?></td>
		    <td class="style1"><div align="right">
		      <?=rupiah_format($row->amount);?>
		    </div></td>
		    <td class="style1"><div align="right">
		      <?=$row->qty;?>
		    </div></td>
		    <td class="style1"><div align="right">
		      <?=rupiah_format($row->total);?>
		    </div></td>
		  </tr>
		 <? $i++; } ?>
		 <tfoot style="border:none">
		  <tr>
			<td colspan="3" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>
			<td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>	
		    <td class="style1"><div align="right">Total</div></td>
		    <td class="style1"><div align="right">USD</div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=rupiah_format($usd);?>
		    </div></td>
		  </tr>
		  <tr>
		    <td colspan="3" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1"><div align="right">IDR</div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=rupiah_format($idr);?>
		    </div></td>
	      </tr>
		  <tr>
		    <td colspan="8" class="style1" style="border: 1px solid #000;"><div align="center">Reimbst/Refund</div></td>
	       </tr>
		  <tr>
		    <td class="style1" style="border-left: 1px solid #000;">&nbsp;</td>
	        <td width="204" class="style1">Adv Giro/Chq</td>
	        <td width="182" class="style1">:</td>
	        <td colspan="2" class="style1">Rp
            <?=rupiah_format($GiroIdr);?></td>
	        <td class="style1">US
            <?=rupiah_format($GiroUsd);?></td>
	        <td class="style1">&nbsp;</td>
	        <td class="style1" style="border-right: 1px solid #000;">EU</td>
		  </tr>
		  <tr>
		    <td class="style1" style="border-left: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>
		    <td class="style1" style="border-bottom: 1px solid #000;">Adv Cash</td>
		    <td class="style1" style="border-bottom: 1px solid #000;">:</td>
		    <td colspan="2" class="style1" style="border-bottom: 1px solid #000;">Rp
                <?=rupiah_format($CashIdr);?>		    </td>
		    <td class="style1" style="border-bottom: 1px solid #000;">US
                <?=rupiah_format($CashUsd);?>		    </td>
		    <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>
		    <td class="style1" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">EU</td>
	       </tr>	
		  </tfoot>
		</table>
	    <span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="33.33%" class="style1"> Received by</td>
		    <td width="33.33%" class="style1">Approved by</td>
		    <td width="33%" class="style1" ><div align="center">Cashier</div></td>
		  </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="37%" class="style1">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    <td width="37%" class="style1">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    <td class="style1"><div align="center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</div></td>
		   </tr>
		</table>
        <span class="style1">
        <? break;


        	case "Reimburse": ?>
	<div style="font-family:Georgia, "Times New Roman", Times, serif;">
</span>
      <table width="100%" class="content">
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>Reimbursement Request</strong></div></td>
	    </tr>
	    <tr>
                   <td colspan="4" class="style1"><div align="center" style="font-size:16px"><strong><? if($data1->company == 1) { echo 'PT. REMA LOGISTIK INDONESIA';}?></strong></div></td>

	
		  <td colspan="2" class="style1">&nbsp;</td>
	    </tr>
	    <tr>
		  <td width="8%" class="style1">Number</td>
		  <td width="47%" class="style1">: <?=$data1->fld_btno;?></td>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>   
	    </tr>
	    <tr>
	      <td class="style1">Division</td>
	      <td class="style1"> : <?=$data1->fld_bedivnm?></td>
	      <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	 <tr>
              <td class="style1">Date</td>
              <td class="style1">: <?=$data1->req_date; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
         <tr>
                    <td width="12%" class="style1">Period From</td>
                    <td width="10%" class="style1">: <?=$data1->from;?></td>
                    <td width="8%" class="style1">To</td>
                    <td width="10%" class="style1">: <?=$data1->to;?> </td>
          </tr>
           <tr>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr> 

</table>
		<table width="100%" class="content">
		  <tr >
		    <th width="4%" class="style1"style="border: 1px solid #000;">No</th>
		    <th width="19%" class="style1" style="border: 1px solid #000;">Cost Type</th>
		    <th width="44%" class="style1" style="border: 1px solid #000;">Description</th>
		    <th width="13%" class="style1" style="border: 1px solid #000;">Reff Doc</th>
                    <th width="10%" class="style1" style="border: 1px solid #000;">Payment</th>
		    <th width="10%" class="style1" style="border: 1px solid #000;">Amount</th>
	            
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; $TransferUsd=0; $TransferIdr=0;  foreach($data->result() as $row) { 
			 if ($row->currency=='USD'){
			 	$usd=$usd+$row->total;
				if ($row->payment=='CASH'){
					$CashUsd=$CashUsd+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroUsd=$GiroUsd+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferUsd=$TransferUsd+$row->total;
                                }
			 }
			 elseif ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='CASH'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroIdr=$GiroIdr+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferIdr=$TransferIdr+$row->total;
                                }
			 }			  	
		  ?>
		  <tr>
			<td class="style1" style="border-left: 1px solid #000;border-right: 1px solid #000;"><?=$i;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->cost_name;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->desc;?></td>
		
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->docreff;?></td>
		         <td class="style1" style="border-right: 1px solid #000;"><?=$row->pay_type;?></td>

		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->amount,2);?>
		    </div></td>
	
		    
		  </tr>
		 <? $i++; } ?>
		</table>
		<table width="100%" class="content">

		  <tr>

			<td colspan="2" class="style1" style="border-left: 1px solid #000; border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>	

		    <td class="style1" style="border-top:1px solid #000;">Total</td>

		    <td class="style1" style="border-top:1px solid #000;">IDR</td>

		    <td class="style1" style="border-top:1px solid #000;border-right: 1px solid #000;"><div align="right">

		      <?=rupiah_format($row->total);?>

		    </div></td>

		  </tr>

		  <tr>

		    <td colspan="10" class="style1" style="border: 1px solid #000;"><div align="center">Payment Detail</div></td>

	       </tr>

		  <tr>

		    <td class="style1" style="border-left: 1px solid #000;border-bottom: 1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-bottom: 1px solid #000;">Cash </td>

		    <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-bottom: 1px solid #000;">:</td>

		    <td colspan="4" class="style1" style="border-bottom: 1px solid #000;">Rp. <?=rupiah_format($row->total);?></td>

                    <td class="style1" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>                    

	       </tr>	

              <tr>

		  </table> 
   
	<span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="25%" class="style1">Prepared By</td>
		    <td width="25%" class="style1">Approved By</td>
		    <td width="25%" class="style1">Ops.Staff</td>
		    <td width="25%" class="style1" ><div align="right">Cashier</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="36%" class="style1">                      <?=$data1->ops_staff;?>                    </td>

                    <td width="27%" class="style1">&nbsp;</td>
                    <td width="27%" class="style1">                      <?=$data1->ops_staff;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>
          
           
          <tr>
                    <td width="25%" class="style1">Notes:</td>

                    <td width="30%" class="style1">                      <?=$data1->note;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>

		</table>
        <span class="style1">
	</div>
        <? break;

        case "ReimburseCOO": ?>
	<div style="font-family:Georgia, "Times New Roman", Times, serif;">
</span>
      <table width="100%" class="content">
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>COO Reimbursement Request</strong></div></td>
	    </tr>
	    <tr>
		  <td colspan="2" class="style1">&nbsp;</td>
		  <td colspan="2" class="style1">&nbsp;</td>
	    </tr>
	    <tr>
		  <td width="8%" class="style1">Number</td>
		  <td width="47%" class="style1">: <?=$data1->fld_btno;?></td>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>   
	    </tr>
	    <tr>
	      <td class="style1">Division</td>
	      <td class="style1"> : <?=$data1->fld_bedivnm?></td>
	      <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	 <tr>
              <td class="style1">Date</td>
              <td class="style1">: <?=$data1->req_date; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
         <tr>
                    <td width="12%" class="style1">Period From</td>
                    <td width="10%" class="style1">: <?=$data1->from;?></td>
                    <td width="8%" class="style1">To</td>
                    <td width="10%" class="style1">: <?=$data1->to;?> </td>
          </tr>
           <tr>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr> 

</table>
		<table width="100%" class="content">
		  <tr >
		    <th width="4%" class="style1"style="border: 1px solid #000;">No</th>
		    <th width="15%" class="style1" style="border: 1px solid #000;">Cost Type</th>
		    <th width="34%" class="style1" style="border: 1px solid #000;">Description</th>
		    <th width="13%" class="style1" style="border: 1px solid #000;">Reff Doc</th>
                    <th width="10%" class="style1" style="border: 1px solid #000;">COO Type</th>
                    <th width="14%" class="style1" style="border: 1px solid #000;">SKA No</th>
		    <th width="10%" class="style1" style="border: 1px solid #000;">Amount</th>
	            
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; $TransferUsd=0; $TransferIdr=0;  foreach($data->result() as $row) { 
			 if ($row->currency=='USD'){
			 	$usd=$usd+$row->total;
				if ($row->payment=='CASH'){
					$CashUsd=$CashUsd+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroUsd=$GiroUsd+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferUsd=$TransferUsd+$row->total;
                                }
			 }
			 elseif ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='CASH'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroIdr=$GiroIdr+$row->total;
				}
                                if ($row->payment=='TRANSFER'){
                                        $TransferIdr=$TransferIdr+$row->total;
                                }
			 }			  	
		  ?>
		  <tr>
			<td class="style1" style="border-left: 1px solid #000;border-right: 1px solid #000;"><?=$i;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->cost_name;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->desc;?></td>
		
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->docreff;?></td>
		         <td class="style1" style="border-right: 1px solid #000;"><?=$row->coo_type;?></td>
                         <td class="style1" style="border-right: 1px solid #000;"><?=$row->ska_num;?></td>

		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->amount,2);?>
		    </div></td>
	
		    
		  </tr>
		 <? $i++; } ?>
		</table>
		<table width="100%" class="content">

		  <tr>

			<td colspan="2" class="style1" style="border-left: 1px solid #000; border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-top:1px solid #000;">&nbsp;</td>	

		    <td class="style1" style="border-top:1px solid #000;">Total</td>

		    <td class="style1" style="border-top:1px solid #000;">IDR</td>

		    <td class="style1" style="border-top:1px solid #000;border-right: 1px solid #000;"><div align="right">

		      <?=rupiah_format($row->total);?>

		    </div></td>

		  </tr>

		  <tr>

		    <td colspan="10" class="style1" style="border: 1px solid #000;"><div align="center">Payment Detail</div></td>

	       </tr>

		  <tr>

		    <td class="style1" style="border-left: 1px solid #000;border-bottom: 1px solid #000;">&nbsp;</td>

		    <td class="style1" style="border-bottom: 1px solid #000;">Cash </td>

		    <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>

			<td class="style1" style="border-bottom: 1px solid #000;">:</td>

		    <td colspan="4" class="style1" style="border-bottom: 1px solid #000;">Rp. <?=rupiah_format($row->total);?></td>

                    <td class="style1" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>                    

	       </tr>	

              <tr>

		  </table> 
   
	<span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="25%" class="style1">Prepared By</td>
		    <td width="25%" class="style1">Approved By</td>
		    <td width="25%" class="style1">Ops.Staff</td>
		    <td width="25%" class="style1" ><div align="right">Cashier</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="36%" class="style1">                      <?=$data1->ops_staff;?>                    </td>

                    <td width="27%" class="style1">&nbsp;</td>
                    <td width="27%" class="style1">                      <?=$data1->ops_staff;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>
          
           
          <tr>
                    <td width="25%" class="style1">Notes:</td>

                    <td width="30%" class="style1">                      <?=$data1->note;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>

		</table>
        <span class="style1">
	</div>
        <? break;


		
		case "ImpSettlement": ?>
		</span>
        <table class="content" width="100%">
		  <tr>
			<td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>SETTLEMENT REPORT</strong></div></td>
		  </tr>
		  <tr>
			<td colspan="2" class="style1">&nbsp;</td>
			<td colspan="2" class="style1">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="75" class="style1">Number</td>
			<td width="384" class="style1">: <?=$data1->fld_btno;?></td>
			<td width="348" class="style1"><div align="right"></div></td>
            		<td width="296" class="style1"><div align="left"></div></td>
		  </tr>
		  <tr>
		    <td class="style1">Advance</td>
		    <td class="style1">: <?=$data1->advance?></td>
		    <td class="style1"><div align="right"></div></td>
		    <td class="style1"><div align="left"></div></td>
          </tr>
		<tr>
                    <td class="style1">Division</td>
                    <td class="style1">: <?=$data1->division?></td>
                    <td class="style1"><div align="right"></div></td>
                    <td class="style1"><div align="left"></div></td>
          </tr>
		<tr>
                    <td class="style1">Date</td>
                     <td class="style1"><div align="left">: <?=$data1->settle_date?></div></td>
                    <td class="style1"><div align="right"></div></td>
                    <td class="style1"><div align="left"></div></td>
          </tr>
 
		 <tr>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
	      </tr>
</table>
		<table class="grid" width="95%">
		  <tr>
		    <th width="53" class="style1">No</th>
		    <th width="164" class="style1">Job Number</th>
		    <th width="464" class="style1">Customer</th>
		    <th width="150" class="style1">B/L Number</th>
		    <th width="185" class="style1">Rp</th>
		    <th width="79" class="style1">USD</th>
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $UsdRelease=0; $IdrRelease=0; foreach($data->result() as $row) { 
			 $idr=$row->terpakaiidr+$idr; $usd=$row->terpakaiusd+$usd; $UsdRelease=$row->releaseusd+$UsdRelease; 
			 $IdrRelease=$row->releaseidr+$IdrRelease;				  	
		  ?>
		  <tr>
		    <td class="style1"><?=$i;?></td>
		    <td class="style1"><?=$row->jo;?></td>
		    <td class="style1"><?=$row->customer;?></td>
		    <td class="style1"><?=$row->bl;?></td>
		    <td class="style1"><div align="right">
		      <?=rupiah_format($row->terpakaiidr);?>
		    </div></td>
		    <td class="style1"><div align="right">
		      <?=rupiah_format($row->terpakaiusd);?>
		    </div></td>
	      </tr>
		 <? $i++; } ?>
		 <tfoot style="border:none">
		  <tr>
                    <td colspan="2" class="style1" style="border-left: 1px solid #000;">
                        <?
                                $sql="SELECT A.fld_btp08 USD, A.fld_btp12 IDR FROM tbl_bth A where A.fld_btid='$id'";
                                $release=$this->db->query($sql)->row();
                        ?>                      </td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1"><div align="right">Advance</div></td>
                    <td class="style1"><div align="right">
                      <?=rupiah_format($release->IDR);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=rupiah_format($release->USD);?>
                    </div></td>
               </tr>
 
		 <tr>
			<td colspan="2" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>
			<td class="style1">&nbsp;</td>
		    <td class="style1"><div align="right">Spent</div></td>	
		    <td class="style1"><div align="right">
		      <?=rupiah_format($idr);?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=rupiah_format($usd);?>
		    </div></td>
	       </tr>
		   <tr>
                    <td colspan="2" class="style1" style="border-left: 1px solid #000;">
                        <?
                                $sql="SELECT IF( A.fld_btp06 IS NULL OR A.fld_btp06 = '', 0, A.fld_btp06 ) USD, 
					IF( A.fld_btp05 IS NULL OR A.fld_btp05 = '', 0, A.fld_btp05 ) IDR
					FROM tbl_bth A where A.fld_btid='$id'";
                                $opayment=$this->db->query($sql)->row();
                        ?>                      </td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1"><div align="right">Over Payment</div></td>
                    <td class="style1"><div align="right">
                      <?=rupiah_format($opayment->IDR);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=rupiah_format($opayment->USD);?>
                    </div></td>
               </tr>

		  <tr>
		    <td colspan="2" class="style1" style="border-left: 1px solid #000;border-bottom: 1px solid #000;">&nbsp;</td>		    		<td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>
		    <td class="style1" style="border-bottom: 1px solid #000;"><div align="right">Remain </div></td>
		    <td class="style1" style="border-bottom: 1px solid #000;"><div align="right">
		      <?=rupiah_format($release->IDR - $opayment->IDR - $idr);?>
		    </div>
            <div align="right"></div></td>
		    <td class="style1" style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><div align="right">
		      <?=rupiah_format($release->USD - $opayment->USD - $usd);?>
		    </div></td>
	       </tr>	
		  </tfoot>
		</table>
	    <span class="style1"><br/>
	    <br/>	
		</span>
		<table width="100%" class="content">
		  <tr>
		    <td width="25%" class="style1">Prepared By</td>
		    <td width="25%" class="style1">Aproved By</td>
		    <td width="25%" class="style1">Ops.Staff</td>
		    <td width="25%" class="style1" ><div align="right">Cashier</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="36%" class="style1">                      <?=$data1->fld_empnm;?>                    </td>

                    <td width="27%" class="style1">&nbsp;</td>
                    <td width="27%" class="style1">                      <?=$data1->staff;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>
		</table>	
        <span class="style1">
        <? break;
  	   case "PC": ?>
	<br/><br/><br/><br/><br/><br/><br/> <br/><br/><br/><br/><br/><br/><br/>
	<table class="content" width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:20px">
  <tr>
    <td colspan="3">Jakarta, <? print date("d-M-Y"); ?> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Kepada Yth, </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><?=$data1->fld_benm;?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Attn</td>
    <td>:</td>
    <td> Import Dept</td>
  </tr>
  <tr>
    <td>Perihal</td>
    <td>:</td>
    <td> Peminjaman Container </td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">Dengan hormat, </td>
  </tr>
  <tr>
    <td colspan="3">Sehubungan dengan ada barang import kami yang menggunakan pelayaran Bapak/Ibu, maka bersama ini kami ajukan surat permohonan peminjaman container untuk barang import kami sebagai berikut: </td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td>Nama Consignee </td>
    <td>:</td>
    <td> <?=$data1->CUSTOMER?></td>
  </tr>
  <tr>
    <td>No. BL </td>
    <td>:</td>
    <td> <?=$data1->DOKUMEN;?></td>
  </tr>
  <tr>
    <td>Nama Kapal </td>
    <td>:</td>
    <td><?=$data1->fld_btp03;?> / <?=$data1->fld_btp04;?> </td>
  </tr>
  <tr>
    <td>Jumlah Container </td>
    <td>:</td>
    <td>
	<?
			$JmlCon='';
			if ($data1->fld_btqty > 0 && $data1->fld_btp06 > 0)
			{
				$JmlCon=$data1->fld_btqty."x20, ".$data1->fld_btp06."x40";
			}
			if ($data1->fld_btqty == 0 && $data1->fld_btp06 > 0)
			{
				$JmlCon=$data1->fld_btp06."x40";
			}
			if ($data1->fld_btqty > 0 && $data1->fld_btp06 == 0)
			{
				$JmlCon=$data1->fld_btqty."x20";
			}
			print $JmlCon;
		?>
   </td>
  </tr>
  <tr>
    <td>No. Container </td>
    <td>:</td>
    <td> TERLAMPIR DI B/L </td>
  </tr>
  <tr>
    <td colspan="3"><p>Kami akan bertanggung jawab atas kerusakan container selama kami meminjam dan tidak bertanggung jawab atas kerusakan lama sebelum kami meminjam.</p>
    <p>Demikian permohonan kami atas perhatian dan kerjasamanya terima kasih.</p>
    <p>Hormat kami,</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Teguh Kristanto   </p></td>
  </tr>
</table>
<? break;			
   
   case "SRT": ?>
   <div style="font-size:10px">
   <br/><br/><br/><br/><br/>		
<p>To: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Orient Overseas Container Line Limited  (&ldquo;OOCLL&rdquo;) and its affiliates, the Carrier; and the owners, charterers,  operators, insurers, masters and agents of the Vessel and their respective  servants and agents </p>

<p>Dear  Sirs,</p>

<p>Re:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Authorization and Indemnity by <?=$data1->CUSTOMER;?> to OOCLL to endorse and exchange B/L Documents </p>

<p><strong>Goods:</strong>&nbsp; all goods which are consigned to <?=$data1->CUSTOMER;?></p>

<p><strong>B/L Documents </strong>OOCL original bills of lading and/or original and copies of  sea waybills for the Goods of document number <strong><?=$data1->fld_btp08;?></strong> <br>

    <strong>Vessel(s):</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><?=$data1->fld_btp03;?> / <?=$data1->fld_btp04;?></strong> </p>

<p>We, <?=$data1->CUSTOMER;?>,  do hereby authorize and empower PT. DUNIA EXPRESS (&ldquo;the Agent&rdquo;), to be our agent in name,  place and stead, with full power and authority, acting singly, to do all such  acts and things which may be deemed proper in or in connection with the Goods ,  including, without limitation, endorsing the B/L Documents or other forms of  rightful identification on our behalf and to collect the relevant release or  delivery orders for the Goods&nbsp; in  exchange of the B/L Documents. We warrant that the Agent shall have sufficient  and proper authority and capacity to validly and effectually bind us and hereby  undertake to ratify any and all actions of the Agent and shall be liable for  all acts and omissions by the Agent, whether arising under contract (including  indemnity) , tort (including negligence), statute by means of strict liability  or otherwise.</p>

<p>In consideration of your acceptance of  the Agent&rsquo;s endorsement of the B/L Documents as ours and your giving the Agent  all the relevant release or delivery orders for the Goods in exchange for the  B/L Documents, we hereby agree and undertake to indemnify all of you and hold  all of you harmless in respect of any liability, loss, damage or expense of  whatsoever nature howsoever arisen which any of you may sustain in connection  thereof.</p>

<p>This authorization will cancel any  previously issued authorization and will remain in effect and valid until such  time that we provide written notice canceling this authorization letter. We  further agree that this request/declaration and any undertaking or indemnity  contained herein shall be construed and governed in accordance with English  law.</p>

<p>Yours  Faithfully,<br>

  For  and on behalf of <br>

 <?=$data1->CUSTOMER;?>  </p>

<p>&nbsp;</p>
<br/>
<p>&nbsp;</p>

<p>&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;..<br>

  <?=$data1->fld_btp22?><br>

  Kuasa Direksi </p>

<p>IN  WITNESS OF </p>

<p>&nbsp;</p>

<p>&nbsp;</p>
<br/>
<p>
Firmandermawan<br/>
&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;..<br>

  Manager Import</p>
</div>
<? break;
case "CashAdvance1":	
?>
<div style="font-family:Georgia, "Times New Roman", Times, serif;">
</span>
      <table width="100%" class="content">
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>ADVANCE REQUEST SUMMARY</strong></div></td>
	    </tr>
	    <tr>
		   <td colspan="4" class="style1"><div align="center" style="font-size:16px"><strong><? if($data1->userid == 1) { echo 'PT. REMA LOGISTIK INDONESIA';}?></strong></div></td>

		  <td colspan="2" class="style1">&nbsp;</td>
	    </tr>
	    <tr>
		  <td width="15%" class="style1">Number</td>
		  <td width="47%" class="style1">: <?=$data1->fld_btno;?></td>
                  <td width="10%" class="style1">Shipping Line</td>
                  <td width="47%" class="style1">: <?=$data1->shipping;?></td>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>   
	    </tr>
	    <tr>
	      <td class="style1">Division</td>
	      <td class="style1"> : <?=$data1->fld_bedivnm ;?></td>
              <td width="10%" class="style1">Bank Name </td>
              <?
                $bank_name = '';

                if($data1->paytype != 3) {
                        $bank_name = '';
                }
                else {

                        $bank_name = $data1->bank_name;
                }
               ?>

              <td width="47%" class="style1">: <?=$bank_name;?></td>
	      <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	 <tr>
              <td class="style1">Date</td>
              <td class="style1">: <?=$data1->date; ?></td>
              <td width="10%" class="style1">Bank Account </td>
               <?
                $bank_acc = '';

                if($data1->paytype != 3) {
                        $bank_acc = '';
                }
                else {

                        $bank_acc = $data1->bank_acc;
                }
               ?>
              <td width="47%" class="style1">: <?=$bank_acc;?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
        <tr>
              <td class="style1">Operational Staff</td>
              <td class="style1">: <?=$data1->krani; ?></td>
              <td class="style1">Payment Type</td>
              <td class="style1">: <?=$data1->pay; ?></td>
        </tr>

	    <tr>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
        </tr>
</table>
		<table width="100%" class="content">
		  <tr >
		    <th width="3%" class="style1"style="border: 1px solid #000;">No</th>
		    <th width="67%" class="style1" style="border: 1px solid #000;">Advance Number</th>
		    <th width="15%" class="style1" style="border: 1px solid #000;">IDR</th>
		    <th width="15%" class="style1" style="border: 1px solid #000;">USD</th>
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; foreach($data->result() as $row) { 
			 if ($row->currency=='USD'){
			 	$usd=$usd+$row->total;
				if ($row->payment=='CASH'){
					$CashUsd=$CashUsd+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroUsd=$GiroUsd+$row->total;
				}
			 }
			 elseif ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='CASH'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroIdr=$GiroIdr+$row->total;
				}
			 }			  	
		  ?>
		  <tr>
			<td class="style1" style="border-left: 1px solid #000;border-right: 1px solid #000;"><?=$i;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->jo;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->idr,2);?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->usd,2);?>
		    </div></td>
		  </tr>
		 <? $i++; } ?>
                   <tr >
                    <th colspan=2 class="style1" style="border: 1px solid #000;" >Total Payment</th>
                    <th width="15%" class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->tot_idr);?></th>
                    <th width="15%" class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->tot_usd);?></th>
              </tr>

		</table>
	<span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="50%" class="style1">Ops.Staff</td>
		    <td width="25%" class="style1" ><div align="right">Cashier</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="36%" class="style1">                      <?=$data1->krani;?>                    </td>

                    <td width="27%" class="style1">&nbsp;</td>
                    <td width="27%" class="style1">                      <?=$data1->kasir;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>
		</table>
        <span class="style1">
	</div>
        <? break;
           case "CashSettlement1":
        ?>
         <div style="font-family:Georgia, "Times New Roman", Times, serif;">
</span>
      <table width="100%" class="content">
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>SETTLEMENT REPORT SUMMARY</strong></div></td>
	    </tr>
	    <tr>
		   <td colspan="4" class="style1"><div align="center" style="font-size:16px"><strong><? if($data1->usercomp == 1) { echo 'PT. REMA LOGISTIK INDONESIA';}?></strong></div></td>

		  <td colspan="2" class="style1">&nbsp;</td>
	    </tr>
	    <tr>
		  <td width="15%" class="style1">Number</td>
		  <td width="47%" class="style1">: <?=$data1->settnum;?></td>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>   
	    </tr>
	    <tr>
	      <td class="style1">Division</td>
	      <td class="style1"> : <?=$data1->fld_bedivnm ;?></td>
	      <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	 <tr>
              <td class="style1">Date</td>
              <td class="style1">: <?=$data1->date; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
        <tr>
              <td class="style1">Operational Staff</td>
              <td class="style1">: <?=$data1->krani; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>

	    <tr>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
        </tr>
</table>
		<table width="100%" class="content">
		  <tr >
		    <th rowspan=2 width="3%" class="style1"style="border: 1px solid #000;">No</th>
		    <th rowspan=2 width="10%" class="style1" style="border: 1px solid #000;">Settlement Number</th>
		    <th colspan=4 width="15%" class="style1" style="border: 1px solid #000;">IDR</th>
		    <th colspan=4 width="15%" class="style1" style="border: 1px solid #000;">USD</th>
	          </tr>
                  <tr >
                    <th class="style1"style="border: 1px solid #000;">Advance</th>
                    <th class="style1" style="border: 1px solid #000;">Over Payment</th>
                    <th class="style1" style="border: 1px solid #000;">Spent</th>
                    <th class="style1" style="border: 1px solid #000;">Remain</th>
                    <th class="style1"style="border: 1px solid #000;">Advance</th>
                    <th class="style1" style="border: 1px solid #000;">Over Payment</th>
                    <th class="style1" style="border: 1px solid #000;">Spent</th>
                    <th class="style1" style="border: 1px solid #000;">Remain</th>
                  </tr>

		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; foreach($data->result() as $row) { 
			 if ($row->currency=='USD'){
			 	$usd=$usd+$row->total;
				if ($row->payment=='CASH'){
					$CashUsd=$CashUsd+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroUsd=$GiroUsd+$row->total;
				}
			 }
			 elseif ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='CASH'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroIdr=$GiroIdr+$row->total;
				}
			 }			  	
		  ?>
		  <tr>
			<td class="style1" style="border-left: 1px solid #000;border-right: 1px solid #000;"><?=$i;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->jo;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->fld_btamt01,2);?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->fld_btamt03,2);?>
		    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->fld_btamt02,2);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->fld_btamt04,2);?>
                    </div></td>
			<td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->fld_btamt05,2);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->fld_btamt07,2);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->fld_btamt06,2);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->fld_btamt08,2);?>
                    </div></td>

		  </tr>
		 <? $i++; } ?>
                   <tr >
                    <th colspan=2 class="style1" style="border: 1px solid #000;" >Total</th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->fld_btp12);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->fld_btp05);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->fld_btamt);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->fld_btp13);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->fld_btp08);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->fld_btp06);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->fld_btp07);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($row->fld_btp09);?></th>
                  </tr>

		</table>
	<span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="50%" class="style1">Ops.Staff</td>
		    <td width="25%" class="style1" ><div align="right">Cashier</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="36%" class="style1">                      <?=$data1->krani;?>                    </td>

                    <td width="27%" class="style1">&nbsp;</td>
                    <td width="27%" class="style1">                      <?=$data1->kasir;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>
		</table>
        <span class="style1">
	</div> 
     
        <?
        break;
    	case "OperatingCost": ?>
		</span>
        <table class="content" width="100%">
		  <tr>
			<td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>ADVANCE REQUEST</strong></div></td>
		  </tr>
		  <tr>
			<td colspan="2" class="style1">&nbsp;</td>
			<td colspan="2" class="style1">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="175" class="style1">Request Number</td>
			<td width="300" class="style1">: <?=$data1->fld_btno;?></td>
			<td width="384" class="style1"><div align="right">Name</div></td>
            <td width="296" class="style1"><div align="left">: 
              <?=$data1->fld_empnm;?>
            </div></td>
		  </tr>
		  <tr>
		    <td class="style1">Customer</td>
		    <td class="style1">: <?=$data1->fld_benm?></td>
		    <td class="style1"><div align="right">Date</div></td>
		    <td class="style1"><div align="left">: <? print date("d-M-Y"); ?></div></td>
	      </tr>
		  <tr>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
	      </tr>
</table>
		<table class="grid" width="95%">
		  <tr>
		    <th width="43" class="style1">No</th>
		    <th colspan="2" class="style1">Desc</th>
		    <th width="96" class="style1">Type</th>
		    <th width="124" class="style1">Currency</th>
		    <th width="172" class="style1">Amount</th>
		    <th width="88" class="style1">Qty</th>
		    <th width="178" class="style1">Total</th>
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; foreach($data->result() as $row) { 
			 if ($row->currency=='USD'){
			 	$usd=$usd+$row->total;
				if ($row->payment=='Cash'){
					$CashUsd=$CashUsd+$row->total;
				}
				if ($row->payment=='Giro/Chq'){
					$GiroUsd=$GiroUsd+$row->total;
				}
			 }
			 elseif ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='Cash'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='Giro/Chq'){
					$GiroIdr=$GiroIdr+$row->total;
				}
			 }			  	
		  ?>
		  <tr>
		    <td class="style1"><?=$i;?></td>
		    <td colspan="2" class="style1"><?=$row->desc;?></td>
		    <td class="style1"><?=$row->tipe;?></td>
		    <td class="style1"><?=$row->currency;?></td>
		    <td class="style1"><div align="right">
		      <?=rupiah_format($row->amount);?>
		    </div></td>
		    <td class="style1"><div align="right">
		      <?=$row->qty;?>
		    </div></td>
		    <td class="style1"><div align="right">
		      <?=rupiah_format($row->total);?>
		    </div></td>
		  </tr>
		 <? $i++; } ?>
		 <tfoot style="border:none">
		  <tr>
			<td colspan="3" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>
			<td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>	
		    <td class="style1"><div align="right">Total</div></td>
		    <td class="style1"><div align="right">USD</div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=rupiah_format($usd);?>
		    </div></td>
		  </tr>
		  <tr>
		    <td colspan="3" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1"><div align="right">IDR</div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=rupiah_format($idr);?>
		    </div></td>
	      </tr>
		  <tr>
		    <td colspan="8" class="style1" style="border: 1px solid #000;"><div align="center">Reimbst/Refund</div></td>
	       </tr>
		  <tr>
		    <td class="style1" style="border-left: 1px solid #000;">&nbsp;</td>
	        <td width="204" class="style1">Adv Giro/Chq</td>
	        <td width="182" class="style1">:</td>
	        <td colspan="2" class="style1">Rp
            <?=rupiah_format($GiroIdr);?></td>
	        <td class="style1">US
            <?=rupiah_format($GiroUsd);?></td>
	        <td class="style1">&nbsp;</td>
	        <td class="style1" style="border-right: 1px solid #000;">EU</td>
		  </tr>
		  <tr>
		    <td class="style1" style="border-left: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>
		    <td class="style1" style="border-bottom: 1px solid #000;">Adv Cash</td>
		    <td class="style1" style="border-bottom: 1px solid #000;">:</td>
		    <td colspan="2" class="style1" style="border-bottom: 1px solid #000;">Rp
                <?=rupiah_format($CashIdr);?>		    </td>
		    <td class="style1" style="border-bottom: 1px solid #000;">US
                <?=rupiah_format($CashUsd);?>		    </td>
		    <td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>
		    <td class="style1" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">EU</td>
	       </tr>	
		  </tfoot>
		</table>
	    <span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="33.33%" class="style1"> Received by</td>
		    <td width="33.33%" class="style1">Approved by</td>
		    <td width="33%" class="style1" ><div align="center">Cashier</div></td>
		  </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="37%" class="style1">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    <td width="37%" class="style1">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    <td class="style1"><div align="center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</div></td>
		   </tr>
		</table>
        <span class="style1">
        <? break;
		
		case "ImpSettlement": ?>
		</span>
        <table class="content" width="100%">
		  <tr>
			<td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>SETTLEMENT REPORT</strong></div></td>
		  </tr>
		  <tr>
			<td colspan="2" class="style1">&nbsp;</td>
			<td colspan="2" class="style1">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="75" class="style1">Number</td>
			<td width="384" class="style1">: <?=$data1->fld_btno;?></td>
			<td width="348" class="style1"><div align="right"></div></td>
            		<td width="296" class="style1"><div align="left"></div></td>
		  </tr>
		  <tr>
		    <td class="style1">Advance</td>
		    <td class="style1">: <?=$data1->advance?></td>
		    <td class="style1"><div align="right"></div></td>
		    <td class="style1"><div align="left"></div></td>
          </tr>
		<tr>
                    <td class="style1">Division</td>
                    <td class="style1">: <?=$data1->division?></td>
                    <td class="style1"><div align="right"></div></td>
                    <td class="style1"><div align="left"></div></td>
          </tr>
		<tr>
                    <td class="style1">Date</td>
                     <td class="style1"><div align="left">: <?$data1->settle_date?></div></td>
                    <td class="style1"><div align="right"></div></td>
                    <td class="style1"><div align="left"></div></td>
          </tr>
 
		 <tr>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
		    <td class="style1">&nbsp;</td>
	      </tr>
</table>
		<table class="grid" width="95%">
		  <tr>
		    <th width="53" class="style1">No</th>
		    <th width="164" class="style1">Job Number</th>
		    <th width="464" class="style1">Customer</th>
		    <th width="150" class="style1">B/L Number</th>
		    <th width="185" class="style1">Rp</th>
		    <th width="79" class="style1">USD</th>
	      </tr>
		  <? $i=1; $usd=0; $idr=0; $UsdRelease=0; $IdrRelease=0; foreach($data->result() as $row) { 
			 $idr=$row->terpakaiidr+$idr; $usd=$row->terpakaiusd+$usd; $UsdRelease=$row->releaseusd+$UsdRelease; 
			 $IdrRelease=$row->releaseidr+$IdrRelease;				  	
		  ?>
		  <tr>
		    <td class="style1"><?=$i;?></td>
		    <td class="style1"><?=$row->jo;?></td>
		    <td class="style1"><?=$row->customer;?></td>
		    <td class="style1"><?=$row->bl;?></td>
		    <td class="style1"><div align="right">
		      <?=rupiah_format($row->terpakaiidr);?>
		    </div></td>
		    <td class="style1"><div align="right">
		      <?=rupiah_format($row->terpakaiusd);?>
		    </div></td>
	      </tr>
		 <? $i++; } ?>
		 <tfoot style="border:none">
		  <tr>
                    <td colspan="2" class="style1" style="border-left: 1px solid #000;">
                        <?
                                $sql="SELECT A.fld_btp08 USD, A.fld_btp12 IDR FROM tbl_bth A where A.fld_btid='$id'";
                                $release=$this->db->query($sql)->row();
                        ?>                      </td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1"><div align="right">Advance</div></td>
                    <td class="style1"><div align="right">
                      <?=rupiah_format($release->IDR);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=rupiah_format($release->USD);?>
                    </div></td>
               </tr>
 
		 <tr>
			<td colspan="2" class="style1" style="border-left: 1px solid #000;">&nbsp;</td>
			<td class="style1">&nbsp;</td>
		    <td class="style1"><div align="right">Spent</div></td>	
		    <td class="style1"><div align="right">
		      <?=rupiah_format($idr);?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=rupiah_format($usd);?>
		    </div></td>
	       </tr>
		   <tr>
                    <td colspan="2" class="style1" style="border-left: 1px solid #000;">
                        <?
                                $sql="SELECT IF( A.fld_btp06 IS NULL OR A.fld_btp06 = '', 0, A.fld_btp06 ) USD, 
					IF( A.fld_btp05 IS NULL OR A.fld_btp05 = '', 0, A.fld_btp05 ) IDR
					FROM tbl_bth A where A.fld_btid='$id'";
                                $opayment=$this->db->query($sql)->row();
                        ?>                      </td>
                    <td class="style1">&nbsp;</td>
                    <td class="style1"><div align="right">Over Payment</div></td>
                    <td class="style1"><div align="right">
                      <?=rupiah_format($opayment->IDR);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=rupiah_format($opayment->USD);?>
                    </div></td>
               </tr>

		  <tr>
		    <td colspan="2" class="style1" style="border-left: 1px solid #000;border-bottom: 1px solid #000;">&nbsp;</td>		    		<td class="style1" style="border-bottom: 1px solid #000;">&nbsp;</td>
		    <td class="style1" style="border-bottom: 1px solid #000;"><div align="right">Remain </div></td>
		    <td class="style1" style="border-bottom: 1px solid #000;"><div align="right">
		      <?=rupiah_format($release->IDR - $opayment->IDR - $idr);?>
		    </div>
            <div align="right"></div></td>
		    <td class="style1" style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><div align="right">
		      <?=rupiah_format($release->USD - $opayment->USD - $usd);?>
		    </div></td>
	       </tr>	
		  </tfoot>
		</table>
	    <span class="style1"><br/>
	    <br/>	
		</span>
		<table width="100%" class="content">
		  <tr>
		    <td width="25%" class="style1">Prepared By</td>
		    <td width="25%" class="style1">Aproved By</td>
		    <td width="25%" class="style1">Ops.Staff</td>
		    <td width="25%" class="style1" ><div align="right">Cashier</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="36%" class="style1">                      <?=$data1->fld_empnm;?>                    </td>

                    <td width="27%" class="style1">&nbsp;</td>
                    <td width="27%" class="style1">                      <?=$data1->staff;?>                    </td>
                    <td class="style1">&nbsp;</td>
          </tr>
		</table>	
        <span class="style1">
        
<? break;
           case "SettlementReceipt":
        ?>
         <div style="font-family:Georgia, "Times New Roman", Times, serif;">
</span>
      <table width="100%" class="content">
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong>SETTLEMENT RECEIPT</strong></div></td>
	    </tr>
	    <tr>
		   <td colspan="4" class="style1"><div align="center" style="font-size:16px"><strong><? if($data1->usercomp == 1) { echo 'PT. REMA LOGISTIK INDONESIA';}?></strong></div></td>

		  <td colspan="2" class="style1">&nbsp;</td>
	    </tr>
	    <tr>
		  <td width="15%" class="style1">Number</td>
		  <td width="47%" class="style1">: <?=$data1->settnum;?></td>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>   
	    </tr>
	    <tr>
	      <td class="style1">Company</td>
	      <td class="style1"> : REMA</td>
	      <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	 <tr>
              <td class="style1">Date</td>
              <td class="style1">: <?=$data1->date; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
        <tr>
              <td class="style1">Operational Staff</td>
              <td class="style1">: <?=$data1->krani; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>

	    <tr>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
        </tr>
</table>
		<table width="100%" class="content">
		  <tr >
		    <th rowspan=2 width="3%" class="style1"style="border: 1px solid #000;">No</th>
		    <th rowspan=2 width="10%" class="style1" style="border: 1px solid #000;">Settlement Number</th>
		    <th rowspan=2 width="10%" class="style1" style="border: 1px solid #000;">Advance Number</th>
		    <th rowspan=2 width="10%" class="style1" style="border: 1px solid #000;">APV Number</th>
		    <th rowspan=2 width="10%" class="style1" style="border: 1px solid #000;">Payment Type</th>
		    <th colspan=4 width="15%" class="style1" style="border: 1px solid #000;">IDR</th>
	          </tr>
                  <tr >
                    <th class="style1"style="border: 1px solid #000;">Advance</th>
                    <th class="style1" style="border: 1px solid #000;">Over Payment</th>
                    <th class="style1" style="border: 1px solid #000;">Spent</th>
                    <th class="style1" style="border: 1px solid #000;">Remain</th>
                  </tr>

                <?php
                    $adv = 0;
                    $op = 0;
                    $spent = 0;
                    $remain = 0;
                ?>

		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; foreach($data->result() as $row) { 
		  	if ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='CASH'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroIdr=$GiroIdr+$row->total;
				}
			 }			  	
		  ?>
		  <tr>
			<td class="style1" style="border-left: 1px solid #000;border-right: 1px solid #000;"><?=$i;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->jo;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->jocno;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->apvno;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->PaymentType;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->fld_btamt01,2);?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->fld_btamt03,2);?>
		    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->fld_btamt02,2);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->fld_btamt04,2);?>
                    </div></td>

                    <?php
                    $adv = $adv + $row->fld_btamt01;
                    $op = $op + $row->fld_btamt03;
                    $spent = $spent + $row->fld_btamt02;
                    $remain = $remain + $row->fld_btamt04;
                    ?>

		  </tr>
		 <? $i++; } ?>
                   <tr >
                    <th colspan=5 class="style1" style="border: 1px solid #000;" >Total</th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($adv);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($op);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($spent);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($remain);?></th>
                  </tr>

		</table>
	<span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="33%" class="style1" ><div align="center">Aproval 1</div></td>
		    <td width="33%" class="style1" ><div align="center">Aproval 2</div></td>
		    <td width="33%" class="style1" ><div align="center">Aproval 3</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="33%" class="style1"><div align="center">Opr.Staff</div></td>
                    <td width="33%" class="style1"><div align="center">Finance REMA</div></td>
                    <td width="33%" class="style1"><div align="center">Finance DE</div></td>
                    <td class="style1">&nbsp;</td>
          </tr>
		</table>
        <span class="style1">
	</div> 
     

<?
break;

case "AdvanceSubmit":
        ?>
         <div style="font-family:Georgia, "Times New Roman", Times, serif;">
</span>
      <table width="100%" class="content">
	    <tr>
		  <td colspan="4" class="style1"><div align="center" style="font-size:20px"><strong><? if($data1->tyid == 2) { echo 'ADVANCE RECEIPT';} else { echo 'SETTLEMENT RECEIPT';}  ?></strong></div></td>
	    </tr>
	    <tr>
		   <td colspan="4" class="style1"><div align="center" style="font-size:16px"><strong><? if($data1->usercomp == 1) { echo 'PT. REMA LOGISTIK INDONESIA';}?></strong></div></td>

		  <td colspan="2" class="style1">&nbsp;</td>
	    </tr>
	    <tr>
		  <td width="15%" class="style1">Number</td>
		  <td width="47%" class="style1">: <?=$data1->settnum;?></td>
          <td class="style1">&nbsp;</td>
          <td class="style1">&nbsp;</td>   
	    </tr>
	    <tr>
	      <td class="style1">Company</td>
	      <td class="style1"> : Dunia Express</td>
	      <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
	 <tr>
              <td class="style1">Date</td>
              <td class="style1">: <?=$data1->date; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>
        <tr>
              <td class="style1">Operational Staff</td>
              <td class="style1">: <?=$data1->krani; ?></td>
              <td class="style1">&nbsp;</td>
              <td class="style1">&nbsp;</td>
        </tr>

	    <tr>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
	      <td class="style1">&nbsp;</td>
        </tr>
</table>
		<table width="100%" class="content">
		  <tr >
		    <th rowspan=2 width="3%" class="style1"style="border: 1px solid #000;">No</th>
		    <th rowspan=2 width="10%" class="style1" style="border: 1px solid #000;">Settlement Number</th>
		    <th rowspan=2 width="10%" class="style1" style="border: 1px solid #000;">Advance Number</th>
		    <th rowspan=2 width="10%" class="style1" style="border: 1px solid #000;">APV Number</th>
		    <th rowspan=2 width="10%" class="style1" style="border: 1px solid #000;">Payment Type</th>
		    <th colspan=4 width="15%" class="style1" style="border: 1px solid #000;">IDR</th>
	          </tr>
                  <tr >
                    <th class="style1"style="border: 1px solid #000;">Advance</th>
                    <th class="style1" style="border: 1px solid #000;">Over Payment</th>
                    <th class="style1" style="border: 1px solid #000;">Spent</th>
                    <th class="style1" style="border: 1px solid #000;">Remain</th>
                  </tr>

                <?php
                    $adv = 0;
                    $op = 0;
                    $spent = 0;
                    $remain = 0;
                ?>

		  <? $i=1; $usd=0; $idr=0; $CashUsd=0; $CashIdr=0; $GiroUsd=0; $GiroIdr=0; foreach($data->result() as $row) { 
		  	if ($row->currency=='IDR'){
			 	$idr=$idr+$row->total;
				if ($row->payment=='CASH'){
					$CashIdr=$CashIdr+$row->total;
				}
				if ($row->payment=='GIRO/CHQ'){
					$GiroIdr=$GiroIdr+$row->total;
				}
			 }			  	
		  ?>
		  <tr>
			<td class="style1" style="border-left: 1px solid #000;border-right: 1px solid #000;"><?=$i;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->jo;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->jocno;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->apvno;?></td>
			<td class="style1" style="border-right: 1px solid #000;"><?=$row->PaymentType;?></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->Advance,2);?>
		    </div></td>
		    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
		      <?=number_format($row->OverPayment,2);?>
		    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->Spent,2);?>
                    </div></td>
                    <td class="style1" style="border-right: 1px solid #000;"><div align="right">
                      <?=number_format($row->Remain,2);?>
                    </div></td>

                    <?php
                    $adv = $adv + $row->Advance;
                    $op = $op + $row->OverPayment;
                    $spent = $spent + $row->Spent;
                    $remain = $remain + $row->Remain;
                    ?>

		  </tr>
		 <? $i++; } ?>
                   <tr >
                    <th colspan=5 class="style1" style="border: 1px solid #000;" >Total</th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($adv);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($op);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($spent);?></th>
                    <th class="style1" style="border: 1px solid #000;"><?=rupiah_format($remain);?></th>
                  </tr>

		</table>
	<span class="style1"><br/>
	    <br/>	
		</span>
	    <table width="100%" class="content">
		  <tr>
		    <td width="33%" class="style1" ><div align="center">Operational Staff</div></td>
		    <td width="33%" class="style1" ><div align="center">Kadiv/Fungsional EXIM</div></td>
		    <td width="33%" class="style1" ><div align="center">Finance DE</div></td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		  <tr>
                    <td class="style1">&nbsp;</td>
                    <td colspan="2" class="style1">&nbsp;</td>
                    <td class="style1">&nbsp;</td>
          </tr>
		   <tr>
                    <td width="33%" class="style1"><div align="center"><?=$data1->krani; ?></div></td>
                    <td width="33%" class="style1"><div align="center"></div></td>
                    <td width="33%" class="style1"><div align="center"></div></td>
                    <td class="style1">&nbsp;</td>
          </tr>
		</table>
        <span class="style1">
	</div> 
     

<?
break;
}

?>
        </span>
</body>

