<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Freelance extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->load->model('login_model','login',TRUE);
    $this->load->model('form_model','form',TRUE);
    $this->load->model('view_model','view',TRUE);
    $this->load->model('query_model','query',TRUE);
    $this->load->model('dnxapps_model','dnxapps',TRUE);
    $this->load->model('freelance_model','freelance',TRUE);
    $this->dbdms = $this->load->database('dms', TRUE);
    $this->dbdnxapps = $this->load->database('dnxapps', TRUE);
    $this->intranet = $this->load->database('intranet', TRUE);

    if(!$this->session->userdata('logged_in')) {
      redirect('/login/login_form');
    }
  }

  public function index() {
    $data_page['usernm'] = $this->session->userdata('usernm');
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
  public function dashboard(){
        $data_page['ctnm'] = $this->session->userdata('ctnm');
        $data_page['content'] = 'dashboard_freelance';
        $data_page['groupid'] = $this->session->userdata('group');
        $data_page['group_add'] = $this->session->userdata('group_add');
        $data_page['group_freelance'] = $this->freelance->getDataSumAppFreelance();
      $this->load->view('page_view',$data_page);
  }
  //freelance stuff
public function getDataAppFreelance($id = null){
    $get_data = $this->freelance->getDataSumAppFreelance($id);
    $data_array= array();
    log_message('info', "naufal_query_data");
log_message('info', print_r($get_data, TRUE));
    if($get_data !== 0){



      foreach ($get_data as $key => $value) {
        // code...

            $data_array[] = [

              'balance' => number_format($value->balance,0,',',','),
              'revenue' => number_format($value->revenue,0,',',','),
              'payment' => number_format($value->payment,0,',',','),
              'id_freelance' => $value->id_freelance,
              'freelance_name' => $value->freelance_name,
              'count_project' => $value->total_project,
              'count_payment' => $value->total_payment,
              ];
          }

      }

          $response['data'] = $data_array;
          echo json_encode($response);


    }

public function getHistoricalFreelance($id,$if_table = null){
  $for_table = false;
  if($if_table !== null){
    $for_table = true;
  }
  $type = "all";
  if(isset($_GET['type'])){
    $type = $_GET['type'];
  }
  $date = "all";
  if(isset($_GET['date'])){
    $date = $_GET['date'];
  }
  $get_data = $this->freelance->getHistoricalFreelance($id,$type,$date);
  $data_array= array();
  log_message('info', "naufal_query_data");
  log_message('info', print_r($get_data, TRUE));
  if($get_data !== 0){

    foreach ($get_data as $key => $value) {
      // code...
      $type = $value->type;
      $type_name = "Fee";
      $checklist_action = "";
      $description = $value->description;
      $trans_number = $value->number_trans;
      if($type == 2){
        $type_name = "Payment";
        //$trans_number = "Payment Number : ". $value->number_trans . '<br>[ <span style="color:green;">'.$value->type_desc.'</span> ]';
        //$trans_number = $value->type_desc;
        $description = $value->description . '<span style="font-weight:bold;color:green"> '.$value->type_desc.' </span>';
      }elseif($type == 3){
        $type_name = "Paid";
      }
      if($type == 1){
      $checklist_action = '<input type="checkbox" class="check_id" name="is_checked" data-amount="'.$value->amount.'" data-id-request="'.$value->crud.'"  id="id['.$value->free_id.'][is_checked]" value="'.$value->free_id.'">';
      }
          $data_array[] = [
            'id_trans' => $value->id_trans,
            'id_freelance' => $value->free_id,
            'freelance_name' => $value->freelance_name,
            // 'amount' => number_format($value->amount,0,',',','),
            'amount' => $value->amount,
            'type' => $type,
            'title' => $value->type_desc,
            'date' => $value->start_date,
            'posted_by' => $value->employee,
            'description' => $description,
            'type_name' => $type_name,
            'checklist_action' => $checklist_action,
            'paynumber' =>  $value->number_trans,
            'number_trans' => $trans_number,
            'division' => $value->division
            ];
        }

    }

        if($for_table){
          $response['data'] = $data_array;
          echo json_encode($response);
        }else{
          echo json_encode($data_array);
        }

  }
  public function submitPayment(){
    $data = $this->input->post('data_freelance');
    $fld_btamt = $data[0]['total_amount'];
    $fld_btiid = $data[0]['id'];
    $baido = 1;
    $bttyid = 59;
    $fld_btno = $this->mkautono($baido,$bttyid);
    log_message('info', "insert_data");
    log_message('info', $fld_btamt);
    log_message('info', $fld_btno);
    $fld_btp01 = $this->session->userdata('ctid');
    $fld_btdt=date('Y-m-d h:i:s');
    $post_payment = array();
    $post_payment['fld_baido'] = $baido;
    $post_payment['fld_btno'] = $fld_btno;
    $post_payment['fld_bttyid'] = $bttyid;
    $post_payment['fld_btamt'] = $fld_btamt;
    $post_payment['fld_baido'] = $baido;
    $post_payment['fld_btp01'] = $fld_btp01;
    $post_payment['fld_btiid'] = $fld_btiid;
    $post_payment['fld_btdt'] = $fld_btdt;
    $last_id = $this->freelance->getforminsertLastId('tbl_bth',$post_payment);
    //$last_id = $this->intranet->insert_id();
    log_message('info', "insert_data");
    log_message('info', $last_id);
    $insert_cond = false;
    $update_cond = false;
    if($last_id > 0){
      foreach ($data as $key => $value) {
        // code...
        $pkeyval = $value['id_req'];
        $post_payment_dtl = array();
        $post_payment_dtl['fld_pay_id'] = $last_id;
        $post_payment_dtl['fld_req_id'] = $pkeyval;
        $post_payment_dtl['fld_free_id'] = $value['id'];
        $post_payment_dtl['amount'] = $value['amount'];
        $insert = $this->freelance->getforminsert('tbl_pay_freelance',$post_payment_dtl);
        if($insert){
          $insert_cond = true;
        }
        $tabelnm = 'tbl_bth';
        $pkey = 'fld_btid';
        $data_update = array();
        $data_update['fld_btnoalt'] = $fld_btno;
        $data_update['fld_btp34'] = $last_id;
        $update = $this->freelance->getformupdate($tabelnm,$pkey,$data_update,$pkeyval);
        if($update){
          $update_cond = true;
        }
      }
    }

    if($update_cond == true &&  $insert_cond == true){
      echo 1;
    }else{
      echo 0;
    }
  }
  function mkautono($baido,$bttyid) {
    $date_trans = date("ym");
    $year_trans = date("y");
    $bacd = $this->intranet->query("select fld_bacd from tbl_ba where fld_baid='$baido'");
    $lbacd = $bacd->row();
    $bttycd = $this->intranet->query("select fld_bttycd from tbl_btty where fld_bttyid='$bttyid'");
    $lbttycd = $bttycd->row();
    $query = $this->intranet->query("select t0.fld_btno  from tbl_bth t0
    where t0.fld_bttyid='$bttyid' and t0.fld_baido = '$baido' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    foreach ($query->result() as $row) {
    }
    $get_seq_number = (substr($row->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $vno = $lbacd->fld_bacd . "/" . $lbttycd->fld_bttycd . "/" . $date_trans . "/" . $seq_number;
    return $vno;
  }
}
