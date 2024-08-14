<?
$fld_btiid = $this->uri->segment(4);
$route = $this->db->query("select
                        t2.fld_gpslat,t2.fld_gpslong
                        from
                        tbl_route_node t2
                        where
                        t2.fld_routeid = $fld_btiid")->result();
foreach($route as $rroute) {
if($noz < count($route)) {
           }
}


?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple Polylines</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>

      // This example creates a 2-pixel-wide red polyline showing the path of
      // the first trans-Pacific flight between Oakland, CA, and Brisbane,
      // Australia which was made by Charles Kingsford Smith.

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: {lat: <?php echo $rroute->fld_gpslat;?>, lng: <?php echo $rroute->fld_gpslong;?>},
          mapTypeId: 'terrain'
        });

        var route1 = [
          <?php
          $noz = 0;
          foreach($route as $rroute) {
          $noz = $noz + 1;
          echo "{lat: $rroute->fld_gpslat, lng: $rroute->fld_gpslong}";
          if($noz < count($route)) {
           echo ",";
           }

          }
          ?>
        ];


        var flightPath = new google.maps.Polyline({
          path: route1,
          geodesic: true,
          strokeColor: '#2400C6',
          strokeOpacity: 2.0,
          strokeWeight: 3
        });



        flightPath.setMap(map);
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIPZzk_kY0rIYKNcHBvvT4o8OeLk_Ur90&callback=initMap">
    </script>
  </body>
</html>
