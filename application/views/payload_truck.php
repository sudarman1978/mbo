<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" src="<?=base_url();?>assets/styles/plugins/bootstrap/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?=base_url();?>assets/js/plugins/bootstrap.bundle.min.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/bootstrap.min.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/popper.min.js"></script>
    <style>
    .lds-roller {
      display: inline-block;
      position: relative;
      width: 80px;
      height: 80px;
    }
    .lds-roller div {
      animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
      transform-origin: 40px 40px;
    }
    .lds-roller div:after {
      content: " ";
      display: block;
      position: absolute;
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #fff;
      margin: -4px 0 0 -4px;
    }
    .lds-roller div:nth-child(1) {
      animation-delay: -0.036s;
    }
    .lds-roller div:nth-child(1):after {
      top: 63px;
      left: 63px;
    }
    .lds-roller div:nth-child(2) {
      animation-delay: -0.072s;
    }
    .lds-roller div:nth-child(2):after {
      top: 68px;
      left: 56px;
    }
    .lds-roller div:nth-child(3) {
      animation-delay: -0.108s;
    }
    .lds-roller div:nth-child(3):after {
      top: 71px;
      left: 48px;
    }
    .lds-roller div:nth-child(4) {
      animation-delay: -0.144s;
    }
    .lds-roller div:nth-child(4):after {
      top: 72px;
      left: 40px;
    }
    .lds-roller div:nth-child(5) {
      animation-delay: -0.18s;
    }
    .lds-roller div:nth-child(5):after {
      top: 71px;
      left: 32px;
    }
    .lds-roller div:nth-child(6) {
      animation-delay: -0.216s;
    }
    .lds-roller div:nth-child(6):after {
      top: 68px;
      left: 24px;
    }
    .lds-roller div:nth-child(7) {
      animation-delay: -0.252s;
    }
    .lds-roller div:nth-child(7):after {
      top: 63px;
      left: 17px;
    }
    .lds-roller div:nth-child(8) {
      animation-delay: -0.288s;
    }
    .lds-roller div:nth-child(8):after {
      top: 56px;
      left: 12px;
    }
    @keyframes lds-roller {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
    .buttonload {
      background-color: #04AA6D; /* Green background */
      border: none; /* Remove borders */
      color: white; /* White text */
      padding: 12px 24px; /* Some padding */
      font-size: 16px; /* Set a font-size */
    }

    /* Add a right margin to each icon */
    .fa {
      margin-left: -12px;
      margin-right: 8px;
    }
    .select2 {
width:100%!important;
}
    </style>
  </head>
  <body>

    <div class="container-fluid">
      <div class="row">
        <div class="table-responsive">
                    <table class="table table-striped" id="table">
                      <!-- <thead>
                        <tr>
                          <th>Id</th>
                          <th>No Polisi</th>
                          <th>Tanggal</th>
                          <th>Posted By</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($data_payload->result() as $key => $value): ?>
                          <tr>
                            <td><?=$value->fld_id?></td>
                            <td><?=$value->nopol?></td>
                            <td><?=$value->fld_date?></td>
                            <td><?=$value->fld_empnm?></td>
                            <td>
                              <button type="button" onClick="edit('<?=$value->fld_id?>')" class="btn round btn-warning btn-icon rounded-circle m-1"><span class="ul-btn__icon"><i class="i-Edit"></i></span></button>
                              <button type="button" class="btn round btn-danger btn-icon rounded-circle m-1"><span class="ul-btn__icon"><i class="i-Close"></i></span></button>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody> -->
                    </table>
            </div>
      </div>
    </div>
    <!-- <?php
    var_dump($unit);
     ?> -->
    <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content" id="form_payloads">
              <div class="modal-header">
                <h4 class="modal-title">Form Payload Truck</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form id="form_payload" action="<?=base_url()?>index.php/payload/process_payload/" method="post">
                  <div class="row" id="row_id">
                    <div class="col-md-12 col-sm-12 form-group mb-3">
                      <label for="fld_id">ID</label>
                      <input type="text" class="form-control form-control-rounded" id="fld_id" name="fld_id" readonly >
                    </div>
                  </div>
                    <div class="row">
                    <div class="col-md-6 col-sm-12 form-group mb-3">
                        <label for="fld_btiid">No Polisi</label>
                        <select id="fld_btiid_desc" onChange="changeNopol(this.value)" name="fld_btiid_desc" class="form-control js-example-basic-single" >
                         <option value="0">[--Select--]</option>
                         <?php foreach ($unit as $key => $value) {
                           ?>
                          <option value="<?=$value->id?>"><?=$value->name?></option>
                        <?php } ?>
                       </select>
                       <input type="hidden" name="fld_btiid" id="fld_btiid" value="">
                    </div>
                    <div class="col-md-6 col-sm-12 form-group mb-3">
                      <label for="fld_date">Tanggal</label>
                      <input type="text" class="form-control form-control-rounded" id="fld_date" name="fld_date" value="" >
                    </div>
                  </div>
                  <div class="card-title mb-3 border-bottom">Payload</div>
                  <div class="row">
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_a01">Berat Kendaraan Kosong</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_a01" name="fld_a01" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_a02">Berat Orang</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_a02" name="fld_a02" value="" >
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_a03">Max Berat Muatan</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_a03" name="fld_a03" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_a04">Jumlah Berat Yang di Izinkan (JBI)</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_a04" name="fld_a04" value="" >
                      </div>
                  </div>
                  <div class="card-title mb-3 border-bottom">Dimensi Truck Box (Dalam)</div>
                  <div class="row">
                      <div class="col-md-4 form-group mb-3">
                        <label for="fld_b01">Panjang</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_b01" name="fld_b01" value="" >
                      </div>
                      <div class="col-md-4 form-group mb-3">
                        <label for="fld_b02">Lebar</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_b02" name="fld_b02" value="" >
                      </div>
                      <div class="col-md-4 form-group mb-3">
                        <label for="fld_b03">Tinggi</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_b03" name="fld_b03" value="" >
                      </div>
                  </div>
                  <div class="card-title mb-3 border-bottom">Dimensi Bak Muatan (Stoper ke Stoper)</div>
                  <div class="row">
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_c01">Panjang</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_c01" name="fld_c01" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_c02">Lebar</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_c02" name="fld_c02" value="" >
                      </div>
                  </div>
                  <div class="card-title mb-3 border-bottom">Dimensi Truck Box (Luar)</div>
                  <div class="row">
                      <div class="col-md-4 form-group mb-3">
                        <label for="fld_d01">Panjang</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_d01" name="fld_d01" value="" >
                      </div>
                      <div class="col-md-4 form-group mb-3">
                        <label for="fld_d02">Lebar</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_d02" name="fld_d02" value="" >
                      </div>
                      <div class="col-md-4 form-group mb-3">
                        <label for="fld_d03">Tinggi</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_d03" name="fld_d03" value="" >
                      </div>
                  </div>
                  <div class="card-title mb-3 border-bottom">Dimensi Pintu Belakang</div>
                  <div class="row">
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_e01">Lebar</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_e01" name="fld_e01" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_e02">Tinggi</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_e02" name="fld_e02" value="" >
                      </div>
                  </div>
                  <div class="card-title mb-3 border-bottom">Head Truck</div>
                  <div class="row">
                      <div class="col-md-4 form-group mb-3">
                        <label for="fld_f01">Panjang</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_f01" name="fld_f01" value="" >
                      </div>
                      <div class="col-md-4 form-group mb-3">
                        <label for="fld_f02">Lebar</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_f02" name="fld_f02" value="" >
                      </div>
                      <div class="col-md-4 form-group mb-3">
                        <label for="fld_f03">Tinggi</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_f03" name="fld_f03" value="" >
                      </div>
                  </div>
                  <div class="card-title mb-3 border-bottom">Dimensi Truck Keseluruhan</div>
                  <div class="row">
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_g01">Panjang Total Truck</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_g01" name="fld_g01" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_g02">Tinggi Truck Dari Tanah Ke Atap</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_g02" name="fld_g02" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_g03">Tinggi Truck Dari Tanah Ke Bak</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_g03" name="fld_g03" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_g04">Tinggi Wings Saat Terbuka</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_g04" name="fld_g04" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_g05">ROH Fisik</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_g05" name="fld_g05" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_g06">FOH Fisik</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_g06" name="fld_g06" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_g07">Jarak Sumbu 1 Ke 2</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_g07" name="fld_g07" value="" >
                      </div>
                      <div class="col-md-6 form-group mb-3">
                        <label for="fld_g08">Jarak Sumbu 2 Ke 3</label>
                        <input type="number" class="form-control form-control-rounded" id="fld_g08" name="fld_g08" value="" >
                      </div>
                  </div>
                  <div class="card-title mb-3 border-bottom">Penanggung Jawab</div>
                  <div class="row">
                    <div class="col-md-12 form-group mb-3">
                      <label for="fld_h01">PIC Pengukuran</label>
                      <input type="text" class="form-control form-control-rounded" id="fld_h01" placeholder="<?=$ctnm?>" name="fld_h01" value="" readonly>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <a href="#" onclick="submit()" class="btn btn-outline btn-primary">Submit</a>
                <a href="#" class="btn btn-outline btn-danger" data-dismiss="modal">Close</a>
              </div>
            </div>
          </div>
      </div>
  </body>
  <script>
  var actDyn;
  var url_api = '<?=base_url()?>index.php/Payload/getDataPayload';
  var table_payload;
  $(document).ready(function() {
  table_payload =  $('#table').DataTable({
        'dom': 'lBfrti',
        'buttons': [
          {
            'text': '<i class="i-Add-File"></i><span class="text-muted">Add New</span>',
            "className": 'btn btn-info btn-icon m-1',
            "titleAttr": 'Add New Record',
            action: function ( e, dt, node, config ) {
              add();
            }
          },
            {
              'text': '<i class="i-File-Excel"></i><span class="text-muted">Export Excel</span>',
              extend: 'excel',
              className: 'btn btn-info btn-icon m-1',
              titleAttr: 'Save To Excel'
            }
        ],
        "ajax": {
            "url": ''+url_api,
            "method": "GET",
            "headers": {
              "accept": "application/json",
              "Access-Control-Allow-Origin":"*",
              "cache-control": "no-cache",
              "Postman-Token": "35557d14-6fa1-448e-a25d-5d74ac042d15"
              }
        },
        "lengthMenu": [[-1], ["All"]],
        "paging": false,
        "filter": false,
        "responsive": true,
        "columns": [
            {
                "data": "id",
                render: function (data, type, row, meta) {
                  // console.log("data_col");
                  // console.log(data);
                    return meta.row + meta.settings._iDisplayStart + 1;
                },"sortable":false
            },
            // { "title": "&nbsp;", "data": "check_box","visible": true },

            { "title": "id", "data": "fld_id","visible" : false},
            { "title": "Nomor Polisi", "data": "nopol"},
            { "title": "Tanggal", "data": "fld_date"},
            { "title": "Posted By", "data": "fld_empnm"},
            {
            "title": "Action",
             "data":null,
              render: function (data, type, row, meta) {

                var id = data['fld_id'];
                var html = `
                <button type="button" id="edit`+id+`" class="btn round btn-warning btn-icon rounded-circle m-1"><span class="ul-btn__icon"><i class="i-Edit"></i></span></button>
                <button type="button" id="del`+id+`"  class="btn round btn-danger btn-icon rounded-circle m-1"><span class="ul-btn__icon"><i class="i-Close"></i></span></button>
                `
                return html;
                //  return meta.row + meta.settings._iDisplayStart + 1;
              }
            }
        ]
    });
    $('#table tbody').on('click', 'button', function () {
       var data = table_payload.row( this ).data();
       var id = data['fld_id'];
       var $edit = "edit"+id;
       var $del = "del"+id;

       if(Object.keys(data).length > 0){
            $.each(data,function(key,value){
                    $('#'+key).val(value);
                    if(key == 'fld_btiid'){
                      $('#fld_btiid_desc').val(value).trigger('change');
                    }
                    if(key == 'fld_h01'){
                      $('#fld_h01').val(data['fld_empnm']);
                    }
              });
        }
        if(this.id == $edit){
          actDyn = 2;
          $('#row_id').show();
          $('#myModal').modal();
        }
        if(this.id == $del){
          actDyn = 3;
          Swal.fire({
            title: 'Delete Actions!',
            html: "Are You Sure To delete This Action?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
              submit();
            }else{
              Swal.fire(
                'Canceled!',
                'Your has been Cancel this Request.',
                'error');
            }
          })
        }
   });

    $(".js-example-basic-single").each((_i, e) => {
    var $e = $(e);
    $e.select2({
      tags: true,
      width:'400px',
      dropdownParent: $e.parent()
    });
  });
  });
  function changeDesc(val){
    console.log("val");
    console.log(val);
  }
  function add(){
    var date = getFormattedDates(new Date());
    $('#fld_date').val(date);
    $('#fld_date').prop('readonly', true);
    actDyn = 1;
    //$('#fld_id').remove();
    $('#row_id').hide();
    $('#myModal').modal();
  }
  function edit(id){
    console.log(id);
    $('#myModal').modal();
  }
  function changeNopol(id){
    $('#fld_btiid').val(id);
  }
  function submit(){
    $('#fld_h01').val('<?=$ctid?>');
    var id = $('#fld_id').val();
    console.log("id");
    console.log(id);
    let data = new Object();
    let error_data = new Object();
    var error = false;
    var count = $('input').length;
    var check = 0;
    $('input').each(function() {
       if(!$(this).val() && this.id && this.id !== 'fld_id'){
         //console.log(this.id)
         error_data[this.id] = 'Harus Di isi';
         error = true;
       }
      if($(this).val() && this.id){
        data[this.id] = $(this).val();
        check += 1;
      }
   });

   if(Object.keys(error_data).length > 0 && error == true){
          var req = '<div style="text-align:left"><ul>Ada Field yang Belum Terisi!';

              $.each(error_data,function(key,value){
                      var label = $('label[for="' + key + '"]').html()
                      req  += '<li>Field : '+label+' '+value+'</li>';
              });

              req += '</ul></div>';

              Swal.fire(
                'Submit Error!',
                ''+req,
                'error'
              );

          }else{
     // console.log("data");
     // console.log(JSON.stringify(data))
     var url = $('#form_payload').attr('action');
     // console.log(url);
     $.ajax({
       type: "POST",
       url: ''+url,
       data: {
             "act": actDyn,
             "data": JSON.stringify(data),
           },
       async:false,
       dataType:'json',
       success: function (data) {
         console.log(data);
         if(data.error == true){
           Swal.fire(
             'Oopss!',
             'Something Wrong Error!!.\n'+data.msg,
             'error').then(function(e){

                                 });
         }else{
           Swal.fire(
             'Submit Success!',
             ''+data.msg,
             'success'
           ).then((result) => {
             $('#myModal').modal('hide');
             $("#form_payload").trigger("reset");
             table_payload.ajax.url(url_api).load();
           });
         }
       },
      error: function (jqXHR, textStatus, errorThrown) {
        $('#loader').addClass('hidden');
        $('#myModal').modal('hide');
        console.log(jqXHR);
        console.log(textStatus);
        Swal.fire(
          'Oopss!',
          'Something Wrong Error!!.\n'+textStatus,
          'error');
      }
     });
   }
  }
  function getFormattedDates(date) {
       var day = ("0" + (date.getDate() )).slice(-2);
       var month = ("0" + (date.getMonth() + 1)).slice(-2);
       var year = date.getFullYear();
       var hours = ("0" + (date.getHours() )).slice(-2);
       var minute = ("0" + (date.getMinutes())).slice(-2);
       var seccond = ("0" + (date.getSeconds() )).slice(-2);
       var a = year + '-' + month + '-' + day;
       return a;
   }
  </script>
</html>
