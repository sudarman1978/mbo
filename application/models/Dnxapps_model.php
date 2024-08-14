<?php
class Dnxapps_model extends CI_Model {
  function __construct() {
    parent::__construct();
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

function printQR() {
    $fld_btid =  $this->uri->segment(3);
    $getData = $this->db->query("
    select
    t0.fld_btid,
    t0.fld_btno,
    t0.fld_baidc,
    t0.fld_btp01 'attn',
    t0.fld_btp02 'cc',
    if(t0.fld_btdt < '2020-01-01','DWI S PRANOTO','DEWIE AGUSTINA') 'check_by',
    t0.fld_btp04 'phone',
    t0.fld_btp03 'signer',
    t5.fld_benm 'letter_head',
    t1.fld_benm 'company_name',
    date_format(t0.fld_btdt,'%d-%m-%Y') 'qr_date',
    date_format(now(),'%d-%m-%Y') 'date_now',
    date_format(t0.fld_btdtsa,'%d-%m-%Y') 'date_submission',
    date_format(t0.fld_btdtso,'%d-%m-%Y') 'date_submission_complete',
    format(t0.fld_btamt,0) 'total'
    from tbl_bth t0
    left join tbl_be t1 on t1.fld_beid=t0.fld_baidc and t1.fld_betyid=5
    left join tbl_tyval t2 on t2.fld_Tyvalcd=t0.fld_btiid and t2.fld_tyid=63
    left join hris.tbl_emp t3 on t3.fld_empid=t0.fld_btp05
    left join tbl_ba t4 on t4.fld_baid=t0.fld_baido
    left join tbl_be t5 on t5.fld_beid=t4.fld_baid
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
    from tbl_btd_purchase t0
    left join tbl_unit t1 on t1.fld_unitid=t0.fld_unitid
    left join tbl_bti t2 on t2.fld_btiid=t0.fld_btiid
    left join tbl_bti t3 on t3.fld_btiid=t0.fld_btp01
    where
    t0.fld_btidp='$fld_btid'
    ");
    $dataDtl_count = $getDataDtl->num_rows();
    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29.7),$orientation='portrait');
    $this->cezpdf->ezSetMargins(10,5,10,5);
    $this->cezpdf->line(578,810,20,810);
    $this->cezpdf->line(578,665,20,665);
    $this->cezpdf->line(578,635,20,635);
    $this->cezpdf->line(578,550,20,550);
    $this->cezpdf->line(578,520,20,520);
    $this->cezpdf->line(578,260,20,260);
    $this->cezpdf->line(578,240,20,240);
    $this->cezpdf->line(578,135,20,135);
    $this->cezpdf->line(20,665,20,810);
    $this->cezpdf->line(578,665,578,810);
    $this->cezpdf->line(20,550,20,635);
    $this->cezpdf->line(578,550,578,635);
    $this->cezpdf->line(20,260,20,520);
    $this->cezpdf->line(578,260,578,520);
    $this->cezpdf->line(20,135,20,240);
    $this->cezpdf->line(578,135,578,240);
        $this->cezpdf->addJpegFromFile('images/logo_commision.jpg',50,747,55);
        $this->cezpdf->addText(110,785,14,'PT.DUNIA EXPRESS TRANSINDO & PT. DUNIA EXPRESS');
        $this->cezpdf->addText(110,770,12,'Jl.AGUNG KARYA VII NO.1 SUNTER     ');
        $this->cezpdf->addText(110,756,12,'JAKARTA UTARA');
        $this->cezpdf->ezSetMargins(60,135,10,15);

        $this->cezpdf->ezSetDy(-30);
         $header = array(array('row1'=>'DIVISI','row2'=>': MARKETING','row3'=>' ','row4'=>''),
                         array('row1'=>'TITLE','row2'=>': QUOTATION REQUEST FORM','row3'=>'DATE ','row4'=>': MAY, 06'),
                         array('row1'=>'DOCUMENT NO','row2'=>': 001/FORM/MKT','row3'=>'CHECKED BY ','row4'=>': '.$data->check_by ),
                       	array('row1'=>'REVISION NO','row2'=>': 03','row3'=>'APPROVED BY','row4'=>':')
                );
     $this->cezpdf->ezTable($header,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'2','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>90,'justification' => 'left'),
         'row2'=>array('width'=>180,'justification' => 'left'),
         'row3'=>array('width'=>100,'justification' => 'left'),
         'row4'=>array('width'=>160,'justification' => 'left'),
         )));

     $this->cezpdf->ezSetDy(-30);
         $header_two = array(array('row1'=>'NAME','row2'=>': MS. DEWIE AGUSTINA ','row3'=>' ','row4'=>''),
                         array('row1'=>'DEPARTMENT','row2'=>': MARKETING','row3'=>'','row4'=>''),
                         array('row1'=>'DATE OF SUBMISSION','row2'=>': '. $data->date_submission,'row3'=>' ','row4'=>''),
                       	array('row1'=>'DATE OF COMPLETE SUBMISSION','row2'=>': '. $data->date_submission_complete,'row3'=>'','row4'=>'')
                );
     $this->cezpdf->ezTable($header_two,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'2','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>220,'justification' => 'left'),
         'row2'=>array('width'=>200,'justification' => 'left'),
         'row3'=>array('width'=>10,'justification' => 'left'),
         'row4'=>array('width'=>10,'justification' => 'left'),
         )));

          $this->cezpdf->ezSetDy(-30);
         $content_one = array(array('row1'=>'REQUEST','row2'=>'','row3'=>'','row4'=>''),
                         array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),
                         array('row1'=>'Company Name','row2'=>': '. $data->company_name,'row3'=>'','row4'=>''),
                         array('row1'=>'Attn','row2'=>': '. $data->attn,'row3'=>'','row4'=>''),
                       	array('row1'=>'Cc','row2'=>': '. $data->cc,'row3'=>'','row4'=>''),
                        array('row1'=>'Tel','row2'=>': '. $data->phone,'row3'=>'','row4'=>''),
                         array('row1'=>'Letter Head','row2'=>': '. $data->letter_head,'row3'=>'','row4'=>''),
                       	array('row1'=>'Signer','row2'=>': '. $data->signer,'row3'=>'','row4'=>''),
                       	array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),
        	        array('row1'=>'RATE TERLAMPIR','row2'=>'','row3'=>'','row4'=>' ')
                );
     $this->cezpdf->ezTable($content_one,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'2','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>100,'justification' => 'left'),
         'row2'=>array('width'=>250,'justification' => 'left'),
         'row3'=>array('width'=>50,'justification' => 'left'),
         'row4'=>array('width'=>50,'justification' => 'left'),
         )));

if ($dataDtl_count < 350) {
     $this->cezpdf->ezSetDy(-110);
         $footer_one = array(array('row1'=>'For Marketing Purpose Only','row2'=>'','row3'=>'','row4'=>''),
                         array('row1'=>'This quotation has been checked and received','row2'=>'','row3'=>'','row4'=>''),
                         array('row1'=>'Name','row2'=>': ','row3'=>'','row4'=>''),
                         array('row1'=>'Date','row2'=>': ','row3'=>' ','row4'=>''),
                       	array('row1'=>'Signature','row2'=>': ','row3'=>'','row4'=>'Acknowledge by Director')
                );
     $this->cezpdf->ezTable($footer_one,array('row1'=>'','row2'=>'','row3'=>'','row4'=>''),'',array
         ('rowGap'=>'2','xPos'=>50,'xOrientation'=>'right','width'=>450,'shaded'=>0,'fontSize'=>'10','showLines'=>0,'cols'=>array        (
         'row1'=>array('width'=>220,'justification' => 'left'),
         'row2'=>array('width'=>50,'justification' => 'left'),
         'row3'=>array('width'=>50,'justification' => 'left'),
         'row4'=>array('width'=>160,'justification' => 'left'),
         )));
    $this->cezpdf->ezSetY(400);

	}


        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=QR-$data->qr_date.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;
  }




  function printOnlineDO ($fld_btid) {

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://172.17.1.17/index.php/PortalApi/getPrintOnlineDO?fld_btid=".$fld_btid,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"fld_btid\"\r\n\r\n".$fld_btid."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: a36a0333-716c-4a33-9f6b-2f1a60b7a74d",
        "cache-control: no-cache",
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      #$data = json_decode($response,TRUE);
    }
    $data = json_decode($response,TRUE);
    // $rdata=$data[0];

    $detail = $data['data'];

    $fld_btno  = $detail[0]['fld_btno'];
    $fld_btdt  = $detail[0]['date'];

    $this->load->library('cezpdf');
    $this->cezpdf->Cezpdf(array(21.5,29.7),$orientation='portrait');
    $this->cezpdf->ezSetMargins(10,5,10,5);

    $this->cezpdf->addJpegFromFile('assets/images/logo_commision.jpg',50,747,55);
    $this->cezpdf->addText(210,785,14,'PT.DUNIA EXPRESS TRANSINDO');
    $this->cezpdf->addText(230,770,12,'COLD STORAGE ONLINE DO');

    $this->cezpdf->addText(10,720,12,'Trans. Number');
    $this->cezpdf->addText(100,720,12,' : ' . $fld_btno);
    $this->cezpdf->addText(10,710,12,'Date');
    $this->cezpdf->addText(100,710,12,' : ' . $fld_btdt);
    $this->cezpdf->ezSetDy(-150);

    $this->cezpdf->ezTable($detail,array('fld_btinm'=>'','fld_good_qty'=>''),'',
    array('rowGap'=>'6','showLines'=>'0','xPos'=>10,'xOrientation'=>'right','width'=>830,'showHeadings'=>0,'shaded'=>0,'fontSize'=>'10',
    'cols'=>array(
    'fld_btinm'=>array('width'=>220),
    'fld_good_qty'=>array('width'=>100),
     )));





        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=DO_Online_Dunex.pdf");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = $this->cezpdf->ezOutput();
        echo $output;

  }


}
