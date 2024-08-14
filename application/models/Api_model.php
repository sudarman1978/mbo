<?php

class Api_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->dnxapps = $this->load->database('dnxapps', TRUE);
	}

	public function getGPSLocation($apiKey)
	{
		$hash =  hash('sha256', 'dunextmmin');
		$fks =  hash('sha256', 'dunexfks');
		$pass = '37182309eccd751a809f731727fb5416ad675962227d60a298dfab98d86e83c3';
		$trial_key = '7bc1b19b501331f22adf6c4b0ae87c9a7d51a24fce07198bd632f17047fe410d';
		$access = false;
			$where = "where b.fld_baidc = 5262 and b.fld_btstat = '3' order by t1.fld_empnm IS NULL, t1.fld_empnm asc, g.fld_gpstime desc";
		if (($hash == $apiKey || $fks == $apiKey) && $apiKey !== 0 ) {
		   $access = true;
			 if($fks == $apiKey){
				 $where = "where b.fld_baidc = 14541 and b.fld_btstat = '3' order by t1.fld_empnm IS NULL, t1.fld_empnm asc, g.fld_gpstime desc";
			 }
			 log_message('info', "hash");
			 log_message('info', $hash);
			 log_message('info', $apiKey);
		} else {
			$response['status'] = 400;
		 $response['error'] = true;
		 $response['message'] = 'You dont Have permission!!';

		 return $response;
		}


		$data_array = array();
		if($access){
			$query = "
			SELECT b.fld_btid,b.fld_btp11, g.*, g.fld_gpstime 'last_date', t1.fld_empnm 'driver_name' from tbl_bth b
			INNER JOIN tbl_truck_job j ON j.fld_btid = b.fld_btid
			left join gps.tbl_gps_update g on g.fld_idp = b.fld_btid
			left JOIN hris.tbl_truck_driver_all t1 ON t1.fld_empid = b.fld_btp11
			$where
	    ";
			$q = $this->dnxapps->query($query);
			if ($q->num_rows() > 0) {
				foreach ($q->result() as  $value) {
					// code...
					$harshBreaking = false;
					$harshAcc= false;
					$harshCorner= false;
					$move= false;
					$engine= false;
					if($value->fld_engine == 1 && $value->fld_speed > 0){
						$move= true;
						$engine= true;
					}
					if($value->fld_engine == 1 && $value->fld_speed == 0){
						//$move= true;
						$engine= true;
					}
					if($value->fld_drive_notif !== 0){
						if($value->fld_drive_notif == 3){
								$harshAcc= true;
						}
						if($value->fld_drive_notif == 2){
								$harshBreaking= true;
						}
						if($value->fld_drive_notif == 1){
								$harshCorner= true;
						}
					}

					$url2 = "http://172.17.1.30/nominatim/reverse?format=xml&lat=".$value->fld_gpslat."&lon=".$value->fld_gpslong."&zoom=27&addressdetails=1";
					$ch = curl_init($url2);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0");
					$file = curl_exec($ch);
					curl_close($ch);
					$xml = simplexml_load_string($file);
					$address = $xml->result;
					$address = trim($address,"'") ;
					$data_array [] = [
						'id_gps' => $value->fld_gpsid,
						'imei' => $value->fld_imei,
						'vehicleNumber' => $value->fld_vehicle,
						'driverName' => $value->driver_name,
						'speed' => $value->fld_speed,
						'odometer' => $value->fld_odo,
						'idling' => $move,
						'engine' => $engine,
						'x' => $value->fld_gpslong,
						'y' => $value->fld_gpslat,
						'battv' => $value->fld_battv,
						'harshBreaking' => $harshBreaking,
						'harshAcceleration' => $harshAcc,
						'harshCornering' => $harshCorner,
						'timestamp' => $value->last_date,
						'location' => $address
					];
				}
				//$response['data'] =
				return $data_array;
			} else {
				$response['status'] = 403;
				$response['error'] = true;
				$response['message'] = 'Record(s) not found...';

				return $response;
			}
		}

	}
	public function getGPSLocationByDate($apiKey,$btdtsa,$btdtso)
	{
		$hash =  hash('sha256', 'dunextmmin');
		$pass = '37182309eccd751a809f731727fb5416ad675962227d60a298dfab98d86e83c3';
		$access = false;
		if ($hash == $apiKey || $apiKey !== 0) {
		   $access = true;
		} else {
			$response['status'] = 400;
		 $response['error'] = true;
		 $response['message'] = 'You dont Have permission!!';

		 return $response;
		}
		$data_array = array();
		if($access){
			$f_date_1="";
			$f_date_2="";
			if($btdtsa!=''){
							$f_date_1="and DATE_FORMAT(g.fld_gpstime, '%Y-%m-%d %H:%i:%s')>='$btdtsa' and";
					}else{
						$response['status'] = 401;
						$response['error'] = true;
						$response['message'] = 'Date From is Empty !!';

						return $response;
					}
					if($btdtso!=''){
							$f_date_2="DATE_FORMAT(g.fld_gpstime, '%Y-%m-%d %H:%i:%s')<='$btdtso'";
					}else{
						$response['status'] = 401;
						$response['error'] = true;
						$response['message'] = 'Date To is Empty !!';

						return $response;
					}
			$query = "
			SELECT g.*, MAX(g.fld_gpstime) 'last_date', t1.fld_empnm 'driver_name' from tbl_bth b
			left join gps.tbl_gps g on g.fld_idp = b.fld_btid
			left JOIN tbl_driver t0 ON t0.fld_driverid = b.fld_btp11
			left JOIN hris.tbl_emp t1 ON t1.fld_empid = t0.fld_empid
			where b.baidc = 5262
			and b.fld_btstat = '3'
			$f_date_1
			$f_date_2
			#AND g.fld_imei = '358899059289531'
			GROUP BY g.fld_imei
			order by t1.fld_empnm IS NULL, t1.fld_empnm asc, g.fld_gpstime desc
	    ";
			$q = $this->dnxapps->query($query);
			if ($q->num_rows() > 0) {
				foreach ($q->result() as  $value) {
					// code...
					$harshBreaking = false;
					$harshAcc= false;
					$harshCorner= false;
					$move= false;
					$engine= false;
					if($value->fld_engine == 1 && $value->fld_speed > 0){
						$move= true;
						$engine= true;
					}
					if($value->fld_engine == 1 && $value->fld_speed == 0){
						//$move= true;
						$engine= true;
					}
					if($value->fld_drive_notif !== 0){
						if($value->fld_drive_notif == 3){
								$harshAcc= true;
						}
						if($value->fld_drive_notif == 2){
								$harshBreaking= true;
						}
						if($value->fld_drive_notif == 1){
								$harshCorner= true;
						}
					}

					$url2 = "http://172.17.1.30/nominatim/reverse?format=xml&lat=".$value->fld_gpslat."&lon=".$value->fld_gpslong."&zoom=27&addressdetails=1";
					$ch = curl_init($url2);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0");
					$file = curl_exec($ch);
					curl_close($ch);
					$xml = simplexml_load_string($file);
					$address = $xml->result;
					$address = trim($address,"'") ;
					$data_array [] = [
						'id_gps' => $value->fld_gpsid,
						'imei' => $value->fld_imei,
						'vehicleNumber' => $value->fld_vehicle,
						'driverName' => $value->driver_name,
						'speed' => $value->fld_speed,
						'odometer' => $value->fld_odo,
						'idling' => $move,
						'engine' => $engine,
						'x' => $value->fld_gpslong,
						'y' => $value->fld_gpslat,
						'battv' => $value->fld_battv,
						'harshBreaking' => $harshBreaking,
						'harshAcceleration' => $harshAcc,
						'harshCornering' => $harshCorner,
						'timestamp' => $value->last_date,
						'location' => $address
					];
				}
				//$response['data'] =
				return $data_array;
			} else {
				$response['status'] = 403;
				$response['error'] = true;
				$response['message'] = 'Record(s) not found...';

				return $response;
			}
		}

	}

}
