<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->library('session');
  }

  public function index() {
    $data_page['usernm'] = $this->session->userdata('usernm');
    $data_page['ctnm'] = $this->session->userdata('ctnm');
    $data_page['groupid'] = $this->session->userdata('group');
    $data_page['location'] = $this->session->userdata('location');
    $data_page['location_nm'] = $this->session->userdata('location_nm');
    $data_page['content'] = 'template/view/event';
  
    $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');
    $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');  
    $this->load->view('template/view/event',$data_page);
  }
}
