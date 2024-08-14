<?php
// $api_url = 'http://localhost:8888/dunex-rest/index.php/PortalApi/';

$api_url = '172.17.1.17/index.php/PortalApi/';

$host = 'http://apps.startrackergps.com';
//  $host = 'http://localhost/startrack-fms';

// $fld_btid = $_GET["btid"];
//test
$fld_baidc = $this->session->userdata('company');

?>

    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <!-- jsFiddle will insert css and js -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <style>
  body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
  }
  #map {
    min-height: 300px;
    height: 50%;
    width: 100%;
    overflow: hidden;
    border: none;
  }
  .signal {
    flex-basis: 50%;
    height: 0;
  }
  .speed {
    flex-basis: 60%;
    height: 0;
  }
  .my-custom-class-for-label {
      width: auto;
      height: 20px;
      border: 1px solid #4A7A4A;
      border-radius: 5px;
      background: #90EE90;
      text-align: center;
      line-height: 20px;
/*      font-weight: bold;
*/      font-size: 8px;
      color: #ffffff;
      margin-top: -35px;
      padding-left: 5px;
      padding-right: 5px;
    }
    td{
      font-size: 10px;
    }
    .wrap{
       flex-wrap: wrap !important;
    }
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {height: 1500px}

    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;

    }

    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    .scroll{
      overflow: auto;
      height: 500px;
    }
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;}
    }
  </style>
  <body>
  <div class="container-fluid">
<div class="row content">
  <div class="sidenav card chat-sidebar-container" data-sidebar-container="chat">
    <div class="chat-sidebar-wrap" data-sidebar="chat">
        <div class="border-right">
            <div class="pt-2 pb-2 pl-3 pr-3 d-flex align-items-center o-hidden box-shadow-1 chat-topbar"><a class="link-icon d-md-none" data-sidebar-toggle="chat"><i class="icon-regular ml-0 mr-3 i-Left"></i></a>
                <div class="form-group m-0 flex-grow-1">
                    <input class="form-control form-control-rounded" id="search_txt" type="text" placeholder="Search Truck" onkeypress="truck_list_search()" />
                </div>
            </div>
            <!-- <div class="d-flex pl-3 pr-3 pt-2 pb-2 o-hidden box-shadow-1 chat-topbar"><a class="link-icon d-md-none" data-sidebar-toggle="chat"><i class="icon-regular i-Right ml-0 mr-3"></i></a>
                          <div class="d-flex align-items-center"> <a href="#" onclick="show_filter();">Filter</a>

                          </div>

                      </div>
                      <div  style="padding: 10px;">

                        <div id="filter_" style="padding: 10px;">
                          <label for="vehicle_type">Vehicle Type</label>
                          <select id="vehicle_type" name="vehicle_type" class="form-control form-control-rounded">
                             <option value="0">[--Select--]</option>
                             <option value="none">None</option>
                             <option value="1">Trailer</option>
                             <option value="2">Car Carrier</option>
                             <option value="3">Box</option>
                          </select>
                        </div>
            </div> -->
            <div class="contacts-scrollable perfect-scrollbar">

                <div class="mt-3 pb-2 pl-3 pr-3 font-weight-bold text-muted border-bottom">Truck List</div>
                <div id="truck_list"></div>

            </div>
        </div>
    </div>
  </div>
  <div class="col-sm-7" id="map"></div>
  <div class="col-sm-2">
    <div class="form-group">
      <div id="detail_trace">
        <h6>Details</h6>
        <table>
          <tr>
            <td style="text-align:left;vertical-align:top;padding:0">Vehicle No</td>
              <td>:</td>
            <td><span id="vehicel_no"></span></td>
          </tr>
          <tr>
            <td style="text-align:left;vertical-align:top;padding:0">Speed </td>
            <td>:</td>
            <td><span id="speed"></span></td>
          </tr>
          <tr>
            <td style="text-align:left;vertical-align:top;padding:0">Route</td>
            <td>:</td>
            <td><span id="route"></span></td>
          </tr>
          <!-- <tr>
            <td style="text-align:left;vertical-align:top;padding:0">Location</td>
            <td> : <span id="location"></span></td>
          </tr> -->
          <tr>
            <td style="text-align:left;vertical-align:top;padding:0">Position</td>
            <td>:</td>
            <td><span id="position"></span></td>
          </tr>
          <tr>
            <td style="text-align:left;vertical-align:top;padding:0">Imei</td>
            <td>:</td>
            <td><span id="imei"></span></td>
          </tr>
          <!-- <tr>
            <td style="text-align:left;vertical-align:top;padding:0">Vehicle ID</td>
            <td>:</td>
            <td><span id="fld_vehicle"></span></td>
          </tr> -->
        </table>
        <button type="button" name="button" id="map_story">Show History Maps</button>
      </div>
    </div>
  </div>
</div>
</div>

</body>


    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDln20e-IXgGS4ICOns9bMXsFZEaun4vqY&callback=initMap"></script> -->
    <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIPZzk_kY0rIYKNcHBvvT4o8OeLk_Ur90&callback=initMap"></script> -->

  <script>
  const url = '<?=$host?>/index.php/ApiLiveMaps/getLocationByCompany?comp_id=<?php echo $fld_baidc ?>';
        // var markersArr = [];
        var locations = new Array();
        var imei = "";
        var fld_vehicle = "";
        var lastclick="";
        var lastimei="";
        var bounds = [];
        var markers;
        <?php
        $url_portal = "https://portal.dunextr.com/"
         ?>
        let markerArr = [];
        $('#detail_trace').hide();
        $('#filter_').hide();
        var map = L.map('map', {
          zoomSnap: 0.1
      });
      initMap();
        function initMap () {
          // getlistvehicle();

          var settings_getv = {
            "async": true,
            "crossDomain": true,
            "dataType": 'jsonp',
            "url": '<?=$host?>/index.php/ApiLiveMaps/getLocationByCompany?comp_id=<?=$fld_baidc?>',
            "method": "GET",
            "headers": {
              "X-M2M-Origin": "e7e349fc2216941a:9d0cf82c25277bdd",
              "Content-Type": "application/x-www-form-urlencoded",
              "Accept": "application/json",
              "cache-control": "no-cache",
              "Postman-Token": "bcf25ea2-8a54-4f40-8576-54deb444563c"
            }
          }


          $.ajax(settings_getv).done(function (response_getv) {
            console.log(response_getv);
            if(response_getv=='No Data.'){
              console.log('tes');
              alert("No Data");
            }else{
              var jml_v=response_getv.length;

              $("#truck_list").html("");
              let vehicle_list_2 = new Array();
              for(var i=0;i<jml_v;i++){
                let vehicle_list = new Array();

                vehicle_list["vehicle_no"] = response_getv[i].vehicle_no;
                vehicle_list["imei"] = response_getv[i].imei;
                vehicle_list["date_server"] = response_getv[i].date_server;
                vehicle_list["date_tracker"] = response_getv[i].date_tracker;
                vehicle_list["lat"] = response_getv[i].lat;
                vehicle_list["lon"] = response_getv[i].lon;
                vehicle_list["signal"] = response_getv[i].signal;
                vehicle_list["speed"] = response_getv[i].speed;
                vehicle_list["position"] = response_getv[i].position;
                //vehicle_list.push(['vehicle_no',response_getv[i].fld_bticd]);
                // vehicle_list.push(['imei',response_getv[i].fld_btip24]);
                var truck_list = document.getElementById('truck_list');

                truck_list.innerHTML += '<div onclick="button_click('+response_getv[i].imei+');" class="p-3 wrap d-flex border-bottom align-items-center contact online" id="clsbtn-'+response_getv[i].imei+'"><img class="avatar-sm rounded-circle mr-3" src="<?= $url_portal ?>assets/images/truck_img.png"  /><h6><a  id="btn-'+response_getv[i].imei+'">'+response_getv[i].vehicle_no+'</a></h6><h6 class="signal"> Signal : '+response_getv[i].signal+'</h6><h6 class="speed"> Speed : '+response_getv[i].speed+'</h6></div>';
                vehicle_list_2.push(vehicle_list);
              }
              console.log(vehicle_list_2);
              locations=vehicle_list_2;
                 map.setView([locations[0].lat, locations[0].lon], 8);
                 L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                   attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                 }).addTo(map);
                 var myIcon = L.icon({
                   iconUrl: 'https://portal.dunextr.com/icon_truck.png',
                   iconSize: [200, 100],

                 });
                 var bounds = [];
              for (i = 0; i < locations.length; i++) {
                      if(lastclick!=""){
                        // document.getElementById(lastclick).click();
                        // console.log(result[i].vehicle_no);
                        $( ".p-3" ).removeClass( "online" );
                        $( "#cls"+lastclick ).addClass( "online" );
                        // $('#focus_view').text(result[i].vehicle_no);
                        if(lastimei==locations[i].imei){
                          imei = locations[i].imei;
                          fld_vehicle = locations[i].fld_vehicle;
                          markers =   L.marker([locations[i].lat, locations[i].lon], {icon: myIcon})
                                      .addTo(map)
                                      .bindTooltip(''+ locations[i].vehicle_no,
                                                  {
                                                      permanent: true,
                                                      direction: 'top',
                                                      offset :  L.point(5, 1)
                                                  });

                              //  bounds[i] = [locations[i].lat, locations[i].lon];
                                fitBound(locations[i].lat,locations[i].lon);

                          // console.log(lastimei+'='+result[i].imei);

                          $('#vehicel_no').text(locations[i].vehicle_no);
                          $('#speed').text(locations[i].speed);
                          $('#route').text(locations[i].route);
                          $('#imei').text(locations[i].imei);
                          $('#fld_vehicle').text(locations[i].fld_vehicle);
                          $('#position').text(locations[i].position);
                          $('#detail_trace').show();
                        }

                      }else{
                        markers =   L.marker([locations[i].lat, locations[i].lon], {icon: myIcon})
                                    .addTo(map)
                                    .bindTooltip(''+ locations[i].vehicle_no,
                                                {
                                                    permanent: true,
                                                    direction: 'top',
                                                    offset :  L.point(5, 1)
                                                });

                              bounds[i] = [locations[i].lat, locations[i].lon];
                      }
              }

              console.log(bounds);
            map.fitBounds(bounds);
            }//end else no data

          });

        }
        function fitBound(lat, lon){
          map.fitBounds([
              [lat, lon]
          ]);
        }
        console.log('location');
        console.log(locations);

        $('#search_txt').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });
        $('#search_txt').on('input', function() {

           response_getv=locations;
          $("#truck_list").html("");
          for (let i = 0; i < locations.length; i++) {
            var str = response_getv[i].vehicle_no;
            var search_txt = $('#search_txt').val();

            if(search_txt==''){
              var truck_list = document.getElementById('truck_list');
              truck_list.innerHTML += '<div onclick="button_click('+response_getv[i].imei+');" class="p-3 d-flex wrap border-bottom align-items-center contact online" id="clsbtn-'+response_getv[i].imei+'"><img class="avatar-sm rounded-circle mr-3" src="<?= $url_portal; ?>assets/images/truck_img.png" alt="alt" /><h6><a  id="btn-'+response_getv[i].imei+'">'+response_getv[i].vehicle_no+'</a></h6><h6 class="signal"> Signal : '+response_getv[i].signal+'</h6><h6 class="speed"> Speed : '+response_getv[i].speed+'</h6></div>';
            }else{
              var rgxp = new RegExp(search_txt, "g");
              var result2 = str.match(rgxp);
              if(result2=='' || result2==null){
                // console.log('tidak ada');
              }else{
                console.log(search_txt+'='+response_getv[i].vehicle_no+'='+result2);
                var truck_list = document.getElementById('truck_list');
                truck_list.innerHTML += '<div onclick="button_click('+response_getv[i].imei+');" class="p-3 d-flex wrap border-bottom align-items-center contact online" id="clsbtn-'+response_getv[i].imei+'"><img class="avatar-sm rounded-circle mr-3" src="<?= $url_portal; ?>assets/images/truck_img.png" alt="alt" /><h6><a  id="btn-'+response_getv[i].imei+'">'+response_getv[i].vehicle_no+'</a></h6><h6 class="signal"> Signal : '+response_getv[i].signal+'</h6><h6 class="speed"> Speed : '+response_getv[i].speed+'</h6></div>';
              }
            }
          }
        });

        var filter="Hide";
        function show_filter(){
          if(filter=='Show'){
            $('#filter_').show();
            filter="Hide";
          }else{
            $('#filter_').hide();
            filter="Show";
          }
        }

        function button_click(imei){

          lastclick='btn-'+imei;
          lastimei=imei;
          $( ".p-3" ).removeClass( "online" );
          $( "#cls"+lastclick ).addClass("online");
          console.log('click');
          $(".leaflet-marker-icon").remove();
          $(".leaflet-tooltip").remove();
          initMap();

        }



         $('#map_story').click(function(e){
           window.open(
          '<?php echo base_url();?>index.php/page/view/78000GPS_MAP_STORY/'+imei+'/'+fld_vehicle,
          '_blank' // <- This is what makes it open in a new window.
        );
           // window.location.href = '<?php echo base_url();?>index.php/page/view/78000LIVE_TRACKING/'+imei+'/'+fld_vehicle;

           e.preventDefault();
           // your statements;
       });





        setInterval(function(){
          lastclick = "";
          $(".leaflet-marker-icon").remove();
          $(".leaflet-tooltip").remove();
           initMap();
         }, 60000);
  </script>
