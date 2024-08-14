<?php

class Track extends CI_Controller
{
    protected $api_url = '';

    public function __construct ()
    {
        parent::__construct();
        // $this->load->model('gps_model');

        // if (!$this->session->userdata('email'))
        //     redirect('login');
        // $this->api_url = 'http://localhost:8888/dunex-rest/index.php/PortalApi/';
        $this->api_url = 'http://172.17.1.17/index.php/PortalApi/';
    }

    public function index ()
    {
        $fld_btid = $_GET['res1'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gpsUpdate1?fld_btid=" . $fld_btid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, TRUE);

        $data = [
            'title' => 'UI Track',
            'res' => $_GET['res1'],
            'fld_gpslat' => (float) $response['data'][0]['fld_gpslat'],
            'fld_gpslong' => (float) $response['data'][0]['fld_gpslong'],
            'fld_btno' => $response['data'][0]['fld_btno'],
            'address' => $response['data'][0]['fld_address2'],
            'date' => $response['data'][0]['fld_btdt'],
            'carrier' => $response['data'][0]['courier'],
            'Idcarrier' => $response['data'][0]['fld_baidv'],
            'point' => $response['data'][0]['point'],
            'status' => $response['data'][0]['fld_btstat'],
        ];

        $this->template->set('title', $data['title']);
        $this->template->load('template', 'contents', 'track/index', $data);
    }

    public function getMap ($id)
    {
        $fld_btid = $id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gpsUpdate1?fld_btid=" . $fld_btid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, TRUE);

        $data = [
            'title' => 'UI Track',
            'res' => $_GET['res1'],
            'fld_gpslat' => $response['data'][0]['fld_gpslat'],
            'fld_gpslong' => $response['data'][0]['fld_gpslong'],
            'fld_btno' => $response['data'][0]['fld_btno'],
            'address' => $response['data'][0]['fld_address2'],
            'date' => $response['data'][0]['fld_btdt'],
            'carrier' => $response['data'][0]['courier'],
            'Idcarrier' => $response['data'][0]['fld_baidv'],
            'point' => $response['data'][0]['point'],
            'status' => $response['data'][0]['fld_btstat'],
        ];

        echo json_encode($data);
    }

    public function getLocationMaps($id)
    {
        $fld_btid = $id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "gpsLocation?fld_btid=" . $fld_btid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, TRUE);
	
        $imei = $response['data'][0]['imei']; 
	    $vehicle_number = $response['data'][0]['vehicle_number'];
	
	    $url = 'https://gps.dunextr.com/api/api.php?api=user&ver=1.0&key=F3BB25328F447ECDBADDEFE532AD4476&cmd=OBJECT_GET_LOCATIONS,' . $imei;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $response2 = json_decode($result, TRUE);

        foreach($response2 as $data) {
            $lng = $data['lng'];
            $lat = $data['lat'];
            $btno = $data['fld_btno'];
            $speed = $data['speed'];
        }
	
        $data = [
            'title' => 'UI Track',
            'res' => $_GET['res1'],
            'fld_gpslat' => $lat,
            'fld_gpslong' => $lng,
            'fld_btno' => $response['data'][0]['fld_btno'],
	        'vehicle_number' => $vehicle_number,
            'speed' => $speed,
            'delivery_date' =>'', 
	        'area' =>'',
	        'customer' => '',
            'driver' => ''
        ];

        echo json_encode($data);
    }

    public function getLocationByCustomer($fld_baidc)
    {
        $curl = curl_init();
        $url = $this->api_url . "listTruckByCustomer?fld_baidc=" . $fld_baidc;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . "listTruckByCustomer?fld_baidc=" . $fld_baidc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        $datas = json_decode($response, TRUE);
        
        $vehicles = [];

        $vehicles = [];
        foreach ($datas['data'] as $truck) {
            $url = 'https://gps.dunextr.com//api/api.php?api=user&ver=1.0&key=F3BB25328F447ECDBADDEFE532AD4476&cmd=OBJECT_GET_LOCATIONS,' . $truck['fld_btip24'];
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: gs_language=english'
                ),
            ));
            
            $resp = curl_exec($curl);
            
            curl_close($curl);
            
            $data = json_decode($resp,TRUE);
            $data = $data[$truck['fld_btip24']];
            
            $vehicles[] = [
                'vehicle_no' => $truck['fld_bticd'],
                'imei' => $truck['fld_btip24'],
                'date_server' => $data['dt_server'],
                'date_tracker' => $data['dt_tracker'],
                'lat' => $data['lat'],
                'lon' => $data['lng'],
            ];
        }
        
        echo json_encode($vehicles);
    }

public function getLocationByCRX($fld_btid){
    $api_url = 'http://172.17.1.17/index.php/PortalApi/';
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url . 'checkByCRX?fld_btid=' . $fld_btid,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $datas = json_decode($response,TRUE);

    $vehicles = [];
    $plat_no="";
    foreach ($datas['data'] as $truck) {
      $url = 'https://gps.dunextr.com//api/api.php?api=user&ver=1.0&key=F3BB25328F447ECDBADDEFE532AD4476&cmd=OBJECT_GET_LOCATIONS,' . $truck['imei'];

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Cookie: gs_language=english'
        ),
      ));

      $resp = curl_exec($curl);

      curl_close($curl);

      $data = json_decode($resp,TRUE);
      $data = $data[$truck['imei']];
      $plat_no=$truck['Vehicle No'];
      $receipt_no=$truck['Receipt Number'];
      
      $vehicles[] = [
        'vehicle_no' => $truck['Vehicle No'],
        'imei' => $truck['imei'],
        'receipt_no' => $truck['Receipt Number'],
        'date_server' => $data['dt_server'],
        'date_tracker' => $data['dt_tracker'],
        'lat' => $data['lat'],
        'lon' => $data['lng'],
      ];
    }

    $vehicles = json_encode($vehicles);
	echo $vehicles;
}	


    public function multi ()
    {
        $data['title'] = 'Multi Track';

        $data['userid'] = $this->session->userdata('fld_baidc');

        $this->template->set('title', $data['title']);
        $this->template->load('template', 'contents', 'track/multi', $data);
    }
}
