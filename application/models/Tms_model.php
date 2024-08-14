<?php
class Tms_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function getMenu() {
    $menuq = $this->db->query("select
                               t0.fld_menuid,
                               t0.fld_menuidp,
                               t0.fld_menunm,
                               concat('menu',t0.fld_menuid) 'class' 
                               from tbl_menu t0
                                where 1
                                order by t0.fld_menuorder
                                 ");
     if ($menuq->num_rows() > 0) {
       return $menuq->result();
     }
  }

  function cekWOPartQty($fld_btid) {
    $gsf = $this->db->query("select sum(t0.fld_btqty01) 'qty' from tbl_btd_wo_part t0 left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp where t1.fld_btstat=3 and t0.fld_btreffid = $fld_btid ");
     if ($gsf->num_rows() > 0) {
       foreach ($gsf->result() as $row) {
         return $row->qty;
       }
     }
  }

  function setPOValue($fld_btid,$fld_btamt,$fld_btflag,$fld_btp02) {

    if ($fld_btp02 * 0 == 0) {
      $discount_value =  $fld_btamt * ($fld_btp02 / 100);
    } else {
      $discount_value = 0;
    }

    if ($fld_btflag == 1) {
      $tax_value = ($fld_btamt - $discount_value) * 0.1;
    } else {
      $tax_value = 0;
    }

   $grand_total = ($fld_btamt - $discount_value) +  $tax_value;


    $gsf = $this->db->query("update tbl_bth set fld_btp03=$grand_total,fld_btp04=$discount_value,fld_btp05= $tax_value where fld_btid = $fld_btid limit 1");
  }

    function message($message) {
    echo "<br><div align='center'><font size='5' color='red'>";
    $display_string = $message;
    echo $display_string;
    echo "</font></div>";
    echo "<br><div align='center'><font size='3' color='blue'>";
    echo "Click <a href=javascript:history.back()>back</a> to continue the process ...";
    echo "</font></div>";

    exit();
    }

  function sendmail($to,$subject,$message)
        {
                $this->load->library('email');
                $result = $this->email
                ->from('application@dunextr.com')
                //->reply_to($this->input->post('email'), $this->input->post('name'))   // Optional, an account where a human being reads.
                ->to($to)
                ->subject(''.$subject.'')
                ->message($message)
                ->send();

                        if($result) {
                          $this->email->print_debugger();
                        } else {
                          $this->email->print_debugger();
                        }
        }
    function sendmailpermit($to,$subject,$message)
        {
                $this->load->library('email');
                $result = $this->email
                ->from('application@dunextr.com')
                //->reply_to($this->input->post('email'), $this->input->post('name'))   // Optional, an account where a human being reads.
                ->to('fajar@dunextr.com,septiyant_hardi@dunextr.com')
                ->subject(''.$subject.'')
                ->message($message)
                ->send();

                        if($result) {
                          $this->email->print_debugger();
                        } else {
                          $this->email->print_debugger();
                        }
        }


     function sendmailinsurance($to,$subject,$message)
        {
                $this->load->library('email');
                $result = $this->email
                ->from('application@dunextr.com')
                //->reply_to($this->input->post('email'), $this->input->post('name'))   // Optional, an account where a human being reads.
                ->to('fajar@dunextr.com,andra@dunextr.com,garnisa@dunextr.com')
                ->subject(''.$subject.'')
                ->message($message)
                ->send();

                        if($result) {
                          $this->email->print_debugger();
                        } else {
                          $this->email->print_debugger();
                        }
        }

       function sendmailcomplain($to,$subject,$message)
        {
                $this->load->library('email');
                $result = $this->email
                ->from('application@dunextr.com')
                //->reply_to($this->input->post('email'), $this->input->post('name'))   // Optional, an account where a human being reads.
                ->to('bod@dunextr.com,msc@mail.dunextr.com')
                //->to('david@dunextr.com')
                ->subject(''.$subject.'')
                ->message($message)
                ->send();

                        if($result) {
                          $this->email->print_debugger();
                        } else {
                          $this->email->print_debugger();
                        }
        }

        function sendmailcomplainclose($to,$subject,$message)
        {
                $this->load->library('email');
                $result = $this->email
                ->from('application@dunextr.com')
                //->reply_to($this->input->post('email'), $this->input->post('name'))   // Optional, an account where a human being reads.
                ->to('bod@dunextr.com,msc@mail.dunextr.com')
                //->to('david@dunextr.com')
                ->subject(''.$subject.'')
                ->message($message)
                ->send();

                        if($result) {
                          $this->email->print_debugger();
                        } else {
                          $this->email->print_debugger();
                        }
        }


  function printPO($fld_btid) {
    $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    date_format(t0.fld_btdt,'%d-%m-%Y') 'po_date',
    t1.fld_benm 'supplier_name',
    t1.fld_beaddr 'supplier_addr',
    format(t0.fld_btamt,0) 'subtotal',
    format(t0.fld_btp04,0) 'discount',
    format(t0.fld_btp05,0) 'tax',
    format(t0.fld_btp03,0) 'total',
    t0.fld_btnoalt 'pr_number',
    t1.fld_becity,
    t1.fld_bezip,
    t1.fld_bephone1,
    t1.fld_befax,
    t2.fld_bedivnm,
    t2.fld_bedivcd 'code',
    t4.fld_benm 'company_name',
    t4.fld_beaddr 'company_address',
    t4.fld_becity 'company_city',
    t4.fld_bezip 'company_zip',
    t4.fld_bephone1 'company_phone',
    t4.fld_befax 'company_fax',
    t5.fld_empnm
    from tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baidv
    left join tbl_bediv t2 on t2.fld_bedivid = t0.fld_baidc
    left join tbl_ba t3 on t3.fld_baid=t0.fld_baido
    left join tbl_be t4 on t4.fld_beid=t3.fld_beid
    left join hris.tbl_emp t5 on t5.fld_empid=t0.fld_btp01
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btqty01,
    t1.fld_unitnm,
    if(t0.fld_btiid=2742,concat(t2.fld_btinm,' : ',t0.fld_btcmt),if(t0.fld_btiid=1972,concat(t2.fld_btinm,' : ',t0.fld_btcmt),t2.fld_btinm)) 'item',
    format(t0.fld_btuamt01,0) 'fld_btuamt01',
    format(t0.fld_btamt01,0) 'fld_btamt01'
    from tbl_btd_purchase t0
    left join tbl_bti t2 on t2.fld_btiid=t0.fld_btiid
    where
    t0.fld_btidp='$fld_btid'
    ");
    $total_cost = 0;
    $counteor = 0;
    if ($getDataDtl->num_rows() > 0) {
      $datadtl = $getDataDtl->result_array();
      		    $count = count($datadtl);
		    for ($i=0; $i<$count; ++$i)
		    {
		      $counteor = $counteor + 1;
			 ###Prepare Data
                        $datadtl[$i]['count'] = $counteor;
			$total_cost = $total_cost + $datadtl[$i]['fld_btuamt01'];
                        $total_hour = $total_hour + $datadtl[$i]['fld_btqty01'];

		    }

    }
    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
    $this->cezpdf->ezSetMargins(170,5,10,15);
    if ($dataDtl_count < 25) {
    $this->cezpdf->addText(126,675,10,$data->fld_btno . "   ");
    $this->cezpdf->ezSetDy(-25);
    $data_prn = array(array('row1'=>$data->supplier_name,'row2'=>$data->company_name),
		  array('row1'=>$data->supplier_addr,'row2'=>$data->company_address),
		  array('row1'=>$data->fld_becity ,'row2'=>$data->company_city),
			  );

	$this->cezpdf->ezTable($data_prn,array('row1'=>'','row2'=>''),'',
	  array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>125,'xOrientation'=>'right','width'=>460,'fontSize'=>'10',
	  'cols'=>array('row1'=>array('width'=>270),'row2'=>array('width'=>230))));
    $this->cezpdf->ezSetDY(-102);

	$this->cezpdf->addText(95,595,10,$data->fld_bephone1 . "   "); $this->cezpdf->addText(385,595,10,$data->company_phone . "   ");  $this->cezpdf->addText(495,595,10,$data->company_fax . "   ");

	$this->cezpdf->addText(170,547,10,$data->po_date . "   "); $this->cezpdf->addText(280,547,10,$data->code . "   ");$this->cezpdf->addText(280,527,10,$data->pr_number . "   ");

  ##Print Detail
   $this->cezpdf->ezTable($datadtl,array('fld_btqty01'=>'','fld_unitnm'=>'','item'=>'','fld_btuamt01'=>'','fld_btamt01'=>''),'',
   array('rowGap'=>'0','showLines'=>'0','xPos'=>60,'xOrientation'=>'right','width'=>580,'shaded'=>0,'fontSize'=>'9',
   'cols'=>array('fld_btqty01'=>array('width'=>75),'fld_unitnm'=>array('width'=>75),'item'=>array('width'=>250),'fld_btuamt01'=>array('width'=>60, 'justification'=>'right'),'fld_btamt01'=>array('width'=>70, 'justification'=>'right'))));

    $this->cezpdf->ezSetY(285);

    $data_sum = array(
		  array('row1'=>'Invoice untuk atas nama ' . "  " . $data->company_name,'row2'=>$data->total),
		  array('row1'=>'Pembayaran 2 minggu setelah invoice di terima','row2'=>" "),
		  array('row1'=>'','row2'=>$data->subtota),
		  array('row1'=>'','row2'=>$data->discount),
		  array('row1'=>'','row2'=>$data->tax),
		  array('row1'=>'','row2'=>$data->total));

		  $this->cezpdf->ezTable($data_sum,array('row1'=>'','row2'=>''),'',
	  array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'11',
	  'cols'=>array('row1'=>array('width'=>470),'row2'=>array('width'=>80,'justification'=>'right') )));


    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',array('rowGap'=>'0','xPos'=>0,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array('row1'=>array('width'=>450),'row2'=>array('width'=>100,'justification' => 'right'))));

    $this->cezpdf->ezSetY(115);

   $data_summary = array(array('row1'=>$data->fld_empnm,'row2'=>'Jakarta, ' . $data->po_date),
                );

    $this->cezpdf->ezTable($data_summary,array('row1'=>'','row2'=>''),'',array('rowGap'=>'0','xPos'=>350,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array('row1'=>array('width'=>100),'row2'=>array('width'=>120,'justification' => 'right'))));

	}


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=po-$data->po_date.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;

  }
 function printPR() {
    $fld_btid =  $this->uri->segment(3);
    $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    date_format(t0.fld_btdt,'%d-%m-%Y') 'pr_date',
    date_format(now(),'%d-%m-%Y') 'date_now',
    format(t0.fld_btamt,0) 'total',
    if(fld_baidc in (5),t0.fld_btp02,t4.fld_empnm)'approved',
    t2.fld_bedivnm,
    t3.fld_empnm 'posted',
    t2.fld_bedivcd 'code'
    from dnxapps.tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baidv
    left join tbl_bediv t2 on t2.fld_bedivid = t0.fld_baidc
    left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_baidp
    left join hris.tbl_emp t4 on t4.fld_empid=t0.fld_btp01
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid,
    concat(t0.fld_btqty01,' ',t1.fld_unitnm) 'fld_btqty01',
    ifnull(t3.fld_bticd,t0.fld_btp02)'fld_unitnm',
    t0.fld_btdesc 'btdesc',
    if(t0.fld_btiid in (2742,3012),t0.fld_btcmt,if(t0.fld_btiid=1972,concat(t2.fld_btinm,' : ',t0.fld_btcmt),t2.fld_btinm)) 'item',
    format(t0.fld_btuamt01,0) 'fld_btuamt01',
    format(t0.fld_btamt01,0) 'fld_btamt01'
    from dnxapps.tbl_btd_purchase t0
    left join tbl_unit t1 on t1.fld_unitid=t0.fld_unitid
    left join tbl_bti t2 on t2.fld_btiid=t0.fld_btiid
    left join tbl_bti t3 on t3.fld_btiid=t0.fld_btp01
    where
    t0.fld_btidp='$fld_btid'
    ");
    $total_cost = 0;
    $counteor = 0;
    if ($getDataDtl->num_rows() > 0) {
      $datadtl = $getDataDtl->result_array();
      		    $count = count($datadtl);
		    for ($i=0; $i<$count; ++$i)
		    {
		      $counteor = $counteor + 1;
			 ###Prepare Data
                        $datadtl[$i]['count'] = $counteor;
		    }

    }

    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,788,30);
        $this->cezpdf->addText(90,810,9,'PT.Dunia Express Transindo     ');
        $this->cezpdf->addText(90,800,9,'Jl.Agung Karya VII No.1 Sunter     ');
        $this->cezpdf->addText(90,790,9,'Jakarta Utara');
        $this->cezpdf->ezSetMargins(60,135,10,15);

$this->cezpdf->ezText("FORM REQUEST" . "   ", 14, array('justification' => 'center'));
        $this->cezpdf->addText(420,730,9,'No.    '.'        : '.'Form/PD/002/PURCH     ');
        $this->cezpdf->addText(420,720,9,'Rev.  '.'        : '.'01     ');
        $this->cezpdf->addText(420,710,9,'Tgl. Efektif'.' : '.'24 September 2013    ');
        $this->cezpdf->addText(70,730,9,'Request No     :');
	$this->cezpdf->addText(70,720,9,'Request Date  :');
	$this->cezpdf->addText(70,710,9,'Division            :');
	$this->cezpdf->addText(47,675,9,'No   ');
	$this->cezpdf->addText(140,675,9,'Item ');
	$this->cezpdf->addText(280,675,9,'Quantity   ');
         $this->cezpdf->addText(380,675,9,'Remaks ');
	$this->cezpdf->addText(470,675,9,'Description');
    $this->cezpdf->addText(460,675,9,' ');
    $this->cezpdf->addText(135,730,8,$data->fld_btno . "   ");
    $this->cezpdf->addText(135,720,8,$data->pr_date. "   ");
    $this->cezpdf->addText(135,710,8,$data->fld_bedivnm . "   ");
    if ($dataDtl_count < 250) {
    $this->cezpdf->ezSetDy(-80);
  ##Print Detail
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
    #$this->cezpdf->line(578,627,10,627);
    #$this->cezpdf->line(578,641,10,641);
   $this->cezpdf->ezTable($datadtl,array('count'=>'','item'=>'','fld_btqty01'=>'','fld_unitnm'=>'','btdesc'=>''),'',
   array('rowGap'=>'4','showLines'=>'1','xPos'=>50,'xOrientation'=>'right','width'=>180,'shaded'=>0,'fontSize'=>'8',
   'cols'=>array('counteor'=>array('width'=>10),
   'item'=>array('width'=>200),
   'fld_btqty01'=>array('width'=>70,'justification'=>'center'),
   'fld_unitnm'=>array('width'=>120,'justification'=>'center'),
   'btdesc'=>array('width'=>110,'justification'=>'center'),
    )));
     $this->cezpdf->ezSetDy(-40);
	 $acc = array(array('row1'=>'Jakarta,'.$data->date_now),
                );
     $this->cezpdf->ezTable($acc,array('row1'=>''),'',array
	 ('rowGap'=>'0','xPos'=>450,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array	 (
	 'row1'=>array('width'=>120,'justification' => 'left'),
	 )));
	  $this->cezpdf->ezSetDy(-10);
	 $acc = array(array('row1'=>'Applicant','row2'=>'Division Head','row3'=>'Aknowledge','row4'=>'Purchasing Manager'),
                );
     $this->cezpdf->ezTable($acc,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
	 ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array	 (
	 'row1'=>array('width'=>120,'justification' => 'center'),
	 'row2'=>array('width'=>120,'justification' => 'center'),
	 'row3'=>array('width'=>120,'justification' => 'center'),
	 'row4'=>array('width'=>120,'justification' => 'center'),
	 )));
    $this->cezpdf->ezSetDy(-20);
	 $acc1 = array(array('row1'=>$data->posted,'row2'=>$data->approved,'row3'=>'YOHANES','row4'=>'TJUNG SIAT FHA'),
                );
     $this->cezpdf->ezTable($acc1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
	 ('rowGap'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array	 (
	 'row1'=>array('width'=>120,'justification' => 'center'),
	 'row2'=>array('width'=>120,'justification' => 'center'),
	 'row3'=>array('width'=>120,'justification' => 'center'),
	 'row4'=>array('width'=>120,'justification' => 'center'),
	 )));
//    $this->cezpdf->ezSetY(385);

	}


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=pr-$data->pr_date.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }

  function PrintL3($btid) {
  $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    date_format(t0.fld_btdt,'%d-%m-%Y') 'pr_date',
    date_format(t0.fld_btdtsa,'%d-%m-%Y') 'datesa',
    date_format(t0.fld_btdtso,'%d-%m-%Y') 'dateso',
    t0.fld_btp01,
    t0.fld_btcmt,
    t0.fld_btp02,
    t0.fld_btp27,
    t0.fld_btp26,
    t0.fld_btp28,
    t0.fld_btdesc,
    t0.fld_btp10,
    t0.fld_btp29,
    date_format(t0.fld_btp11,'%d-%m-%Y') 'Acknowledge',
    concat(t1.fld_empnm , ' [' , t1.fld_empnip , ']') 'Posted'
    from tbl_bth t0
    left join hris.tbl_emp t1 on t1.fld_empid = t0.fld_baidp
    where
    t0.fld_btid='$btid'
    ");
    $data = $getData->row();

    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,28),$orientation='portrait');
    //$this->cezpdf->ezSetMargins(5,5,5,5);
    $this->cezpdf->ezSetMargins(200,5,10,15);

   $this->cezpdf->addText(250,720,15,'COMPLAIN LOG');
        $this->cezpdf->addText(450,720,9,$data->fld_btno . "   ");
        $this->cezpdf->ezSetDY(80);
        $data_hdr = array(
                        array('row1'=>'Detail Complaint ','row2'=>'','row3'=>''),
                        array('row1'=>'------------------------------ ','row2'=>'','row3'=>''),
                        array('row1'=>'Complain Date','row2'=>':','row3'=>$data->pr_date),
                        array('row1'=>'Reported By ','row2'=>':','row3'=>$data->fld_btp01),
                        array('row1'=>'Company / Division','row2'=>':','row3'=>$data->fld_btp02),
                        array('row1'=>'Complain Description','row2'=>':','row3'=>$data->fld_btcmt),
                        array('row1'=>'Response ','row2'=>'','row3'=>''),
                        array('row1'=>'------------------------------ ','row2'=>'','row3'=>''),
                        array('row1'=>'PIC','row2'=>':','row3'=>$data->fld_btp02),
                        array('row1'=>'Start Date','row2'=>':','row3'=>$data->datesa),
                        array('row1'=>'Finish date','row2'=>'','row3'=>$data->dateso),
                        array('row1'=>'Division','row2'=>':','row3'=>$data->fld_btp02),
                        array('row1'=>'Problem Identification','row2'=>':','row3'=>$data->fld_btp26),
                        array('row1'=>'Corrective Action','row2'=>':','row3'=>$data->fld_btp27),
                        array('row1'=>'Review Summary ','row2'=>'','row3'=>''),
                        array('row1'=>'------------------------------ ','row2'=>'','row3'=>''),
                        array('row1'=>'Root Cause','row2'=>':','row3'=>$data->fld_btp28),
                        array('row1'=>'Conclusion','row2'=>':','row3'=>$data->fld_btdesc),
                        array('row1'=>'Preventive Action','row2'=>':','row3'=>$data->fld_btp29),
                        array('row1'=>'Acknowledge By','row2'=>':','row3'=>$data->fld_btp10),
                        array('row1'=>'Acknowledge Date','row2'=>'','row3'=>$data->Acknowledge),
                        array('row1'=>'','row2'=>'','row3'=>''),
                        array('row1'=>'Posted By','row2'=>':','row3'=>$data->Posted),
                        );
        $this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>''),'',
        array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>20,'xOrientation'=>'right','width'=>750,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>120)
        ,'row2'=>array('width'=>15),'row3'=>array('width'=>420)
     )));

	    $this->cezpdf->ezSetDY(-20);


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=pr-$data->fld_btno.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  #echo"O###KE $btid";
  #exit();
  }

  function printPRR() {
   $fld_btid =  $this->uri->segment(3);
   $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    date_format(t0.fld_btdt,'%d-%m-%Y') 'pr_date',
    format(t0.fld_btamt,0) 'total',
    t2.fld_bedivnm,
    t2.fld_bedivcd 'code'
    from tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baidv
    left join tbl_bediv t2 on t2.fld_bedivid = t0.fld_baidc
    where
    t0.fld_btid='$fld_btid'
    ");
    $data = $getData->row();
    $getDataDtl = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btqty01,
    t1.fld_unitnm,
    if(t0.fld_btiid=2742,t0.fld_btcmt,if(t0.fld_btiid=1972,concat(t2.fld_btinm,' : ',t0.fld_btcmt),t2.fld_btinm)) 'item',
    format(t0.fld_btuamt01,0) 'fld_btuamt01',
    format(t0.fld_btamt01,0) 'fld_btamt01'
    from tbl_btd_purchase t0
    left join tbl_unit t1 on t1.fld_unitid=t0.fld_unitid
    left join tbl_bti t2 on t2.fld_btiid=t0.fld_btiid
    where
    t0.fld_btidp='$fld_btid'
    ");
    $total_cost = 0;
    $counteor = 0;
    if ($getDataDtl->num_rows() > 0) {
      $datadtl = $getDataDtl->result_array();
      		    $count = count($datadtl);
		    for ($i=0; $i<$count; ++$i)
		    {
		      $counteor = $counteor + 1;
			 ###Prepare Data
                        $datadtl[$i]['count'] = $counteor;
		    }

    }

    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
    $this->cezpdf->ezSetMargins(110,5,10,15);

    #$this->cezpdf->ezText("FORM REQUEST" . "   ", 14, array('justification' => 'center'));
    $this->cezpdf->addText(480,710,8,$data->fld_btno . "   ");
    $this->cezpdf->addText(395,710,8,$data->fld_bedivnm . "   ");
    $this->cezpdf->addText(480,700,8,$data->pr_date . "   ");
    if ($dataDtl_count < 250) {
    $this->cezpdf->ezSetDy(-129);

  ##Print Detail
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
    #$this->cezpdf->line(578,627,10,627);
    #$this->cezpdf->line(578,641,10,641);
   $this->cezpdf->ezTable($datadtl,array('count'=>'','item'=>'','fld_btqty01'=>'',''=>''),'',
   array('rowGap'=>'5','showLines'=>'0','xPos'=>50,'xOrientation'=>'right','width'=>180,'shaded'=>0,'fontSize'=>'8',
   'cols'=>array('counteor'=>array('width'=>10),'item'=>array('width'=>200),'fld_btqty01'=>array('width'=>70),'fld_unitnm'=>array('width'=>70),)));

    $this->cezpdf->ezSetY(385);

	}


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=pr-$data->pr_date.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }


  function printCashAdvance($fld_btid) {
   $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
     select
     t0.fld_btid 'crud',
     t0.fld_btno 'trans_no',
     substr(t1.fld_empnm,1,20) 'employee',
     t2.fld_bedivnm 'division',
     t0.fld_btuamt 'amount',
     SUBSTRING(t0.fld_btdesc,1, 60) 'purpose',
     SUBSTRING(t0.fld_btdesc,61, 60) 'purpose1',
     t3.fld_benm 'company',
     date_format(t0.fld_btdt,'%d-%m-%Y') 'trans_date'
     from
     dnxapps.tbl_bth t0
     left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_btiid
     left join dnxapps.tbl_bediv t2 on t2.fld_bedivid=t0.fld_baidc
     left join dnxapps.tbl_be t3 on t3.fld_beid=t0.fld_baido
     where
     t0.fld_bttyid=25
     and
     t0.fld_btid=$fld_btid

    ");
    $data = $getData->row();
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29.7),$orientation='portrait');
    $this->cezpdf->ezSetMargins(30,5,30,5);
    $this->cezpdf->addJpegFromFile('images/logo.jpg',50,755,35);
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(-55);
     $this->cezpdf->addText(440,775,7,'Form number : FRM/002/FAD');
     $this->cezpdf->ezText( "CASH ADVANCE REQUEST   ",12, array('justification' => 'center'));
        $this->cezpdf->addText(95,775,10,'PT Dunia Express Transindo');
        $this->cezpdf->addText(95,760,10,'PT Dunia Express');

  #    $this->cezpdf->ezText($data->company . "   ",10, array('justification' => 'left'));
    if ($dataDtl_count < 10) {
  #  $this->cezpdf->ezText("Request No :".$data->trans_no . "   ",10, array('justification' => 'left'));
        $this->cezpdf->ezSetDy(-45);
	$this->cezpdf->addText(170,710,10,':  '.$data->trans_no);
        $this->cezpdf->addText(170,690,10,':  '.$data->company);
	$this->cezpdf->addText(170,670,10,':  '.number_format($data->amount,2,',','.'));
 	$this->cezpdf->addText(170,650,10,':  ');
 	$this->cezpdf->addText(170,630,10,':  ');
	$this->cezpdf->addText(440,690,10,':  '.$data->employee);
	$this->cezpdf->addText(440,670,10,':  '.$data->division);
        $this->cezpdf->addText(170,610,10,':  '.$data->purpose);
        $this->cezpdf->addText(170,590,10,'   '.$data->purpose1);
        $this->cezpdf->addText(440,710,10,':  '.$data->trans_date);
        $this->cezpdf->addText(50,710,10,'Request No');
        $this->cezpdf->addText(50,690,10,'Company');
        $this->cezpdf->addText(50,670,10,'Amount Requested');
	$this->cezpdf->addText(50,650,10,'Spent');
	$this->cezpdf->addText(50,630,10,'Remains');
	$this->cezpdf->addText(50,610,10,'Purpose of Cash Advance');
        $this->cezpdf->addText(350,710,10,'Request Date  ');
	$this->cezpdf->addText(350,690,10,'Name  ');
	$this->cezpdf->addText(350,670,10,'Division  ');

	##Print Detail
 	$this->cezpdf->ezSetDy(50);
	$nl = 667;
        $this->cezpdf->ezSetDy(-200);
        $this->cezpdf->addText(70,540,10,"Jakarta ,".$data->trans_date);
         $this->cezpdf->ezSetDy(-10);
        $note = array(array('row1'=>"Requester",'row2'=>"Ass Manager/Manager Division",'row3'=>"Finance Manager",'row4'=>"Cashier"),
        );
        $this->cezpdf->ezTable($note,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
	  array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));
       $this->cezpdf->ezSetDy(-40);
        $person= array(array('row1'=>"( ".$data->employee." )",'row2'=>"(                                     )",'row3'=>"(      Johan      )",'row4'=>"(                                )"),);
        $this->cezpdf->ezTable($person,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));
#$this->cezpdf->addText(80,425,10,'.......................................................................................................................................................................');
$this->cezpdf->addJpegFromFile('images/logo.jpg',50,370,35);
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(-90);
     $this->cezpdf->ezText( "SETTLEMENT   ",12, array('justification' => 'center'));
     $this->cezpdf->addText(440,390,7,'Form number : FRM/002/FAD');
        $this->cezpdf->addText(95,390,10,'PT Dunia Express Transindo');
        $this->cezpdf->addText(95,375,10,'PT Dunia Express');
$this->cezpdf->ezSetDy(-350);
        $this->cezpdf->addText(170,320,10,':  '.$data->trans_no);
        $this->cezpdf->addText(170,300,10,':  '.$data->company);
        $this->cezpdf->addText(170,280,10,':  '.number_format($data->amount,2,',','.'));
        $this->cezpdf->addText(170,260,10,':  ');
        $this->cezpdf->addText(170,240,10,':  ');
        $this->cezpdf->addText(170,220,10,':  '.$data->purpose);
        $this->cezpdf->addText(170,200,10,'   '.$data->purpose1);
        $this->cezpdf->addText(440,320,10,':  '.$data->trans_date);
	$this->cezpdf->addText(440,300,10,':  '.$data->employee);
	$this->cezpdf->addText(440,280,10,':  '.$data->division);
        $this->cezpdf->addText(50,320,10,'Request No');
        $this->cezpdf->addText(50,300,10,'Company');
        $this->cezpdf->addText(50,280,10,'Amount Requested');
        $this->cezpdf->addText(50,260,10,'Spent');
        $this->cezpdf->addText(50,240,10,'Remains');
        $this->cezpdf->addText(50,220,10,'Purpose of Cash Advance');
        $this->cezpdf->addText(350,320,10,'Request Date  ');
	$this->cezpdf->addText(350,300,10,'Name  ');
	$this->cezpdf->addText(350,280,10,'Division  ');

$this->cezpdf->ezSetDy(50);
        $nl = 667;
        $this->cezpdf->ezSetDy(100);
        $this->cezpdf->addText(70,160,10,"Jakarta ,".$data->trans_date);
         $this->cezpdf->ezSetDy(-10);
        $note = array(array('row1'=>"Requester",'row2'=>"Ass Manager/Manager Division",'row3'=>"Finance Manager",'row4'=>"Cashier"),
        );
        $this->cezpdf->ezTable($note,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));
       $this->cezpdf->ezSetDy(-40);
        $person= array(array('row1'=>"( ".$data->employee." )",'row2'=>"(                                     )",'row3'=>"(      Johan      )",'row4'=>"(                                )"),);
        $this->cezpdf->ezTable($person,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));

    }

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=cash_advance.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }

  function print_legal_request($fld_btid) {
   $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
     select
     t0.fld_btid 'crud',
     t0.fld_btno 'trans_no',
     substr(t1.fld_empnm,1,20) 'employee',
     t2.fld_bedivnm 'division',
     t5.fld_benm 'customer',
     date_format(t0.fld_btdtsa, '%d-%m-%Y') 'periodestart',
     date_format(t0.fld_btdtso, '%d-%m-%Y') 'periodeend',
     t4.fld_tyvalnm 'requestedlegalproduct',
     t6.fld_tyvalnm 'doclegality',
     t3.fld_benm 'company',
     t0.fld_btp03 'handphone',
     t0.fld_btp04 'email',
     t0.fld_btp07 'agreement',
     t0.fld_btdesc 'Desc',
     date_format(t0.fld_btdt,'%d-%m-%Y') 'trans_date'
     from tbl_bth t0
     left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_btiid
     left join dnxapps.tbl_bediv t2 on t2.fld_bedivid=t0.fld_baidc
     left join dnxapps.tbl_be t3 on t3.fld_beid=t0.fld_baido
     left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp02 and t4.fld_tyid=52
     left join dnxapps.tbl_be t5 on t5.fld_beid=t0.fld_btp01 and t5.fld_betyid=5
     left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btidp and t6.fld_tyid=53
     where
     t0.fld_bttyid=46
     and
     t0.fld_btid=$fld_btid


    ");
    $data = $getData->row();
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29.7),$orientation='portrait');
    $this->cezpdf->ezSetMargins(30,5,30,5);
    $this->cezpdf->addJpegFromFile('images/logo.jpg',50,755,35);
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(-55);
     $this->cezpdf->addText(440,775,7,'Form number : 058/FRM/PGA/17');
     $this->cezpdf->ezText( "LEGAL REQUEST   ",12, array('justification' => 'center'));
        $this->cezpdf->addText(95,775,10,'PT Dunia Express Transindo');
        $this->cezpdf->addText(95,760,10,'PT Dunia Express');
     ##$pecah = explode("\r\n\r\n", $data->agreement);
     ##$text = "";
     ##for ($i=0; $i<=count($pecah)-1; $i++)
     ##{
      ##$part = str_replace($pecah[$i], "<p>".$pecah[$i]."</p>", $pecah[$i]);
      ##$text .= $part;
     #}
  #    $this->cezpdf->ezText($data->company . "   ",10, array('justification' => 'left'));
    if ($dataDtl_count < 10) {
  #  $this->cezpdf->ezText("Request No :".$data->trans_no . "   ",10, array('justification' => 'left'));
        $this->cezpdf->ezSetDy(-45);
	$this->cezpdf->addText(170,710,10,':  '.$data->trans_no);
        $this->cezpdf->addText(170,690,10,':  '.$data->company);
	$this->cezpdf->addText(170,670,10,':  '.$data->requestedlegalproduct);
 	$this->cezpdf->addText(170,650,10,':  '.$data->customer);
 	$this->cezpdf->addText(170,630,10,':  '.$data->periodestart);
	$this->cezpdf->addText(440,690,10,':  '.$data->employee);
	$this->cezpdf->addText(440,670,10,':  '.$data->division);
        $this->cezpdf->addText(170,610,10,':  '.$data->periodeend);
        $this->cezpdf->addText(170,590,10,':  '.$data->handphone);
        $this->cezpdf->addText(170,570,10,':  '.$data->email);
        $this->cezpdf->addText(440,710,10,':  '.$data->trans_date);
        $this->cezpdf->addText(50,710,10,'Request No');
        $this->cezpdf->addText(50,690,10,'Company');
        $this->cezpdf->addText(50,670,10,'Requested Legal Product');
	$this->cezpdf->addText(50,650,10,'Customer');
	$this->cezpdf->addText(50,630,10,'Time Periode Start');
	$this->cezpdf->addText(50,610,10,'Time Periode End');
        $this->cezpdf->addText(50,590,10,'Customer Handphone');
        $this->cezpdf->addText(50,570,10,'Customer Email');
        $this->cezpdf->addText(350,710,10,'Request Date  ');
	$this->cezpdf->addText(350,690,10,'Name  ');
	$this->cezpdf->addText(350,670,10,'Division  ');


         $this->cezpdf->ezSetDy(-140);
        $agree1 = array(array('row1'=>"Agreement Name",'row2'=>" ",'row3'=>"Description",'row4'=>" "),
        );
        $this->cezpdf->ezTable($agree1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'left'),'row2'=>array('width'=>50,'justification'=>'left'),'row3'=>array('width'=>200,'justification'=>'left'),'row4'=>array('width'=>50,'justification'=>'left'))));
       $this->cezpdf->ezSetDy(-5);
        $agreement1= array(array('row1'=>"$data->agreement",'row2'=>" ",'row3'=>"$data->Desc",'row4'=>" "),);
        $this->cezpdf->ezTable($agreement1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'left'),'row2'=>array('width'=>50,'justification'=>'left'),'row3'=>array('width'=>200,'justification'=>'left'),'row4'=>array('width'=>50,'justification'=>'left'))));

	##Print Detail
 	$this->cezpdf->ezSetDy(50);
	$nl = 667;
        $this->cezpdf->ezSetDy(-100);
        $this->cezpdf->addText(60,380,10,"Jakarta ,".$data->trans_date);
         $this->cezpdf->ezSetDy(-100);
        $note = array(array('row1'=>"Requester",'row2'=>" ",'row3'=>"Head Of Division ",'row4'=>" "),
        );
        $this->cezpdf->ezTable($note,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
	  array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));
       $this->cezpdf->ezSetDy(-80);
        $person= array(array('row1'=>"( ".$data->employee." )",'row2'=>" ",'row3'=>"(                                    )",'row4'=>" "),);
        $this->cezpdf->ezTable($person,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));


$this->cezpdf->ezSetDy(-80);

$this->cezpdf->addText(40,200,10,'Quality Control :');
$this->cezpdf->addText(40,185,10,'Head Of The Related Division');

$this->cezpdf->ezSetDy(-80);
        $person1= array(array('row1'=>"(                            )",'row2'=>"(                                 )",'row3'=>"(                               )",'row4'=>"(                               )"),);
        $this->cezpdf->ezTable($person1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>150,'justification'=>'center'),'row2'=>array('width'=>150,'justification'=>'center'),'row3'=>array('width'=>150,'justification'=>'center'),'row4'=>array('width'=>150,'justification'=>'center'))));

    }

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=legal_request.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }

function print_permit_request($fld_btid) {
   $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
     select
     t0.fld_btid 'crud',
     t0.fld_btno 'trans_no',
     substr(t1.fld_empnm,1,20) 'employee',
     t2.fld_bedivnm 'division',
     t5.fld_benm 'customer',
     date_format(t0.fld_btdtsa, '%d-%m-%Y') 'periodestart',
     date_format(t0.fld_btdtso, '%d-%m-%Y') 'periodeend',
     t4.fld_tyvalnm 'requestedlegalproduct',
     t6.fld_tyvalnm 'doclegality',
     t3.fld_benm 'company',
     t0.fld_btp03 'handphone',
     t0.fld_btp04 'email',
     t0.fld_btp07 'agreement',
     t0.fld_btdesc 'Desc',
     date_format(t0.fld_btdt,'%d-%m-%Y') 'trans_date'
     from tbl_bth t0
     left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_btiid
     left join dnxapps.tbl_bediv t2 on t2.fld_bedivid=t0.fld_baidc
     left join dnxapps.tbl_be t3 on t3.fld_beid=t0.fld_baido
     left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp02 and t4.fld_tyid=54
     left join dnxapps.tbl_be t5 on t5.fld_beid=t0.fld_btp01 and t5.fld_betyid=5
     left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btidp and t6.fld_tyid=53
     where
     t0.fld_bttyid=47
     and
     t0.fld_btid=$fld_btid


    ");
    $data = $getData->row();
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29.7),$orientation='portrait');
    $this->cezpdf->ezSetMargins(30,5,30,5);
    $this->cezpdf->addJpegFromFile('images/logo.jpg',50,755,35);
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(-55);
     $this->cezpdf->addText(440,775,7,'Form number : 058/FRM/PGA/17');
     $this->cezpdf->ezText( "PERMIT REQUEST   ",12, array('justification' => 'center'));
        $this->cezpdf->addText(95,775,10,'PT Dunia Express Transindo');
        $this->cezpdf->addText(95,760,10,'PT Dunia Express');
     ##$pecah = explode("\r\n\r\n", $data->agreement);
     ##$text = "";
     ##for ($i=0; $i<=count($pecah)-1; $i++)
     ##{
      ##$part = str_replace($pecah[$i], "<p>".$pecah[$i]."</p>", $pecah[$i]);
      ##$text .= $part;
     #}
  #    $this->cezpdf->ezText($data->company . "   ",10, array('justification' => 'left'));
    if ($dataDtl_count < 10) {
  #  $this->cezpdf->ezText("Request No :".$data->trans_no . "   ",10, array('justification' => 'left'));
        $this->cezpdf->ezSetDy(-45);
        $this->cezpdf->addText(170,710,10,':  '.$data->trans_no);
        $this->cezpdf->addText(170,690,10,':  '.$data->company);
        $this->cezpdf->addText(170,670,10,':  '.$data->requestedlegalproduct);
       # $this->cezpdf->addText(170,650,10,':  '.$data->customer);
        $this->cezpdf->addText(170,650,10,':  '.$data->periodestart);
        $this->cezpdf->addText(440,690,10,':  '.$data->employee);
        $this->cezpdf->addText(440,670,10,':  '.$data->division);
        $this->cezpdf->addText(170,630,10,':  '.$data->periodeend);
        #$this->cezpdf->addText(170,590,10,':  '.$data->handphone);
        #$this->cezpdf->addText(170,570,10,':  '.$data->email);
        $this->cezpdf->addText(440,710,10,':  '.$data->trans_date);
        $this->cezpdf->addText(50,710,10,'Request No');
        $this->cezpdf->addText(50,690,10,'Company');
        $this->cezpdf->addText(50,670,10,'Requested Legal Product');
        #$this->cezpdf->addText(50,650,10,'Customer');
        $this->cezpdf->addText(50,650,10,'Time Periode Start');
        $this->cezpdf->addText(50,630,10,'Time Periode End');
        #$this->cezpdf->addText(50,590,10,'Customer Handphone');
        #$this->cezpdf->addText(50,570,10,'Customer Email');
        $this->cezpdf->addText(350,710,10,'Request Date  ');
        $this->cezpdf->addText(350,690,10,'Name  ');
        $this->cezpdf->addText(350,670,10,'Division  ');


         $this->cezpdf->ezSetDy(-140);
        $agree1 = array(array('row1'=>"Agreement Name",'row2'=>" ",'row3'=>"Description",'row4'=>" "),
        );
        $this->cezpdf->ezTable($agree1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'left'),'row2'=>array('width'=>50,'justification'=>'left'),'row3'=>array('width'=>200,'justification'=>'left'),'row4'=>array('width'=>50,'justification'=>'left'))));
       $this->cezpdf->ezSetDy(-5);
        $agreement1= array(array('row1'=>"$data->agreement",'row2'=>" ",'row3'=>"$data->Desc",'row4'=>" "),);
        $this->cezpdf->ezTable($agreement1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'left'),'row2'=>array('width'=>50,'justification'=>'left'),'row3'=>array('width'=>200,'justification'=>'left'),'row4'=>array('width'=>50,'justification'=>'left'))));

        ##Print Detail
        $this->cezpdf->ezSetDy(50);
        $nl = 667;
        $this->cezpdf->ezSetDy(-100);
        $this->cezpdf->addText(60,380,10,"Jakarta ,".$data->trans_date);
         $this->cezpdf->ezSetDy(-100);
        $note = array(array('row1'=>"Requester",'row2'=>" ",'row3'=>"Head Of Division ",'row4'=>" "),
        );
        $this->cezpdf->ezTable($note,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));
       $this->cezpdf->ezSetDy(-80);
        $person= array(array('row1'=>"( ".$data->employee." )",'row2'=>" ",'row3'=>"(                                    )",'row4'=>" "),);
        $this->cezpdf->ezTable($person,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));


$this->cezpdf->ezSetDy(-80);

$this->cezpdf->addText(40,200,10,'Quality Control :');
$this->cezpdf->addText(40,185,10,'Head Of The Related Division');

$this->cezpdf->ezSetDy(-80);
        $person1= array(array('row1'=>"(                            )",'row2'=>"(                                 )",'row3'=>"(                               )",'row4'=>"(                               )"),);
        $this->cezpdf->ezTable($person1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
         array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>150,'justification'=>'center'),'row2'=>array('width'=>150,'justification'=>'center'),'row3'=>array('width'=>150,'justification'=>'center'),'row4'=>array('width'=>150,'justification'=>'center'))));

    }

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=permit_request.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }

   function print_insurance_claim($fld_btid) {
   $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
     select
     t0.fld_btid 'crud',
     t0.fld_btno 'trans_no',
     substr(t1.fld_empnm,1,20) 'employee',
     t2.fld_bedivnm 'division',
     t5.fld_benm 'customer',
     t4.fld_tyvalnm 'typeclaim',
     date_format(t0.fld_btdtsa, '%d-%m-%Y') 'periodestart',
     date_format(t0.fld_btdtso, '%d-%m-%Y') 'periodeend',
     t4.fld_tyvalnm 'requestedlegalproduct',
     t6.fld_tyvalnm 'doclegality',
     t3.fld_benm 'company',
     t0.fld_btp03 'contactperson',
     t0.fld_btp04 'email',
     t0.fld_btp09 'handphone',
     t0.fld_btdesc 'Desc',
     t0.fld_btp08 'headof',
     t0.fld_btp14 'headoffin',
     t0.fld_btp15 'headofpga',
     date_format(t0.fld_btdt,'%d-%m-%Y') 'trans_date'
     from tbl_bth t0
     left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_btiid
     left join dnxapps.tbl_bediv t2 on t2.fld_bedivid=t0.fld_baidc
     left join dnxapps.tbl_be t3 on t3.fld_beid=t0.fld_baido
     left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btp02 and t4.fld_tyid=56
     left join dnxapps.tbl_be t5 on t5.fld_beid=t0.fld_btidp and t5.fld_betyid=5
     left join tbl_tyval t6 on t6.fld_tyvalcd=t0.fld_btidp and t6.fld_tyid=53
     where
     t0.fld_bttyid=48
     and
     t0.fld_btid=$fld_btid


    ");

      $data = $getData->row();
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29.7),$orientation='portrait');
    $this->cezpdf->ezSetMargins(30,5,30,5);
    $this->cezpdf->addJpegFromFile('images/logo.jpg',50,755,35);
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(-55);
     $this->cezpdf->addText(440,775,7,'Form number : 006/FRM/PGA/17');
     $this->cezpdf->ezText( "INSURANCE CLAIM REQUEST   ",12, array('justification' => 'center'));
        $this->cezpdf->addText(95,775,10,'PT Dunia Express Transindo');
        $this->cezpdf->addText(95,760,10,'PT Dunia Express');
     ##$pecah = explode("\r\n\r\n", $data->agreement);
     ##$text = "";
     ##for ($i=0; $i<=count($pecah)-1; $i++)
     ##{
      ##$part = str_replace($pecah[$i], "<p>".$pecah[$i]."</p>", $pecah[$i]);
      ##$text .= $part;
     #}
  #    $this->cezpdf->ezText($data->company . "   ",10, array('justification' => 'left'));
    if ($dataDtl_count < 10) {
  #  $this->cezpdf->ezText("Request No :".$data->trans_no . "   ",10, array('justification' => 'left'));
        $this->cezpdf->ezSetDy(-45);
        $this->cezpdf->addText(170,710,10,':  '.$data->trans_no);
        $this->cezpdf->addText(170,690,10,':  '.$data->company);
        $this->cezpdf->addText(170,670,10,':  '.$data->typeclaim);
        $this->cezpdf->addText(170,650,10,':  '.$data->customer);
        #$this->cezpdf->addText(170,650,10,':  '.$data->periodestart);
        $this->cezpdf->addText(440,690,10,':  '.$data->employee);
        $this->cezpdf->addText(440,670,10,':  '.$data->division);
        $this->cezpdf->addText(170,630,10,':  '.$data->contactperson);
        $this->cezpdf->addText(170,610,10,':  '.$data->handphone);
        $this->cezpdf->addText(170,590,10,':  '.$data->email);
        $this->cezpdf->addText(440,710,10,':  '.$data->trans_date);
        $this->cezpdf->addText(50,710,10,'Request No');
        $this->cezpdf->addText(50,690,10,'Company');
        $this->cezpdf->addText(50,670,10,'Claim Type');
        $this->cezpdf->addText(50,650,10,'Customer');
        #$this->cezpdf->addText(50,650,10,'Time Periode Start');
        $this->cezpdf->addText(50,630,10,'Contact Person');
        $this->cezpdf->addText(50,610,10,'Handphone');
        $this->cezpdf->addText(50,590,10,'Email');
        $this->cezpdf->addText(350,710,10,'Request Date  ');
        $this->cezpdf->addText(350,690,10,'Name  ');
        $this->cezpdf->addText(350,670,10,'Division  ');


         $this->cezpdf->ezSetDy(-120);
        $agree1 = array(array('row1'=>"Description",'row2'=>" ",'row3'=>" ",'row4'=>" "),
        );
        $this->cezpdf->ezTable($agree1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'left'),'row2'=>array('width'=>50,'justification'=>'left'),'row3'=>array('width'=>200,'justification'=>'left'),'row4'=>array('width'=>50,'justification'=>'left'))));
       $this->cezpdf->ezSetDy(-5);
        $agreement1= array(array('row1'=>"$data->Desc",'row2'=>" ",'row3'=>" ",'row4'=>" "),);
        $this->cezpdf->ezTable($agreement1,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'left'),'row2'=>array('width'=>50,'justification'=>'left'),'row3'=>array('width'=>200,'justification'=>'left'),'row4'=>array('width'=>50,'justification'=>'left'))));

        ##Print Detail
        $this->cezpdf->ezSetDy(50);
        $nl = 680;
        $this->cezpdf->ezSetDy(-90);
        $this->cezpdf->addText(50,420,10,"Jakarta ,".$data->trans_date);
         $this->cezpdf->ezSetDy(-100);
        $note = array(array('row1'=>"Requester",'row2'=>" ",'row3'=>"  ",'row4'=>" "),
        );
        $this->cezpdf->ezTable($note,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'left'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));
       $this->cezpdf->ezSetDy(-50);
        $person= array(array('row1'=>"( ".$data->employee." )",'row2'=>" ",'row3'=>" ",'row4'=>" "),);
        $this->cezpdf->ezTable($person,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'left'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));

$this->cezpdf->ezSetDy(-40);
$this->cezpdf->addText(50,280,10,'APPROVED BY :');
$this->cezpdf->ezSetDy(-25);
        $personhead= array(array('row1'=>"HEAD DIVISION",'row2'=>"HEAD OF FINANCE",'row3'=>"HEAD OF PGA"),);
        $this->cezpdf->ezTable($personhead,array('row1'=>'','row2'=>'','row3'=>''),'',
         array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>5,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>200,'justification'=>'center'),'row3'=>array('width'=>200,'justification'=>'center'))));

$this->cezpdf->ezSetDy(-80);
        $person1= array(array('row1'=>"( " .$data->headof. " )",'row2'=>"( " . $data->headoffin ." )",'row3'=>"( ". $data->headofpga ." )"),);
        $this->cezpdf->ezTable($person1,array('row1'=>'','row2'=>'','row3'=>''),'',
         array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>5,'xOrientation'=>'right','width'=>900,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>200,'justification'=>'center'),'row3'=>array('width'=>200,'justification'=>'center'))));

    }

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=insurance_claim.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }



  function printCashAdvanceDE($fld_btid) {
   $fld_btid =  $this->uri->segment(3);
    $getData =$this->db->query("
     select
     t0.fld_btid 'crud',
     t0.fld_btno 'trans_no',
     substr(t1.fld_empnm,1,20) 'employee',
     t2.fld_bedivnm 'division',
     t0.fld_btamt 'amount',
     SUBSTRING(t0.fld_btdesc, 1, 60) 'purpose',
     t3.fld_benm 'company',
     date_format(t0.fld_btdt,'%d-%m-%Y') 'trans_date'
     from
     exim.tbl_bth t0
     left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_btiid
     left join exim.tbl_bediv t2 on t2.fld_bedivid=t0.fld_baidc
     left join exim.tbl_be t3 on t3.fld_beid=t0.fld_baido
     where
     t0.fld_bttyid=2
     and
     t0.fld_btid=$fld_btid

    ");
    $data = $getData->row();
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29.7),$orientation='portrait');
    $this->cezpdf->ezSetMargins(30,5,30,5);
    $this->cezpdf->addJpegFromFile('images/logo.jpg',50,755,35);
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(-55);
     $this->cezpdf->addText(440,775,7,'Form number : FRM/002/FAD');
     $this->cezpdf->ezText( "CASH ADVANCE REQUEST   ",12, array('justification' => 'center'));
        $this->cezpdf->addText(95,775,10,'PT Dunia Express Transindo');
        $this->cezpdf->addText(95,760,10,'PT Dunia Express');

  #    $this->cezpdf->ezText($data->company . "   ",10, array('justification' => 'left'));
    if ($dataDtl_count < 10) {
  #  $this->cezpdf->ezText("Request No :".$data->trans_no . "   ",10, array('justification' => 'left'));
        $this->cezpdf->ezSetDy(-45);
	$this->cezpdf->addText(170,710,10,':  '.$data->trans_no);
        $this->cezpdf->addText(170,690,10,':  '.$data->company);
	$this->cezpdf->addText(170,670,10,':  '.number_format($data->amount,2,',','.'));
 	$this->cezpdf->addText(170,650,10,':  ');
 	$this->cezpdf->addText(170,630,10,':  ');
	$this->cezpdf->addText(440,690,10,':  '.$data->employee);
	$this->cezpdf->addText(440,670,10,':  '.$data->division);
        $this->cezpdf->addText(170,610,10,':  '.$data->purpose);
        $this->cezpdf->addText(440,710,10,':  '.$data->trans_date);
        $this->cezpdf->addText(50,710,10,'Request No');
        $this->cezpdf->addText(50,690,10,'Company');
        $this->cezpdf->addText(50,670,10,'Amount Requested');
	$this->cezpdf->addText(50,650,10,'Spent');
	$this->cezpdf->addText(50,630,10,'Remains');
	$this->cezpdf->addText(50,610,10,'Purpose of Cash Advance');
        $this->cezpdf->addText(350,710,10,'Request Date  ');
	$this->cezpdf->addText(350,690,10,'Name  ');
	$this->cezpdf->addText(350,670,10,'Division  ');

	##Print Detail
 	$this->cezpdf->ezSetDy(50);
	$nl = 667;
        $this->cezpdf->ezSetDy(-200);
        $this->cezpdf->addText(70,540,10,"Jakarta ,".$data->trans_date);
         $this->cezpdf->ezSetDy(-10);
        $note = array(array('row1'=>"Requester",'row2'=>"Ass Manager/Manager Division",'row3'=>"Finance Manager",'row4'=>"Cashier"),
        );
        $this->cezpdf->ezTable($note,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
	  array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));
       $this->cezpdf->ezSetDy(-40);
        $person= array(array('row1'=>"( ".$data->employee." )",'row2'=>"(                                     )",'row3'=>"( INDRA FEBI OKTIANO )",'row4'=>"(                                )"),);
        $this->cezpdf->ezTable($person,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));
#$this->cezpdf->addText(80,425,10,'.......................................................................................................................................................................');
$this->cezpdf->addJpegFromFile('images/logo.jpg',50,370,35);
    $this->cezpdf->setStrokeColor(0,0,0);
    $this->cezpdf->setLineStyle(1);
     $this->cezpdf->ezSetDy(-90);
     $this->cezpdf->ezText( "SETTLEMENT   ",12, array('justification' => 'center'));
     $this->cezpdf->addText(440,390,7,'Form number : FRM/002/FAD');
        $this->cezpdf->addText(95,390,10,'PT Dunia Express Transindo');
        $this->cezpdf->addText(95,375,10,'PT Dunia Express');
$this->cezpdf->ezSetDy(-220);
        $this->cezpdf->addText(170,320,10,':  '.$data->trans_no);
        $this->cezpdf->addText(170,300,10,':  '.$data->company);
        $this->cezpdf->addText(170,280,10,':  '.number_format($data->amount,2,',','.'));
        $this->cezpdf->addText(170,260,10,':  ');
        $this->cezpdf->addText(170,240,10,':  ');
        $this->cezpdf->addText(170,220,10,':  '.$data->purpose);
        $this->cezpdf->addText(440,320,10,':  '.$data->trans_date);
	$this->cezpdf->addText(440,300,10,':  '.$data->employee);
	$this->cezpdf->addText(440,280,10,':  '.$data->division);
        $this->cezpdf->addText(50,320,10,'Request No');
        $this->cezpdf->addText(50,300,10,'Company');
        $this->cezpdf->addText(50,280,10,'Amount Requested');
        $this->cezpdf->addText(50,260,10,'Spent');
        $this->cezpdf->addText(50,240,10,'Remains');
        $this->cezpdf->addText(50,220,10,'Purpose of Cash Advance');
        $this->cezpdf->addText(350,320,10,'Request Date  ');
	$this->cezpdf->addText(350,300,10,'Name  ');
	$this->cezpdf->addText(350,280,10,'Division  ');

$this->cezpdf->ezSetDy(50);
        $nl = 667;
        $this->cezpdf->ezSetDy(100);
        $this->cezpdf->addText(70,160,10,"Jakarta ,".$data->trans_date);
         $this->cezpdf->ezSetDy(-90);
        $note = array(array('row1'=>"Requester",'row2'=>"Ass Manager/Manager Division",'row3'=>"Finance Manager",'row4'=>"Cashier"),
        );
        $this->cezpdf->ezTable($note,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>200,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>120,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));
       $this->cezpdf->ezSetDy(-40);
        $person= array(array('row1'=>"( ".$data->employee." )",'row2'=>"(                                     )",'row3'=>"(  INDRA FEBI OKTIANO   )",'row4'=>"(                                )"),);
        $this->cezpdf->ezTable($person,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>10,'xOrientation'=>'right','width'=>800,'fontSize'=>'10','cols'=>array('row1'=>array('width'=>190,'justification'=>'center'),'row2'=>array('width'=>120,'justification'=>'center'),'row3'=>array('width'=>150,'justification'=>'center'),'row4'=>array('width'=>120,'justification'=>'center'))));

    }

    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=cash_advance.pdf");
    header("Pragma: no-cache");
    header("Expires: 0");
    $output = $this->cezpdf->ezOutput();
    echo $output;
  }
  function printSalary($fld_empid,$periode){
    $hris = $this->load->database('hris', TRUE);
    $gsf = $hris->query("
    select
    t0.fld_periode,
    t2.fld_benm,
    t3.fld_empnm,
    t3.fld_empnrk,
    t3.fld_empbasepay,
    t0.fld_basepay,
    t3.fld_emptaxstat,
    t0.fld_overtimepay,
    t0.fld_latecharge,
    t0.fld_funcpay,
    t0.fld_transpay,
    t0.fld_medicalpay,
    t0.fld_mealpay,
    t0.fld_comploan,
    t0.fld_cooploan,
    t0.fld_additionalpay,
    t0.fld_aviva,
    t0.fld_avivaplbk,
    t0.fld_avivaemp,
    t0.fld_additionalgh,
    t4.fld_tyvalnm,
    t0.fld_ritasepay,
    t0.fld_absencecharge,
    t0.fld_astekcharge1,
    t0.fld_astekcharge2,
    t0.fld_transpaycut,
    t0.fld_grosspay,
    t0.fld_emppphcharge,
    t0.fld_coopcharge,
    t0.fld_bpjscharge1,
    t0.fld_bpjscharge2,
    t0.fld_deduction,
    t0.fld_addattendance,
    t0.fld_vehicleallowance,
    t5.fld_bedivnm,
    t0.fld_totalpay,
    t3.fld_empfpid,
    t0.fld_pensioncharge1,
    t0.fld_pensioncharge2
    from hris.tbl_empsalary t0
    left join hris.tbl_ba t1 on t1.fld_baid=t0.fld_company
    left join hris.tbl_be t2 on t2.fld_beid=t1.fld_beid
    left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_empid
    left join hris.tbl_tyval t4 on t4.fld_tyvalcd=t3.fld_emptaxstat and t4.fld_tyid=29
    inner join hris.tbl_bediv t5 on t5.fld_bedivid=t3.fld_empdiv
    left join hris.tbl_tyval t6 on t6.fld_tyvalcd=t3.fld_emplevel and t6.fld_tyid=19
    left join hris.tbl_bth t7 on t7.fld_btid = t0.fld_btid
    where
    t0.fld_periode = '$periode'
    and
    t0.fld_empid = $fld_empid
    and t7.fld_btflag = 1
    and t6.fld_tyvalcfg = 3
    ");
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,14),$orientation='portrait');
    $this->cezpdf->ezSetMargins(10,5,10,5);
    if ($gsf->num_rows() > 0) {
      foreach ($gsf->result() as $row) {
        $this->cezpdf->addJpegFromFile('images/logo.jpg',50,325,50);
        $data_kop = array(array('row1'=>$row->fld_benm),
                  array('row1'=>"Jl. Agung Karya VII No.1 , Sunter"),
                  array('row1'=>"Jakarta Utara 14340")
                );
        $this->cezpdf->ezTable($data_kop,array('row1'=>''),'',
          array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>122,'xOrientation'=>'right','width'=>470,'fontSize'=>'9','cols'=>array('row1'=>array('width'=>300))));
        $this->cezpdf->ezSetDy(-5);

        $data_hdr = array(array('row1'=>'Periode :','row2'=>$row->fld_periode,'row3'=>'NRK :','row4'=>$row->fld_empnrk,'row5'=>'Divisi :','row6'=>$row->fld_bedivnm),
		  array('row1'=>'','row2'=>'','row3'=>'Nama :','row4'=>$row->fld_empnm,'row5'=>'Status Pajak :','row6'=>$row->fld_tyvalnm)
		);
	$this->cezpdf->ezTable($data_hdr,array('row1'=>'','row2'=>'','row3'=>'','row4'=>'','row5'=>'','row6'=>''),'',
	  array('rowGap'=>'0.5','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'9','cols'=>array('row1'=>array('width'=>60),'row2'=>array('width'=>70),'row3'=>array('width'=>50),'row4'=>array('width'=>180),'row5'=>array('width'=>80),'row6'=>array('width'=>120))));
        $this->cezpdf->ezSetDy(-5);

        $tot1=$row->fld_medicalpay+$row->fld_avivaplbk+$row->fld_astekcharge2+$row->fld_mealpay+$row->fld_ritasepay+$row->fld_additionalpay+$row->fld_transpay+$row->fld_funcpay+$row->fld_overtimepay+$row->fld_basepay+$row->fld_additionalgh+$row->fld_vehicleallowance+$row->fld_addattendance+$row->fld_bpjscharge1+$row->fld_pensioncharge1;
        $tot2=$row->fld_medicalpay+$row->fld_aviva+$row->fld_astekcharge2+$row->fld_cooploan+$row->fld_comploan+$row->fld_astekcharge1+$row->fld_emppphcharge+$row->fld_transpaycut+$row->fld_latecharge+$row->fld_absencecharge+$row->fld_coopcharge+$row->fld_deduction+$row->fld_ritasepay+$row->fld_bpjscharge1+$row->fld_bpjscharge2+$row->fld_pensioncharge1+$row->fld_pensioncharge2+$row->fld_avivaemp;
        $tot3=$tot1 - $tot2;

         $data_dtl = array(array('row1'=>"Gaji Pokok",'row2'=>number_format($row->fld_basepay,0),'row3'=>"Pot. Absen",'row4'=>number_format($row->fld_absencecharge,0)),
                  array('row1'=>"Lembur",'row2'=>number_format($row->fld_overtimepay,0),'row3'=>"Pot. Telat",'row4'=>number_format($row->fld_latecharge,0)),
                  array('row1'=>"Tunj. Jabatan",'row2'=>number_format($row->fld_funcpay,0),'row3'=>"Pot. Libur",'row4'=>number_format($row->fld_transpaycut,0)),
                  array('row1'=>"Transport",'row2'=>number_format($row->fld_transpay,0),'row3'=>'PPH 21','row4'=>number_format($row->fld_emppphcharge,0)),
                  array('row1'=>"Tambahan Lain-lain",'row2'=>number_format($row->fld_additionalpay,0),'row3'=>'Astek Karyawan','row4'=>number_format($row->fld_astekcharge1,0)),
                  array('row1'=>"Tambahan Rit",'row2'=>number_format($row->fld_ritasepay,0),'row3'=>'Pot. Pinjaman Prshn','row4'=>number_format($row->fld_comploan,0)),
                  array('row1'=>"Uang Makan",'row2'=>number_format($row->fld_mealpay,0),'row3'=>'Pot. Pinj. Koperasi','row4'=>number_format($row->fld_cooploan,0)),
                  array('row1'=>"Astek Prshn",'row2'=>number_format($row->fld_astekcharge2,0),'row3'=>'Pblk Astek Prshn','row4'=>number_format($row->fld_astekcharge2,0)),
                  array('row1'=>"As.Simas Prshn",'row2'=>number_format($row->fld_avivaplbk,0),'row3'=>'Pblk As.Simas Prshn','row4'=>number_format($row->fld_aviva,0)),
                  array('row1'=>"Uang Obat",'row2'=>number_format($row->fld_medicalpay,0),'row3'=>'Uang Obat','row4'=>number_format($row->fld_medicalpay,0)),
                  array('row1'=>"Tambahan Ganti Hari",'row2'=>number_format($row->fld_additionalgh,0),'row3'=>'Iuran Koperasi','row4'=>number_format($row->fld_coopcharge,0)),
                  array('row1'=>"Service Kendaraan",'row2'=>number_format($row->fld_vehicleallowance,0),'row3'=>'Pot. Lain-lain','row4'=>number_format($row->fld_deduction,0)),
                  array('row1'=>"BPJS Kes.Prshn",'row2'=>number_format($row->fld_bpjscharge1,0),'row3'=>'Pblk BPJS Kes.Prshn','row4'=>number_format($row->fld_bpjscharge1,0)),
                  array('row1'=>" ",'row2'=>'','row3'=>'BPJS Kes.Karyawan','row4'=>number_format($row->fld_bpjscharge2,0)),
                  array('row1'=>"Pensiun Prshn",'row2'=>number_format($row->fld_pensioncharge1,0),'row3'=>'Pblk Pensiun Prshn','row4'=>number_format($row->fld_pensioncharge1,0)),
                  array('row1'=>" ",'row2'=>'','row3'=>'Pensiun Karyawan','row4'=>number_format($row->fld_pensioncharge2,0)),
                  array('row1'=>"Pblk Absensi",'row2'=>number_format($row->fld_addattendance,0),'row3'=>'Pembalik Gaji','row4'=>number_format($row->fld_ritasepay,0)),
                  array('row1'=>" ",'row2'=>" ",'row3'=>'As.Simas Karyawan','row4'=>number_format($row->fld_avivaemp,0)),
                  array('row1'=>'','row2'=>'-------------- +','row3'=>'','row4'=>'------------- +'),
                  array('row1'=>"T O T A L",'row2'=>number_format($tot1,0),'row3'=>'T O T A L','row4'=>number_format($tot2,0)),
                  array('row1'=>'===============','row2'=>'===============','row3'=>'','row4'=>''),
                  array('row1'=>"Gaji Terima",'row2'=>number_format($row->fld_totalpay,0),'row3'=>'','row4'=>''),
                  array('row1'=>'===============','row2'=>'===============','row3'=>'','row4'=>'')

                );
        $this->cezpdf->ezTable($data_dtl,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',
          array('rowGap'=>'0.3','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>50,'xOrientation'=>'right','width'=>460,'fontSize'=>'9','cols'=>array('row1'=>array('width'=>100),'row2'=>array('width'=>100,'justification'=>'right'),'row3'=>array('width'=>100,'left'=>10),'row4'=>array('width'=>100,'justification'=>'right'))));

 $data_foot = array(array('row1'=>$row->fld_empnm,'row2'=>'')

                );
        $this->cezpdf->ezTable($data_foot,array('row1'=>'','row2'=>''),'',
          array('rowGap'=>'8','showHeadings'=>0,'shaded'=>0,'showLines'=>0,'xPos'=>430,'xOrientation'=>'right','width'=>460,'fontSize'=>'9','cols'=>array('row1'=>array('width'=>180,'justification'=>'center'),'row2'=>array('width'=>50,'justification'=>'center'))));
    #    $this->cezpdf->ezNewPage();
        $this->cezpdf->ezSetY(365);
      }
     }

     header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=salalry_slip.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
exit();
  }

}
