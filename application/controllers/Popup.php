<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Popup extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->helper('url');
    $this->load->library('session');
    $this->load->model('login_model','login',TRUE);
    $this->load->model('form_model','form',TRUE);
    $this->load->model('view_model','view',TRUE);
    $this->load->model('query_model','query',TRUE);
  }

  function index() {

  }

  function selector() {
    $val =  $this->input->get('val');
    $qname = $this->input->get('qname');
    $bind = array();
    foreach($this->input->get() as $key => $value) {  
      if (substr($key,0,7) == 'bindval' && $value != '999' && $value != "undefined") {
        $dbind [] =  $value;
      }
    }

    $data['ffname'] = $this->input->get('ffname');
    $data['viewrs_all'] = $this->query->query_selector_all($qname,$val,$dbind);
    $data['numrows'] = count($data['viewrs_all']);
    $data['val'] = $val;
    $data['qname'] = $qname;
    $data['totalpages']  = ceil($data['numrows'] / 10);
    $data['dbind'] = $bind;
    $get_currentpage = $this->input->get('currentpage');
    if (isset($get_currentpage) && is_numeric($get_currentpage) ) {
      $data['currentpage']  = (int) $get_currentpage;
    } else {
      $data['currentpage'] = 1;
    }
    if ( $data['currentpage'] > $data['totalpages']) {
      $data['currentpage'] = $data['totalpages'];
    }
    if ( $data['currentpage'] < 1) {
      $data['currentpage'] = 1;
    }
    $data['offset'] = ( $data['currentpage'] - 1) * 10;
    $data['viewrs'] = $this->query->query_selector($qname,$val,$data['offset'],$dbind);
     if ($qname == "list_pcnumber") {
                  $this->load->view('selector_hpm', $data);
                }

                else if ($qname == "list_employee_mtnpc") {
                  $this->load->view('selector_employee', $data);
                }
                 else if ($qname == "list_employee_legal") {
                  $this->load->view('selector_employee_legal', $data);
                }
                 else if ($qname == "list_pc_mtc") {
                  $this->load->view('selector_pc', $data);
                }

                 else if ($qname == "list_container_in") {
                  $this->load->view('selector_container_in', $data);
                }

                else {
                  $this->load->view('selector', $data);
                }
  }
}

