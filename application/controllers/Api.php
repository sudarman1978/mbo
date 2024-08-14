<?php

require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('api_model', 'api', TRUE);

	}

	public function getGPSLocation_get()
	{
		$apiKey = 0;
	if(isset($_GET['apiKey'])){
				$apiKey = $this->get('apiKey');
				log_message('info', "masuk_apikey");
				log_message('info', $apiKey);

		}
		// $apiKey = $this->get('apiKey');
		// $maxCount = $this->get('maxCount');
		$response = $this->api->getGPSLocation($apiKey);

		$this->response($response);
	}

	public function gpsLocation_get()
	{
		$apiKey = 0;
	if(isset($_GET['apiKey'])){
				$apiKey = $this->get('apiKey');
				log_message('info', "masuk_apikey");
				log_message('info', $apiKey);

		}
		// $apiKey = $this->get('apiKey');
		// $maxCount = $this->get('maxCount');
		$response = $this->api->getGPSLocation($apiKey);

		$this->response($response);
	}
	public function getGPSLocationByDate_get()
	{
		$apiKey = 0;
		$btdtsa  = 0;
		$btdtso = 0;
		if(isset($_GET['apiKey'])){
				$apiKey = $this->get('apiKey');
				log_message('info', "masuk_apikey");
		}
		if(isset($_GET['datefrom'])){
				$btdtsa = $this->get('datefrom');

		}
		if(isset($_GET['dateto'])){
				$btdtso = $this->get('dateto');
		}


						log_message('info', 'btdtsa');
						log_message('info', $btdtsa);

		$response = $this->api->getGPSLocationByDate($apiKey,$btdtsa,$btdtso);

		$this->response($response);
	}

	}
