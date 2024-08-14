<?
error_reporting(0);
$curl = curl_init();
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
// session_start();
$fld_baidc_desc=$_SESSION['customer_nm'];
$fld_baidc=$_SESSION['customer'];
$fld_baidp=$_SESSION['userid'];
// echo $fld_baidp.'test';
$fld_baidp_dsc=$_SESSION['usernm'];
$fld_btloc=$_SESSION['location'];
$fld_btloc_dsc=$_SESSION['location_nm'];

$fld_btid=0;
$fld_btid_book=0;
$fld_btno="";
$fld_btno_book="";
$fld_btdt="";
$fld_btnoreff="";
$fld_btp22="";
$fld_btp22_dsc="";
$fld_btp17="";

//book 20"
$fld_btp01=0;//total 20"
$fld_btp70=0;//20 dry
$fld_btp72=0;//20 ft
$fld_btp74=0;//20 ot
$fld_btp76=0;//20 R
//book 40"
$fld_btp02=0;//total 40"
$fld_btp71=0;//40 dry
$fld_btp73=0;//40 ft
$fld_btp78=0;//40 ot
$fld_btp77=0;//40 rh
$fld_btp75=0;//40 HC

//book 45"
$fld_btp03=0;//total 45"
$fld_btp81=0;//45 HC

$fld_btp23=0;//total 20
$fld_btp24=0;//total 40
$fld_btp25=0;//total 45

$fld_btp04="";
$fld_btp05="";
$fld_btp06="";

$fld_btp13="";
$fld_btp14="";
$fld_btp15="";
$fld_btp16="";

$fld_btstat_dsc="NEW";
$fld_btstat=1;

$fld_total=0;
$fld_tax=0;
$fld_gtotal=0;

// echo $uri_segments[5];
  $host_curl="http://172.17.1.17";
  $host="https://rest.dunextr.com";
  $host_upload="https://portal.dunextr.com";
  // $host_curl="http://localhost/dunex/dunex-rest";
  // $host="http://localhost/dunex/dunex-rest";
  // $host="localhost/dunex-rest/";

// echo $_SERVER['REQUEST_URI'];

if($_SERVER['HTTP_HOST']=='localhost'){
  $segmen_action=$uri_segments[7];
  $segmen_btid=$uri_segments[8];
}else{
  $segmen_action=$uri_segments[5];
  $segmen_btid=$uri_segments[6];
}


$fld_btuamt=0;
$fld_btamt01=0;
$fld_btamt=0;

// echo $segmen_action;


if($segmen_action=='edit'){

  // echo $segmen_btid;


  // $host_curl="http://172.17.1.17";
  // $host="https://rest.dunextr.com";

  // echo "$host_curl/index.php/PortalApi/releasebookedit/$segmen_btid";

  curl_setopt_array($curl, array(
  CURLOPT_URL => "$host_curl/index.php/PortalApi/releasebookedit/$segmen_btid",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => array('fld_btid' => $segmen_btid,'fld_baidp' => $fld_baidp)
));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  // var_dump($response);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    #$data = json_decode($response,TRUE);
  }
  $data = json_decode($response,TRUE);
  $rdata=$data[0];


  $fld_btid=$data['data'][0]['fld_btid'];
  $fld_btid_book=$data['data'][0]['fld_btid_book'];
  $fld_btno=$data['data'][0]['fld_btno'];
  $fld_btno_book=$data['data'][0]['fld_btno_book'];
  $fld_btdt=$data['data'][0]['fld_btdt'];
  $fld_btnoreff=$data['data'][0]['fld_btnoreff'];
  $fld_btp22=$data['data'][0]['fld_btp22'];
  $fld_btp22_dsc=$data['data'][0]['fld_btp22_dsc'];
  $fld_btp17=$data['data'][0]['fld_btp17'];
  $fld_btp17_dsc=$data['data'][0]['fld_btp17_dsc'];

  // var_dump($data);

  //book 20"
  $fld_btp01=$data['data'][0]['fld_btp01'];//total 20"
  $fld_btp70=$data['data'][0]['fld_btp70'];//20 dry
  $fld_btp72=$data['data'][0]['fld_btp72'];//20 ft
  $fld_btp74=$data['data'][0]['fld_btp74'];//20 ot
  $fld_btp76=$data['data'][0]['fld_btp76'];//20 R
  //book 40"
  $fld_btp02=$data['data'][0]['fld_btp02'];//total 40"
  $fld_btp71=$data['data'][0]['fld_btp71'];//40 dry
  $fld_btp73=$data['data'][0]['fld_btp73'];//40 ft
  $fld_btp78=$data['data'][0]['fld_btp78'];//40 ot
  $fld_btp77=$data['data'][0]['fld_btp77'];//40 rh

  //book 45"
  $fld_btp03=$data['data'][0]['fld_btp03'];//total 45"
  $fld_btp75=$data['data'][0]['fld_btp75'];//40 HC
  $fld_btp81=$data['data'][0]['fld_btp81'];//45 HC


  $fld_btdtsa=$data['data'][0]['fld_btdtsa'];
  $fld_btflag_book=$data['data'][0]['fld_btflag_book'];
  $fld_btdesc=$data['data'][0]['fld_btdesc'];

  $fld_btstat_dsc=$data['data'][0]['fld_btstat_dsc'];
  $fld_btstat=$data['data'][0]['fld_btstat'];

  $fld_btp13=$data['data'][0]['fld_btp13'];
  $fld_btp14=$data['data'][0]['fld_btp14'];
  $fld_btp15=$data['data'][0]['fld_btp15'];
  $fld_btp16=$data['data'][0]['fld_btp16'];

  $fld_btp23=$data['data'][0]['fld_btp23'];
  $fld_btp24=$data['data'][0]['fld_btp24'];
  $fld_btp25=$data['data'][0]['fld_btp25'];

  $fld_btuamt=$data['data'][0]['fld_btuamt'];
  $fld_btamt01=$data['data'][0]['fld_btamt01'];
  $fld_btamt=$data['data'][0]['fld_btamt'];

}

?>

<style type="text/css">
  .container-fluid {
    padding: 0px;
  }
</style>

<div class="row">
  <div class="col-md-12">
    <div class="card-body">
     

      <form id="form">
        <div class="row" style="margin-top: -25px;margin-bottom: 10px;">
          <div class="col-md-9 form-group mb-2">
            <label style="color:red;">*Please bring your Release Receipt and Temporary Payment Receipt, to get Payment Receipt.</label>
            <label style="color:red;">*Transfer Info : Bank Mandiri - 1200010305972 a/n Dunia Express Transindo (KCP Jakarta Sunter Paradise). Bukti transfer email ke : depo@dunextr.com</label>
          </div>
        </div>
    <!--     <div class="row">
          <div class="col-md-6 form-group mb-2">
            <a id="approve_btn" class="btn btn-primary" style="color:white;" onclick="approve_do()">Request Approve</a>
          </div>
        </div> -->
        <div class="row">
          <div class="col-md-6 form-group mb-2">
              <label for="fld_btid">ID</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btid" name="fld_btid" size="10" value="<?=$fld_btid;?>" readonly>
              <input type="hidden" name="action_form" id="action_form" value="<?=$segmen_action;?>">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btstat">Transaction Status</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btstat_dsc" name="fld_btstat_dsc" value="<?=$fld_btstat_dsc;?>" readonly>
              <input type="hidden" class="form-control form-control-rounded" id="fld_btstat" name="fld_btstat" value="<?=$fld_btstat;?>" readonly>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btno">Transaction Number</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btno" name="fld_btno" value="<?=$fld_btno;?>" readonly>
          </div>
        </div>
        <div class="card-title mb-3 border-bottom">Booking Information</div>
        <div class="row">

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btnoreff">Booking Number</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btnoreff" name="fld_btnoreff" value="<?=$fld_btnoreff;?>" >
          </div>
          <div class="col-md-6 form-group mb-3">
            <br>
              <a class="btn btn-primary" id="search_btn" name="search_btn" onclick="search_transno()" style="color: white;"><i class="i-Magnifi-Glass1" style="color: white;"></i></a>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btno">Transaction Booking Number</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btno_book" name="fld_btno_book" value="<?=$fld_btno_book;?>" readonly>
              <input type="hidden" name="fld_btid_book" id="fld_btid_book" value="<?=$fld_btid_book;?>">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btdt">Booking Date</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btdt" name="fld_btdt" value="<?=$fld_btdt;?>" readonly>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp22_dsc">Shipper</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btp22_dsc" name="fld_btp22_dsc" value="<?=$fld_btp22_dsc;?>" readonly>
              <input type="hidden" class="form-control form-control-rounded" id="fld_btp22" name="fld_btp22" value="<?=$fld_btp22;?>" readonly>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp17_dsc">Principal</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btp17_dsc" name="fld_btp17_dsc" value="<?=$fld_btp17_dsc;?>" readonly>
              <input type="hidden" class="form-control form-control-rounded" id="fld_btp17" name="fld_btp17" value="<?=$fld_btp17;?>" readonly>
          </div>

        </div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btdtsa">Exclude EDI</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btdtsa" name="fld_btdtsa" value="<?=$fld_btdtsa;?>" readonly>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btflag_book">Expired DO</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btflag_book" name="fld_btflag_book" value="<?=$fld_btflag_book;?>" readonly>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btdesc">Notes</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btdesc" name="fld_btdesc" value="<?=$fld_btdesc;?>" readonly>
          </div>

        </div>
        <div class="card-title mb-3 border-bottom">Lift On / Lift Off</div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btflag">LoLo Type*</label>
              <select id="fld_btflag" name="fld_btflag" class="form-control form-control-rounded" placeholder="yyyy-mm-dd" onchange="pricereload();" readonly disabled="true">
               <option value="0">[--Select--]</option>
               <option value="6">Lift On</option>
               <option value="5">Lift Off</option>
             </select>
          </div>
        </div>
        <div class="card-title mb-3 border-bottom">Booked Qty 20" : Total (<span id="fld_btp01_r"></span>)
          <input type="hidden" class="form-control form-control-rounded" id="fld_btp01" name="fld_btp01" value="<?=$fld_btp01;?>" >
        </div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp70">20 Dry : Remain (<span id="fld_btp70_r"></span>) <div class="price"> Price (<span id="fld_btp70_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp70" name="fld_btp70" value="<?=$fld_btp70;?>" onchange="total()">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp72">20 FT : Remain (<span id="fld_btp72_r"></span>) <div class="price"> Price (<span id="fld_btp72_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp72" name="fld_btp72" value="<?=$fld_btp72;?>" onchange="total()">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp74">20 OT : Remain (<span id="fld_btp74_r"></span>) <div class="price"> Price (<span id="fld_btp74_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp74" name="fld_btp74" value="<?=$fld_btp74;?>" onchange="total()">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp76">20 R : Remain (<span id="fld_btp76_r"></span>) <div class="price"> Price (<span id="fld_btp76_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp76" name="fld_btp76" value="<?=$fld_btp76;?>" onchange="total()">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
            <label for="fld_btp23">Subtotal</label>
            <input type="number" class="form-control form-control-rounded" id="fld_btp23" name="fld_btp23" value="<?=$fld_btp23;?>" readonly>
          </div>
        </div>

        <div class="card-title mb-3 border-bottom">Booked Qty 40" : Total (<span id="fld_btp02_r"></span>)
          <input type="hidden" class="form-control form-control-rounded" id="fld_btp02" name="fld_btp02" value="<?=$fld_btp02;?>" ></div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp71">40 Dry : Remain (<span id="fld_btp71_r"></span>) <div class="price"> Price (<span id="fld_btp71_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp71" name="fld_btp71" value="<?=$fld_btp71;?>" onchange="total()">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp73">40 FT : Remain (<span id="fld_btp73_r"></span>) <div class="price"> Price (<span id="fld_btp73_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp73" name="fld_btp73" value="<?=$fld_btp73;?>" onchange="total()">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp78">40 OT : Remain (<span id="fld_btp78_r"></span>) <div class="price"> Price (<span id="fld_btp78_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp78" name="fld_btp78" value="<?=$fld_btp78;?>" onchange="total()">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp77">40 RH : Remain (<span id="fld_btp77_r"></span>) <div class="price"> Price (<span id="fld_btp77_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp77" name="fld_btp77" value="<?=$fld_btp77;?>" onchange="total()">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp75">40 HC : Remain (<span id="fld_btp75_r"></span>) <div class="price"> Price (<span id="fld_btp75_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp75" name="fld_btp75" value="<?=$fld_btp75;?>" onchange="total()">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 form-group mb-3">
            <label for="fld_btp24">Subtotal</label>
            <input type="number" class="form-control form-control-rounded" id="fld_btp24" name="fld_btp24" value="<?=$fld_btp24;?>" readonly>
          </div>
        </div>
        

        <div class="card-title mb-3 border-bottom">Booked Qty 45" : Total (<span id="fld_btp03_r"></span>)
          <input type="hidden" class="form-control form-control-rounded" id="fld_btp03" name="fld_btp03" value="<?=$fld_btp03;?>" >
        </div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp81">45 HC : Remain (<span id="fld_btp81_r"></span>) <div class="price"> Price (<span id="fld_btp81_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp81" name="fld_btp81" value="<?=$fld_btp81;?>" onchange="total()">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
            <label for="fld_btp25">Subtotal</label>
            <input type="number" class="form-control form-control-rounded" id="fld_btp25" name="fld_btp25" value="<?=$fld_btp25;?>" readonly>
          </div>
        </div>

        <div class="card-title mb-3 border-bottom">Total</div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
              <label for="fld_total">Total</label>
              <input type="number" class="form-control form-control-rounded" id="fld_btuamt" name="fld_btuamt" value="<?=$fld_btuamt;?>" readonly>
              <br>
              <label for="fld_tax">Tax</label>
              <input type="number" class="form-control form-control-rounded" id="fld_btamt01" name="fld_btamt01" value="<?=$fld_btamt01;?>" readonly>
              <br>
              <label for="fld_gtotal">Grand Total</label>
              <input type="number" class="form-control form-control-rounded" id="fld_btamt" name="fld_btamt" value="<?=$fld_btamt;?>" readonly>
          </div>
        </div>

        <!-- <div class="card-title mb-3 border-bottom">Water Washing</div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp04">20" :  <div class="price"> Price (<span id="fld_btp04_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp04" name="fld_btp04" value="<?=$fld_btp05;?>">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp05">40" : <div class="price"> Price (<span id="fld_btp05_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp05" name="fld_btp05" value="<?=$fld_btp05;?>">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp06">45" : <div class="price"> Price (<span id="fld_btp06_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp06" name="fld_btp06" value="<?=$fld_btp06;?>">
          </div>
        </div>

        <div class="card-title mb-3 border-bottom">Detergent Washing</div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp07">20" : <div class="price"> Price (<span id="fld_btp07_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp07" name="fld_btp07" value="<?=$fld_btp07;?>">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp14">40" : <div class="price"> Price (<span id="fld_btp14_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp14" name="fld_btp14" value="<?=$fld_btp14;?>">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp15">45" : <div class="price"> Price (<span id="fld_btp15_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp15" name="fld_btp15" value="<?=$fld_btp15;?>">
          </div>
        </div>

        <div class="card-title mb-3 border-bottom">Chemical Washing</div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp16">20" : <div class="price"> Price (<span id="fld_btp16_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp16" name="fld_btp16" value="<?=$fld_btp16;?>">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp18">40" : <div class="price"> Price (<span id="fld_btp18_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp18" name="fld_btp18" value="<?=$fld_btp18;?>">
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp19">45" : <div class="price"> Price (<span id="fld_btp19_pr"></span>)</div></label>
              <input type="number" class="form-control form-control-rounded" id="fld_btp19" name="fld_btp19" value="<?=$fld_btp19;?>">
          </div>
        </div> -->
    


        <div id="payment_tittle" class="card-title mb-3 border-bottom">Payment Info</div>
        <div id="attachment_info" class="row">
            <div class="col-md-12 payment_info">
                <label class="payment_info" for="fld_btp14_dsc">Nama Bank : </label>
                <span class="payment_info" type="text" id="fld_btp14_dsc" name="fld_btp14_dsc"><?=$fld_btp14;?></span>
                <br>
                <label class="payment_info" for="fld_btp15_dsc">Nomor Rekening : </label>
                <span class="payment_info" type="text" id="fld_btp15_dsc" name="fld_btp15_dsc"><?=$fld_btp15;?></span>
                <br>
                <label class="payment_info" for="fld_btp16_dsc">Nama Pemegang Rekening : </label>
                <span class="payment_info" type="text" id="fld_btp16_dsc" name="fld_btp16_dsc"><?=$fld_btp16;?></span>
                <br>
                <label class="payment_info"for="fld_btp13_dsc">Bukti Pembayaran :</label>
                <span class="payment_info"type="text" id="fld_btp13_dsc" name="fld_btp13_dsc"><?=$fld_btp13;?></span>
  
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a id="submit_btn" class="btn btn-primary" style="color:white;" onclick="savedata()">Submit</a>
                <a id="checkout_btn" class="btn btn-primary" data-toggle="modal" data-target="#modal_attachment" onclick="" style="color:white;">Checkout</a>
                <a id="downloadattach_btn" class="btn btn-primary" style="color:white;" onclick="downloadattach()">Download Attachment</a>
            </div>
            
        </div>
    
      </form>
      
    </div>
  </div>
</div>

<div class="modal fade" id="modal_attachment" tabindex="-1" role="dialog" aria-labelledby="Attachment" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content" style="margin-top: 50%;">
          <div class="modal-header">
              <h5 class="modal-title" id="Attachment_title">Attachment</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
          </div>

          <div class="col-md-12 form-group mb-3">
                <label for="fld_btp14">Nama Bank : </label>
                <input type="text" class="form-control form-control-rounded" id="fld_btp14" name="fld_btp14" value="<?=$fld_btp14;?>">

                <label for="fld_btp15">Nomor Rekening : </label>
                <input type="text" class="form-control form-control-rounded" id="fld_btp15" name="fld_btp15" value="<?=$fld_btp15;?>">

                <label for="fld_btp16">Nama Pemegang Rekening : </label>
                <input type="text" class="form-control form-control-rounded" id="fld_btp16" name="fld_btp16" value="<?=$fld_btp16;?>">

               <label for="fld_btp13">Bukti Pembayaran :</label>
               <input type="file" id="fld_btp13" name="fld_btp13" class="form-control form-control-rounded" value="<?=$fld_btp13;?>">
               <input type="hidden" id="fld_btp13_dsc" name="fld_btp13_dsc" class="form-control form-control-rounded" value="<?=$fld_btp13;?>">
               <br>
               <a id="btn_uploadattach" class="btn btn-primary" style="color:white;" onclick="uploadattach()">Upload</a>
          </div>
      
       </div>
   </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('#search_btn').show();
    $('#submit_btn').show();
    $('.price').hide();
    $('#payment_tittle').hide();
    $('.payment_info').hide();
    $('#downloadattach_btn').hide();
    $('#checkout_btn').hide();

    $('#fld_btflag').val(6);
    // pricereload();


    $('#fld_btp70_r').text(0);
    $('#fld_btp72_r').text(0);
    $('#fld_btp74_r').text(0);
    $('#fld_btp76_r').text(0);
    $('#fld_btp71_r').text(0);
    $('#fld_btp73_r').text(0);
    $('#fld_btp78_r').text(0);
    $('#fld_btp77_r').text(0);
    $('#fld_btp75_r').text(0);
    $('#fld_btp81_r').text(0);
    $('#fld_btp01_r').text(0);
    $('#fld_btp02_r').text(0);
    $('#fld_btp03_r').text(0);
    // $('#fld_btp70_pr').text(0);
    // $('#fld_btp72_pr').text(0);
    // $('#fld_btp74_pr').text(0);
    // $('#fld_btp76_pr').text(0);
    // $('#fld_btp71_pr').text(0);
    // $('#fld_btp73_pr').text(0);
    // $('#fld_btp78_pr').text(0);
    // $('#fld_btp77_pr').text(0);
    // $('#fld_btp75_pr').text(0);
    // $('#fld_btp81_pr').text(0);
    // $('#fld_btp04_pr').text(0);
    // $('#fld_btp05_pr').text(0);
    // $('#fld_btp06_pr').text(0);
    // $('#fld_btp07_pr').text(0);
    // $('#fld_btp14_pr').text(0);
    // $('#fld_btp15_pr').text(0);
    // $('#fld_btp16_pr').text(0);
    // $('#fld_btp18_pr').text(0);
    // $('#fld_btp19_pr').text(0);
    $('#fld_btp70').val(0);
    $('#fld_btp72').val(0);
    $('#fld_btp74').val(0);
    $('#fld_btp76').val(0);
    $('#fld_btp71').val(0);
    $('#fld_btp73').val(0);
    $('#fld_btp78').val(0);
    $('#fld_btp77').val(0);
    $('#fld_btp75').val(0);
    $('#fld_btp81').val(0);
    $('#fld_btuamt').val(0);
    $('#fld_btamt01').val(0);
    $('#fld_btamt').val(0);
    // $('#fld_btp04').val(0);
    // $('#fld_btp05').val(0);
    // $('#fld_btp06').val(0);
    // $('#fld_btp07').val(0);
    // $('#fld_btp14').val(0);
    // $('#fld_btp15').val(0);
    // $('#fld_btp16').val(0);
    // $('#fld_btp18').val(0);
    // $('#fld_btp19').val(0);


    if('<?=$segmen_action;?>'=='edit'){
      document.getElementById("fld_btnoreff").readOnly = true;
      // console.log('tes');
      $('#search_btn').hide();
      
      if('<?=$fld_btstat?>'==2){
        $('#submit_btn').hide();
        $('#checkout_btn').hide();
        $('#downloadattach_btn').show();
        $('#payment_tittle').show();
        $('.payment_info').show();
      }else{
        // console.log('tes');
        $('#checkout_btn').show();
      }
      $('#fld_btp70_r').text('<?=$fld_btp70?>');
      $('#fld_btp72_r').text('<?=$fld_btp72?>');
      $('#fld_btp74_r').text('<?=$fld_btp74?>');
      $('#fld_btp76_r').text('<?=$fld_btp76?>');
      $('#fld_btp71_r').text('<?=$fld_btp71?>');
      $('#fld_btp73_r').text('<?=$fld_btp73?>');
      $('#fld_btp78_r').text('<?=$fld_btp78?>');
      $('#fld_btp77_r').text('<?=$fld_btp77?>');
      $('#fld_btp75_r').text('<?=$fld_btp75?>');
      $('#fld_btp81_r').text('<?=$fld_btp81?>');
      $('#fld_btp70_pr').text('<?=$fld_btp70_pr?>');
      $('#fld_btp72_pr').text('<?=$fld_btp72_pr?>');
      $('#fld_btp74_pr').text('<?=$fld_btp74_pr?>');
      $('#fld_btp76_pr').text('<?=$fld_btp76_pr?>');
      $('#fld_btp71_pr').text('<?=$fld_btp71_pr?>');
      $('#fld_btp73_pr').text('<?=$fld_btp73_pr?>');
      $('#fld_btp78_pr').text('<?=$fld_btp78_pr?>');
      $('#fld_btp77_pr').text('<?=$fld_btp77_pr?>');
      $('#fld_btp75_pr').text('<?=$fld_btp75_pr?>');
      $('#fld_btp81_pr').text('<?=$fld_btp81_pr?>');
      // $('#fld_btp04_pr').text('<?=$fld_btp04_pr?>');
      // $('#fld_btp05_pr').text('<?=$fld_btp05_pr?>');
      // $('#fld_btp06_pr').text('<?=$fld_btp06_pr?>');
      // $('#fld_btp07_pr').text('<?=$fld_btp07_pr?>');
      // $('#fld_btp14_pr').text('<?=$fld_btp14_pr?>');
      // $('#fld_btp15_pr').text('<?=$fld_btp15_pr?>');
      // $('#fld_btp16_pr').text('<?=$fld_btp16_pr?>');
      // $('#fld_btp18_pr').text('<?=$fld_btp18_pr?>');
      // $('#fld_btp19_pr').text('<?=$fld_btp19_pr?>');

      $('#fld_btid').val('<?=$fld_btid?>');
      $('#fld_btid_book').val('<?=$fld_btid_book?>');
      $('#fld_btno').val('<?=$fld_btno?>');
      $('#fld_btno_book').val('<?=$fld_btno_book?>');
      $('#fld_btdt').val('<?=$fld_btdt?>');
      $('#fld_btnoreff').val('<?=$fld_btnoreff?>');
      $('#fld_btflag_book').val('<?=$fld_btflag_book?>');
      search_transno();
      $('#fld_btp22').val('<?=$fld_btp22?>');
      $('#fld_btp22_dsc').val('<?=$fld_btp22_dsc?>');
      $('#fld_btp17').val('<?=$fld_btp17?>');

      $('#fld_btp70').val('<?=$fld_btp70?>');
      $('#fld_btp72').val('<?=$fld_btp72?>');
      $('#fld_btp74').val('<?=$fld_btp74?>');
      $('#fld_btp76').val('<?=$fld_btp76?>');

      $('#fld_btp71').val('<?=$fld_btp71?>');
      $('#fld_btp73').val('<?=$fld_btp73?>');
      $('#fld_btp78').val('<?=$fld_btp78?>');
      $('#fld_btp77').val('<?=$fld_btp77?>');

      $('#fld_btp75').val('<?=$fld_btp75?>');
      $('#fld_btp81').val('<?=$fld_btp81?>');

      // $('#fld_btp04').val('<?=$fld_btp04?>');
      // $('#fld_btp05').val('<?=$fld_btp05?>');
      // $('#fld_btp06').val('<?=$fld_btp06?>');
      // $('#fld_btp07').val('<?=$fld_btp07?>');
      // $('#fld_btp14').val('<?=$fld_btp14?>');
      // $('#fld_btp15').val('<?=$fld_btp15?>');
      // $('#fld_btp16').val('<?=$fld_btp16?>');
      // $('#fld_btp18').val('<?=$fld_btp18?>');
      // $('#fld_btp19').val('<?=$fld_btp19?>');

      $('#fld_btdtsa').val('<?=$fld_btdtsa?>');
      $('#fld_btdesc').val('<?=$fld_btdesc?>');

      $('#fld_btuamt').val('<?=$fld_btuamt?>');
      $('#fld_btamt01').val('<?=$fld_btamt01?>');
      $('#fld_btamt').val('<?=$fld_btamt?>');

      if('<?=$fld_btstat?>'<2){
        pricereload();
      }else{
        
      }
      
    }
   
  });

  function search_transno(){
    console.log("<?=$host?>/index.php/PortalApi/searchbooking?fld_btnoreff="+$('#fld_btnoreff').val());
    var settings = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host?>/index.php/PortalApi/searchbooking?fld_btnoreff="+$('#fld_btnoreff').val(), 
      "method": "GET",
      "headers": {
        "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
        "Content-Type": "application/x-www-form-urlencoded",
        "Accept": "application/json",
        "cache-control": "no-cache",
        "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
      }
    };

    $.ajax(settings).done(function (response) {
      console.log(response);
      // console.log(parseFloat(response['jml_data']));
      if(parseFloat(response['jml_data'])>0){
        if('<?=$segmen_action?>'=='add'){
          $('#fld_btp81_r').text(parseFloat(response['data'][0].fld_btp01));
          $('#fld_btp70_r').text(parseFloat(response['data'][0].fld_btp70)-parseFloat(response['data'][0].fld_btp70_book));
          $('#fld_btp72_r').text(parseFloat(response['data'][0].fld_btp72)-parseFloat(response['data'][0].fld_btp72_book));
          $('#fld_btp74_r').text(parseFloat(response['data'][0].fld_btp74)-parseFloat(response['data'][0].fld_btp74_book));
          $('#fld_btp76_r').text(parseFloat(response['data'][0].fld_btp76)-parseFloat(response['data'][0].fld_btp76_book));

          $('#fld_btp02').text(parseFloat(response['data'][0].fld_btp02));
          $('#fld_btp71_r').text(parseFloat(response['data'][0].fld_btp71)-parseFloat(response['data'][0].fld_btp71_book));
          $('#fld_btp73_r').text(parseFloat(response['data'][0].fld_btp73)-parseFloat(response['data'][0].fld_btp73_book));
          $('#fld_btp78_r').text(parseFloat(response['data'][0].fld_btp78)-parseFloat(response['data'][0].fld_btp78_book));
          $('#fld_btp77_r').text(parseFloat(response['data'][0].fld_btp77)-parseFloat(response['data'][0].fld_btp77_book));
          $('#fld_btp75_r').text(parseFloat(response['data'][0].fld_btp75)-parseFloat(response['data'][0].fld_btp75_book));

          $('#fld_btp03').text(parseFloat(response['data'][0].fld_btp03));
          $('#fld_btp81_r').text(parseFloat(response['data'][0].fld_btp81));
        }else{
          $('#fld_btp70_r').text(parseFloat(response['data'][0].fld_btp70_book));
          console.log(parseFloat(response['data'][0].fld_btp70));
          $('#fld_btp72_r').text(parseFloat(response['data'][0].fld_btp72_book));
          $('#fld_btp74_r').text(parseFloat(response['data'][0].fld_btp74_book));
          $('#fld_btp76_r').text(parseFloat(response['data'][0].fld_btp76_book));
          $('#fld_btp71_r').text(parseFloat(response['data'][0].fld_btp71_book));
          $('#fld_btp73_r').text(parseFloat(response['data'][0].fld_btp73_book));
          $('#fld_btp78_r').text(parseFloat(response['data'][0].fld_btp78_book));
          $('#fld_btp77_r').text(parseFloat(response['data'][0].fld_btp77_book));
          $('#fld_btp75_r').text(parseFloat(response['data'][0].fld_btp75_book));
          $('#fld_btp81_r').text(parseFloat(response['data'][0].fld_btp81_book));
        }
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp70').val('<?=$fld_btp70?>');}else{$('#fld_btp70').val(0);}
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp72').val('<?=$fld_btp72?>');}else{$('#fld_btp72').val(0);}
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp74').val('<?=$fld_btp74?>');}else{$('#fld_btp74').val(0);}
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp76').val('<?=$fld_btp76?>');}else{$('#fld_btp76').val(0);}
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp71').val('<?=$fld_btp71?>');}else{$('#fld_btp71').val(0);}
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp73').val('<?=$fld_btp73?>');}else{$('#fld_btp73').val(0);}
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp78').val('<?=$fld_btp78?>');}else{$('#fld_btp78').val(0);}
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp77').val('<?=$fld_btp77?>');}else{$('#fld_btp77').val(0);}
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp75').val('<?=$fld_btp75?>');}else{$('#fld_btp75').val(0);}
        if('<?=$segmen_action?>'=='edit'){$('#fld_btp81').val('<?=$fld_btp81?>');}else{$('#fld_btp81').val(0);}
        $('#fld_btid_book').val(response['data'][0].fld_btid_book);
        $('#fld_btno_book').val(response['data'][0].fld_btno_book);
        $('#fld_btdt').val(response['data'][0].fld_btdt);
        $('#fld_btnoreff').val(response['data'][0].fld_btnoreff);
        $('#fld_btp22').val(response['data'][0].fld_btp22);
        $('#fld_btp22_dsc').val(response['data'][0].fld_btp22_dsc);
        $('#fld_btp17').val(response['data'][0].fld_btp17);
        $('#fld_btp17_dsc').val(response['data'][0].fld_btp17_dsc);
        if(parseFloat(response['data'][0].fld_btp70)>0){document.getElementById("fld_btp70").readOnly = false;}else{document.getElementById("fld_btp70").readOnly = true;}
        if(parseFloat(response['data'][0].fld_btp72)>0){document.getElementById("fld_btp72").readOnly = false;}else{document.getElementById("fld_btp72").readOnly = true;}
        if(parseFloat(response['data'][0].fld_btp74)>0){document.getElementById("fld_btp74").readOnly = false;}else{document.getElementById("fld_btp74").readOnly = true;}
        if(parseFloat(response['data'][0].fld_btp76)>0){document.getElementById("fld_btp76").readOnly = false;}else{document.getElementById("fld_btp76").readOnly = true;}
        if(parseFloat(response['data'][0].fld_btp71)>0){document.getElementById("fld_btp71").readOnly = false;}else{document.getElementById("fld_btp71").readOnly = true;}
        if(parseFloat(response['data'][0].fld_btp73)>0){document.getElementById("fld_btp73").readOnly = false;}else{document.getElementById("fld_btp73").readOnly = true;}
        if(parseFloat(response['data'][0].fld_btp78)>0){document.getElementById("fld_btp78").readOnly = false;}else{document.getElementById("fld_btp78").readOnly = true;}
        if(parseFloat(response['data'][0].fld_btp77)>0){document.getElementById("fld_btp77").readOnly = false;}else{document.getElementById("fld_btp77").readOnly = true;}
        if(parseFloat(response['data'][0].fld_btp75)>0){document.getElementById("fld_btp75").readOnly = false;}else{document.getElementById("fld_btp75").readOnly = true;}
        if(parseFloat(response['data'][0].fld_btp81)>0){document.getElementById("fld_btp81").readOnly = false;}else{document.getElementById("fld_btp81").readOnly = true;}
        $('#fld_btdtsa').val(response['data'][0].fld_btdtsa);
        $('#fld_btflag_book').val(response['data'][0].fld_btflag_book);
        $('#fld_btdesc').val(response['data'][0].fld_btdesc);
        if('<?=$fld_btstat?>'<2){
          pricereload();
        }else{

        }
      }else{//else jml_data <1
        alert('Booking No. Not registered or Booking No has been Canceled.');
      }


    });
  }

  function pricereload(){
    if($('#fld_btp17').val()!=''){
      console.log("<?=$host?>/index.php/PortalApi/pricereload?fld_beid="+$('#fld_btp17').val()+'&lolo='+$('#fld_btflag').val());
      var settings = {
        "async": true,
        "crossDomain": true,
        "dataType": 'jsonp',
        "url": "<?=$host?>/index.php/PortalApi/pricereload?fld_beid="+$('#fld_btp17').val()+'&lolo='+$('#fld_btflag').val(), 
        "method": "GET",
        "headers": {
          "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
          "Content-Type": "application/x-www-form-urlencoded",
          "Accept": "application/json",
          "cache-control": "no-cache",
          "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
        }
      };

      $.ajax(settings).done(function (response) {
        // console.log(response['fld_btp70_pr']);
        $('#fld_btp70_pr').text(response['fld_btp70_pr']);
        $('#fld_btp72_pr').text(response['fld_btp72_pr']);
        $('#fld_btp74_pr').text(response['fld_btp74_pr']);
        $('#fld_btp76_pr').text(response['fld_btp76_pr']);
        $('#fld_btp71_pr').text(response['fld_btp71_pr']);
        $('#fld_btp73_pr').text(response['fld_btp73_pr']);
        $('#fld_btp78_pr').text(response['fld_btp78_pr']);
        $('#fld_btp77_pr').text(response['fld_btp77_pr']);
        $('#fld_btp75_pr').text(response['fld_btp75_pr']);
        $('#fld_btp81_pr').text(response['fld_btp81_pr']);
        // $('#fld_btp04_pr').text(response['fld_btp04_pr']);
        // $('#fld_btp05_pr').text(response['fld_btp05_pr']);
        // $('#fld_btp06_pr').text(response['fld_btp06_pr']);
        // $('#fld_btp07_pr').text(response['fld_btp07_pr']);
        // $('#fld_btp14_pr').text(response['fld_btp14_pr']);
        // $('#fld_btp15_pr').text(response['fld_btp15_pr']);
        // $('#fld_btp16_pr').text(response['fld_btp16_pr']);
        // $('#fld_btp18_pr').text(response['fld_btp18_pr']);
        // $('#fld_btp19_pr').text(response['fld_btp19_pr']);
        if('<?=$segmen_action?>'=='edit'){total();}
      });
    }else{

    }
  }

  function total(){
    if(parseFloat($('#fld_btp70').val())>0){var s_fld_btp70=parseFloat($('#fld_btp70').val())*parseFloat($('#fld_btp70_pr').text());var t_fld_btp70=parseFloat($('#fld_btp70').val());}else{var s_fld_btp70=0;var t_fld_btp70=0;}
    if(parseFloat($('#fld_btp72').val())>0){var s_fld_btp72=parseFloat($('#fld_btp72').val())*parseFloat($('#fld_btp72_pr').text());var t_fld_btp72=parseFloat($('#fld_btp72').val());}else{var s_fld_btp72=0;var t_fld_btp72=0;}
    if(parseFloat($('#fld_btp74').val())>0){var s_fld_btp74=parseFloat($('#fld_btp74').val())*parseFloat($('#fld_btp74_pr').text());var t_fld_btp74=parseFloat($('#fld_btp74').val());}else{var s_fld_btp74=0;var t_fld_btp74=0;}
    if(parseFloat($('#fld_btp76').val())>0){var s_fld_btp76=parseFloat($('#fld_btp76').val())*parseFloat($('#fld_btp76_pr').text());var t_fld_btp76=parseFloat($('#fld_btp76').val());}else{var s_fld_btp76=0;var t_fld_btp76=0;}

    if(parseFloat($('#fld_btp71').val())>0){var s_fld_btp71=parseFloat($('#fld_btp71').val())*parseFloat($('#fld_btp71_pr').text());var t_fld_btp71=parseFloat($('#fld_btp71').val());}else{var s_fld_btp71=0;var t_fld_btp71=0;}
    if(parseFloat($('#fld_btp73').val())>0){var s_fld_btp73=parseFloat($('#fld_btp73').val())*parseFloat($('#fld_btp73_pr').text());var t_fld_btp73=parseFloat($('#fld_btp73').val());}else{var s_fld_btp73=0;var t_fld_btp73=0;}
    if(parseFloat($('#fld_btp78').val())>0){var s_fld_btp78=parseFloat($('#fld_btp78').val())*parseFloat($('#fld_btp78_pr').text());var t_fld_btp78=parseFloat($('#fld_btp78').val());}else{var s_fld_btp78=0;var t_fld_btp78=0;}
    if(parseFloat($('#fld_btp77').val())>0){var s_fld_btp77=parseFloat($('#fld_btp77').val())*parseFloat($('#fld_btp77_pr').text());var t_fld_btp77=parseFloat($('#fld_btp77').val());}else{var s_fld_btp77=0;var t_fld_btp77=0;}
    if(parseFloat($('#fld_btp75').val())>0){var s_fld_btp75=parseFloat($('#fld_btp75').val())*parseFloat($('#fld_btp75_pr').text());var t_fld_btp75=parseFloat($('#fld_btp75').val());}else{var s_fld_btp75=0;var t_fld_btp75=0;}

    if(parseFloat($('#fld_btp81').val())>0){var s_fld_btp81=parseFloat($('#fld_btp81').val())*parseFloat($('#fld_btp81_pr').text());var t_fld_btp81=parseFloat($('#fld_btp81').val());}else{var s_fld_btp81=0;var t_fld_btp81=0;}

    var s_fld_btp23=s_fld_btp70+s_fld_btp72+s_fld_btp74+s_fld_btp76;
    var s_fld_btp24=s_fld_btp71+s_fld_btp73+s_fld_btp78+s_fld_btp77+s_fld_btp75;
    var s_fld_btp25=s_fld_btp81;

    var t_fld_btp01=t_fld_btp70+t_fld_btp72+t_fld_btp74+t_fld_btp76;
    var t_fld_btp02=t_fld_btp71+t_fld_btp73+t_fld_btp78+t_fld_btp77+t_fld_btp75;
    var t_fld_btp03=t_fld_btp81;

    var s_fld_total=s_fld_btp70+s_fld_btp72+s_fld_btp74+s_fld_btp76+s_fld_btp71+s_fld_btp73+s_fld_btp78+s_fld_btp77+s_fld_btp75+s_fld_btp81;

    var s_fld_tax=s_fld_total*10/100;
    var s_fld_gtotal=s_fld_total+s_fld_tax;

    $('#fld_btp01_r').text(t_fld_btp01);
    $('#fld_btp02_r').text(t_fld_btp02);
    $('#fld_btp03_r').text(t_fld_btp03);

    $('#fld_btp01').val(t_fld_btp01);
    $('#fld_btp02').val(t_fld_btp02);
    $('#fld_btp03').val(t_fld_btp03);

    $('#fld_btp23').val(s_fld_btp23);
    $('#fld_btp24').val(s_fld_btp24);
    $('#fld_btp25').val(s_fld_btp25);

    $('#fld_btuamt').val(s_fld_total);
    $('#fld_btamt01').val(s_fld_tax);
    $('#fld_btamt').val(s_fld_gtotal);

    if(parseFloat($('#fld_btp70').val())>parseFloat($('#fld_btp70_r').text())){$('#fld_btp70').val('<?=$fld_btp70?>');total();alert('More than Remain QTY.');$('#fld_btp70').focus();}
    if(parseFloat($('#fld_btp72').val())>parseFloat($('#fld_btp72_r').text())){$('#fld_btp72').val('<?=$fld_btp72?>');total();alert('More than Remain QTY.');$('#fld_btp72').focus();}
    if(parseFloat($('#fld_btp74').val())>parseFloat($('#fld_btp74_r').text())){$('#fld_btp74').val('<?=$fld_btp74?>');total();alert('More than Remain QTY.');$('#fld_btp74').focus();}
    if(parseFloat($('#fld_btp76').val())>parseFloat($('#fld_btp76_r').text())){$('#fld_btp76').val('<?=$fld_btp76?>');total();alert('More than Remain QTY.');$('#fld_btp76').focus();}
    if(parseFloat($('#fld_btp71').val())>parseFloat($('#fld_btp71_r').text())){$('#fld_btp71').val('<?=$fld_btp71?>');total();alert('More than Remain QTY.');$('#fld_btp71').focus();}
    if(parseFloat($('#fld_btp73').val())>parseFloat($('#fld_btp73_r').text())){$('#fld_btp73').val('<?=$fld_btp73?>');total();alert('More than Remain QTY.');$('#fld_btp73').focus();}
    if(parseFloat($('#fld_btp78').val())>parseFloat($('#fld_btp78_r').text())){$('#fld_btp78').val('<?=$fld_btp78?>');total();alert('More than Remain QTY.');$('#fld_btp78').focus();}
    if(parseFloat($('#fld_btp77').val())>parseFloat($('#fld_btp77_r').text())){$('#fld_btp77').val('<?=$fld_btp77?>');total();alert('More than Remain QTY.');$('#fld_btp77').focus();}
    if(parseFloat($('#fld_btp75').val())>parseFloat($('#fld_btp75_r').text())){$('#fld_btp75').val('<?=$fld_btp75?>');total();alert('More than Remain QTY.');$('#fld_btp75').focus();}
    if(parseFloat($('#fld_btp81').val())>parseFloat($('#fld_btp81_r').text())){$('#fld_btp81').val('<?=$fld_btp81?>');total();alert('More than Remain QTY.');$('#fld_btp81').focus();}

  }

  function savedata(){
    total();
    if(parseFloat($('#fld_btamt').val())>0){
      console.log($("#form").serialize());
      var settings = {
        "async": true,
        "crossDomain": true,
        "dataType": 'jsonp',
        "url": "<?=$host;?>/index.php/PortalApi/insertreleasebook",
        "data":$("#form").serialize()+'&fld_btflag='+$('#fld_btflag').val()+'&fld_baidc=<?=$fld_baidc?>'+'&fld_baidp=<?=$fld_baidp?>',
        "method": "GET",
        "headers": {
          "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
          "Content-Type": "application/x-www-form-urlencoded",
          "Accept": "application/json",
          "cache-control": "no-cache",
          "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
        }
      }

      $.ajax(settings).done(function (response) {
        // console.log(response);
        alert('Data has been saved.');
        window.open("https://portal.dunextr.com/index.php/page/form/78000DEPO_EXPORT/edit/"+response['data'], "_self");
      });
    }else{
      alert('Pleace check Booked Qty.');
    }

  }

  function uploadattach(){
    var form = new FormData();
    form.append('fld_btp13', $('#fld_btp13')[0].files[0]);
    form.append('fld_btp14', $('#fld_btp14').val());
    form.append('fld_btp15', $('#fld_btp15').val());
    form.append('fld_btp16', $('#fld_btp16').val());
    form.append('fld_btid', $('#fld_btid').val());
    // console.log($('#fld_btp22')[0].files[0]);
    // console.log($('#fld_btp22')[0].files[0]);

    var settings = {
      "async": true,
      "crossDomain": true,
      "url": "<?=$host_upload?>/index.php/page/uploadattach_br",
      "method": "POST",
      "headers": {
        "cache-control": "no-cache",
        "Postman-Token": "cc23c416-8e3e-4471-b1c0-25f7d1d77814"
      },
      "processData": false,
      "contentType": false,
      "mimeType": "multipart/form-data",
      "data": form
    }

    $.ajax(settings).done(function (response) {
      console.log(response);
      if(response==false){
        alert('Upload failed.');
      }else{
        $('#fld_btp22_dsc').val(response);
        // console.log("http://localhost/dunex-rest/index.php/PortalApi/doonline_setatchmnt?fld_btid=<?=$fld_btid;?>&fld_btp22="+encodeURI(response));
        var settings2 = {
          "async": true,
          "crossDomain": true,
          "url": "<?=$host?>/index.php/PortalApi/bookingrelease_setatchmnt?fld_btid=<?=$fld_btid;?>&fld_btp13="+encodeURI(response)+"&fld_btp14="+encodeURI($('#fld_btp14').val())+"&fld_btp15="+encodeURI($('#fld_btp15').val())+"&fld_btp16="+encodeURI($('#fld_btp16').val()),
          "method": "GET",
          "headers": {
            "cache-control": "no-cache",
            "Postman-Token": "cc23c416-8e3e-4471-b1c0-25f7d1d77814"
          },
          "processData": false,
          "contentType": false,
          "dataType": 'jsonp',
          "mimeType": "multipart/form-data"
        }

        $.ajax(settings2).done(function (response2) {
          console.log(response2);
          alert('Data has been uploaded.');
          window.open("https://portal.dunextr.com/index.php/page/form/78000DEPO_EXPORT/edit/<?=$fld_btid;?>", "_self");
        });
      }
    });

  }

  function downloadattach(){
    window.open("https://portal.dunextr.com/uploads/dms/"+$('#fld_btp13_dsc').text());
    // window.open("http://localhost/dunex/new-portal/uploads/dms/"+$('#fld_btp13_dsc').text());
  }

</script>
