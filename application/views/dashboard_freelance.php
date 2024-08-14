<style media="screen">
  .row{
    margin: 10px;
  }

  .a-link{
  cursor: pointer;
  }
  .scroll {
      max-height: 400px;
      overflow-y: auto;
  }
  #ul-widget{
    border-bottom: .07rem dashed #020202 !important;
    cursor:pointer;
  }
  .ul-hover{
    padding: 5px;
  }
  .ul-hover:hover{
  background-color: #d4cfd982;
  cursor: pointer;
  -webkit-transition: background-color 1s ease-out;
  -moz-transition: background-color 1s ease-out;
  -o-transition: background-color 1s ease-out;
  transition: background-color 0.2s ease-out;
  border-radius: 10px;
  }
  table.dataTable tbody tr td {
    word-wrap: break-word;
    word-break: break-all;
}
</style>
    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card">
          <div class="card-header">
            <h4>History Transaction Freelance</h4>
          </div>
          <div class="card-body">
            <div class="content scroll">
              <?php foreach ($group_freelance as $key => $value) {
                ?>
                <div class="ul-hover">
                  <div class="ul-widget__item" id="ul-widget" onclick="clickAction(<?=$value->id_freelance?>,3)">
                    <div class="ul-widget5__content">
                      <div class="ul-widget5__section">
                        <a href="#" class="ul-widget4__title">Balance : [<?=number_format($value->balance,0,',',',')?>]</a>
                        <?php  ?>
                        <p class="ul-widget4__title">Name : <?=$value->freelance_name?></p>
                        <div class="ul-widget5__info">
                          <span>Total Project:</span>
                          <span class="text-primary"><?=$value->total_project?></span>
                          <span>Total Payment:</span>
                          <span class="text-primary"><?=$value->total_payment?></span>
                        </div>
                      </div>
                    </div>
                    <div class="ul-widget5__content">
                      <div class="ul-widget5__stats">
                        <span class="ul-widget5__number t-font-boldest text-primary text-center">Fee</span>
                        <span class="ul-widget5__sales badge badge-primary p-2 m-1 xl badge-pill"><?=number_format($value->revenue,0,',',',')?></span>
                      </div>
                      <div class="ul-widget5__stats" >
                        <span class="ul-widget5__number t-font-boldest text-success text-center">Payment</span>
                        <span class="ul-widget5__sales badge badge-success p-2 m-1 xl badge-pill"><?=number_format($value->payment,0,',',',')?></span>
                      </div>
                    </div>
                  </div>
                </div>

            <?php  } ?>
            </div>
          </div>
        </div>

      </div>
      <div class="modal fade" id="modalOut" tabindex="-1" role="dialog" aria-labelledby="modalOutLabel" aria-hidden="true">
      	<div class="modal-dialog modal-lg" role="document">
      		<div class="modal-content">
      			<div class="modal-header">
      				<h5 class="modal-title" id="modalOutLabel">Details Transaction For :
                <span id="name"></span>
              </h5>
      				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
      					<span aria-hidden="true">&times;</span>
      				</button>
      			</div>
      			<div class="modal-body mt-3">
              <div class="row" id="type">

              </div>
              <div class="row" id="date">

              </div>
      				<div class="row">
      					<div class="col-md-12">
      						<table id="modalOutTbl" class="table table-striped table-bordered" style="width:100%">
      						</table>
      					</div>
      				</div>
              <div class="row">
                <div class="col-md-6">
                  <a class="toggle-vis btn btn-danger" style="color:white" id="show_action"  data-column="2">Make Payment</a>
                </div>
                <div class="col-md-6 text-right">
                  <a class="btn btn-primary" style="color:white;" id="submit_action" onclick="submitPayment()">Submit</a>
                </div>
              </div>
              <div class="row justify-content-center" id="loader">
                <div class="spinner-bubble spinner-bubble-primary m-5"></div>

              </div>

      			</div>
      			<div class="modal-footer">
      				<button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
      			</div>
      		</div>
      	</div>
      </div>

    </div>
    <script>
    var tblModal;
    var count = 0;
    var total_all = 0;
    var countVisible = false;
    var dynamic_id_freelance;
    var column;
    var _type = 'all';
    var _date = 'all';
    var paynumber;
      var periode= "";
    // $(document).ready(function() {
      $('#submit_action').hide();
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        console.log("countVisible");

        if(countVisible == false){
          $(this).html("Cancel");
          $('#submit_action').show();
          countVisible = true;
          console.log("countVisible1");
          console.log(countVisible);
        }else{
          $(this).html("Make Payment");
          $('#submit_action').hide();
          countVisible = false;
          console.log("countVisible2");
          console.log(countVisible);
        }
        column.visible( ! column.visible() );


    } );
    function visible(){

      //countVisible = true;
      if(countVisible == false){
        $('#show_action').html("Cancel");
        $('#submit_action').show();
        countVisible = true;
        console.log("countVisible1");
        console.log(countVisible);
      }else{
        $('#show_action').html("Make Payment");
        $('#submit_action').hide();
        countVisible = false;
        console.log("countVisible2");
        console.log(countVisible);
      }
      column.visible(false);
    }

// } );

      async function clickAction(id, type){

         _type = 'all';
         _date = 'all';
         //periode = '';
        dynamic_id_freelance = id;
        //$('#type').remove();
        $('#modalOut').modal({backdrop: 'static', keyboard: false});
        console.log("_date");
        console.log(_date);
        var valuePeriode= 0;
        $('#type').html(`
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btflag">Filter Type</label>
              <select id="fld_btflag" name="fld_btflag" class="form-control form-control-rounded" onchange="drawTable(this.value,`+id+`,'`+_date+`');">
               <option value="all">All</option>
               <option value="1">Fee</option>
               <option value="2">Payment</option>
               <option value="3">Paid</option>
             </select>
          </div>
          `);

        //$('#modalOutTbl_wrapper').remove();
        if(count == 0){
          tblModal = $('#modalOutTbl').DataTable({
    				"ajax": {
    					"async": true,
    					"crossDomain": true,
    					"url": "<?=base_url()?>index.php/Freelance/getHistoricalFreelance/"+dynamic_id_freelance+"/1",
    					"method": "GET",
    					"headers": {
    						"cache-control": "no-cache",
    						"Postman-Token": "35557d14-6fa1-448e-a25d-5d74ac042d15"
    					}
    				},
    				'dom': 'Bfrtip',
    				'buttons': [
              {
                      text: 'Print Excel',
                      action: function ( e, dt, node, config ) {
                         fnExcelReport(paynumber);
                      }
                  },
    				],
    				"columns": [
              {
                  "title": "No",
                  "data": "id",
                  render: function (data, type, row, meta) {
                    // console.log("data_table");
                    // console.log(data);
                      return meta.row + meta.settings._iDisplayStart + 1;
                  },"sortable":false
              },
              {
    						"title": "id",
    						"data": "id_freelance",
                "visible" : false
    					},
              {
    						"title": "Action",
    						"data": "checklist_action",
                "visible" : false
    					},
              {
    						"title": "Nama Freelance",
    						"data": "freelance_name",
                "visible" : false
    					},
              {
    						"title": "Transaction Number",
    						"data": "number_trans"
    					},
              {
    						"title": "Division",
    						"data": "division"
    					},
              {
    						"title": "Descriptions",
    						"data": "description",
                "width": "500px"
    					},
              {
    						"title": "Type",
    						"data": "type_name"
    					},
              {
    						"title": "Amount",
    						"data": "amount",
                render: function (data, type, row, meta) {
                  console.log("data_table");
                  console.log(data);
                    return get_ammont_format(data);
                }
    					},
              {
    						"title": "Date",
    						"data": "date"
    					},
              {
    						"title": "id Trans",
    						"data": "id_trans",
                "visible" : false
    					},
              {
    						"title": "type",
    						"data": "type",
                "visible" : false
    					},
              {
    						"title": "paynumber",
    						"data": "paynumber",
                "visible" : false
    					},

    				],
    				'responsive': true,
    				"deferRender": true,
            "paging" : false,
            "searching": false,
            "scrollY" : "300px",
    				"initComplete": function(settings, json) {
              console.log("json");
              console.log(json);
                // console.log("periode");
                // console.log(periode);
                console.log("completed");

    					$('#loader').hide();
    				},
            "drawCallback": function ( settings ) {
              periode = '';
        var api = this.api(),data;
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;

        //console.log("api.column()");
        api.column(10).data().each( function ( group, i ) {
          //console.log(group);
           if ( last !== group ) {
             var type_data = api.column(11).data()[i];

             if ( type_data == "2" )
             {
               paynumber = api.column(12).data()[i];
               var date =api.column(9).data()[i];
               valuePeriode += 1;
               console.log("date");
               console.log(date);
               periode += '<option value="'+date+'">'+date+'</option>';
             }
             last = group;
           }

        });
        console.log("paynumber");
        console.log(paynumber);
        $('#date').html(`
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btflag">Filter Periode Payment</label>
              <select id="fld_btflag" name="fld_btflag" class="form-control form-control-rounded" onchange="drawTable(`+_type+`,`+id+`,this.value);">
               <option value="all">All Periode</option>
               `+periode+`
             </select>
          </div>
          `);
       },
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
          // console.log("aData");
          // console.log(aData["status"]);

          $('#name').html(aData["freelance_name"]);
                  if ( aData["type"] == "3" )
                  {
                  $('td', nRow).css('background-color', 'Green');
                  $('td', nRow).css('color', 'White');

                  }

                // else if ( aData["type"] == "2" )
                //   {
                //   $('td', nRow).css('background-color', 'Yellow');
                // }

              },
              "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            // var amount =  api
            //       .column( 8 , {filter:'applied'})
            //       .data();
            // console.log("amount");
            // console.log(amount);
            // Total over all pages
          var  total = api
                .column( 8 , {filter:'applied'})
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);


               total_all = total;
               console.log("1total_all");
               console.log(total_all);

        },
    			});
        }else{
          tblModal.ajax.url('<?=base_url()?>index.php/Freelance/getHistoricalFreelance/'+id+'/1').load();
        }
        count += 1;
        column = tblModal.column(2);

      //await dropdownDate(periode, id);

        // if(countVisible == false){
        //   countVisible = true;
        // }else{

        // $('#show_action').html("Make Payment");
        // $('#submit_action').hide();
        countVisible = true;
        // // }
        // console.log("countVisible click");
        // console.log(countVisible);
        visible();

      }
    async function dropdownDate(option, id){
    await timer(2000);
        console.log("masuk_dropdown");
        $('#date').html(`
          <div class="col-md-6 form-group mb-3">
              <label for="fld_btflag">Filter Periode Payment</label>
              <select id="fld_btflag" name="fld_btflag" class="form-control form-control-rounded" onchange="drawTable(`+_type+`,`+id+`,this.value);">
               <option value="all">All Periode</option>
               `+option+`
             </select>
          </div>
          `);
      }
      //timer delay ajax
function timer(ms) { return new Promise(res => setTimeout(res, ms)); }
//end delay
      function submitPayment(){
         if (confirm("Are You Sure to Submit Payment ?") == true) {
           var ck_string = [];
           var total_amount = 0;
           var data_request = [];
                      $.each($("input[name='is_checked']:checked"), function(){
                        var amount = parseInt($(this).attr('data-amount'));
                        total_amount += amount;
                          //e.preventDefault();
                          ck_string.push({
                            id : $(this).val(),
                            amount : $(this).attr('data-amount'),
                            id_req : $(this).attr('data-id-request'),
                          });
                      });
             if(ck_string.length > 0){
               for(var i=0; i<ck_string.length; i++){
                 data_request.push({
                   id : ck_string[i].id,
                   amount : ck_string[i].amount,
                   id_req : ck_string[i].id_req,
                   total_amount : total_amount
                 });
               }
                 console.log(data_request);
                        var settings_getv = {

                                  // "crossDomain": true,
                                  "dataType": 'json',
                                  "url": "<?=base_url()?>index.php/freelance/submitPayment",
                                  "method": "POST",
                                  "data" : {
                                    "data_freelance" : data_request
                                  }


                                }


                                $.ajax(settings_getv).done(function (response_getv) {
                                  console.log("data");
                                  console.log(response_getv);
                                  if(response_getv == 1){
                                    alert("Payment Success!!");
                                    tblModal.ajax.url("<?=base_url()?>index.php/Freelance/getHistoricalFreelance/"+dynamic_id_freelance+"/1").load();
                                  }else{
                                    alert("gagal proses !!");
                                  }
                                });
             }else{
               alert("Nothing Selected!!");
             }

         console.log(ck_string)
       }else{
         alert("Cancel Payment!!")
       }

      }
      function drawTable(type,id,date){
        var getdate = date;

        if(date){
          _date = date;
        }
        if(type){
            _type = type;
        }
        if(_type == 2){
          $('#date').show();
        }else{
          $('#date').hide();
        }
        // console.log(type);
        // console.log(id);
         tblModal.ajax.url("<?=base_url()?>index.php/Freelance/getHistoricalFreelance/"+id+"/1?type="+_type+"&date="+_date).load();
      }
      function closeModal(){
        periode = "";
        console.log("masuk_close");
        console.log(periode);
        $('#modalOut').modal('hide');
      }
      function fnExcelReport(paynumber){
	//var id_cust;
//  var batch = parseInt(_batch) + 1;
	var tab = document.getElementById('modalOutTbl');
	var title = "Report Payment Freelance ["+paynumber+"]";

		var htmls = "";
            var uri = 'data:application/vnd.ms-excel;base64,';
            var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
            var base64 = function(s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            };

            var format = function(s, c) {
                return s.replace(/{(\w+)}/g, function(m, p) {
                    return c[p];
                })
            };
						var column_count = tab.rows[0].childElementCount;
            var name = $('#name').html();
						var row_title = '<tr align="center"><td colspan="'+column_count+'"><span style="font-size:35px;font-weight:bold">'+title+'<br>'+name+'</span></td></tr>';


						var tab_text="<table border='2px'>"+row_title+"<tr bgcolor='#87AFC6'>";
							console.log(tab.rows[0].childElementCount);
							console.log(tab.rows[0]);
				    var textRange; var j=0;
				     // id of table

				    for(j = 0 ; j < tab.rows.length ; j++)
				    {
              var html_text = tab.rows[j].innerHTML;
              if(j == 0){
              html_text = html_text.replaceAll("height: 0px;","");
              console.log(html_text);
              }
				        tab_text=tab_text+html_text+"</tr>";
				        //tab_text=tab_text+"</tr>";
				    }
            var colspanFooter = column_count - 2;
				    tab_text=tab_text+'<tr align="center" bgcolor="#87AFC6"><td colspan="'+colspanFooter+'"><span style="font-size:30px;font-weight:bold">Total All</span></td><td><span style="font-size:30px;font-weight:bold">'+get_ammont_format(total_all)+'</span></td></tr></table>';
						//console.log(tab_text);
				    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
				    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
				    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            htmls = tab_text;

            var ctx = {
                worksheet : 'Worksheet',
                table : htmls
            }
						var date = getFormattedDates(new Date());
						var filename = date;
            var link = document.createElement("a");
            link.download = "export"+filename+".xls";
            link.href = uri + base64(format(template, ctx));
            link.click();
            location.reload();
}
function get_ammont_format(number){
  var format = new Intl.NumberFormat(['ban', 'id']).format(number)
  return format;
}
function getFormattedDates(date) {
       var day = ("0" + (date.getDate() )).slice(-2);
       var month = ("0" + (date.getMonth() + 1)).slice(-2);
       var year = date.getFullYear();
       var hours = ("0" + (date.getHours() )).slice(-2);
       var minute = ("0" + (date.getMinutes())).slice(-2);
       var seccond = ("0" + (date.getSeconds() )).slice(-2);
       var a = day + '-' + month + '-' + year + " " + hours + ":" + minute + ":" + seccond;
       return a;
   }
    </script>
