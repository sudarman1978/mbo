<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payload extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->load->model('login_model','login',TRUE);
    $this->load->model('form_model','form',TRUE);
    $this->load->model('view_model','view',TRUE);
    $this->load->model('query_model','query',TRUE);
    $this->load->model('dnxapps_model','dnxapps',TRUE);
    $this->load->model('freelance_model','freelance',TRUE);
    $this->load->model('payload_modal','payload',TRUE);
    $this->dbdms = $this->load->database('dms', TRUE);
    $this->dbdnxapps = $this->load->database('dnxapps', TRUE);
    $this->intranet = $this->load->database('intranet', TRUE);

    if(!$this->session->userdata('logged_in')) {
      redirect('/login/login_form');
    }
  }

  public function index() {
    $data_page['usernm'] = $this->session->userdata('usernm');
    $data_page['ctid'] = $this->session->userdata('ctid');
    $data_page['ctnm'] = $this->session->userdata('ctnm');
    $data_page['groupid'] = $this->session->userdata('group');
    $data_page['group_add'] = $this->session->userdata('group_add');
    $data_page['location'] = $this->session->userdata('location');
    $data_page['location_nm'] = $this->session->userdata('location_nm');
    $data_page['customer'] = $this->session->userdata('customer');
    $data_page['customernm'] = $this->session->userdata('customer_nm');
    $data_page['content'] = 'home_view';
    $data_page['api_url'] = "https://rest.dunextr.com/index.php/PortalApi/";
    $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');
    $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    $this->load->view('page_view',$data_page);
  }
  public function payload_truck(){
        $data_page['ctnm'] = $this->session->userdata('ctnm');
        $data_page['ctid'] = $this->session->userdata('ctid');
        $data_page['content'] = 'payload_truck';
        $data_page['groupid'] = $this->session->userdata('group');
        $data_page['group_add'] = $this->session->userdata('group_add');
        $data_page['unit'] = $this->payload->getUnit();
        $data_page['data_payload'] = $this->payload->getDataPayload();
        //$data_page['group_freelance'] = $this->freelance->getDataSumAppFreelance();
      $this->load->view('page_view',$data_page);
  }
  public function getDataPayload(){
    $getData = $this->payload->getDataPayload();
    $result = array();
    $result['data'] = $getData->result();
    echo json_encode($result);
  }
  public function process_payload(){
    $data = null;
    $act = null;
    $result = array();
    $input_data = array();
    if(isset($_POST['data'])){
      $data = $this->input->post('data');
      $input_data = json_decode($data);
      $conv_data = json_decode(json_encode($input_data), true);
    }
    if(isset($_POST['act'])){
      $act = $this->input->post('act');
    }
    if($data == null && $act == null){
      $result["error"] = true;
      $result["msg"] = "Error get Data!!";
      echo json_encode($result);
    }
    $tabelnm = 'tbl_payload_truck';
    $pkey = 'fld_id';

    if($act == "2"){
      $pkeyval = $conv_data[$pkey];
      $update = $this->payload->getformupdate($tabelnm,$pkey,$conv_data,$pkeyval);
      $result["error"] = false;
      $result["msg"] = "Success Updated Data!!";
      echo json_encode($result);
    }
    if($act == "3"){
      $pkeyval = $conv_data[$pkey];
      $update = $this->payload->deleteForm($pkeyval,$tabelnm,$pkey);
      $result["error"] = false;
      $result["msg"] = "Success Deleted Data!!";
      echo json_encode($result);
    }
    if($act == "1"){
      $insert = $this->payload->getforminsert($tabelnm,$conv_data);
      $result["error"] = false;
      $result["msg"] = "Success Input Data!!";
      echo json_encode($result);
    }
  }
}
