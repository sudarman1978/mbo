<?
$curl = curl_init();
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
// session_start();
// echo $uri_segments[6];
$fld_btid=0;
$fld_btstat_dsc="";
$fld_btstat="";
$fld_btno="";
$fld_btdt="";
$fld_baidc_desc=$_SESSION['customer_nm'];
$fld_baidc=$_SESSION['customer'];

$fld_btdtso="";
$fld_btp02="";
$fld_btp03="";
$fld_btp04="";
$fld_btp05="";
$fld_btp06="";
$fld_btp11="";
$fld_btp01="";
$fld_btp25="";
$fld_btp30="";
$fld_btp26="";
$fld_btp28="";
$fld_btp29="";
$fld_btp31="";
$fld_btp27="";
$fld_btdesc="";
$fld_btp22="";
$fld_baidp=$_SESSION['ctid'];
$fld_baidp_dsc=$_SESSION['ctnm'];
$fld_baido="";
$fld_btloc=$_SESSION['location'];
$fld_btloc_dsc=$_SESSION['location_nm'];
// echo $uri_segments[5];
$host="https://rest.dunextr.com";
// $host="localhost/dunex-rest/";

// echo $_SERVER['REQUEST_URI'];

if($_SERVER['HTTP_HOST']=='localhost'){
  $segmen_action=$uri_segments[6];
  $segmen_btid=$uri_segments[7];
}else{
  $segmen_action=$uri_segments[5];
  $segmen_btid=$uri_segments[6];
}

if($segmen_action=='edit'){

  // echo "https://".$host."/index.php/PortalApi/whsOutboundEdit";
  // $host="localhost/dunex-rest";
  curl_setopt_array($curl, array(
  CURLOPT_URL => "http://172.17.1.17/index.php/PortalApi/whsOutboundEdit",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"fld_btid\"\r\n\r\n".$segmen_btid."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: a36a0333-716c-4a33-9f6b-2f1a60b7a74d",
    "cache-control: no-cache",
    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
    "fld_btid:$segmen_btid"
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
  $rdata=$data[0];


  $fld_btid=$data['data'][0]['fld_btid'];
  $fld_btstat=$data['data'][0]['fld_btstat'];
  $fld_btno=$data['data'][0]['fld_btno'];
  $fld_btdt=$data['data'][0]['fld_btdt'];
  $fld_baidc=$data['data'][0]['fld_baidc'];
  $fld_btdtso=$data['data'][0]['fld_btdtso'];
  $fld_btp02=$data['data'][0]['fld_btp02'];
  $fld_btp03=$data['data'][0]['fld_btp03'];
  $fld_btp04=$data['data'][0]['fld_btp04'];
  $fld_btp05=$data['data'][0]['fld_btp05'];
  $fld_btp06=$data['data'][0]['fld_btp06'];
  $fld_btp11=$data['data'][0]['fld_btp11'];
  $fld_btp01=$data['data'][0]['fld_btp01'];
  $fld_btp25=$data['data'][0]['fld_btp25'];
  $fld_btp30=$data['data'][0]['fld_btp30'];
  $fld_btp26=$data['data'][0]['fld_btp26'];
  $fld_btp26=trim(preg_replace('/\s+/', ' ', $fld_btp26));
  $fld_btp28=$data['data'][0]['fld_btp28'];
  $fld_btp28=trim(preg_replace('/\s+/', ' ', $fld_btp28));
  $fld_btp29=$data['data'][0]['fld_btp29'];
  $fld_btp29=trim(preg_replace('/\s+/', ' ', $fld_btp29));
  $fld_btp31=$data['data'][0]['fld_btp31'];
  $fld_btp27=$data['data'][0]['fld_btp27'];
  $fld_btp27=trim(preg_replace('/\s+/', ' ', $fld_btp27));
  $fld_btdesc=$data['data'][0]['fld_btdesc'];
  $fld_btdesc=trim(preg_replace('/\s+/', ' ', $fld_btdesc));
  $fld_btp22=$data['data'][0]['fld_btp22'];
  $fld_btp24=$data['data'][0]['fld_btp24'];
  $fld_baido=$data['data'][0]['fld_baido'];
  $fld_btloc=$data['data'][0]['fld_btloc'];
  $fld_baidc_desc=$data['data'][0]['fld_baidc_desc'];
  $fld_btstat_dsc=$data['data'][0]['fld_btstat_dsc'];

}
// echo $fld_baidc;
 //var_dump($data);
// $var = $this->session->userdata;

// echo $location.$location_nm;
// echo $customer.'a';
// echo 'test'.$data['fld_btid'];
// foreach($data['data'] as $rdata){
#endforeach;
#print_r($response);
$userloc = $this->session->userdata('userloc');
$fld_baidc = $this->session->userdata('customer');
$id_user = $this->session->userdata('userid');
$whs_project = $this->session->userdata('whs_project');

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
        <input type="hidden" class="form-control form-control-rounded" id="fld_btloc" name="fld_btloc" value="<?=$userloc;?>" readonly>
          <input type="hidden" class="form-control form-control-rounded" id="fld_btp13" name="fld_btp13" value="<?=$whs_project;?>" readonly>
          <input type="hidden" class="form-control form-control-rounded" id="fld_btp20" name="fld_btp20" value="100" readonly>
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
              <label for="fld_btno">DO Number</label>
              <input type="text" class="form-control form-control-rounded" id="fld_btno" name="fld_btno" value="<?=$fld_btno;?>"readonly>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label for="fld_btdt">Transaction Date</label>
              <input id="fld_btdt" name="fld_btdt" class="form-control form-control-rounded datepicker" name="dp" value="<?=$fld_btdt;?>" readonly>
          </div>
          <div class="col-md-6 form-group mb-3">
              <label for="fld_baidc_desc">Customer Name</label>
              <input type="text" class="form-control form-control-rounded" id="fld_baidc_desc" name="fld_baidc_desc"value="<?=$fld_baidc_desc;?>" readonly>
              <input type="hidden" class="form-control form-control-rounded" id="fld_baidc" name="fld_baidc" value="<?=$fld_baidc;?>" >
          </div>
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btdtso">Delivery Date</label>
              <input id="fld_btdtso" name="fld_btdtso" class="form-control form-control-rounded datepicker"  value="<?=$fld_btdtso;?>" readonly>
          </div>

        </div>
        <br>

        <div class="card-title mb-3 border-bottom">Outbound Information</div>
        <div class="row">
          <div class="col-md-6 form-group mb-3">
            <label for="fld_btp11">Ship To</label>
            <select id="fld_btp01" name="fld_btp01" class="form-control form-control-rounded" value="<?=$fld_btp01;?>">
              <option value="0">[--Select--]</option>
               <?
                  $curl_csize = curl_init();
                  curl_setopt_array($curl_csize, array(
                    CURLOPT_URL => "http://172.17.1.17/index.php/PortalApi/listshipto",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                      "cache-control: no-cache",
                      "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                      "fld_btid: 312312",
                      "postman-token: a0af077c-fbd7-c13d-566e-f7b8d34f8ec1"
                    ),
                  ));

                  $response_csize = curl_exec($curl_csize);
                  $err_csize = curl_error($curl_csize);

                  curl_close($curl_csize);

                  if ($err_csize) {
                    echo "cURL Error #:" . $err_csize;
                  } else {
                    #$data = json_decode($response,TRUE);
                  }
                  $data_csize = json_decode($response_csize,TRUE);
                  // $rdata_ship=$data_ship[0];
                  // var_dump($data_csize);
                  foreach($data_csize['data'] as $rdata){
                  ?>
                    <option value=<?=$rdata['id'];?>><?=$rdata['name'];?></option>
                  <?};?>
            </select>
          </div>


          <div class="col-md-6 form-group mb-3">
            <label for="fld_btp03">Fleet Type</label>
            <select id="fld_btp03" name="fld_btp03" class="form-control form-control-rounded" placeholder="yyyy-mm-dd">
               <option value="0">[--Select--]</option>
               <?
                  $curl_fleet = curl_init();
                  curl_setopt_array($curl_fleet, array(
                    CURLOPT_URL => "http://172.17.1.17/index.php/PortalApi/listfleettype",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                      "cache-control: no-cache",
                      "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                      "fld_btid: 312312",
                      "postman-token: a0af077c-fbd7-c13d-566e-f7b8d34f8ec1"
                    ),
                  ));

                  $response_fleet = curl_exec($curl_fleet);
                  $err_fleet = curl_error($curl_fleet);

                  curl_close($curl_fleet);

                  if ($err_fleet) {
                    echo "cURL Error #:" . $err_fleet;
                  } else {
                    #$data = json_decode($response,TRUE);
                  }
                  $data_fleet = json_decode($response_fleet,TRUE);
                  // $rdata_ship=$data_ship[0];
                  // var_dump($data_fleet);
                  foreach($data_fleet['data'] as $rdata){
                  ?>
                    <option value=<?=$rdata['id'];?>><?=$rdata['name'];?></option>
              <?};?>
            </select>
          </div>
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp04">Truck Number</label>
              <input id="fld_btp04" name="fld_btp04" class="form-control form-control-rounded" value="<?=$fld_btp04;?>">
          </div>
          <div class="col-md-6 form-group mb-3">
            <label for="fld_btp02">Transporter</label>
            <input id="fld_btp02" name="fld_btp02" class="form-control form-control-rounded" value="<?=$fld_btp02;?>">
          </div>


          <div class="col-md-6 form-group mb-3">
              <label for="fld_btp06">Container Number</label>
              <input id="fld_btp06" name="fld_btp06" class="form-control form-control-rounded" value="<?=$fld_btp06;?>">
          </div>
          <div class="col-md-6 form-group mb-3">
             <label for="fld_btp05">Driver</label>
             <input id="fld_btp05" name="fld_btp05" class="form-control form-control-rounded" value="<?=$fld_btp05;?>">
          </div>

          <div class="col-md-6 form-group mb-3">
            <label for="fld_btp11">Container Size</label>
            <select id="fld_btp11" name="fld_btp11" class="form-control form-control-rounded" value="<?=$fld_btp11;?>">
              <option value="0">[--Select--]</option>
               <?
                  $curl_csize = curl_init();
                  curl_setopt_array($curl_csize, array(
                    CURLOPT_URL => "http://172.17.1.17/index.php/PortalApi/listcontainersize",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                      "cache-control: no-cache",
                      "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                      "fld_btid: 312312",
                      "postman-token: a0af077c-fbd7-c13d-566e-f7b8d34f8ec1"
                    ),
                  ));

                  $response_csize = curl_exec($curl_csize);
                  $err_csize = curl_error($curl_csize);

                  curl_close($curl_csize);

                  if ($err_csize) {
                    echo "cURL Error #:" . $err_csize;
                  } else {
                    #$data = json_decode($response,TRUE);
                  }
                  $data_csize = json_decode($response_csize,TRUE);
                  // $rdata_ship=$data_ship[0];
                  // var_dump($data_csize);
                  foreach($data_csize['data'] as $rdata){
                  ?>
                    <option value=<?=$rdata['id'];?>><?=$rdata['name'];?></option>
                  <?};?>
            </select>
          </div>

          <div class="col-md-6 form-group mb-3">
             <label for="fld_btp05">Remark</label>
             <input id="fld_btdesc" name="fld_btdesc" class="form-control form-control-rounded" value="<?=$fld_btdesc;?>">
          </div>


        </div>
        <br>


            <div class="row">
            <div class="col-md-12">
                <a id="submit_btn" class="btn btn-primary" style="color:white;" onclick="savedata()">Save</a>
                <a id="adddetail_btn" class="btn btn-primary" data-toggle="modal" data-target="#modal_product" onclick="reloadproduct2()" style="color:white;">Add Detail</a>
            </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="card-body">

                <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-bottom: 10px: ">
                    <li class="nav-item"><a class="nav-link active" id="home-basic-tab" data-toggle="tab" href="#homeBasic" role="tab" aria-controls="homeBasic" aria-selected="true">Detail Item</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" id="profile-basic-tab" data-toggle="tab" href="#profileBasic" role="tab" aria-controls="profileBasic" aria-selected="false">Delivery Status</a></li> -->
                </ul>
                <div class="tab-content" id="myTabContent" style="padding: 0px;margin-top: 10px;">
                  <div class="tab-pane fade show active" id="homeBasic" role="tabpanel" aria-labelledby="home-basic-tab">
                    <div class="table-responsive">
                      <table class="display table table-striped table-bordered" id="table_detail" style="width:100%"></table>
                    </div>
                  </div>
<!--                                   <div class="tab-pane fade" id="profileBasic" role="tabpanel" aria-labelledby="profile-basic-tab">
                      Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore.

                  </div> -->

                </div>
              </div>
            </div>
        </div>
      </form>

    </div>
  </div>
</div>


<!--  Modal -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content" style="margin-top: 70%;">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Edit Qty</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
          </div>
          <div class="modal-body">
            <input type="hidden" class="form-control form-control-rounded" id="e_fld_btid" name="e_fld_btid" size="10" readonly>
            <input type="hidden" class="form-control form-control-rounded" id="e_fld_btiid" name="e_fld_btiid" size="10" readonly>
            <label for="e_fld_btinm">Item Name</label>
            <input type="text" class="form-control form-control-rounded" id="e_fld_btinm" name="e_fld_btinm" size="10" readonly><br/>
            <label for="e_fld_good_qty">Qty</label>
            <input type="text" class="form-control form-control-rounded" id="e_fld_good_qty" name="e_fld_good_qty" size="10""><br/>
            <input type="hidden" class="form-control form-control-rounded" id="e_fld_good_qty_old" name="e_fld_good_qty_old" size="10"><br/>
            <label for="e_fld_good_qty">Available Stock</label>
            <input type="text" class="form-control form-control-rounded" id="e_avai" name="e_avai" size="10" readonly>

          </div>
          <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
              <a id="saveedit_btn" class="btn btn-primary ml-2" type="button" onclick="saveeditdetail();" style="color:white;">Save changes</a>
          </div>
       </div>
   </div>
</div>


<!--  Large Modal -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" id="modal_product" >
  <div class="modal-dialog modal-lg">
      <div class="modal-content" >
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">Product List</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table id="table_product" class="display table table-striped table-bordered" style="width:100%"></table>

            </div>


          </div>
          <div class="modal-footer">
              <a class="btn btn-secondary" type="button" onclick="clearCartData()" data-dismiss="modal" id="close_btn">Cancel</a>
              <a class="btn btn-primary ml-2" type="button" onclick="savedetail()" style="color:white;">Save changes</a>
          </div>
      </div>
  </div>
</div>


<script type="text/javascript">
  // console.log('<?=$uri_segments[5];?>'+'a');
  var left_stock ;
  $(document).ready(function(){
    $('.datepicker').pickadate({
      format: 'yyyy-mm-dd'
    });
    // console.log('test');
    $('#adddetail_btn').hide();
    $('#approve_btn').hide();
    $('#btn_downloadattach').hide();
    $('#btn_uploadattach').hide();

    if('<?=$segmen_action;?>'=='edit'){
      // console.log('<?=$host;?>/index.php/PortalApi/listproduct?fld_baidc='+<?=$fld_baidc;?>);
      var table_product=$('#table_product').DataTable( {
      "ajax": {
          "url": '<?=$host;?>/index.php/PortalApi/listproduct?fld_baidc='+<?=$fld_baidc;?>+'&whs_project='+<?=$whs_project;?>+'&location='+<?=$userloc;?>,
          "crossDomain": true,
          "dataType": "jsonp"
      },
      "lengthMenu": [[-1], ["All"]],
      "paging": false,
      "columns": [
          {
              "data": "id",
              render: function (data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
              },"sortable":false
          },
          // { "title": "&nbsp;", "data": "check_box","visible": true },
          { "title": "ID", "data": "fld_btiid","visible": false },
          { "title": "Qty", "data": "input_box" },
          { "title": "Avaliable", "data": "stock_available","visible": true  },
          { "title": "Product Name", "data": "fld_btinm","visible": true ,"width": 'auto' }
      ]
    } );
      $('#approve_btn').show();
      // console.log('test');
      if('<?=$fld_btstat?>'=='3'){
        $('#submit_btn').hide();
        $('#adddetail_btn').hide();
        $('#approve_btn').hide();
        $('#btn_downloadattach').show();
        $('#btn_uploadattach').hide();
      }else{
        $('#submit_btn').show();
        $('#adddetail_btn').show();
        $('#approve_btn').show();
        $('#btn_downloadattach').show();
        $('#btn_uploadattach').show();
      }


      // console.log('<?=$host;?>/index.php/PortalApi/listdetaildoonline?fld_btid='+<?=$fld_btid;?>);

      var table_detail=$('#table_detail').DataTable( {
          "ajax": {
              "url": '<?=$host;?>/index.php/PortalApi/listdetaildoonline?fld_btid='+<?=$fld_btid;?>,
              "crossDomain": true,
              "dataType": "jsonp"
          },

          "order": [[ 0, "asc" ]],
          "lengthMenu": [[-1], ["All"]],
          "columns": [
              { "title": "ID", "data": "fld_btid","visible": false },
              { "title": "Item Name", "data": "fld_btinm","visible": true },
              { "title": "Qty", "data": "fld_good_qty" },
              { "title": "Pallet ID", "data": "fld_palletid" },
              { "title": "Batch No/Lot No", "data": "fld_batch_no","visible": true },
              { "title": "Edit", "data": "edit_btn","visible": true },
              { "title": "Delete", "data": "delete_btn","visible": true }
          ]
      } );

    }


    if('<?=$segmen_action?>'=='add'){
      // console.log('tes');
        var now_dt=new Date();
        var fld_btdt = $('#fld_btdt').pickadate({format: 'yyyy-mm-dd'});
        var fld_btdtso = $('#fld_btdtso').pickadate({format: 'yyyy-mm-dd'});

        var fld_btdt = fld_btdt.pickadate('picker');fld_btdt.set('select',now_dt);
        var fld_btdtso = fld_btdtso.pickadate('picker');fld_btdtso.set('select',now_dt);
    }else{
        var now_dt=new Date();
        var fld_btdt = $('#fld_btdt').pickadate({format: 'yyyy-mm-dd'});
        var fld_btdtso = $('#fld_btdtso').pickadate({format: 'yyyy-mm-dd'});

        var fld_btdt = fld_btdt.pickadate('picker');fld_btdt.set('select','<?=$fld_btdt;?>');
        var fld_btdtso = fld_btdtso.pickadate('picker');fld_btdtso.set('select','<?=$fld_btdtso;?>');
        setaddress(<?=$fld_btp01?>);
        $("#fld_btp03").val("<?=$fld_btp03;?>");
        $("#fld_btp02").val("<?=$fld_btp02;?>");
        $("#fld_btp11").val("<?=$fld_btp11;?>");
        $("#fld_btp01").val("<?=$fld_btp01;?>");
        $("#fld_btp30").val("<?=$fld_btp30;?>");
        $("#fld_btp26").val("<?=($fld_btp26);?>");
        $("#fld_btp25").val(parseFloat("<?=($fld_btp25);?>"));
        $("#fld_btp28").val("<?=$fld_btp28;?>");
        $("#fld_btp27").val("<?=$fld_btp27;?>");
        $("#fld_btdesc").val("<?=$fld_btdesc;?>");
        if('<?=$fld_btp24;?>'=='1'){
          $("#fld_btp24").prop('checked', true);
        }else{
          $("#fld_btp24").prop('checked', false);
        }
    }
  });

  var filladdress="";

  function setaddress(fld_btp01){
    //gunakan jsonp ketika cors blocked
    var settings = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host?>/index.php/PortalApi/listcontaineraddress?fld_btp01="+fld_btp01,
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
      filladdress="";
      var test=response['data'];
      test.forEach(isiaddress);
      $('#fld_btp25').empty().append(filladdress);
      if('<?=$segmen_action?>'=='add'){

      }else{

        $("#fld_btp25").val(parseFloat("<?=($fld_btp25);?>"));
      }

    });
  }

  function isiaddress(item, index, arr){

    filladdress+="<option value="+item['id']+">"+item['name']+"</option>";
    // console.log(filladdress);
  }

  function reloadproduct2(){

    var table_data_product = $('#table_product').DataTable();
    table_data_product.ajax.reload();
  }

  function editdetail(fld_btid,fld_good_qty,fld_btinm,fld_btiid){
    if('<?=$fld_btstat?>'!='3'){
      // console.log(fld_btid);
      // console.log(fld_good_qty);
      // console.log(fld_btinm);
      // console.log(fld_btiid);
      // $('#saveedit_btn').addClass('disabled');
      $('#e_fld_btid').val(fld_btid);
      $('#e_fld_good_qty_old').val(fld_good_qty);
      $('#e_fld_good_qty').val(fld_good_qty);
      $('#e_fld_btinm').val(fld_btinm);
      $('#e_fld_btiid').val(fld_btiid);

      var settings4 = {
        "async": true,
        "crossDomain": true,
        "dataType": 'jsonp',
        "url": "<?=$host;?>/index.php/PortalApi/checkstockdoonline",
        "data":"fld_btidp=<?=$fld_btid;?>&fld_btiid="+fld_btiid+'&value=0',
        "method": "GET",
        "headers": {
          "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
          "Content-Type": "application/x-www-form-urlencoded",
          "Accept": "application/json",
          "cache-control": "no-cache",
          "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
        }
      }

      // console.log(settings);

      $.ajax(settings4).done(function (response4) {

          $('#e_avai').val(response4.data1);

      });
    }else{
      alert('This DO Approved, can\'t edit.');
    }

  }

  function saveeditdetail(){

    var e_fld_btid=$('#e_fld_btid').val();

    $('#saveedit_btn').addClass('disabled');
    var qty_old=$('#e_fld_good_qty_old').val();
    var qty_new=$('#e_fld_good_qty').val();
    if($('#e_fld_good_qty').val()==''){
      qty_new=0;
    }
    if($('#e_fld_good_qty_old').val()==''){
      qty_old=0;
    }
    var selisih=qty_new-qty_old;
    if(selisih==''){
      selisih=0;
    }
    var settings4 = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host;?>/index.php/PortalApi/checkstockdoonline",
      "data":"fld_btidp=<?=$fld_btid;?>&fld_btiid="+$('#e_fld_btiid').val()+'&value='+selisih,
      "method": "GET",
      "headers": {
        "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
        "Content-Type": "application/x-www-form-urlencoded",
        "Accept": "application/json",
        "cache-control": "no-cache",
        "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
      }
    }

    var settings_saveedit = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host;?>/index.php/PortalApi/updateqtydetaildoonline",
      "data":"fld_btid="+e_fld_btid+'&value='+qty_new,
      "method": "GET",
      "headers": {
        "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
        "Content-Type": "application/x-www-form-urlencoded",
        "Accept": "application/json",
        "cache-control": "no-cache",
        "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
      }
    }

    $.ajax(settings4).done(function (response4) {
      // console.log(response4);
      if(response4.data<0){
        alert('Stock is just unavaliable');
        $('#e_fld_good_qty').val(qty_old);
        $('#e_avai').val(response4.data1);
        $('#saveedit_btn').removeClass('disabled');
      }else{
        $('#saveedit_btn').removeClass('disabled');

        $.ajax(settings_saveedit).done(function (responseedit) {

            // console.log(response4);
            alert('Qty has been edited.');
            var table_detail3=$('#table_detail').DataTable();
            table_detail3.ajax.reload();
            $('#modal_edit').trigger('click');

        });
      }

      // $("#saveedit_btn").disabled();

    });
    // console.log(fld_btid);
    // console.log(fld_good_qty);
    // console.log(fld_btinm);
    // console.log(fld_btiid);



  }

  function editcekstock(){
    $('#saveedit_btn').addClass('disabled');
    var qty_old=$('#e_fld_good_qty_old').val();
    var qty_new=$('#e_fld_good_qty').val();
    if($('#e_fld_good_qty').val()==''){
      qty_new=0;
    }
    if($('#e_fld_good_qty_old').val()==''){
      qty_old=0;
    }
    var selisih=qty_new-qty_old;
    if(selisih==''){
      selisih=0;
    }

    var settings4 = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host;?>/index.php/PortalApi/checkstockdoonline",
      "data":"fld_btidp=<?=$fld_btid;?>&fld_btiid="+$('#e_fld_btiid').val()+'&value='+selisih,
      "method": "GET",
      "headers": {
        "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
        "Content-Type": "application/x-www-form-urlencoded",
        "Accept": "application/json",
        "cache-control": "no-cache",
        "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
      }
    }

    // console.log(settings);

    $.ajax(settings4).done(function (response4) {
      // console.log(response4);
      if(response4.data<0){
        alert('Stock is just unavaliable');
        $('#e_fld_good_qty').val(qty_old);
        $('#e_avai').val(response4.data1);
        $('#saveedit_btn').removeClass('disabled');
      }else{
        $('#e_avai').val(response4.data);
        $('#saveedit_btn').removeClass('disabled');
      }

      // $("#saveedit_btn").disabled();

    });
  }

  function deletedetail(fld_btid){
    // console.log(fld_btid);
    if('<?=$fld_btstat?>'!='3'){
      var settings_del = {
        "async": true,
        "crossDomain": true,
        "dataType": 'jsonp',
        "url": "<?=$host;?>/index.php/PortalApi/deletedetail",
        "data":"fld_btid="+fld_btid,
        "method": "GET",
        "headers": {
          "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
          "Content-Type": "application/x-www-form-urlencoded",
          "Accept": "application/json",
          "cache-control": "no-cache",
          "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
        }
      }

      // console.log(settings);

      if (confirm("Are you sure delete this Product from this DO ?") == true) {
        $.ajax(settings_del).done(function (response_del) {
          // console.log(response_del);
          var table_detail3=$('#table_detail').DataTable();
          table_detail3.ajax.reload();
          alert("Has been Deleted.");

        });
      } else {
        txt = "Cancel";
        alert(txt);
      }
    }else{
      alert('This DO Approved, can\'t Delete.');
    }

  }

  function savedata(){
      var settings = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host;?>/index.php/PortalApi/insertdoonline",
      "data":$("#form").serialize(),
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
      window.open("<?=base_url();?>index.php/page/form/78000DO_ONLINE/edit/"+response['data'], "_self");
    });



  }



  function cekstock(qty_id){
    var row_id = qty_id.substring(4);
    var settings3 = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host;?>/index.php/PortalApi/clearcarttemp",
      "data":"fld_btidp=<?=$fld_btid;?>&fld_btiid="+row_id,
      "method": "GET",
      "headers": {
        "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
        "Content-Type": "application/x-www-form-urlencoded",
        "Accept": "application/json",
        "cache-control": "no-cache",
        "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
      }
    }

    // console.log(settings);

    $.ajax(settings3).done(function (response3) {
      // console.log(response3);
      // $('#qtya_'+row_id).val(stock);
    });
    // console.log(row_id);
    // console.log(row_id);

    var qty = $('#'+qty_id).val() * 1;
    var settings5 = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host;?>/index.php/PortalApi/changestock",
      "data":"fld_btidp=<?=$fld_btid;?>&fld_btiid="+row_id+'&value='+qty,
      "method": "GET",
      "headers": {
        "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
        "Content-Type": "application/x-www-form-urlencoded",
        "Accept": "application/json",
        "cache-control": "no-cache",
        "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
      }
    }

    // console.log(settings);

    $.ajax(settings5).done(function (response4) {
      // console.log(response4);
      if(qty>response4.data1){
        $('#'+qty_id).val(0);
        alert('Stock is unavaliable.');
        $('#qtya_'+row_id).val(response4.data1);
      }else{
        if(qty==''){
          $('#'+qty_id).val(0);
        }
        $('#qtya_'+row_id).val(response4.data);
      }
    });



  }

  function clearCartData () {
    var settings5 = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host;?>/index.php/PortalApi/deltempdetail",
      "data":"fld_btidp=<?=$fld_btid;?>",
      "method": "GET",
      "headers": {
        "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
        "Content-Type": "application/x-www-form-urlencoded",
        "Accept": "application/json",
        "cache-control": "no-cache",
        "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
      }
    }

  }

  function savedetail(){
    var table = document.getElementById("table_product");
    // console.log(table);
    var table_data = $('#table_product').DataTable();
    var data = table_data.rows().data();
    $('#table_product').DataTable().search('').draw();
    for(i=0;i<data.length;i++){

        if($('#qty_'+data[i].fld_btiid).val()>0){
          // console.log($('#qty_'+data[i].fld_btiid).val());
          var settings4 = {
            "async": true,
            "crossDomain": true,
            "dataType": 'jsonp',
            "url": "<?=$host;?>/index.php/PortalApi/checkstockdoonline",
            "data":"fld_btidp=<?=$fld_btid;?>&fld_btiid="+data[i].fld_btiid+'&value='+$('#qty_'+data[i].fld_btiid).val(),
            "method": "GET",
            "headers": {
              "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
              "Content-Type": "application/x-www-form-urlencoded",
              "Accept": "application/json",
              "cache-control": "no-cache",
              "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
            }
          }



          $.ajax(settings4).done(function (response4) {
            // console.log(response4);

            if(response4.data<0){
              alert(response4.fld_btinm + ' Stock is just unavaliable');

            } else {
                  var settings2 = {
                  "async": true,
                  "crossDomain": true,
                  "dataType": 'jsonp',
                  "url": "<?=$host;?>/index.php/PortalApi/insertdoonlinedetail",
                  "data":"fld_btidp=<?=$fld_btid;?>&fld_btiid="+response4.fld_btiid+"&fld_good_qty="+response4.qty,
                  "method": "GET",
                  "headers": {
                    "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
                    "Content-Type": "application/x-www-form-urlencoded",
                    "Accept": "application/json",
                    "cache-control": "no-cache",
                    "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
                  }
                }
                $.ajax(settings2).done(function (response2) {


                });
            }



           var table_detail3=$('#table_detail').DataTable();
           table_detail3.ajax.reload();

          });








        }

    }







     // $('#modal_product').hide();
     // $('#modal_product').modal('hide');
     // $('#modal_product').fadeOut();
     // $('.modal-backdrop').click();
     // $('.modal-backdrop').remove();
     $('#modal_product').trigger('click');



  }

  function approve_do(){
    var settings4 = {
      "async": true,
      "crossDomain": true,
      "dataType": 'jsonp',
      "url": "<?=$host;?>/index.php/PortalApi/approvedo",
      "data":"fld_btid=<?=$fld_btid;?>",
      "method": "GET",
      "headers": {
        "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
        "Content-Type": "application/x-www-form-urlencoded",
        "Accept": "application/json",
        "cache-control": "no-cache",
        "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
      }
    }

    // console.log(settings);

    $.ajax(settings4).done(function (response4) {
      // console.log(response4);
      alert('DO has been Approved.');
    });
  }

  function uploadattach(){
    var form = new FormData();
    form.append('fld_btp22', $('#fld_btp22')[0].files[0]);
    form.append('fld_btid', $('#fld_btid').val());
    // console.log($('#fld_btp22')[0].files[0]);
    // console.log($('#fld_btp22')[0].files[0]);

    var settings = {
      "async": true,
      "crossDomain": true,
      "url": "https://portal.dunextr.com/index.php/page/uploadattach",
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
      // console.log(response);
      if(response==false){
        alert('Upload failed.');
      }else{
        $('#fld_btp22_dsc').val(response);
        // console.log("http://localhost/dunex-rest/index.php/PortalApi/doonline_setatchmnt?fld_btid=<?=$fld_btid;?>&fld_btp22="+encodeURI(response));
        var settings2 = {
          "async": true,
          "crossDomain": true,
          "url": "<?=$host?>/index.php/PortalApi/doonline_setatchmnt?fld_btid=<?=$fld_btid;?>&fld_btp22="+encodeURI(response),
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
        });
        alert('Data has been uploaded.');
      }
    });

  }

  function downloadattach(){
    window.open("https://portal.dunextr.com/new-portal2/uploads/coldstorage/"+$('#fld_btp22_dsc').val());
  }



</script>
