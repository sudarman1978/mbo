<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#ini_set('display_errors', '1');
#ini_set('display_startup_errors', '1');
#error_reporting(E_ALL);
class Page extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->load->model('login_model','login',TRUE);
    $this->load->model('form_model','form',TRUE);
    $this->load->model('view_model','view',TRUE);
    $this->load->model('query_model','query',TRUE);
    $this->load->model('ffis_model','ffis',TRUE);
    if(!$this->session->userdata('logged_in') && $this->uri->segment(2) != 'getWhatsApp') {
      redirect('/login/login_form');
    }
  }

  public function index() {
    $data_page['usernm'] = $this->session->userdata('usernm');
    $data_page['ctnm'] = $this->session->userdata('ctnm');
    $data_page['groupid'] = $this->session->userdata('group');
    $data_page['location'] = $this->session->userdata('location');
    $data_page['location_nm'] = $this->session->userdata('location_nm');
    $data_page['content'] = 'home_view';

    $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');
    $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    $this->load->view('page_view',$data_page);
  }

  function form() {
    $fname =  $this->uri->segment(3);
    $mode =  $this->uri->segment(4);
    $node =  $this->uri->segment(5);
    $lform = $this->form->getform($fname);
    $data_form['mode'] =  $mode;
    $data_form['act'] = $this->input->get('act');
    $data_form['sf'] = $this->input->get('sf');
    $data_form['fld_formlbl'] =  $lform->fld_formlbl;
    $data_form['fld_formnm'] =  $lform->fld_formnm;
    $data_form['fld_formid'] =  $lform->fld_formid;
    $data_form['fld_formcreate'] =  $lform->fld_formcreate;
    $data_form['fld_formupdate'] =  $lform->fld_formupdate;
    $data_form['fld_formdelete'] =  $lform->fld_formdelete;
    $data_form['fld_formlist'] =  $lform->fld_formlist;
    $data_form['fld_formcopy'] =  $lform->fld_formcopy;
    $data_form['fld_tblid'] =  $lform->fld_tblid;
    $data_form['fld_formtyid'] =  $lform->fld_formtyid;
    $fld_formid = $lform->fld_formid;
    $fld_tblnm = $lform->fld_tblnm;
    $fld_tblpkey = $lform->fld_tblpkey;
    $data_form['dbName'] = $fld_tblnm;
    $data_form['formfield'] =  $this->form->getformfield($fld_formid);
    $data_form['subform'] =  $this->form->getsubform($fld_formid);
    $data_form['defsubform'] =  $this->form->getdefsubform($fld_formid);
    $data_form['formview'] =  $this->form->getformview($fld_formid);
    $data_form['defsubform'] =  $this->form->getdefsubform($fld_formid);
    $data_form['userid'] = $this->session->userdata('userid');
    $data_form['usernm'] = $this->session->userdata('usernm');
    $data_form['ctnm'] = $this->session->userdata('ctnm');
    $data_form['groupid'] = $this->session->userdata('group');
    $data_form['group_add'] = $this->session->userdata('group_add');
    $data_form['location'] = $this->session->userdata('location');
    $data_form['location_nm'] = $this->session->userdata('location_nm');
    if (count($data_form['subform']) > 0) {
      $data_form['issubform'] = 1;
      $lsubform = $data_form['subform'];
      $sfinfo = array();
    }

	if (count($data_form['formview']) > 0) {
      $data_form['isformview'] = 1;
      $lformview = $data_form['formview'];
      $sfinfo = array();
    }

    ###Check Value from Query String
    $ffgval = array();
    foreach ($data_form['formfield']  as $gvalue) {
      $gvaluenm = $gvalue->fld_formfieldnm;
      $ffgval [$gvaluenm] =  $this->input->get($gvaluenm);
    }
    $data_form['ffgval'] = $ffgval;
    $data_form['formfieldval'] =  $this->form->getformfieldval($fld_tblnm,$fld_tblpkey,$node);
    if ($lform->fld_formtmpl != '') {
      $data_form['content'] = "template/form/$lform->fld_formtmpl";
    }
    else {
      $data_form['content'] = 'form_view';
    }
    if ($mode == 'edit') {
      if (substr($fld_tblnm,0,7) == 'tbl_bth') {
	$data_form['futrans'] =  $this->form->getfollowup($node,$this->session->userdata('group'));
	$data_form['printout'] =  $this->form->getPrintLink($lform->fld_formid,$this->session->userdata('group'));
        $data_form['trans_map'] =  $this->form->getTransMap($node);
	$data_form['aprvdata'] =  $this->form->getApprovalRule($node);
	### Cek Approval Status
	$data_form['aprvstatus'] =  $this->form->getApprovalStatus($node);
        $data_form['aprv_act'] =  $this->form->getApprovalRole($node,$this->session->userdata('group'));
        //print_r ($data_form['aprv_act']);
        $data_form['aprv_req'] =  $this->form->getApprovalInisiator($node,$this->session->userdata('group'));
      }
    }
    $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');
    $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    $this->load->view('page_view', $data_form);
  }

  function view() {
    $vname =  $this->uri->segment(3);
    $lview = $this->view->getview($vname);
    foreach ($lview as $rview)
    ### Base Url in Query
    $url =  base_url();
    $vquery = str_replace("base_url/",$url,$rview->fld_querysql);
    $orderval = $this->input->get('order');
    $sortingval = $this->input->get('sorting');
    $vformsearch = $rview->fld_formsearch;
    $lform = $this->form->getformbyID($vformsearch);
    $vorder = $rview->fld_vieworder;
    $data_view['footer'] = $rview->fld_viewfooter;
    $data_view['header'] = $rview->fld_viewheader;
    $data_view['usernm'] = $this->session->userdata('usernm');
    $data_view['ctnm'] = $this->session->userdata('ctnm');
    $data_view['location'] = $this->session->userdata('location');
    $data_view['location_nm'] = $this->session->userdata('location_nm');

    ### Form Search
    if ($vformsearch > 0) {
      $data_view['fld_formlbl'] =  $lform->fld_formlbl;
      $data_view['fld_formnm'] =  $lform->fld_formnm;
      $data_view['fld_formid'] =  $lform->fld_formid;
      $fld_formid = $lform->fld_formid;
      $fld_tblnm = $lform->fld_tblnm;
      $fld_tblpkey = $lform->fld_tblpkey;
      $data_view['formfield'] =  $this->form->getformfield($fld_formid);
    }
    $data_view['fld_viewnm'] =  $rview->fld_viewnm;
    $data_view['fld_formsearch'] =  $rview->fld_formsearch;
    if ($rview->fld_viewauth > 0) {
      $data_view['auth'] =  $this->view->getauth($rview->fld_viewauth);
    }
    $vtmpl = $rview->fld_viewtmpl;
    $data_view['fld_viewlbl'] = $rview->fld_viewlbl;
    if ($vquery != '') {
      $queryid = $rview->fld_queryid;
      $gbind = $this->view->getbind($queryid);
      $dbind = array();
      if (count($gbind) > 0) {
	foreach ($gbind as $rbind) {
	  $bindname = $rbind->fld_querybindnm;
	  $bindval =  $rbind->fld_querybindval;
	  if (preg_match('/^!/',$bindval)) {
	    $bindval = substr($bindval,1);
	    $bindval =  $this->session->userdata($bindval);
	  }
	  if ($this->input->get($bindname)) {
	    $bindval =  $this->input->get($bindname);
	  }
	  $dbind [] =  $bindval;
	}
      }
      ### Pagination
      $data_view['numrows'] = $this->view->getviewnrow($vquery,$dbind,$dbind);
      $data_view['rowsperpage'] = $rview->fld_viewrpp;
      $data_view['totalpages']  = ceil($data_view['numrows'] / $data_view['rowsperpage']);
      $get_currentpage = $this->input->get('currentpage');
      $data_view['order']  = $this->input->get('order');
      $data_view['sorting'] = $this->input->get('sorting');
      if (isset($get_currentpage) && is_numeric($get_currentpage) ) {
	$data_view['currentpage']  = (int) $get_currentpage;
      }
      else {
	$data_view['currentpage'] = 1;
      }
      if ( $data_view['currentpage'] > $data_view['totalpages']) {
	$data_view['currentpage'] = $data_view['totalpages'];
      }
      if ( $data_view['currentpage'] < 1) {
	$data_view['currentpage'] = 1;
      }
      $data_view['offset'] = ( $data_view['currentpage'] - 1) * $data_view['rowsperpage'];
      $data_view['viewdata'] =  $lview;
      $data_view['viewcol'] =  $this->view->getviewcol($vquery,$dbind);
      $data_view['viewrs'] =  $this->view->getviewrs($vquery,$data_view['offset'],$data_view['rowsperpage'],$dbind,$orderval,$sortingval,$vorder);
    }
    if ($vtmpl != '') {
      $data_view['content'] = "template/view/$vtmpl";
    }
    else {
      $data_view['content'] = 'view_view';
    }
    $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');
    $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    $this->load->view('page_view', $data_view);
  }

  function printout() {
    $vname =  $this->uri->segment(3);
    $lview = $this->view->getview($vname);
    foreach ($lview as $rview)
    ### Base Url in Query
    $url =  base_url();
    $vquery = str_replace("base_url/",$url,$rview->fld_querysql);
    $orderval = $this->input->get('order');
    $sortingval = $this->input->get('sorting');
    $print_all = $this->input->get('all');
    $print_page = $this->input->get('page');
    $vformsearch = $rview->fld_formsearch;
    $lform = $this->form->getformbyID($vformsearch);
    $vorder = $rview->fld_vieworder;
    $data_view['usernm'] = $this->session->userdata('usernm');
    $data_view['ctnm'] = $this->session->userdata('ctnm');
    ## Form Search
    $data_view['fld_viewnm'] =  $rview->fld_viewnm;
    $data_view['fld_formsearch'] =  $rview->fld_formsearch;
    $rtmpl = $rview->fld_viewreporttmpl;
    $data_view['fld_viewlbl'] = $rview->fld_viewlbl;
    if ($vquery != '') {
      $queryid = $rview->fld_queryid;
      $gbind = $this->view->getbind($queryid);
      $dbind = array();
      if (count($gbind) > 0) {
	foreach ($gbind as $rbind) {
	  $bindname = $rbind->fld_querybindnm;
	  $bindval =  $rbind->fld_querybindval;
	  if (preg_match('/^!/',$bindval)) {
	    $bindval = substr($bindval,1);
	    $bindval =  $this->session->userdata($bindval);
	  }
	  if ($this->input->get($bindname)) {
	    $bindval =  $this->input->get($bindname);
	  }
	  $dbind [] =  $bindval;
	}
      }
      ### Pagination
      $data_view['numrows'] = $this->view->getviewnrow($vquery,$dbind,$dbind);
      $data_view['rowsperpage'] = $rview->fld_viewrpp;
      $data_view['totalpages']  = ceil($data_view['numrows'] / $data_view['rowsperpage']);
      $get_currentpage = $this->input->get('currentpage');
      $data_view['order']  = $this->input->get('order');
      $data_view['sorting'] = $this->input->get('sorting');
      if (isset($get_currentpage) && is_numeric($get_currentpage) ) {
	$data_view['currentpage']  = (int) $get_currentpage;
      }
      else {
	$data_view['currentpage'] = 1;
      }
      if ( $data_view['currentpage'] > $data_view['totalpages']) {
		     $data_view['currentpage'] = $data_view['totalpages'];
      }
      if ( $data_view['currentpage'] < 1) {
	$data_view['currentpage'] = 1;
      }

		$data_view['offset'] = ( $data_view['currentpage'] - 1) * $data_view['rowsperpage'];
		$data_view['viewdata'] =  $lview;

		if ($print_all == 1) {
		$rpp = 100000000000;
		$data_view['offset'] = 0;
		}
		if ($print_page == 1) {
		$rpp = $rview->fld_viewrpp;
		}

		$data_view['viewcol'] =  $this->view->getviewcol($vquery,$dbind);
		$data_view['viewrs'] =  $this->view->getviewrs($vquery,$data_view['offset'],$rpp,$dbind,$orderval,$sortingval,$vorder);
		}

		if ($rtmpl != '') {
		  $this->load->view("template/report/$rtmpl", $data_view);
		}
		else {
		  $this->load->view('printout_view', $data_view);
		}

  }

  function form_process() {
  $fld_formid = $this->input->post('fid');
  $fld_formnm = $this->input->post('fnm');
  $lform2 = $this->form->getform($fld_formnm);
  $formfield = $this->form->getformfield($fld_formid);
  $fld_tblnm = $lform2->fld_tblnm;
  $fld_tblpkey = $lform2->fld_tblpkey;
  $pkeyval = $this->input->post($fld_tblpkey);
  //print $pkeyval;
  $subform =  $this->form->getsubform($fld_formid);
  $mode = $this->input->post('act');
  switch ($fld_formnm) {
      case "78000DELIVERY_ORDER_TRAILER":
          $this->check_validation($fld_formnm);
          break;

      case "green":
          echo "Your favorite color is green!";
          break;
    }
  $post_data = array();
  foreach ($formfield as $rpost) {
    $ffname = $rpost->fld_formfieldnm;
    $fftag = $rpost->fld_formfieldtag;
    $ffval = $this->input->post($ffname);
    if ($fftag == 'password') {
      if ($mode == 'add') {
        $ffval = ":-)" . MD5($ffval);
      }
      elseif ($mode == 'edit') {
        if (substr($ffval,0,3) == ":-)") {
           $ffval =  $ffval;
        }
        else {
          $ffval = ":-)" . MD5($ffval);
        }
      }


    }
    if ($ffval == '[AUTO]' && $mode == 'add') {
      $ffval = $this->mkautono($this->input->post('fld_baido'),$this->input->post('fld_bttyid'));

    }
    if(preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/', $ffval)) {
      $ffvalq = explode("-",$ffval);
      $ffval = date('Y-m-d',mktime(0, 0, 0, $ffvalq[1], $ffvalq[0], $ffvalq[2]));
    }
    $post_data[$ffname] = $ffval;

  }
  if ($mode == 'add' && $fld_formnm == '78000PIB_COUNTER') {
     $post_data['fld_btno']= $this->pibcounter();
  }
  if ($mode == 'add' && $fld_formnm == '78000SUBMIT_BP'){
          $this->ffis->cekBukpot($last_insert_id,$this->input->post(fld_baidc),$this->input->post(fld_btnoalt));
        }


  if ($mode == 'add' || $mode == 'edit'){


    ### Cek Double B/L
    if ($fld_formnm == '78000JOB_ORDER_IMP' && $post_data['fld_btp08'] != "") {
     // $house_bl = $post_data['fld_btp07'];
      $master_bl = $post_data['fld_btp08'];

      $cek = $this->db->query("select * from tbl_bth t0 where t0.fld_btp08 = '$master_bl'
                               and t0.fld_bttyid in(1,65)
			       and if('$mode' = 'edit',t0.fld_btid != '$pkeyval',1)");
       if ($cek->num_rows() > 0) {
         $this->ffis->message("B/L Number Is Already Exist");
       }

       $cek2 = $this->db->query("select * from tbl_bth t0 where t0.fld_btp07 = '$master_bl'
                               and t0.fld_bttyid in(1,65)
                               and if('$mode' = 'edit',t0.fld_btid != '$pkeyval',1)");
       if ($cek2->num_rows() > 0) {
         $this->ffis->message("B/L Number Is Already Exist");
       }
    }

    if ($fld_formnm == '78000JOB_ORDER_IMP' && $post_data['fld_btp07'] != "") {
        $house_bl = $post_data['fld_btp07'];
     // $master_bl = $post_data['fld_btp08'];

      $cek3 = $this->db->query("select * from tbl_bth t0 where t0.fld_btp08 = '$house_bl'
                               and t0.fld_bttyid in(1,65)
                               and if('$mode' = 'edit',t0.fld_btid != '$pkeyval',1)");
       if ($cek3->num_rows() > 0) {
         $this->ffis->message("B/L Number Is Already Exist");
       }

       $cek4 = $this->db->query("select * from tbl_bth t0 where t0.fld_btp07 = '$house_bl'
                               and t0.fld_bttyid in(1,65)
                               and if('$mode' = 'edit',t0.fld_btid != '$pkeyval',1)");
       if ($cek4->num_rows() > 0) {
         $this->ffis->message("B/L Number Is Already Exist");
       }
    }



     ### Flag Job Rema from Advance
/*
    if ($mode == 'edit' && ($fld_formnm == '78000JOCASH_ADVANCE' || $fld_formnm == '78000JOCASH_ADVANCE_EXP')) {
      $userid = $this->session->userdata('userid');

      $cek_user = $this->db->query("select t0.fld_usercomp 'company'
                                    from tbl_user t0
                                    where t0.fld_userid = '$userid'");

       $cek_user = $cek_user->row();
       if ($cek_user->company == 1) {
         $data = $this->db->query("select fld_bt01 'job_id' from tbl_btd_cost where fld_btidp = '$pkeyval'");
                 $data = $data->result();

                 foreach ($data as $rdata) {
                        $this->db->query("update tbl_bth set fld_btp43 = 1
                                          where fld_btid = '$rdata->job_id' limit 1 ");
                 }
       }
    }
*/
	##cek P2H Ticket

	  if($mode == 'add'){
        $location = $this->session->userdata('location');
        $groupid = $this->session->userdata('group');
         if ($location == 1){
         if($fld_formnm == '78000DELIVERY_ORDER_BOX' || $fld_formnm == '78000DELIVERY_ORDER_TRAILER'){
           $truck_no=$post_data['fld_btp12'];
           // echo $truck_no;
           // exit();
            $this->cekp2hticket($truck_no);
         }
        }
       }

    if($fld_formnm == '78000JOB_ORDER_IMP' && $post_data['fld_btloc'] == 4 && $post_data['fld_btnoalt']==""){
      // exit();
      $this->ffis->message("AJU Number is empty!");
      exit();

    }

     ### Cek Field CBM for LCL/By Air
    if ($fld_formnm == '78000JOB_ORDER_IMP' && ($post_data['fld_bttax'] == 2 || $post_data['fld_bttax'] == 5 )) {
       $fld_cbm = $post_data['fld_btp09'];

       if ($fld_cbm == "") {
         $this->ffis->message("CBM Field is empty!");
       }

    }
     if ($fld_formnm == '78000INVOICE'){
       $post_data['fld_btp37'] = 1;
    }

    if ($fld_formnm == '78000TPK'){

        $groupid = $this->session->userdata('group');
        if ($groupid == 34){
          $this->ffis->message("You don't have permission to create or edit this transaction. Please call your IT Administrator.");
        }
    }


    ### Cek PIB Create by
    if ($fld_formnm == '78000JOB_ORDER_IMP' && $post_data['fld_btiid'] == 2){
       $post_data['fld_btp27'] = 2;
    }
    ### Cek Price Scheme
    if ($fld_formnm == '78000JOB_ORDER_IMP') {
      $fld_baidc = $this->input->post(fld_baidc);
      $fld_btdt = $this->input->post(fld_btdt);
      $service = $this->input->post(fld_bttax);
      $price_s = $this->db->query("select * from dnxapps.tbl_price_scheme t0
                                   left join dnxapps.tbl_tyval t1 on t1.fld_tyvalcd = t0.fld_btiid and t1.fld_tyid=87 and t1.fld_tyvalcfg=4
                                   where
                                   t0.fld_baidc = '$fld_baidc'
                                   and t1.fld_tyvalp01 = $service
                                   and date_format('$fld_btdt','%Y-%m-%d')  between t0.fld_btdtsa and t0.fld_btdtso");
      $price_s =  $price_s->row();
      if($post_data['fld_btp24'] > 0) {
        $post_data['fld_btp24'] = $price_s->fld_price_schemeid;
      } else {
        $post_data['fld_btp24'] = 0;
      }
    }
  /*
   ### Cek Quotation Number
   if ($fld_formnm == '78000JOB_ORDER_IMP') {
     $fld_baidc = $this->input->post(fld_baidc);
     $fld_btdt = $this->input->post(fld_btdt);
     $service = $this->input->post(fld_bttax);
     $quotation = $this->db->query("select * from dnxapps.tbl_bth t0
                                   where
                                   t0.fld_baidc = $fld_baidc
                                   and
                                   CASE
                                      WHEN $service=1 THEN t0.fld_btp13=1
                                      WHEN $service=2 THEN t0.fld_btp12=1
                                      WHEN $service=3 THEN t0.fld_btp11=1
                                      WHEN $service=5 THEN t0.fld_btp14=1
                                   else
                                   $service = 0
                                   END
                                   and
                                   date_format('$fld_btdt','%Y-%m-%d')  between t0.fld_btdtsa and t0.fld_btdtso");

     if ($quotation->num_rows() > 0) {
        $quotation =  $quotation->row();
        $post_data['fld_bttaxno'] = $quotation->fld_btnoalt;

     }

     }

   if ($fld_formnm == '78000EXT_JOB_ORDER') {
     $fld_baidc = $this->input->post(fld_baidc);
     $fld_btdt = $this->input->post(fld_btdt);
     $service = $this->input->post(fld_bttax);
     $quotation = $this->db->query("select * from dnxapps.tbl_bth t0
                                   where
                                   t0.fld_baidc = $fld_baidc
                                   and
                                   CASE
                                      WHEN $service=1 THEN t0.fld_btp07=1
                                      WHEN $service=2 THEN t0.fld_btp08=1
                                      WHEN $service=3 THEN t0.fld_btp09=1

                                   else
                                   $service = 0
                                   END
                                   and
                                   date_format('$fld_btdt','%Y-%m-%d')  between t0.fld_btdtsa and t0.fld_btdtso");

     if ($quotation->num_rows() > 0) {
        $quotation =  $quotation->row();
        $post_data['fld_bttaxno'] = $quotation->fld_btnoalt;

     }

     }
 */


  }

  if ($mode == 'edit') {
     if($fld_formnm == '78000JOCASH_ADVANCE' || $fld_formnm == '78000JO_SETTLEMENT' || $fld_formnm == '78000JOCASH_ADVANCE_EXP') {
       $group = $this->session->userdata('group');
       $cek1 = $this->db->query("select * from tbl_bth t0 where t0.fld_btid='$pkeyval'");
       $cek1 = $cek1->row();
       if(($group == 8 || $group == 7 || $group == 4 || $group == 35 || $group == 38 || $group == 39 ||  $group == 49)&&  $cek1->fld_btstat == 6) {
         $this->ffis->message("This Transaction Status is Verified , You Cannot Update This Transaction ...");
       }
    }

    if($fld_formnm == '78000DEPOSIT_ENTRY') {
        $btid = $post_data['fld_btid'];

        $checkcomp = $this->db->query("
            SELECT
            res.*

            FROM(
                SELECT
                t1.fld_btno 'cdenumber',
                if(t1.fld_btp23='' or t1.fld_btp23='0', '0', t1.fld_btp23) 'cdecomp',
                t2.fld_btno 'transnumber',
                CASE
                    WHEN t2.fld_bttyid in(46,51) THEN if(t2.fld_btp23 = 1,'1','0')
                    ELSE '0'
                END 'transcomp'

                FROM tbl_btd_deposit t0
                LEFT JOIN tbl_bth t1 on t1.fld_btid=t0.fld_btidp and t1.fld_bttyid=60
                LEFT JOIN tbl_bth t2 on t2.fld_btno=t0.fld_btp02

                WHERE
                t1.fld_bttyid=60
                and date_format(t1.fld_btdt, '%Y-%m-%d') >= '2021-04-01'
                and t1.fld_btid='$btid'
            ) res

            WHERE
            res.cdecomp != res.transcomp
        ");

        if ($checkcomp->num_rows() > 0) {
            $diffcomp = '';
            foreach ($checkcomp->result() as $key => $item) {
              $diffcomp = $diffcomp . ', ' . $item->transnumber;
            }
            $this->ffis->message("This transaction has a different company, please check $diffcomp");
        } else {
            $btrlist = $this->db->query("SELECT fld_btrdst FROM tbl_btr WHERE fld_btrsrc = '$btid' AND fld_btrdsttyid=11 GROUP by fld_btrdst")->result();
            if ($post_data['fld_btp23'] == 1) {
                foreach ($btrlist as $key => $item) {
                  $this->db->query("UPDATE exim.tbl_bth SET fld_btp37 = '1' WHERE tbl_bth.fld_btid = '$item->fld_btrdst' LIMIT 1");
                }
            } else {
                foreach ($btrlist as $key => $item) {
                  $this->db->query("UPDATE exim.tbl_bth SET fld_btp37 = '0' WHERE tbl_bth.fld_btid = '$item->fld_btrdst' LIMIT 1");
                }
            }
        }
    }

    if($fld_formnm == '78000CONTAINER_DEPOSIT') {
        $btid = $post_data['fld_btid'];
        $btrlist = $this->db->query("SELECT fld_btrdst FROM tbl_btr WHERE fld_btrsrc = '$btid' GROUP by fld_btrdst")->result();
        if ($post_data['fld_btp37'] == 1) {
            foreach ($btrlist as $key => $item) {
              $this->db->query("UPDATE exim.tbl_bth SET fld_btp23 = '1' WHERE tbl_bth.fld_btid = '$item->fld_btrdst' LIMIT 1");
            }
        } else {
            foreach ($btrlist as $key => $item) {
              $this->db->query("UPDATE exim.tbl_bth SET fld_btp23 = '' WHERE tbl_bth.fld_btid = '$item->fld_btrdst' LIMIT 1");
            }
        }
    }


    if ($fld_formnm == '78000INVOICE'){
    $data=$this->db->query("select fld_btnoreff from tbl_bth where fld_btid ='$pkeyval'")->row();
    $post = $this->input->post(fld_btnoreff);
    if(empty($post)){
                $postnoreff = '9x9x9x9x';
		}else
		{
		$postnoreff = str_replace(",","','",$post);
		}

     $cek_jo = $this->db->query("select t0.fld_btnoreff from tbl_bth t0 where t0.fld_btid = $pkeyval");
     $query1=$cek_jo->row();
     if ($query1->fld_btnoreff == '') {
         $noreff = '9x9x9x9x';
         $noreff2 = '9x9x9x9x';
       } else {
         $noreff2 = str_replace(",","','",$query1->fld_btnoreff);
         $noreff = $query1->fld_btnoreff;
       }

         $this->db->query("update tbl_bth set fld_btp38 = 0 where fld_btno in ('$noreff2') and fld_bttyid in (1,6,10)");
         $this->db->query("update tbl_bth set fld_btp38 = 1 where fld_btno in ('$postnoreff') and fld_bttyid in (1,6,10)");

 }
    if($fld_formnm == '78000CA_REQUEST') {
      $post_data['fld_btbalance'] = $post_data['fld_btuamt'] - $post_data['fld_btamt'];
    }

    if($fld_formnm == '78000CUSTOMER') {
        $post_data['fld_baidp'] = $this->session->userdata('ctid');
    }


    if($fld_formnm == '78000JOB_ORDER_IMP') {
      $this->ffis->cekJobData($pkeyval,$this->input->post(fld_btp07),$this->input->post(fld_btp08),$this->input->post(fld_bttax),$this->input->post(fld_btqty),$this->input->post(fld_btp06),$this->input->post(fld_btp42));
    }

/*    if($fld_formnm == '78000COMMISSION_DEDUCTION'){
     $this->ffis->setDriverId($pkeyval);

   }*/

   # if ($fld_formnm == '78000WEEKLYBONUS') {
   #   $this->ffis->PostingWeeklyBonus($pkeyval,$this->session->userdata('location'));
   # }

   if($fld_formnm == '78000CHECKLIST_DEMO'){
      $post_data['fld_btnoalt']='tes';
    }

    $update = $this->form->getformupdate($fld_tblnm,$fld_tblpkey,$post_data,$pkeyval);
    ### Update Record Log
    $data_log = array(
    'fld_acclogtyid' => '3' ,
    'fld_accloghost' => $_SERVER['REMOTE_ADDR'] ,
    'fld_acclogdt' => date('Y-m-d H:i:s'),
    'fld_acclogcmt' => 'User ' . $this->session->userdata('usernm') . ' Update record number ' . $pkeyval . ' on table ' . $fld_tblnm
    );
    $this->db->insert('tbl_acclog', $data_log);



    if (count($subform) > 0) {
      foreach ($subform as $rsubform) {
        if  ($rsubform->fld_subformty == 1) {
          $sffnm =  $rsubform->fld_formnm;
	      $sffid =  $rsubform->fld_formid;
	      $count = $rsubform->fld_formid . "Count";
          $countori = $rsubform->fld_formid . "Count-ori";
	      $sfform = $this->form->getform($sffnm);
	      $ffsf = $this->form->getformfield($sffid);
	      $sffld_tblnm = $sfform->fld_tblnm;
	      $sffld_tblpkey = $sfform->fld_tblpkey;
	      $sfpkeyval = $this->input->post($sffld_tblpkey);
	      $txtCount = $this->input->post($count);
		  $txtCountori = $this->input->post($countori);

          ### Delete Record
    	  $lval =  $this->form->getdatafupsub($rsubform->fld_formrelc,0,$pkeyval,$sffld_tblnm);
 	      $count = count($lval);
 	      for ($a=0; $a<$count; ++$a) {
 	        ### Check Data
  	        $daval =  $lval[$a][$sffld_tblpkey];
	        $del = 'yes';
	        for ($u=1;$u<=$count; $u++) {
 	          foreach ($ffsf as $sfrpost) {
                $sfffnamefield = $sfrpost->fld_formfieldnm;
 	            $sfffname = $sffnm . $sfrpost->fld_formfieldnm . $u;
	            $sfffval = $this->input->post($sfffname);
                  if ($sfffnamefield == $sffld_tblpkey) {
	                if ($daval == $sfffval) {
 	                  $del = 'no';
		            }
 	              }
              }
 	        }
            if ($del == 'yes') {
	          $delsf = $this->db->query("delete from $sffld_tblnm where $sffld_tblpkey='$daval' limit 1");
            }
          }
          ## Update Record
          for ($i=1;$i<=$txtCountori; $i++) {
            $sfpost_data = array();
            $dataexist = 0;
            foreach ($ffsf as $sfrpost) {
	      $sfffnamefield = $sfrpost->fld_formfieldnm;
	      $sfffname = $sffnm . $sfrpost->fld_formfieldnm . $i;
	      $sfffval = $this->input->post($sfffname);
              if($sfrpost->fld_formfieldsum == 1) {
                if($sfrpost->fld_formfieldnm == 'fld_btamt01' ) {
                  $sfqty = $sffnm . 'fld_btqty01' . $i;
                  $sfuamt = $sffnm . 'fld_btuamt01' . $i;
                  if($this->input->post($sfqty) != "" && $this->input->post($sfuamt) != "") {
                    $sfffval = $this->input->post($sfqty) * $this->input->post($sfuamt);
                  }
                }
              }

	      if ($sfffnamefield == $rsubform->fld_formrelc) {
	        $sfffval = $this->input->post($rsubform->fld_formrelp);
	      }
	      if ($sfffnamefield != $rsubform->fld_formrelc && $sfffval != '') {
	        $dataexist = $dataexist + 1;
	      }
	      $sfpost_data[$sfffnamefield] = $sfffval;
        }
            if ($dataexist != 0) {
              $sfreplace = $this->form->getformreplace($sffld_tblnm,$sfpost_data);
            }
	  }
        }
      }
    }
    if ($fld_formnm == '78000JO_SETTLEMENT'){
        $this->ffis->cekCurrency($post_data['fld_btid']);
    }
    if ($fld_formnm == '78000SETTLEMENT_LOLO'){
        $this->ffis->setBalanceLolo($post_data['fld_btid']);
    }

    if ($fld_formnm == '78000JOCASH_ADVANCE' || $fld_formnm == '78000JOCASH_ADVANCE_EXP'){
         $this->ffis->SumAdCashOrder($post_data['fld_btid']);
    }

    if ($fld_formnm == '78000JOCASH_ADVANCE_REPO'){
         $this->ffis->SumAdvanceRepo($post_data['fld_btid']);
    }
     if ($fld_formnm == '78000BANK_IN'|| $fld_formnm == '78000BANK_OUT'){
         $this->ffis->SumBankIn($post_data['fld_btid']);
    }


    if ($fld_formnm == '78000JOCASH_ADVANCE' || $fld_formnm == '78000JOCASH_ADVANCE_EXP'){
         $this->ffis->cekCurrency($post_data['fld_btid']);
    }

    if($fld_formnm == '78000EXT_JOB_ORDER' || $fld_formnm == '78000INTER_ISLAND') {
       //$date = $this->input->post(fld_btp12);
      //echo"###$date";
      //exit();
      $this->ffis->insertContExp($pkeyval,$this->input->post(fld_btqty),$this->input->post(fld_btp06),$this->input->post(fld_btuamt));
    }
    // remove space on container number
    if($fld_formnm == '78000EXT_JOB_ORDER' || $fld_formnm == '78000INTER_ISLAND') {
      $cek_cont = $this->db->query("select fld_btid,fld_contnum from tbl_btd_container t0 where t0.fld_btidp='$pkeyval'");
      foreach ($cek_cont->result() as $row) {
         $string = preg_replace('/\s+/', '', $row->fld_contnum);
         $update_cont = $this->db->query("update tbl_btd_container set fld_contnum = '$string' where fld_btid = '$row->fld_btid'");
      }
    }

    if ($fld_formnm == '78000CASH_OUT') {
      $this->ffis->cekRemain($pkeyval,$post_data['fld_btamt'],$post_data['fld_btuamt'],$post_data['fld_btp05'],$post_data['fld_btp06']);
    }

    if($fld_formnm == '78000CUSTOMER_PAYMENT') {
      $this->ffis->setPaymentTax($pkeyval);
    }

    if ($fld_formnm == '78000PLANNING_TRUCKING' && $post_data['fld_btp06'] != '') {
      $this->ffis->cekDateComplete($pkeyval);
    }

    if ($fld_formnm == '78000POD_SUBMIT') {
        $this->ffis->submitPOD($pkeyval,$this->input->post(fld_btdtsa),$this->input->post(fld_btdtso),$this->input->post(fld_btiid),$this->input->post(fld_btp01));
      }

    if ($fld_formnm == '78000TRUCKING_SETTLEMENT_TRAILER') {
        $this->ffis->TruckCashSettlementTrailer($pkeyval,$this->input->post(fld_btp01),$this->input->post(fld_btdtsa),$this->input->post(fld_btdtso),$this->input->post(fld_btflag),$this->input->post(fld_btiid),$this->input->post(fld_btp37));
      }

    if ($fld_formnm == '78000DELIVERY_ORDER_BOX' && $post_data['fld_btflag'] == 2) {
      $this->ffis->setCashAdvance($pkeyval,$post_data['fld_baidc'],$post_data['fld_btp09'],$post_data['fld_btflag'],$post_data['fld_baidv']);
    }
     if ($fld_formnm == '78000JOCASH_ADVANCE_EXP'){
         $count=$_POST['332Count-ori'];
	 print $count;
	 for ($i = 1; $i <= $count; $i++)
	 {
            $Mbl=$_POST["78000CASH_COST_EXPfld_bt04$i"];
		if (!empty($Mbl)) {
                 $sid=$_POST['fld_btid'];
		$this->ffis->UpdJOExp($sid,$Mbl);
           }
	 }
        }
    if ($fld_formnm == '78000CHANGE_PASSWORD') {
        $this->changePassword($post_data['fld_userid'],$post_data['fld_userp01'],$post_data['fld_userp02']);
      }

     if ($fld_formnm == '78000POSTING_COMMISSION') {
        $this->ffis->PostingCommission($pkeyval,$this->session->userdata('location'),$this->input->post(fld_btiid));
      }

      if ($fld_formnm == '78000POST_INVOICE_DEPO') {
        $this->ffis->PostingInvoiceDepo($pkeyval,$this->input->post(fld_btdtsa),$this->input->post(fld_btdtso),$this->input->post(fld_btiid));
      }

      if ($fld_formnm == '78000PST_ADVSELL_PRICE') {
        $this->ffis->PostingAdvSell($pkeyval,$this->input->post(fld_btdtsa),$this->input->post(fld_btdtso));
      }

      if($fld_formnm == '78000EXT_JOB_ORDER') {
          $this->ffis->cekETDVia($pkeyval,$this->input->post(fld_btp21),$this->input->post(fld_btp22));
        }

      if ($fld_formnm == '78000INVOICE'){
          $this->ffis->updateJO($pkeyval,$mode,$this->input->post(fld_btp35));
        }
      ##updateDO RETURN
      if ($fld_formnm == '78000RETURN_DO_TRAILER' || $fld_formnm == '78000DELIVERY_ORDER_TRAILER'){
          $this->ffis->updateDO($pkeyval,$mode,$this->input->post(fld_btp11));
        }
      if ($fld_formnm == '78000DRIVER_LOAN'){
       $this->ffis->updateLoan($pkeyval);
      }
      if ($fld_formnm == '78000BOTRUCK_DETAILEXP_INFO'){
       $this->ffis->updatebookTruck($pkeyval);
      }

       if ($fld_formnm =='78000DELIVERY_ORDER_TRAILER' ){
        $this->ffis->setPicture($pkeyval,$mode,$this->input->post(fld_btp11));
      #echo"hore";
      #exit();
      }

      if ($fld_formnm =='78000INVOICE_DELIVERY' ){
        $fld_btdtp = $_POST['fld_btdtp'];
        $fld_btp03 = $_POST['fld_btp03'];
        if ($fld_btdtp != '0000-00-00' || $fld_btp03 != '') {
          $this->ffis->PICReceive($pkeyval,$mode,$fld_btdtp,$fld_btp03);
        }
      }

          if ($fld_formnm == '78000INVOICE_DELIVERY') {
        $btid = $post_data[fld_btid];
        $user = $post_data[fld_btp35];
        $cek = $this->db->query("update tbl_bth set fld_btp05 = '$user' where fld_btid = '$btid' limit 1");
      }

      if ($fld_formnm =='78000TRUCKING_BILLING' ){
        $this->ffis->SUMTotalAmount($pkeyval,$mode);
      }

      if($fld_formnm == '78000COMMISSION_DEDUCTION'){
        $this->ffis->setDriverId($pkeyval);

       }

      if($fld_formnm == '78000JOCASH_ADVANCE') {
          $btid = $post_data['fld_btid'];
          $group = $this->session->userdata('group');
          $cekjoc = $this->db->query("
              SELECT
              t1.fld_btid 'jocid',
              t0.fld_bt01 'joid',
              t0.fld_costtype 'costtype',
              if(t0.fld_btp02='' or t0.fld_btp02=0 or t0.fld_btp02 is null, 0, t0.fld_btp02) 'depo'

              FROM tbl_btd_cost t0
              LEFT JOIN tbl_bth t1 ON t1.fld_btid=t0.fld_btidp

              WHERE
              t1.fld_bttyid=2
              and date_format(t1.fld_btdt, '%Y-%m-%d') >= '2021-07-01'
              and t1.fld_baidv=14
              and t0.fld_costtype=5453
              and t1.fld_btid='$btid'

              GROUP BY t0.fld_bt01
              ORDER BY t1.fld_btid DESC
          ")->result();

          foreach ($cekjoc as $key => $item) {
              if ($item->depo == 0) {
                  $this->ffis->message("This transaction has a  LIFT OFF Cost Description, please fill in the Depo column !");
              } else {
                  $this->db->query("UPDATE exim.tbl_bth SET fld_btp43 = '$item->depo' WHERE fld_btid = '$item->joid' LIMIT 1");
              }
          }
      }

    
    ### Set Total Amount
    $this->ffis->setTotalAmount($pkeyval,$post_data['fld_bttyid'],$fld_formnm,$post_data['fld_btflag']);

    $url = base_url() . "index.php/page/form/$fld_formnm/edit/$pkeyval?act=edit";
    if ( $this->input->post('sf') == 1) {
      echo '<script>history.go(-2)</script>';
    } else {
      redirect($url);
    }

    }
    ## Copy Record
    elseif ($mode == 'copy') {
      $post_data = array();
      foreach ($formfield as $rpost) {
        if (($rpost->fld_formfieldnm != $fld_tblpkey) || ($rpost->fld_formfieldnm == 'fld_btstat')) {
	      $ffname = $rpost->fld_formfieldnm;
          if ($rpost->fld_formfieldnm == 'fld_btno') {
            $post_data[$ffname] = $this->mkautono($this->input->post('fld_baido'),$this->input->post(fld_bttyid));
          }
         elseif ($rpost->fld_formfieldnm == 'fld_btstat') {
            $post_data[$ffname] = 1;
          }
         elseif ($rpost->fld_formfieldnm == 'fld_btdt') {
            $post_data[$ffname] = date('Y-m-d H:i:s');
          }

          else {
	        $post_data[$ffname]= $this->input->post($ffname);
          }
		}
      }
       if ($fld_formnm == '78000JOB_ORDER_IMP'){
          $post_data['fld_btp07'] = '';
          $post_data['fld_btp08'] = '';
          $post_data['fld_btp38'] = '';
       }
       if ($fld_formnm == '78000EXT_JOB_ORDER'){
          $post_data['fld_btp30'] = '';
          $post_data['fld_btp38'] = '';
       }

      if ($fld_formnm == '78000SUBMIT_BP'){
          $this->ffis->cekBukpot($this->input->post(fld_btid),$this->input->post(fld_baidc),$this->input->post(fld_btnoalt));
        }


      $insert = $this->form->getforminsert($fld_tblnm,$post_data);
      $lid = $this->db->insert_id();
      ### Copy Record Log
      $data_log = array(
      'fld_acclogtyid' => '4' ,
      'fld_accloghost' => $_SERVER['REMOTE_ADDR'] ,
      'fld_acclogdt' => date('Y-m-d H:i:s'),
      'fld_acclogcmt' => 'User ' . $this->session->userdata('usernm') . ' Copy record number from ' . $pkeyval . ' to ' . $lid . ' on table ' . $fld_tblnm
      );
      $this->db->insert('tbl_acclog', $data_log);

      if (count($subform) > 0) {
        foreach ($subform as $rsubform) {
          $sffnm =  $rsubform->fld_formnm;
          $sffid =  $rsubform->fld_formid;
          $sfform = $this->form->getform($sffnm);
          $ffsf = $this->form->getformfield($sffid);
          $sffld_tblnm = $sfform->fld_tblnm;
          $sffld_tblpkey = $sfform->fld_tblpkey;
          $count = $rsubform->fld_formid . "Count";
          $txtCount = $this->input->post($count);
          for ($i=1;$i<=$txtCount; $i++) {
            $sfpost_data = array();
	    foreach ($ffsf as $sfrpost) {
						$sfffnamefield = $sfrpost->fld_formfieldnm;
						$sfffname = $sffnm . $sfrpost->fld_formfieldnm . $i;
						$sfffval = $this->input->post($sfffname);
						if ($sfrpost->fld_formfieldnm != $sffld_tblpkey)
						{
 						if ($sfffnamefield == $rsubform->fld_formrelc)
						{
						    $sfffval = $lid;
						}
					  	$sfpost_data[$sfffnamefield] = $sfffval;
						}
				    }
				$insert = $this->form->getforminsert($sffld_tblnm,$sfpost_data);
			    }
			}

		    }
		    $url = base_url() . "index.php/page/form/$fld_formnm/edit/$lid?act=copy";
   		    redirect($url);

 		}
    elseif ($mode == 'add') {
      ### Cek double cashbon repo
      if ($fld_formnm == '78000JOCASH_ADVANCE_REPO' ) {
        $krani = $post_data['fld_baidp'];
        $tgl = $post_data['fld_btdt'];
        $cek = $this->db->query("select * from tbl_bth t0 where t0.fld_baidp = '$krani'
                                         and
                                         date_format(t0.fld_btdt,'%Y-%m-%d') = date_format('$tgl','%Y-%m-%d')
					 and
                                         t0.fld_btp20 = 1
					 and
					 t0.fld_bttyid=2
			               ");
        if ($cek->num_rows() > 0) {
          $this->ffis->message("Cannot create Repo Cashbon. You have made one other repo cashbon today!");
        }
      }




      ### Adding Record
      $insert = $this->form->getforminsert($fld_tblnm,$post_data);
      $last_insert_id = $this->db->insert_id();

      ### Add Record Log
      $data_log = array(
		  'fld_acclogtyid' => '2' ,
		  'fld_accloghost' => $_SERVER['REMOTE_ADDR'] ,
		  'fld_acclogdt' => date('Y-m-d H:i:s'),
		  'fld_acclogcmt' => 'User ' . $this->session->userdata('usernm') . ' Add record number ' . $last_insert_id . ' on table ' . $fld_tblnm
		  );
      $this->db->insert('tbl_acclog', $data_log);
      if (count($subform) > 0) {
        foreach ($subform as $rsubform) {
			    $sffnm =  $rsubform->fld_formnm;
			    $sffid =  $rsubform->fld_formid;
			    $count = $rsubform->fld_formid . "Count";
			    $sfform = $this->form->getform($sffnm);
			    $ffsf = $this->form->getformfield($sffid);
			    $sffld_tblnm = $sfform->fld_tblnm;
			    $sffld_tblpkey = $sfform->fld_tblpkey;
			    $sfpkeyval = $this->input->post($sffld_tblpkey);
			    $txtCount = $this->input->post($count);
			    for ($i=1;$i<=$txtCount; $i++)
			    {
				$check = 0;
			  #### Cek Grid yangg kosong
			      foreach ($ffsf as $lffsf)
			      {
				$ffval = $sffnm . $lffsf->fld_formfieldnm . $i;
				$val = $this->input->post($ffval);

				if ($this->input->post($ffval))
				{
				    $check = 1;
				}
			      }
			      if ($check == 1)
				{
 				$sfpost_data = array();
				foreach ($ffsf as $sfrpost)
				    {
						$sfffnamefield = $sfrpost->fld_formfieldnm;
						$sfffname = $sffnm . $sfrpost->fld_formfieldnm . $i;
						$sfffval = $this->input->post($sfffname);
					        if($sfrpost->fld_formfieldsum == 1) {
                                                  if($sfrpost->fld_formfieldnm == 'fld_btamt01' ) {
                                                    $sfqty = $sffnm . 'fld_btqty01' . $i;
                                                    $sfuamt = $sffnm . 'fld_btuamt01' . $i;
                                                    if($this->input->post($sfqty) != "" && $this->input->post($sfuamt) != "") {
                                                      $sfffval = $this->input->post($sfqty) * $this->input->post($sfuamt);
                                                    }
                                                  }
                                                }


						   if(preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/', $sfffval))
						    {
							$sfffvalq = explode("-",$sfffval);
							$sfffval = date('Y-m-d',mktime(0, 0, 0, $sfffvalq[1], $sfffvalq[0], $ffvalq[2]));
						    }
					  ## Get Relation key Value form Parent Form
						if ($sfffnamefield == $rsubform->fld_formrelc)
						{
						    $sfffval = $last_insert_id;
						}
					 ##
					  $sfpost_data[$sfffnamefield] = $sfffval;
				    }
				      $sfinsert = $this->form->getforminsert($sffld_tblnm,$sfpost_data);
				}

			    }
			}
		    }
        if ($fld_formnm == '78000JOB_ORDER_IMP'){
          $this->ffis->insertJOProgress($last_insert_id);
        }

        if ($fld_formnm == '78000JOB_ORDER_IMP'){
          $this->ffis->insertImpDoc($last_insert_id);
        }

        if ($fld_formnm == '78000JOCASH_ADVANCE' || $fld_formnm == '78000JOCASH_ADVANCE_EXP'){
          $this->ffis->SumAdCashOrder($last_insert_id);
        }

        if ($fld_formnm == '78000JOCASH_ADVANCE_REPO'){
          $this->ffis->SumAdvanceRepo($last_insert_id);
        }


        if ($fld_formnm == '78000JOCASH_ADVANCE' || $fld_formnm == '78000JOCASH_ADVANCE_EXP'){
          $this->ffis->cekCurrency($last_insert_id);
        }

        if ($fld_formnm == '78000JO_SETTLEMENT'){
          $this->ffis->cekSettlement($last_insert_id);
        }

        if ($fld_formnm == '78000SETTLEMENT_LOLO'){
        $this->ffis->setBalanceLolo($post_data['fld_btid']);
        }
        if($fld_formnm == '78000EXT_JOB_ORDER' || $fld_formnm == '78000INTER_ISLAND') {
          $this->ffis->insertContExp($last_insert_id,$this->input->post(fld_btqty),$this->input->post(fld_btp06),$this->input->post(fld_btuamt));
        }

        if($fld_formnm == '78000EXT_JOB_ORDER') {
          $this->ffis->cekETDVia($last_insert_id,$this->input->post(fld_btp21),$this->input->post(fld_btp22));
        }

        if ($fld_formnm == '78000INVOICE'){
          $this->ffis->updateJO($last_insert_id,$mode,$this->input->post(fld_btp35));
        }
       if ($fld_formnm == '78000POD_SUBMIT') {
          $this->ffis->submitPOD($last_insert_id,$this->input->post(fld_btdtsa),$this->input->post(fld_btdtso),$this->input->post(fld_btiid),$this->input->post(fld_btp01));
        }


	 if ($fld_formnm == '78000JOCASH_ADVANCE_EXP'){
         $count=$_POST['332Count-ori'];
         for ($i = 1; $i <= $count; $i++)
         {
            $Mbl=$_POST["78000CASH_COST_EXPfld_bt04$i"];
                if (!empty($Mbl)) {
                 $sid=$_POST['fld_btid'];
                $this->ffis->UpdJOExp($last_insert_id,$Mbl);
           }
         }
        }

    if ($fld_formnm == '78000TRUCKING_SETTLEMENT_TRAILER') {
      $this->ffis->TruckCashSettlementTrailer($last_insert_id,$this->input->post(fld_btp01),$this->input->post(fld_btdtsa),$this->input->post(fld_btdtso),$this->input->post(fld_btflag),$this->input->post(fld_btiid),$this->input->post(fld_btp37));
    }

    if ($fld_formnm == '78000POSTING_COMMISSION') {
      $this->ffis->PostingCommission($pkeyval,$this->session->userdata('location'),$this->input->post(fld_btiid));
    }

    if ($fld_formnm == '78000POST_INVOICE_DEPO') {
      $this->ffis->PostingInvoiceDepo($last_insert_id,$this->input->post(fld_btdtsa),$this->input->post(fld_btdtso),$this->input->post(fld_btiid));
    }
    if ($fld_formnm == '78000BANK_IN'){
         $this->ffis->SumBankIn($last_insert_id);
    }

    if ($fld_formnm == '78000PST_ADVSELL_PRICE') {
        $this->ffis->PostingAdvSell($last_insert_id,$this->input->post(fld_btdtsa),$this->input->post(fld_btdtso));
      }
    if ( $fld_formnm == '78000DELIVERY_ORDER_TRAILER'){
          $this->ffis->updateDO($last_insert_id,$mode,$this->input->post(fld_btp11));
        }

     if ($fld_formnm == '78000DRIVER_LOAN'){
       $this->ffis->updateLoan($last_insert_id);
      }

     if ($fld_formnm == '78000DRIVER_REFERENCE' || $fld_formnm == '78000DRIVER_IMPROVE') {
        $this->ffis->setIdDriver($last_insert_id,$fld_formnm);
        }


     ### Flag Job Rema from Advance
    /*
      if ($fld_formnm == '78000JOCASH_ADVANCE' || $fld_formnm == '78000JOCASH_ADVANCE_EXP') {
      $userid = $this->session->userdata('userid');

      $cek_user = $this->db->query("select t0.fld_usercomp 'company'
                                    from tbl_user t0
                                    where t0.fld_userid = '$userid'");

       $cek_user = $cek_user->row();
       if ($cek_user->company == 1) {
         $data = $this->db->query("select fld_bt01 'job_id' from tbl_btd_cost where fld_btidp = '$last_insert_id'");
                 $data = $data->result();

                 foreach ($data as $rdata) {
                        $this->db->query("update tbl_bth set fld_btp43 = 1
                                          where fld_btid = '$rdata->job_id' limit 1 ");
                 }
       }
    }
*/
/*
    ### Protect advance Import by Import Type (FCL/LCL)
     if ($fld_formnm == '78000JOCASH_ADVANCE') {
      // cek import type
      $cek = $this->db->query("select t1.fld_bttax 'imptype'
	                       from tbl_btd_cost t0
	                       left join tbl_bth t1 on t1.fld_btid = t0.fld_bt01
			       where t0.fld_btidp = '$last_insert_id'");

      $cek = $cek->result();

      //cek company user
      $userid = $this->session->userdata('userid');

      $cek_user = $this->db->query("select t0.fld_usercomp 'company'
                                    from tbl_user t0
                                    where t0.fld_userid = '$userid'");
      $cek_user = $cek_user->row();


      //cek unlock flag Job Order
      $cek_unlock = $this->db->query("select t1.fld_btp44 'flag_lock'
	                              from tbl_btd_cost t0
	                              left join tbl_bth t1 on t1.fld_btid = t0.fld_bt01
				      where t0.fld_btidp = '$last_insert_id'");

      $cek_unlock = $cek_unlock->row();

      //cek FCL Job - REMA
      foreach ($cek->result() as $rcek) {
      if ($rcek->imptype != 1 && $cek_user->company == 1 && $cek_unlock->flag_lock == 0) {
	$this->ffis->message("Cannot create Advance..REMA Advance must be FCL Import Job Order. Please unlock Job first!");
	exit();
	}
      }

      //cek job selain FCL - DE
      foreach ($cek->result() as $rcek) {
      if($rcek->imptype > 1 && $cek_user->company == 1 && $cek_unlock->flag_lock == 0) {
	$this->ffis->message("Cannot create Advance..DE Advance must be LCL Import Job Order. Please unlock Job first!");
	exit();
	}
      }

  }
*/

		if($fld_formnm == '78000DELIVERY_ORDER_BOX' || $fld_formnm == '78000DELIVERY_ORDER_TRAILER') {
          $location = $this->session->userdata('location');
          #$groupid = $this->session->userdata('group');
           if($location == 1){
            $this->getp2hticket($last_insert_id);
          }
         }


    $userid = $this->session->userdata('userid');
    if ($userid == 502 || $userid == 516) {
        $id = $last_insert_id;
        $cek = $this->db->query("SELECT fld_baidv FROM tbl_bth WHERE fld_btid = '$id' LIMIT 1")->row();

        if ($cek->fld_baidv == 14) {
            $this->db->query("UPDATE tbl_bth set fld_baidv = 13 where fld_btid = '$id' limit 1 ");
        }
    } elseif ($userid == 482) {
        $id = $last_insert_id;
        $cek = $this->db->query("SELECT fld_baidv FROM tbl_bth WHERE fld_btid = '$id' LIMIT 1")->row();

        if ($cek->fld_baidv == 13) {
            $this->db->query("UPDATE tbl_bth set fld_baidv = 14 where fld_btid = '$id' limit 1 ");
        }
    }


    /* if ($fld_formnm =='78000DELIVERY_ORDER_TRAILER' && $post_data['fld_btloc'] != 0){
          $cekData = $this->db->query("select fld_btp16,fld_btp19 from tbl_bth where fld_btid ='$last_insert_id' and fld_btp16='' and fld_btbalance =0 and fld_baidv !=1 limit 1");
       if ($cekData->num_rows() > 0){
         $this->dnxapps->message("Please Check.. This Transaction don't have Quotation Number. ");
         }
        }*/


    #if ($fld_formnm == '78000WEEKLYBONUS') {
    #  $this->ffis->PostingWeeklyBonus($last_insert_id,$this->session->userdata('location'));
    #}

    ### Set Total Amount
    $this->ffis->setTotalAmount($last_insert_id,$post_data['fld_bttyid'],$fld_formnm,$post_data['fld_btflag']);

    $url = base_url() . "index.php/page/form/$fld_formnm/edit/$last_insert_id?act=add";
    if ( $this->input->post('sf') == 1) {
      echo '<script>history.go(-2)</script>';
    } else {
      redirect($url);
    }
  }

    if ($mode == 'fup') {
      $btid = $this->input->post(btid);
      $tynextid = $this->input->post(tynextid);
      $tynextform = $this->input->post(tynextform);
      $nextformid = $this->input->post(nextformid);
      $formfield = $this->form->getformfieldbyName($tynextform);
      $nextsubform =  $this->form->getsubform($nextformid);

      ###Prepare Data
      $data =  $this->form->getdatafup($btid);
      foreach ($formfield as $rformfield) {
        if ($rformfield->fld_formfieldcopy == 1) {
          $data[0][$rformfield->fld_formfieldnm] = '';
	    }
        if ($rformfield->fld_formfieldcopyval != "") {
	      if (substr($rformfield->fld_formfieldcopyval,0,4) == "fld_") {
	        $data[0][$rformfield->fld_formfieldnm] = $data[0][$rformfield->fld_formfieldcopyval];
	      } else {
            $data[0][$rformfield->fld_formfieldnm] = $rformfield->fld_formfieldcopyval;
          }
        }
      }
      $data[0]['fld_btid'] = '';
      $data[0]['fld_btstat'] = '1';
      $data[0]['fld_bttyid'] = $tynextid;
      $data[0]['fld_btno'] = $this->mkautono($data[0]['fld_baido'],$tynextid);
      $data[0]['fld_baidp'] =  $this->session->userdata('ctid');
      $data[0]['fld_btloc'] =  $this->session->userdata('location');
      $data[0]['fld_btdt'] = date('Y-m-d H:i:s');

      ###Insert Data
      $sfinsert = $this->form->getforminsert('tbl_bth', $data[0]);
      $fup_lid = $this->db->insert_id();

      ###Preare Data Subform
      if (count($subform) > 0 && $tynextform != '78000WORK_ORDER_ADDITIONAL') {
        $sf_count = 0;
        foreach ($subform as $rsubform) {
          $sf_count = $sf_count + 1;
          $sffnm =  $rsubform->fld_formnm;
          $sffid =  $rsubform->fld_formid;
	  $sfform = $this->form->getform($sffnm);
	  $ffsf = $this->form->getformfield($sffid);
	  $sffld_tblnm = $sfform->fld_tblnm;
	  $sffld_tblpkey = $sfform->fld_tblpkey;
	  $count = $rsubform->fld_formid . "Count";
	  $txtCount = $this->input->post($count);
          ${"recordsf" . $sf_count} = array();
	  for ($i=1;$i<=$txtCount; $i++) {
            $sfpost_data = array();
	    foreach ($ffsf as $sfrpost) {
	      $sfffnamefield = $sfrpost->fld_formfieldnm;
	      $sfffname = $sffnm . $sfrpost->fld_formfieldnm . $i;
	      $sfffval = $this->input->post($sfffname);
              if ($sfrpost->fld_formfieldnm != $sffld_tblpkey) {
	        if ($sfffnamefield == $rsubform->fld_formrelc) {
	          $sfffval = $fup_lid;
		}
		$sfpost_data[$sfffnamefield] = $sfffval;
	      }
	      if ($_POST['fnm']=='78000JOPREYCASH_ADVANCE'){
		$sfpost_data['fld_btreffid'] = $this->input->post($sffnm . 'fld_btid' . $i);
	        //print $sid;
	        //exit();
	      }
              if ($sffnm == '78000COST_SETT' || $sffnm == '78000PURCHASE_ORDER_DETAIL' || $sffnm == '78000PURCHASE_RECEIVE_DETAIL' || $sffnm == '78000WO_PART') {
                $sfpost_data['fld_btreffid'] = $this->input->post($sffnm . 'fld_btid' . $i);
              }
	    }
            if ($sffnm == '78000PURCHASE_REQUEST_DETAIL' || $sffnm == '78000PURCHASE_ORDER_DETAIL' || $sffnm == '78000PURCHASE_RECEIVE_DETAIL') {
              $cekqty = $this->ffis->cekPRQty($this->input->post($sffnm . 'fld_btid' . $i));
              if($cekqty < $sfpost_data['fld_btqty01']) {
                $sfpost_data['fld_btqty01'] = $sfpost_data['fld_btqty01'] - $cekqty;
	            ${"recordsf" . $sf_count}[] = $sfpost_data;
              }
            }

            else if( $sffnm == '78000WO_PART' ) {
			  $cekqty = $this->ffis->cekWOPartQty($this->input->post($sffnm . 'fld_btid' . $i));
			  if($cekqty < $sfpost_data['fld_btqty01']) {
                $sfpost_data['fld_btqty01'] = $sfpost_data['fld_btqty01'] - $cekqty;
	            ${"recordsf" . $sf_count}[] = $sfpost_data;
              }
            }

			else {
              ${"recordsf" . $sf_count}[] = $sfpost_data;
            }
          }
	}
      }
      ###

      ### Insert Data Subform
      if (count($nextsubform) > 0) {
        $nxsf_count = 0;
        foreach ($nextsubform as $rnextrsubform) {
          $nxsf_count = $nxsf_count + 1;
          $nxsfform = $this->form->getform($rnextrsubform->fld_formnm);
	  $nxffsf = $this->form->getformfield($rnextrsubform->fld_formid);
	  $nxsffld_tblnm = $nxsfform->fld_tblnm;
	  $nxsffld_tblpkey = $nxsfform->fld_tblpkey;
          if (count(${"recordsf" . $nxsf_count}) > 0) {
            for ($ix=0;$ix<count(${"recordsf" . $nxsf_count}); $ix++) {
              $data = array();
              $data = ${"recordsf" . $nxsf_count}[$ix];
              foreach ($nxffsf as $rnxffsf) {
	        if ($rnxffsf->fld_formfieldcopy == 1) {
	          $data[$rnxffsf->fld_formfieldnm] = '';
	        }
                if ($rnxffsf->fld_formfieldcopyval != "") {
	          if (substr($rnxffsf->fld_formfieldcopyval,0,4) == "fld_") {
	            $data[$rnxffsf->fld_formfieldnm] = $data[$rnxffsf->fld_formfieldcopyval];
	          } else {
                    $data[$rnxffsf->fld_formfieldnm] = $rnxffsf->fld_formfieldcopyval;
                  }
	        }
	      }
              $insert = $this->form->getforminsert($nxsffld_tblnm, $data);
            }
          }
        }
      }
      ###

      ### Follow Up Record Log
      $data_log = array(
      'fld_acclogtyid' => '5' ,
      'fld_accloghost' => $_SERVER['REMOTE_ADDR'] ,
      'fld_acclogdt' => date('Y-m-d H:i:s'),
      'fld_acclogcmt' => 'User ' . $this->session->userdata('usernm') . ' Follow Up record number from ' . $btid . ' to ' . $fup_lid . ' on table ' . $fld_tblnm
      );
      $this->db->insert('tbl_acclog', $data_log);

      ###Insert BTR
      $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($btid,$fup_lid,$tynextid)");
      $fup_subform = $this->form->getsubform($formid);
      foreach ($fup_subform as $rfup_subform) {
        $datasf =  $this->form->getdatafupsub($rfup_subform->fld_formrelc,$rfup_subform->fld_formrelp,$btid,$rfup_subform->fld_tblnm);
	$count = count($datasf);
	for ($i=0; $i<$count; ++$i) {
	  ###Prepare Data
	  $datasf[$i]['fld_btid'] = '';
	  $datasf[$i][$rfup_subform->fld_formrelc] = $fup_lid;
	  $datasf[$i]['fld_bttyid'] = $tynextid;
	  ###Insert Data
	  $sfinsert = $this->form->getforminsert($rfup_subform->fld_tblnm, $datasf[$i]);
	}
      }
      if($tynextform =='78000RETURN_DO') {
      $advance =  $this->input->post(fld_btp01) ;
	$query = $this->db->query("insert into tbl_btd_truck_cost (fld_btidp,fld_btiid,fld_btamt01) values ($fup_lid,1,'$advance')");
      }

      if ($tynextform == '78000DELIVERY_ORDER_BOX' && $this->input->post(fld_btflag) == 2) {
                     $this->ffis->setCashAdvance($fup_lid,$this->input->post(fld_baidc),$this->input->post(fld_btp09),$this->input->post(fld_btflag));
      }
     if ($tynextform == '78000JOCASH_ADVANCE' || $tynextform == '78000JOCASH_ADVANCE_EXP') {
		$sql="update tbl_btd_cost set fld_bt01='$btid' where fld_btidp='$fup_lid' and fld_bt01 =''";
		$this->db->query($sql);
      }
       if ($_POST['fnm']=='78000JOCASH_ADVANCE_REPO' && $this->input->post(fld_btp20) == 1){
         $fup_url = base_url() . "index.php/page/form/78000JO_SETTLEMENT_REPO/edit/$fup_lid";
       } else {
         $fup_url = base_url() . "index.php/page/form/$tynextform/edit/$fup_lid";
       }
      redirect($fup_url);
    }
  }

  function delete_process() {
    $fld_formnm =  $this->uri->segment(3);
    $node = $this->uri->segment(4);
    $lform = $this->form->getform($fld_formnm);
    $fld_tblnm = $lform->fld_tblnm;
    $fld_tblpkey = $lform->fld_tblpkey;
    $subform = $this->form->getsubform($lform->fld_formid);
    switch ($fld_formnm) {
      case "78000DELIVERY_ORDER_TRAILER":
          $this->check_validation($fld_formnm);
          break;

      case "green":
          echo "Your favorite color is green!";
          break;
    }
    foreach ($subform as $rsubform) {
      $sffld_tblnm = $sfform->fld_tblnm;
      $sffld_tblpkey = $sfform->fld_tblpkey;
      $delsfrecord = $this->db->query("delete from $rsubform->fld_tblnm where $rsubform->fld_formrelc='$node'");
    }
    $gffval = $this->db->query("delete from $fld_tblnm where $fld_tblpkey='$node' limit 1");
    ### Add By Sudarman 2014-07-23 13:30
    if($fld_tblnm == 'tbl_bth'){
      $this->db->query("delete from tbl_btr where fld_btrsrc ='$node' or fld_btrdst = '$node'");
    }

    ### Delete Record Log
    $data_log = array(
    'fld_acclogtyid' => '6' ,
    'fld_accloghost' => $_SERVER['REMOTE_ADDR'] ,
    'fld_acclogdt' => date('Y-m-d H:i:s'),
    'fld_acclogcmt' => 'User ' . $this->session->userdata('usernm') . ' Delete record number' . $node . ' on table ' . $fld_tblnm
    );
    $this->db->insert('tbl_acclog', $data_log);
    ###
    $url = base_url() . "index.php/page/view/$fld_formnm/";
    redirect($url);
  }

  function searchbox() {
    $fvalid = $this->input->GET(fid);
    $lform = $this->form->getformbyID($fvalid);
    $data_form['fld_formlbl'] =  $lform->fld_formlbl;
    $data_form['fld_formnm'] =  $lform->fld_formnm;
    $data_form['fld_formid'] =  $lform->fld_formid;
    $fld_formid = $lform->fld_formid;
    $fld_tblnm = $lform->fld_tblnm;
    $fld_tblpkey = $lform->fld_tblpkey;
    $data_form['formfield'] =  $this->form->getformfield($fld_formid);
    $this->load->view('search_view', $data_form);

  }

  function mkautono ($baido,$bttyid) {

  $date_trans = date("ym");
  $year_trans = date("y");
  $bacd = $this->db->query("select fld_bacd from tbl_ba where fld_baid='$baido'");
  $lbacd = $bacd->row();
  $bttycd = $this->db->query("select fld_bttycd from tbl_btty where fld_bttyid='$bttyid'");
  $lbttycd = $bttycd->row();
  $query = $this->db->query("select t0.fld_btno  from tbl_bth t0  where t0.fld_bttyid='$bttyid' and t0.fld_baido = '$baido' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
  foreach ($query->result() as $row) {
  }
  $get_seq_number = (substr($row->fld_btno,13,5)+1);
  $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
  $vno = $lbacd->fld_bacd . "/" . $lbttycd->fld_bttycd . "/" . $date_trans . "/" . $seq_number;
  return $vno;

  }

 
  function setApproval ($btid) {
    $groupid = $this->session->userdata('group');
    $group_add = $this->session->userdata('group_add');
    $userid = $this->session->userdata('userid');
    $FrmName=$this->db->query("select t1.fld_bttyform 'fld_formnm'
								from tbl_bth t0
								left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
								where
								t0.fld_btid=$btid")->row()->fld_formnm;
    switch ($fld_formnm) {
      case "78000DELIVERY_ORDER_TRAILER":
          $this->check_validation($fld_formnm);
          break;

      case "green":
          echo "Your favorite color is green!";
          break;
    }
    $grule = $this->db->query("select t3.fld_aprvrulenm,t3.fld_aprvruleid,t0.fld_bttyid,t3.fld_usergrpid
                               from tbl_bth t0
                               left join tbl_btty t1 on t1.fld_bttyid=t0.fld_bttyid
                               left join tbl_transaprv t2 on t2.fld_bttyid=t1.fld_bttyid
                               left join tbl_aprvrule t3 on t3.fld_aprvruleid=t2.fld_aprvruleid
                               where t0.fld_btid=$btid and t3.fld_usergrpid  in ($groupid,$group_add)  limit 1");
    $grule = $grule->row();
    $aprv_act = $this->uri->segment(4);
    $fld_aprvtktno = date('YmdHis');
    $transty = $this->db->query("select t0.fld_bttyid from tbl_bth t0
                                 where t0.fld_btid=$btid");
    $transty = $transty->row();

    $bttyid = $grule->fld_bttyid;


	### Is All Addional Work Order Completed ?
    $gadd_wo = $this->db->query("select count(1) 'add_wo' from tbl_btr t0
                                  left join tbl_bth t1 on t1.fld_btid=t0.fld_btrdst and t1.fld_bttyid=18
                                  where t0.fld_btrsrc=$btid and t1.fld_btstat!=3");
    $gadd_wo =  $gadd_wo->row();
    if($gadd_wo->add_wo > 0) {
      echo '<script>alert("You have to complete all Additional WO !!! "); history.go(-1)</script>';
      exit();
    }

    $do = $this->ffis->setApprovalAction($btid,$transty->fld_bttyid,$aprv_act);
    ###
    if ($aprv_act == 'req') {
      if ($transty->fld_bttyid == 41 || $transty->fld_bttyid == 42 ||  $transty->fld_bttyid == 45 || $transty->fld_bttyid == 46 ||
           $transty->fld_bttyid == 51 || $transty->fld_bttyid == 53 || $transty->fld_bttyid == 54 || $transty->fld_bttyid == 55 ||
   $transty->fld_bttyid == 59 ||$transty->fld_bttyid == 82 || $transty->fld_bttyid == 85 || $transty->fld_bttyid == 94 ||$transty->fld_bttyid == 95)
      {
        ###Cek Monthly Closing
        $this->ffis->cekClosingDate ($btid);
      }
       if($transty->fld_bttyid == 4){
      $this->ffis->cekInvjst($btid);
      }
/*
       #update terminal location
      if($transty->fld_bttyid == 2){
          $cekjoc = $this->db->query("
              SELECT
              t1.fld_btid 'jocid',
              t0.fld_bt01 'joid',
              if(t1.fld_btp45=0, 0, t1.fld_btp45) 'terminal',
              t2.fld_bttyid 'tyid'
              FROM tbl_btd_cost t0
              LEFT JOIN tbl_bth t1 ON t1.fld_btid=t0.fld_btidp
              LEFT JOIN tbl_bth t2 ON t2.fld_btid=t0.fld_bt01
              WHERE
              t1.fld_bttyid=2
              and t1.fld_btid='$btid'

              GROUP BY t0.fld_bt01
              ORDER BY t1.fld_btid DESC

          ")->result();

          foreach ($cekjoc as $key => $item) {
              if ($item->terminal > 0) {
                 $this->db->query("UPDATE exim.tbl_bth
                                   SET
                                   fld_btp01=if($item->tyid in(1,65),'$item->terminal',fld_btp01),
                                   fld_btp24 = if($item->tyid in(6,93),'$item->terminal',fld_btp24)
                                   WHERE fld_btid = '$item->joid' LIMIT 1");
              }


          }

      }
*/

       #check double item cost JOC
      if($transty->fld_bttyid == 2){
          $cekjoc = $this->db->query("
              SELECT
              t2.fld_btno 'jobno', t3.fld_btinm 'costnm',
              t0.fld_bt01 'joid', count(t0.fld_bt01) 'countjo',
              t0.fld_costtype 'desc', count(t0.fld_costtype) 'countcost',
              t0.fld_btuamt01 'amount', count(t0.fld_btuamt01) 'countamount'
              FROM tbl_btd_cost t0
              left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
              left join tbl_bth t2 on t2.fld_btid = t0.fld_bt01
              left join tbl_bti t3 on t3.fld_btiid = t0.fld_costtype and t3.fld_bticid = 1
              WHERE
              t1.fld_bttyid=2
              and
              t1.fld_btid='$btid'
              group by t0.fld_bt01,t0.fld_costtype,t0.fld_btuamt01
              HAVING COUNT(countjo) > 1
              AND COUNT(countcost) > 1
              AND COUNT(countamount) > 1

          ");
         $cekjoc = $cekjoc->row();
              if ($cekjoc->countjo > 1 && $cekjoc->countcost > 1 && $cekjoc->countamount > 1 ) {

                  $this->ffis->message("Double item cost in Job Number : $cekjoc->jobno, Desc : $cekjoc->costnm !");
                  exit();

              }

	// cek double item cost and amount in all JOC

        $item_joc = $this->db->query("SELECT
              t0.fld_bt01 'joid',
              t0.fld_costtype 'desc',
              t0.fld_btuamt01 'amount'
              FROM tbl_btd_cost t0
              left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
              left join tbl_user t2 on t2.fld_userid = t1.fld_btp23
              where
              t0.fld_btidp = '$btid'
              and
              t2.fld_usercomp = 1");

         foreach ($item_joc->result() as $rjoc){
            $cekjoc2 = $this->db->query("
              SELECT
              t1.fld_btno 'jocno',
              t0.fld_bt01 'joid',
              t0.fld_costtype 'desc',
              t0.fld_btuamt01 'amount'
              FROM tbl_btd_cost t0
              left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
              left join tbl_user t2 on t2.fld_userid = t1.fld_btp23
              WHERE
              t1.fld_bttyid=2
              and
              t1.fld_btstat !=5
              and
              t0.fld_bt01 = '$rjoc->joid'
              and
              t0.fld_costtype = '$rjoc->desc'
              and
              t0.fld_btuamt01 = '$rjoc->amount'
              and
              date_format(t1.fld_btdt,'%Y-%m') >= '2021-05'
              and
              t2.fld_usercomp = 1
              and
              t1.fld_btid != '$btid'
              limit 1

          ");

		  $count = $cekjoc2->num_rows();
                  if ($count > 0) {
                      $cekjoc2 = $cekjoc2->row();

                      $this->ffis->message("Double request with advance number $cekjoc2->jocno! Please check your detail request.");
                      exit();

                  }

      }
         //cek description detail
         $cek_desc = $this->db->query("SELECT *
              FROM tbl_btd_cost t0
              where t0.fld_btidp = '$btid' and t0.fld_costtype = 0 ");
         $count2 = $cek_desc->num_rows();
         if ($count2 > 0) {
                      $this->ffis->message("Cost Description cannot be empty! Please check your detail request.");
                      exit();
                  }


}

      #check double item cost JST
      if($transty->fld_bttyid == 4){
          $cekjst = $this->db->query("
              SELECT
              t0.fld_bt01 'joid', count(t0.fld_bt01) 'countjo',
              t0.fld_costtype 'desc', count(t0.fld_costtype) 'countcost',
              t0.fld_btuamt01 'amount', count(t0.fld_btuamt01) 'countamount', t0.fld_btp09 'dep_lolo'
              FROM tbl_btd_cost t0
              left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
              WHERE
              t1.fld_bttyid=4
              and
              t1.fld_btp18 !=4
              and
              t1.fld_btid='$btid'
              group by t0.fld_bt01,t0.fld_costtype,t0.fld_btuamt01
              HAVING COUNT(countjo) > 1
              AND COUNT(countcost) > 1
              AND COUNT(countamount) > 1

          ");
         $cekjst = $cekjst->row();
              if ($cekjst->countjo > 1 && $cekjst->countcost > 1 && $cekjst->countamount > 1 && $cekjst->dep_lolo == '') {

                  $this->ffis->message("Double item cost in 1 Job Order!");
                  exit();

              }

          //cek description detail
         $cek_desc = $this->db->query("SELECT *
              FROM tbl_btd_cost t0
              where t0.fld_btidp = '$btid' and t0.fld_costtype = 0 ");
         $count2 = $cek_desc->num_rows();
         if ($count2 > 0) {
                      $this->ffis->message("Cost Description cannot be empty! Please check your detail request.");
                      exit();
                  }

    //cek proforma number
    $edc = $this->db->query("select t0.fld_bt03 'edc',t1.fld_btdesc 'desc',t2.fld_btno 'jst' from tbl_btd_cost t0
                             left join tbl_btd_edc t1 on t1.fld_btid = t0.fld_bt03
                             left join tbl_bth t2 on t2.fld_btid = t0.fld_btidp
                             where t0.fld_btidp != '$btid' and t0.fld_bt03 !=0 ");
    $ops_edc = $this->db->query("select * from tbl_btd_cost where fld_btidp = '$btid' and fld_bt03 !=0");

    foreach($ops_edc->result() as $rops_edc) {
        foreach($edc->result() as $redc) {
                if($rops_edc->fld_bt03 == $redc->edc ) {
                        //cek no UBP sudah terpakai/tidak
                                $this->ffis->message("Proforma Number $redc->desc has been used before in JST number : $redc->jst. Please check again!");
                                exit();
                }
        }
     }

     //beri flag no Deposit terpakai
     $deposit_flag = $this->db->query("select fld_btp09 from tbl_btd_cost where fld_btidp = '$btid' and fld_btp09 !='' group by fld_btp09");

     foreach($deposit_flag->result() as $rdeposit) {

                        $this->db->query("update tbl_btd_upload_deposit set fld_btflag2 = '$btid'
                                          where fld_btid = '$rdeposit->fld_btp09' limit 1");
     }

     //cek double invoice deposit
     $inv_deposit = $this->db->query("SELECT
				      t0.fld_btp09 'invid'
				      FROM tbl_btd_cost t0
				      where
				      t0.fld_btidp = '$btid'");

         foreach ($inv_deposit->result() as $rdeposit){
	    if($rdeposit->invid > 0) {
            $deposit = $this->db->query("
              SELECT
              t1.fld_btno 'settno',
              t0.fld_btp09 'inv'
              FROM tbl_btd_cost t0
              left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
              WHERE
              t1.fld_bttyid=4
              and
              t1.fld_btstat !=5
              and
              t0.fld_btp09 = '$rdeposit->invid'
              and
              date_format(t1.fld_btdt,'%Y-%m') >= '2022-01'
              and
              t1.fld_btid != '$btid'
              limit 1

          ");

                  $count = $deposit->num_rows();
                  if ($count > 0) {
                      $deposit =  $deposit->row();
                      $this->ffis->message("Double invoice deposit with settlement number $deposit->settno! Please check your invoice detail.");
                      exit();

                  }
		}
	   }

    }

//check detail APV in SRC
      if($transty->fld_bttyid == 116) {
       $get_apv = $this->db->query("SELECT t6.fld_btid 'apv_id',t6.fld_btno 'apv_no'
                                     from tbl_btd_receipt t0
                                     left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                     left join tbl_bth t2 on t2.fld_btid = t0.fld_btreffid
                                     left join tbl_btr t3 on t3.fld_btrdst = t2.fld_btid
                                     left join tbl_bth t4 on t4.fld_btid = t3.fld_btrsrc and t4.fld_bttyid = 2
                                     left join tbl_btd_advaprv t5 on t5.fld_btreffid = t4.fld_btid
                                     left join tbl_bth t6 on t6.fld_btid=t5.fld_btidp and t6.fld_bttyid=8
                                     where
                                     t1.fld_btid = '$btid'
                                     group by t6.fld_btid
                                     ");

       foreach ($get_apv->result() as $rapv){

        if (!empty($rapv->apv_id)) {


       $countdtlapv = $this->db->query("select count(fld_btid) 'apvcnt' from tbl_btd_advaprv where fld_btidp = $rapv->apv_id");
       $countapvdtl = $countdtlapv->row();

       $get_apv_src = $this->db->query("SELECT count(t0.fld_btreffid) 'apvsrccnt'
                                     from tbl_btd_receipt t0
                                     left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                     left join tbl_bth t2 on t2.fld_btid = t0.fld_btreffid
                                     left join tbl_btr t3 on t3.fld_btrdst = t2.fld_btid
                                     left join tbl_bth t4 on t4.fld_btid = t3.fld_btrsrc and t4.fld_bttyid = 2
                                     left join tbl_btd_advaprv t5 on t5.fld_btreffid = t4.fld_btid
                                     left join tbl_bth t6 on t6.fld_btid=t5.fld_btidp and t6.fld_bttyid=8
                                     where
                                     t1.fld_btid = '$btid'
                                     and
                                     t6.fld_btid = '$rapv->apv_id'");

        $countapvsrc = $get_apv_src->row();

                if($countapvdtl->apvcnt != $countapvsrc->apvsrccnt) {
                         $this->ffis->message("Please Check all JOC/JST in APV Number : $rapv->apv_no !");
                         exit();
                }
          }
       }
      }


      if($transty->fld_bttyid == 60) {
          $btid = $btid;

          $checkcomp = $this->db->query("
              SELECT
              res.*

              FROM(
                  SELECT
                  t1.fld_btno 'cdenumber',
                  if(t1.fld_btp23='' or t1.fld_btp23='0', '0', t1.fld_btp23) 'cdecomp',
                  t2.fld_btno 'transnumber',
                  CASE
                      WHEN t2.fld_bttyid in(46,51) THEN if(t2.fld_btp23 = 1,'1','0')
                      ELSE '0'
                  END 'transcomp'

                  FROM tbl_btd_deposit t0
                  LEFT JOIN tbl_bth t1 on t1.fld_btid=t0.fld_btidp and t1.fld_bttyid=60
                  LEFT JOIN tbl_bth t2 on t2.fld_btno=t0.fld_btp02

                  WHERE
                  t1.fld_bttyid=60
                  and date_format(t1.fld_btdt, '%Y-%m-%d') >= '2021-04-01'
                  and t1.fld_btid='$btid'
              ) res

              WHERE
              res.cdecomp != res.transcomp
          ");

          $cekcde = $this->db->query("SELECT fld_btp23 FROM tbl_bth WHERE fld_btid = '$btid' AND fld_bttyid=60 LIMIT 1")->row();

          if ($checkcomp->num_rows() > 0) {
              $diffcomp = '';
              foreach ($checkcomp->result() as $key => $item) {
                $diffcomp = $diffcomp . ', ' . $item->transnumber;
              }
              $this->ffis->message("This transaction has a different company, please check $diffcomp");
          } else {
              $btrlist = $this->db->query("SELECT fld_btrdst FROM tbl_btr WHERE fld_btrsrc = '$btid' AND fld_btrdsttyid=11 GROUP by fld_btrdst")->result();
              if ($cekcde->fld_btp23 == 1) {
                  foreach ($btrlist as $key => $item) {
                    $this->db->query("UPDATE exim.tbl_bth SET fld_btp37 = '1' WHERE tbl_bth.fld_btid = '$item->fld_btrdst' LIMIT 1");
                  }
              } else {
                  foreach ($btrlist as $key => $item) {
                    $this->db->query("UPDATE exim.tbl_bth SET fld_btp37 = '0' WHERE tbl_bth.fld_btid = '$item->fld_btrdst' LIMIT 1");
                  }
              }
          }
      }

      # DO Cancel
      if ($bttyid == 14) {
        $cekSettlement = $this->db->query("select *
                           from tbl_trk_settlement t0 where t0.fld_btno = (select tx0.fld_btnoalt from tbl_bth tx0 where tx0.fld_btid='$btid') ");
        if ($cekSettlement->num_rows() > 0) {
          echo "<div align='center'>
          Please remove all settlement cost before canceling this DO , click <a href='javascript:history.back();'>here</a> to go back </div>";
          exit();
        } else {
          $doid = $this->db->query("select tx1.fld_btid
          from tbl_btr tx0 left join tbl_bth tx1 on tx1.fld_btid= tx0.fld_btrsrc where tx0.fld_btrdst = '$btid' and tx1.fld_bttyid = 77 ");
          $doid = $doid->row();
          $this->db->query("update tbl_bth set fld_btstat = 5 where fld_btid = '$doid->fld_btid' limit 1 ");
        }
      }

     /*if ($bttyid == 77) {
      $loc = $this->session->userdata('location');
      if ($loc !=0 ) {
        $cekData = $this->db->query("select fld_btp16,fld_btp19 from tbl_bth where fld_btid ='$btid' and fld_btbalance ='' and fld_btp16='' and fld_baidv !=1 limit 1");
      if ($cekData->num_rows() > 0){

      $this->dnxapps->message("Please Check.. This Transaction don't have Quotation Number. ");
      }

       }
      }*/


      ###Cek Quo
      if($bttyid == 5){

/*
       # remark mandatory hold,req yuhendra export
	$data = $this->db->query("select
				t0.fld_btid 'crud',
				date_format(t0.fld_btdt,'%Y-%m-%d') 'Trans Date',
				date_format(t0.fld_btdtsa,'%Y-%m-%d') 'Booking Date',
				t5.fld_bedivnm 'Division',
				t0.fld_btno 'Booking Number',
				t3.fld_tyvalnm 'Booking Status',
				t0.fld_btdesc 'Desc',
				t6.fld_btp09'POI 1',
				t6.fld_btp10 'POI 2',
				t6.fld_btp11 'POI 3',
				t6.fld_btp05 'Route',
				t6.fld_bt09 'Lcl Qty',
				t6.fld_btp12 'Veh Type',
				t6.fld_btp13 'Veh Qty',
				t7.fld_btno 'JONumber',
				t6.fld_bt02 'si',
				t2.fld_empnm 'Posted By'
				from tbl_bth t0
				left join tbl_be t1 on t1.fld_beid=t0.fld_baidc
				left join hris.tbl_emp t2 on t2.fld_empid=t0.fld_baidp
				left join tbl_tyval t3 on t3.fld_tyvalcd=t0.fld_btstat and t3.fld_tyid=2
				left join tbl_tyval t4 on t4.fld_tyvalcd=t0.fld_btflag and t4.fld_tyid=53
				left join hris.tbl_bediv t5 on t5.fld_bedivid=t0.fld_baidv
				left join tbl_btd_truck t6 on t6.fld_btidp = t0.fld_btid
				left join tbl_bth t7 on t7.fld_btid = t6.fld_bt01 and t7.fld_bttyid in (1,6,10)
				where
				t0.fld_bttyid=5
				and
				t0.fld_baidv=13

				and
				t0.fld_btloc = 1
				AND
				t6.fld_bt09 > 0

				AND (t6.fld_btp12 =0 or t6.fld_btp13 =  '')
				and
				t0.fld_btid = $btid
				order by t0.fld_btid desc");
		if($data->num_rows() > 0){
			foreach($data->result() as $rdata){
			 $jo = $rdata->JONumber;
			 $si = $rdata->si;
			 $desc = $rdata->Desc;
			 echo "<br><div align='center' width ='80%'><font size='5' >";
		         echo "JO Number=$jo , S/I Number= $si, Dest= $desc<br>";
			 echo "</font></div>";
			}

		 	$this->dnxapps->message("Please complete data Vehicle type and Qty on LCL Items Description.. . ");

		} */

$Booking = $this->db->query("select
t0.fld_btp05 'Route',
t0.fld_baidc 'Customer',
t0.fld_bt04  'feet1',
t0.fld_bt05  'feet2',
t0.fld_bt09  'feet3',
t0.fld_bt10  'feet4',
t0.fld_bt09  'lcl',
t0.fld_btp06 'cbu',
t2.fld_btno 'JO',
t1.fld_baidv 'Div',
t1.fld_btp01 'ltl',
t1.fld_btdtsa 'BookingDate'
from
tbl_btd_truck t0
left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
left join tbl_bth t2 on t2.fld_btid = t0.fld_bt01 and t2.fld_bttyid in(1,65) and t2.fld_btstat in(1,2,3,6)
where
t1.fld_bttyid = 5
and
t0.fld_btidp = $btid")->result();

foreach($Booking as $rbooking){
$route2 = $rbooking->Route;
$Customers2 = $rbooking->Customer;
$BookingDate = $rbooking->BookingDate;
$panjang1 = $rbooking->feet1;
$jonumber = $rbooking->JO;
$panjang2 = $rbooking->feet2;
$panjang3 = $rbooking->feet3;
$panjang4 = $rbooking->feet4;
$panjang5 = $rbooking->lcl;
$panjang6 = $rbooking->cbu;
$Divisi = $rbooking->Div;
if($panjang1 > 0){
  $panjang = 1 ;
}else if($panjang2 > 0){
  $panjang = 2 ;
}else if($panjang3 > 0){
 $panjang = 4  ;
}else if($panjang4 > 0){
 $panjang = 3  ;
}else if($panjang5 > 0){
 $panjang = 5  ;
}else if($panjang6 > 0){
 $panjang = 0  ;
 $route2  = ' ';
}

$quo_trk = $this->db->query("select
t0.fld_btp02 'VehicleType',
t0.fld_btp01 'Route',
t1.fld_baidc 'Customers',
t1.fld_btdtso 'ValidUntil'
from
dnxapps.tbl_btd_quo_trk t0
left join dnxapps.tbl_bth t1 on t1.fld_btid = t0.fld_btidp
where
t1.fld_bttyid in (33,86)
and t1.fld_btstat = 3
and t0.fld_btp01 = '$route2'
and t1.fld_baidc = '$Customers2'
#and t0.fld_btp02 = 1
and t0.fld_container = '$panjang'
and date_format(t1.fld_btdtso,'%Y-%m-%d') >= date_format('$BookingDate','%Y-%m-%d')
");
if($rbooking->Div == 14){
if($quo_trk->num_rows() < 1 && $rbooking->ltl != 1 ){
$this->ffis->message("Please Check ".$jonumber. " Quotation Cannot Find");
}
}
}
}
      ### Cek Posting Date
      if ($transty->fld_bttyid == 41) {
          $cekpostdt = $this->db->query("select fld_bttaxno, fld_btnoreff, fld_baidc, fld_btp40, date_format(fld_btdt, '%Y-%m-%d') 'transdate',date_format(fld_btdtp, '%Y-%m-%d') 'postdate', fld_btuamt 'vat' from tbl_bth where fld_btid ='$btid'");
              $cekpostdate = $cekpostdt->row();
              if ($cekpostdate->postdate <> $cekpostdate->transdate && $cekpostdate->fld_bttaxno!=''){
              $this->ffis->message("Please Check.. Posting Date Must be equal  to Transaction Date!! ");
          #echo "test";
          #exit();
              }

              $cekpic = $this->db->query("SELECT
                  t0.fld_btid 'id'

                  from
                  tbl_btd_job_pic t0
                  left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp and t1.fld_bttyid=104
                  left join tbl_bth t2 on t2.fld_btid=t0.fld_bt01
                  left join tbl_bth t3 on t3.fld_btnoreff=t2.fld_btno and t3.fld_bttyid=41

                  where
                  t3.fld_btnoreff = '$cekpostdate->fld_btnoreff'
                  and t0.fld_btdesc != ''

                  limit 1
              ")->row();

              if (($cekpic->id == NULL || $cekpic->id == '' || $cekpic->id == 0) && ($cekpostdate->fld_baidc!='14056' && $cekpostdate->fld_baidc!='5535') && ($cekpostdate->fld_btp40!='15')) {
                  $this->ffis->message("Please Check.. PIC Number must be created first !! ");
              }

         }


      ##cek entry invoice type settle
      if ($transty->fld_bttyid == 4){
      $cek = $this->db->query ("select t0.fld_btid,t0.fld_bt05,t1.fld_btno,t2.fld_btinm  from tbl_btd_cost t0
                                left join tbl_bth t1 on t1.fld_btid =t0.fld_bt01 and t1.fld_bttyid in (1,6)
                                left join tbl_bti t2 on t2.fld_btiid = t0.fld_costtype and t2.fld_bticid = 1
                                where t0.fld_btidp ='$btid' and t0.fld_bt05 =''");
      $count = $cek->num_rows();
      if ($count > 0){
      echo "<center>Please check the list below, the item must be filled with invoice type:<br>";
       $no =0;
      foreach ($cek->result() as $rcek){
       $no = $no +1;

          echo "<center style='color:red'>$no . $rcek->fld_btno  [ $rcek->fld_btinm ] <br>";
           }
           echo exit();
        }

      }

      ## DO Approval Summary Advance
      if ($bttyid == 8 || $bttyid == 9) {
        $this->ffis->approvalSummary($btid,$userid,$groupid,$group_add);
      }

      if ($transty->fld_bttyid == 41) {
        $this->ffis->insertJournalInvoice($btid);
      }

      if($transty->fld_bttyid == 26){
        $this->ffis->updateInvFromBIT($btid);
      }


      $grole = $this->db->query("select t0.fld_aprvroleid,t0.fld_usergrpid,t0.fld_aprvruleroleord
                                 from tbl_aprvrulerole t0
                                 where t0.fld_aprvruleid=$grule->fld_aprvruleid and t0.fld_usergrpid != $grule->fld_usergrpid order by t0.fld_aprvruleroleord");
      $countRole =  count($grole->result());
      if ($countRole < 1 ) {
        $query = $this->db->query("update tbl_bth set fld_btstat=3 where fld_btid=$btid");
      } else {
        foreach ($grole->result() as $rgrole) {
          ### Create Approval Ticket
           if($transty->fld_bttyid == 5){
        $quo_trk = $this->db->query("select
t0.fld_btp02 'VehicleType',
t0.fld_btp01 'Route',
t1.fld_baidc 'Customers',
t1.fld_btdtso 'ValidUntil',
from
tbl_btd_quo_trk t0
left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
where
t1.fld_bttyid = 33")->result();

$Booking = $this->db->query("select
t0.fld_bto05 'Route',
t0.fld_baidc 'Customer',
t1.fld_btdtsa 'BookingDate',
from
tbl_btd_truck t0
left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
where
t1.fld_bttyid = 5")->result();

foreach($quo_trk as $rquo){
$vehicletype = $rquo->VehicleType;
$Route = $rquo->Route;
$customers = $rquo->Customers;
$date = $rquo->ValidUntil;
foreach($booking as $rbooking){
$route2 = $rbooking->Route;
$Customers2 = $rbooking->Customer;
$BookingDate = $rbooking->BookingDate;
}
}
}


          $this->db->query("insert into tbl_aprvtkt (fld_aprvtktno,fld_usergrpid,fld_aprvroleid,fld_aprvruleroleord,fld_btid,fld_aprvtktstat)
          value ($fld_aprvtktno,$rgrole->fld_usergrpid,$rgrole->fld_aprvroleid,$rgrole->fld_aprvruleroleord,$btid,1) ");
        }
        $query = $this->db->query("update tbl_bth set fld_btstat=2 where fld_btid=$btid");
      }
      ### Set Work Order Complete Date
      if($bttyid == 4) {
        $upd1 = $this->db->query("update tbl_bth set fld_btdtso=now() where fld_btid=$btid");
      }

      if($bttyid == 62) {
        $getamount = $this->db->query("select fld_btamt,fld_btamt01 from tbl_bth where fld_btid = $btid");
        $getamount = $getamount->row();
        $this->ffis->UpdateOverPayment($btid,$getamount->fld_btamt,$getamount->fld_btamt01);
      }


      ### Insert Jurnal Transaction

      if ($transty->fld_bttyid == 42 || $transty->fld_bttyid == 46) {
        $company = $this->db->query("select fld_btp23 from tbl_bth where fld_btid = $btid");
        $company = $company->row();
        $this->ffis->insertJournalCash($btid,$company->fld_btp23);

      }

	   if ($transty->fld_bttyid == 94 || $transty->fld_bttyid == 95) {
        $this->ffis->insertJournalBank($btid,$aprv_act);
      }

      if ($transty->fld_bttyid == 51 ) {
        $this->ffis->insertGeneralJournal($btid);
      }

      if ($transty->fld_bttyid == 53) {
        $this->ffis->insertJournalAP($btid);
      }

      if ($transty->fld_bttyid == 54) {
        $this->ffis->insertJournalCRT($btid);
      }

      if ($transty->fld_bttyid == 55) {
        $this->ffis->insertJournalPaymentAP($btid);
      }

      if ($transty->fld_bttyid == 59) {
        $this->ffis->insertJournalSID($btid);
      }

      if ($transty->fld_bttyid == 45) {
        $this->ffis->insertJournalPayment($btid);
      }

      if ($transty->fld_bttyid == 61) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 63) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 64) {
	$this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 66) {
         $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 84) {
         $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 87) {
         $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 82) {
        $this->ffis->insertJournalInvoiceRev($btid);
      }

      if ($transty->fld_bttyid == 85) {
        $this->ffis->insertJournalDeposit($btid);
      	 }
/*
     if($transty->fld_bttyid == 5){
        $quo_trk = $this->db->query("select
t0.fld_btp02 'VehicleType',
t0.fld_btp01 'Route',
t1.fld_baidc 'Customers',
t1.fld_btdtso 'ValidUntil',
from
tbl_btd_quo_trk t0
left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
where
t1.fld_bttyid = 33")->result();

$Booking = $this->db->query("select
t0.fld_bto05 'Route',
t0.fld_baidc 'Customer',
t1.fld_btdtsa 'BookingDate',
from
tbl_btd_truck t0
left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
where
t1.fld_bttyid = 5")->result();


}
  */
    }

    if ($aprv_act == 'aprv') {

       if ($transty->fld_bttyid == 41 || $transty->fld_bttyid == 42 ||  $transty->fld_bttyid == 45 || $transty->fld_bttyid == 46 ||
           $transty->fld_bttyid == 51 || $transty->fld_bttyid == 53 || $transty->fld_bttyid == 54 || $transty->fld_bttyid == 55 || $transty->fld_bttyid == 59 || $transty->fld_bttyid == 82 || $transty->fld_bttyid == 85 || $transty->fld_bttyid == 94 ||$transty->fld_bttyid == 95)
      {
        ###Cek Monthly Closing
        $this->ffis->cekClosingDate ($btid);
      }

      ## Cek Posting Date
     if ($transty->fld_bttyid == 41) {
          $cekpostdt = $this->db->query("select fld_bttaxno,date_format(fld_btdt, '%Y-%m-%d') 'transdate',date_format(fld_btdtp, '%Y-%m-%d') 'postdate', fld_btuamt 'vat' from tbl_bth where fld_btid ='$btid'");
              $cekpostdate = $cekpostdt->row();
              if ($cekpostdate->postdate <> $cekpostdate->transdate && $cekpostdate->fld_bttaxno!=''){
              $this->ffis->message("Please Check.. Posting Date Must be equal  to Transaction Date!! ");
          #echo "test";
          #exit();
              }
         }

          // if($transty->fld_bttyid == 60) {
          //     $cekCDE = $this->db->query("SELECT * FROM tbl_bth WHERE fld_btid = '$btid' LIMIT 1")->row();

          //     if ($cekCDE->fld_btp23 == 1) {
          //       $btrlist = $this->db->query("SELECT fld_btrdst FROM tbl_btr WHERE fld_btrsrc = '$btid' AND fld_btrdsttyid=11 GROUP by fld_btrdst")->result();
          //       foreach ($btrlist as $key => $item) {
          //         $this->db->query("UPDATE exim.tbl_bth SET fld_btp37 = '1' WHERE tbl_bth.fld_btid = '$item->fld_btrdst' LIMIT 1");
          //       }
          //     }
          // }
    /*  if ($transty->fld_bttyid == 77) {
      $loc = $this->session->userdata('location');
      if ($loc !=0 ) {
        $cekData = $this->db->query("select fld_btp16,fld_btp19 from tbl_bth where fld_btid ='$btid' and fld_btbalance ='' and fld_btp16='' and fld_baidv !=1 limit 1");
      if ($cekData->num_rows() > 0){

      $this->dnxapps->message("Please Check.. This Transaction don't have Quotation Number. ");
      }

       }
      }*/

      if ($transty->fld_bttyid == 14) {
        $cekSettlement = $this->db->query("select *
                           from tbl_trk_settlement t0 where t0.fld_btno = (select tx0.fld_btnoalt from tbl_bth tx0 where tx0.fld_btid='$btid') ");
        if ($cekSettlement->num_rows() > 0) {
          echo "<div align='center'>
          Please remove all settlement cost before canceling this DO , click <a href='javascript:history.back();'>here</a> to go back </div>";
          exit();
        } else {
          $doid = $this->db->query("select tx1.fld_btid
          from tbl_btr tx0 left join tbl_bth tx1 on tx1.fld_btid= tx0.fld_btrsrc where tx0.fld_btrdst = '$btid' and tx1.fld_bttyid = 77 ");
          $doid = $doid->row();
          $this->db->query("update tbl_bth set fld_btstat = 5 where fld_btid = '$doid->fld_btid' limit 1 ");
        }
      }

      if($transty->fld_bttyid == 26){
        $this->ffis->updateInvFromBIT($btid);
      }

      $query = $this->db->query("update tbl_bth set fld_btstat=3 where fld_btid=$btid");
      $query1 = $this->db->query("update tbl_aprvtkt set fld_aprvtktstat=2 , fld_userid=$userid ,fld_aprvtktmoddt = now()
                                  where fld_btid=$btid and fld_aprvroleid=3 and fld_usergrpid in (ifnull('$groupid',0),ifnull('$group_add',0))");

      ## Cash Advance

      if ($transty->fld_bttyid == 2) {
        $query = $this->db->query("update tbl_bth set fld_btdtsa=now() where fld_btid=$btid");
      }

      if ($bttyid == 5) {
        $this->ffis->CopyBookingData($btid);
      }

      if($bttyid == 62) {
        $getamount = $this->db->query("select fld_btamt,fld_btamt01 from tbl_bth where fld_btid = $btid");
        $getamount = $getamount->row();
        $this->ffis->UpdateOverPayment($btid,$getamount->fld_btamt,$getamount->fld_btamt01);

      }

      if ($bttyid == 8  || $bttyid == 9) {
        $this->ffis->approvalSummary($btid,$userid,$groupid,$group_add);
      }

      if ($bttyid == 95 ) {
      $this->ffis->approvalSummaryTrf($btid,$userid,$groupid,$group_add);
      }
      ### Insert Jurnal Transaction
      if ($transty->fld_bttyid == 41 || $transty->fld_bttyid == 461) {
        $this->ffis->insertJournalInvoice($btid);
      }
       ### Trailer Additional Cost
      if ($transty->fld_bttyid == 78) {
        $this->ffis->setTotalAdditionalCost($btid);
      }


      if ($transty->fld_bttyid == 42 || $transty->fld_bttyid == 46) {
        $company = $this->db->query("select fld_btp23 from tbl_bth where fld_btid = $btid");
        $company = $company->row();
        $this->ffis->insertJournalCash($btid,$company->fld_btp23);

      }
	  if ($transty->fld_bttyid == 94 || $transty->fld_bttyid == 95) {
        $this->ffis->insertJournalBank($btid);
      }

       if ($transty->fld_bttyid == 45) {
        $this->ffis->insertJournalPayment($btid);
      }

      if ($transty->fld_bttyid == 51 ) {
        $this->ffis->insertGeneralJournal($btid);
      }

      if ($transty->fld_bttyid == 53) {
        $this->ffis->insertJournalAP($btid);
      }

      if ($transty->fld_bttyid == 54) {
        $this->ffis->insertJournalCRT($btid);
      }

      if ($transty->fld_bttyid == 55) {
        $this->ffis->insertJournalPaymentAP($btid);
      }

      if ($transty->fld_bttyid == 59) {
        $this->ffis->insertJournalSID($btid);
      }

      if ($transty->fld_bttyid == 85) {
        $this->ffis->insertJournalDeposit($btid);
      }

      if ($transty->fld_bttyid == 60) {
        $this->ffis->insertContainerDeposit($btid);
      }

      if ($transty->fld_bttyid == 61) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 63) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 64) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 66) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 84) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 87) {
         $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 82) {
        $this->ffis->insertJournalInvoicerev($btid);
      }

      if($transty->fld_bttyid == 91){
        $this->ffis->appvPDS($btid);
      }

      ###

    }

    if ($aprv_act == 'very') {
     $query = $this->db->query("update tbl_bth set fld_btstat=6 where fld_btid=$btid");
     $query1 = $this->db->query("update tbl_aprvtkt set fld_aprvtktstat=2 , fld_userid=$userid ,fld_aprvtktmoddt = now() where fld_btid=$btid and fld_aprvroleid=2 and fld_usergrpid in (ifnull('$groupid',0),ifnull('$group_add',0))");

    }


    if ($aprv_act == 'rev') {
      if ($transty->fld_bttyid == 41 || $transty->fld_bttyid == 42 || $transty->fld_bttyid == 45 || $transty->fld_bttyid == 46 || $transty->fld_bttyid == 51           || $transty->fld_bttyid == 53 || $transty->fld_bttyid == 54 || $transty->fld_bttyid == 55 || $transty->fld_bttyid == 59
          || $transty->fld_bttyid == 82 || $transty->fld_bttyid == 85 || $transty->fld_bttyid == 94 ||$transty->fld_bttyid == 95) {

         ### Revise Authority
        $str = "$groupid,$group_add";
        $pos = strpos($str, '10');
        if ($pos === false){
          $this->ffis->message("You don't have permission to revise this transaction , Please call your IT Administrator");
        }
         ###Cek Monthly Closing
        $this->ffis->cekClosingDate($btid);

         ### Delete Journal Record
        $this->db->query("delete from tbl_journal where fld_btid=$btid");
        if ($transty->fld_bttyid == 41 ) {
        $this->db->query("update tbl_bth set fld_btp32 = '' where fld_btid = $btid and fld_bttyid in (41,44) limit 1");
        }
      }
      //Deposit Entry
       if ($transty->fld_bttyid == 60) {
         ### Revise Authority
        $str = "$groupid,$group_add";
        $pos = strpos($str, '1');
        if ($pos === false){
          $this->ffis->message("You don't have permission to revise this transaction , Please call your IT Administrator");
        }
      }

      //RSC

       if ($transty->fld_bttyid == 87) {
         ### Revise Authority
        $str = "$groupid,$group_add";
        $pos = strpos($str, '1');

        if ($pos === false){
          $this->ffis->message("You don't have permission to revise this transaction , Please call your IT Administrator");
        }
      }

      $query = $this->db->query("update tbl_bth set fld_btstat=2 where fld_btid=$btid");
      $query = $this->db->query("update tbl_aprvtkt set fld_aprvtktstat=1 where fld_btid=$btid and fld_usergrpid=$groupid");

      //JST
      if ($transty->fld_bttyid == 4) {
         $query = $this->db->query("update tbl_bth set fld_btp44=1,fld_btdtp = now() where fld_btid=$btid limit 1");

      }

      if ($transty->fld_bttyid == 61) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 63) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 64) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 66) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 84) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 87) {
        $this->ffis->UpdateContainerDeposit($btid,$transty->fld_bttyid,$aprv_act);
      }

      if ($transty->fld_bttyid == 94 || $transty->fld_bttyid == 95) {
       echo $btid;
       $this->db->query("update tbl_btd_finance t0
                left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid
                set t1.fld_btstat = 6
                WHERE t0.fld_btidp ='$fld_btid' and t1.fld_bttyid = 4 and t1.fld_btstat =3");
      }

      if($transty->fld_bttyid == 26){
        $this->ffis->updateInvFromBITRev($btid);
      }

    }
      if ($aprv_act == 'canc') {
       $this->db->query("update tbl_bth set fld_btidp=0, fld_btnoreff='',fld_btstat = 5,fld_btp38 = '$userid' where fld_btid = '$btid' and fld_bttyid in (41,82,51,8,9,95,73,46,42) and fld_btstat in (1,2) limit 1 ");
       $this->db->query("delete from tbl_btr  where fld_btrsrc = '$btid' or fld_btrdst ='$btid' limit 1 ");
       echo '<script>history.go(-1)</script>';
      }


      if ($aprv_act == 'cancJOCJST') {
        $checkjoc = $this->db->query("SELECT
          t0.fld_btid 'jocid',
          t0.fld_btstat 'jocstat',
          t0.fld_btno 'jocno',
          t0.fld_btdesc 'jocdesc',
          t2.fld_btid 'jstid',
          t2.fld_btstat 'jststat',
          t2.fld_btno 'jstno',
          if(t3.fld_btid is null, 0, t3.fld_btid) 'apvid',
          if(t4.fld_btid is null, 0, t4.fld_btid) 'svpid'

          FROM tbl_bth t0
          LEFT JOIN tbl_btr t1 on t1.fld_btrsrc=t0.fld_btid
          LEFT JOIN tbl_bth t2 on t2.fld_btid=t1.fld_btrdst AND t2.fld_bttyid=4
          LEFT JOIN tbl_btd_advaprv t3 on t3.fld_btreffid=t0.fld_btid
          LEFT JOIN tbl_btd_advaprv t4 on t4.fld_btreffid=t2.fld_btid

          WHERE
          t0.fld_bttyid=2
          and t0.fld_btid='$btid'

          LIMIT 1
        ")->row();

        // if (($checkjoc->jocstat != 3 || $checkjoc->jocstat != 5) && ($checkjoc->apvid == 0 && $checkjoc->svpid == 0)) {
        //   $this->db->query("update tbl_bth set fld_btstat = 5,fld_btp44 = '$userid' where fld_btid = '$checkjoc->jocid' and fld_bttyid = 2 limit 1 ");
        //   $this->db->query("delete from tbl_btr  where fld_btrsrc = '$checkjoc->jocid' or fld_btrdst ='$checkjoc->jocid' limit 1 ");
        //   echo '<script>history.go(-1)</script>';
        // } elseif ($checkjoc->jocstat == 3 && $checkjoc->jstid > 0 && ($checkjoc->apvid == 0 && $checkjoc->svpid == 0)) {
        //   $this->db->query("update tbl_bth set fld_btstat = 5,fld_btp44 = '$userid' where fld_btid = '$checkjoc->jocid' and fld_bttyid = 2 limit 1 ");
        //   $this->db->query("update tbl_bth set fld_btstat = 5,fld_btp44 = '$userid' where fld_btid = '$checkjoc->jstid' and fld_bttyid = 4 limit 1 ");
        //   $this->db->query("delete from tbl_btr  where fld_btrsrc = '$checkjoc->jocid' or fld_btrdst ='$checkjoc->jocid' limit 1 ");
        //   $this->db->query("delete from tbl_btr  where fld_btrsrc = '$checkjoc->jstid' or fld_btrdst ='$checkjoc->jstid' limit 1 ");
        //   echo '<script>history.go(-1)</script>';
        // } else {
        //   $this->ffis->message("Please Call Finance for Cancel This Transaction.");
        // }

        if ($checkjoc->jocdesc != '' || $checkjoc->jocdesc != NULL) {
          if ((/*$checkjoc->jocstat != 3 || */$checkjoc->jocstat != 5) && ($checkjoc->apvid == 0 && $checkjoc->svpid == 0)) {
            $this->db->query("update tbl_bth set fld_btstat = 5,fld_btp44 = '$userid' where fld_btid = '$checkjoc->jocid' and fld_bttyid = 2 limit 1 ");
            $this->db->query("delete from tbl_btr  where fld_btrsrc = '$checkjoc->jocid' or fld_btrdst ='$checkjoc->jocid' limit 1 ");
            $this->db->query("delete from tbl_btd_cost  where fld_btidp = '$checkjoc->jocid' ");
            if ($checkjoc->jstid > 0) {
              $this->db->query("update tbl_bth set fld_btstat = 5,fld_btp44 = '$userid' where fld_btid = '$checkjoc->jstid' and fld_bttyid = 4 limit 1 ");
              $this->db->query("delete from tbl_btr  where fld_btrsrc = '$checkjoc->jstid' or fld_btrdst ='$checkjoc->jstid' limit 1 ");
              $this->db->query("delete from tbl_btd_cost  where fld_btidp = '$checkjoc->jstid' ");
            }
            echo '<script>history.go(-1)</script>';
          } else {
            $this->ffis->message("Please Call Finance for Cancel This Transaction.");
          }
        } else {
          $this->ffis->message("Please Fill in The Remark Column for Cancel This Transaction.");
        }


      }

      if ($aprv_act == 'deleteASM') {
        $checkASMDtl = $this->db->query("SELECT * FROM tbl_btd_doc WHERE fld_btidp = '$btid'");

        if ($checkASMDtl->num_rows() > 0) {
          $this->ffis->message("Sorry, You Cannot Delete This Transaction. Please Call Finance for Delete This Transaction.");
        } else {
          $this->db->query("DELETE FROM exim.tbl_bth WHERE tbl_bth.fld_btid = '$btid' and tbl_bth.fld_bttyid = 114 limit 1 ");
          $url = base_url() . "index.php/page/view/78000ADVANCE_SUBMIT";
          redirect($url);
        }

      }



    ### Posting Bonus
    if ($transty->fld_bttyid == 32) {
        $this->ffis->PostingWeeklyBonus($btid,$this->session->userdata('location'),$aprv_act);
      }

    echo '<script>history.go(-1)</script>';
  }

  function changePassword($userid,$password1,$password2) {
    $cek_user = $this->db->query("select * from tbl_user where fld_userid = '$userid'");
    $data = $cek_user->row();
    if ($password1 == $data->fld_userpass) {
      $this->db->query("update tbl_user set fld_userpass = '$password2' where fld_userid = '$userid' ");
    } else {
      $this->db->query("update tbl_user set fld_userpass = '$data->fld_userpass' where fld_userid = '$userid' ");
    }
  }
  function changePasswordnew() {
    $userid=$_REQUEST['userid'];
    $password2=$_REQUEST['password2'];
    $password2 = ":-)" . MD5($password2);
      $this->db->query("update tbl_user set fld_userpass = '$password2' where fld_userid = '$userid' ");
    // echo "update tbl_user set fld_userpass = '$password2' where fld_userid = '$userid' ";
  }
  function insertEFaktur () {
    $fld_btid  =  $this->uri->segment(3);
    $fld_btidp = $this->uri->segment(4);
    #$cont_size = $this->uri->segment(5);
    $this->db->query("insert into tbl_btd_faktur (fld_btidp,fld_btreffid) select  $fld_btid,$fld_btidp from tbl_btd_faktur limit 1 ");
    echo '<script>history.go(-1)</script>';

  }

  function print_po() {
    $fld_btid =  $this->uri->segment(3);
    $print_po = $this->ffis->printPO($fld_btid);
  }

  function print_settlement() {
    $fld_btid =  $this->uri->segment(3);
    $print_settlement = $this->ffis->printSettlement($fld_btid);
  }

  function print_quo() {
    $fld_btid =  $this->uri->segment(3);
    $print_quo = $this->ffis->printQUO($fld_btid);
  }

  function print_advPrc2() {
  $fld_btid =  $this->uri->segment(3);
  $print_advPrc = $this->ffis->print_advPrc2($fld_btid);
  }

  function print_advPrc() {
  $fld_btid =  $this->uri->segment(3);
  $print_advPrc = $this->ffis->print_advPrc($fld_btid);
  }
  function exportJO_home (){
 # $jonb =$this->uri->segment(3);
  $jonb = $this->input->GET(jo);
  $exportJO_home = $this->ffis->exportJO_home($fld_btid,$jonb);
  }

  function print_surat_kuasa() {
    $fld_btid =  $this->uri->segment(3);
    $print_pr = $this->ffis->printSK($fld_btid);
  }
  function printSubmitDlv(){
    $fld_btid = $this->uri->segment(3);
    $print = $this->ffis->printSubmitDlv($fld_btid);
  }
 function printCommissionA() {
    $fld_btid =  $this->uri->segment(3);
    $print = $this->ffis->printCommissionA($fld_btid);
  }
  function exportCommission50() {
    $fld_btid =  $this->uri->segment(3);
    $export = $this->ffis->exportCommission50($fld_btid);
  }
  function exportCommission51() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->exportCommission51($node,$_POST['fld_btflag']);
  }
 function printCommissionB() {
    $fld_btid =  $this->uri->segment(3);
    $print = $this->ffis->printCommissionB($fld_btid);
  }
 function printCommission2() {
    $fld_btid =  $this->uri->segment(3);
    $print = $this->ffis->printCommission2($fld_btid);
  }
 function printCommission3() {
    $fld_btid =  $this->uri->segment(3);
    $print = $this->ffis->printCommission3($fld_btid);
  }


  function print_advaprv() {
    $fld_btid =  $this->uri->segment(3);
    $print_wo = $this->ffis->printAdvaprv($fld_btid);
  }

  function print_dpr() {
    $fld_btid =  $this->uri->segment(3);
    $print_dpr = $this->ffis->printDPR($fld_btid);
  }
  function printDOTrailers()
  {
    $fld_btid =  $this->uri->segment(3);
    $printDOTrailers = $this->ffis->printDOTrailers($fld_btid);
  }
  function printDOTruck() {
    $fld_btid =  $this->uri->segment(3);
    $printDOTruck = $this->ffis->printDOTruck($fld_btid);
  }
/*
  function printCommission() {
    $node =  $this->uri->segment(3);
    $printCommission = $this->ffis->printCommission($node,$this->session->userdata('location'));
  }

  function printCommission2() {
    $node =  $this->uri->segment(3);
    $printCommission = $this->ffis->printCommission2($node,$this->session->userdata('location'));
  }*/

  function exportSettlement() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->exportSettlement($node);
  }

  function exportSettlement2() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->exportSettlement2($node);
  }

  function exportInvoiceFaktur() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->exportInvoiceFaktur($node);
  }

  function exportInvoiceFaktur2() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->exportInvoiceFaktur2($node);
  }

  function AddCOfromSPV() {
    $node =  $this->uri->segment(3);
    $add_co = $this->ffis->AddCOfromSPV($node);
  }

 function AddCRTfromSPV() {
    $node =  $this->uri->segment(3);
    $add_crt = $this->ffis->AddCRTfromSPV($node);
  }


  function AddBOfromSPV() {
    $node =  $this->uri->segment(3);
    $add_bo = $this->ffis->AddBOfromSPV($node);

  }

 function AddBOfromAPV() {
    $node =  $this->uri->segment(3);
    $add_bo = $this->ffis->AddBOfromAPV($node);

  }

  function AddCOfromCA() {
    $node =  $this->uri->segment(3);
    $add_co_ca = $this->ffis->AddCOfromCA($node);
  }

  function AddCOfromCOP() {
    $node =  $this->uri->segment(3);
    $add_co_cop = $this->ffis->AddCOfromCOP($node);
  }

  function AddCIJfromCOJ() {
    $node =  $this->uri->segment(3);
    $add_cij = $this->ffis->AddCIJfromCOJ($node);
  }

  function AddCAfromJOExp() {
    $node = $this->uri->segment(3);
    $add_ca_jo = $this->ffis->AddCAfromJOExp($node);
  }

  function AddGJLfromSPV() {
    $node =  $this->uri->segment(3);
    $add_gjl = $this->ffis->AddGJLfromSPV($node);
  }

  function AddGJLfromAPV() {
    $node =  $this->uri->segment(3);
    $add_gjl_apv = $this->ffis->AddGJLfromAPV($node);
  }

  function AddGJLfromINV() {
    $node =  $this->uri->segment(3);
    $add_gjl_inv = $this->ffis->AddGJLfromINV($node);
  }

  function AddGJLfromINV1() {
    $node =  $this->uri->segment(3);
    $add_gjl_inv = $this->ffis->AddGJLfromINV1($node);
  }

  function AddGJLfromCOP() {
    $node =  $this->uri->segment(3);
    $add_gjl_cop = $this->ffis->AddGJLfromCOP($node);
  }

  function AddGJLfromBPS() {
    $node =  $this->uri->segment(3);
    $add_gjl_bps = $this->ffis->AddGJLfromBPS($node);
  }

  function CreateTPKfromPDS() {
    $node =  $this->uri->segment(3);
    $status = $this->db->query("select fld_btstat from tbl_bth where fld_btid='$node'")->row()->fld_btstat;
    $create_tpk = $this->ffis->CreateTPKfromPDS($node,$status);
  }

  function CreateSPVfromAPV() {
    $node =  $this->uri->segment(3);
    $status = $this->db->query("select fld_btstat from tbl_bth where fld_btid='$node'")->row()->fld_btstat;
    $create_spv = $this->ffis->CreateSPVfromAPV($node,$status);
  }

  function CreateJSTfromJOC() {
    $node =  $this->uri->segment(3);
    $status = $this->db->query("select fld_btstat from tbl_bth where fld_btid='$node'")->row()->fld_btstat;
    $create_jst = $this->ffis->CreateJSTfromJOC($node,$status);
  }

  function CreateJSTfromJOCDE() {
    $node =  $this->uri->segment(3);
    $status = $this->db->query("select fld_btstat from tbl_bth where fld_btid='$node'")->row()->fld_btstat;
    $create_jst = $this->ffis->CreateJSTfromJOCDE($node,$status);
  }

   //tambahanjuli
    function exportDeduction()
  {
    $fld_btid =  $this->uri->segment(3);
    //echo $fld_btid;
    $exportDeduction = $this->ffis->exportDeduction($fld_btid);
  }

  function apvReprocess() {
    $node =  $this->uri->segment(3);
    $status = $this->db->query("select fld_btstat from tbl_bth where fld_btid='$node'")->row()->fld_btstat;
    $apvReprocess = $this->ffis->apvReprocess($node,$status);
  }


  function completePODTrailer ($fld_btid) {
    $this->ffis->cekCashApproval($fld_btid);
    $this->db->query("update tbl_bth set fld_btstat=3 where fld_btid=$fld_btid limit 1");
    $url = base_url() . "index.php/page/view/78000RETURN_DO_TRAILER_UNCOMPLETE";
    redirect($url);
  }

  function CreateCTDfromAPV() {
    $node =  $this->uri->segment(3);
    $create_ctd = $this->ffis->CreateCTDfromAPV($node);
  }

  function CreateRSFfromSHI() {
    $node =  $this->uri->segment(3);
    $create_rsf = $this->ffis->CreateRSFfromSHI($node);
  }

  function AddRSCfromCTD() {
    $node =  $this->uri->segment(3);
    $add_rsc = $this->ffis->AddRSCfromCTD($node);
  }

  function EditBookTruck ($fld_btid) {
    $this->db->query("update tbl_btd_truck set fld_btp07=1 where fld_btid=$fld_btid limit 1");
    $url = base_url() . "index.php/page/form/78000BOTRUCK_DETAILEXP_INFO/edit/$fld_btid";
    redirect($url);
  }


  function CancelCA() {
    $node =  $this->uri->segment(3);
    $cancel_ca = $this->ffis->CancelCA($node);
  }



  function CloseJO() {
    $node =  $this->uri->segment(3);
    $close_jo = $this->ffis->CloseJO($node);
  }

  function OpenJO() {
    $node =  $this->uri->segment(3);
    $open_jo = $this->ffis->OpenJO($node);
  }
/*
  function UnlockAdvanceSIJ() {
    $node =  $this->uri->segment(3);
    $unlock_sij = $this->ffis->UnlockAdvanceSIJ($node);
  }
*/
  function CancelSRC() {
    $node =  $this->uri->segment(3);
    $cancel_src = $this->ffis->CancelSRC($node);
  }

  function addAdv() {
  $node = $this->uri->segment(3);
  $addAdv =$this->ffis->addAdv($node);
  }


  function aprvBosb() {
    $node = $this->uri->segment(3);
    $aprv_Bosb = $this->ffis->aprvBosb($node);
  }



  function UpdateDepositDate() {
    $node =  $this->uri->segment(3);
    $update_deposit = $this->ffis->UpdateDepositDate($node);
  }

  function deleteSettlement() {
    $node =  $this->uri->segment(3);
    $node2 = $this->uri->segment(4);
    $this->db->query("delete from tbl_trk_settlement where fld_trk_settlementid = '$node' limit 1");
     echo '<script>history.go(-1)</script>';
  }

  function deleteFakturInvoice() {
    $node =  $this->uri->segment(3);
    $node2 = $this->uri->segment(4);
    $this->db->query("update tbl_btd_faktur set fld_btreffid='',fld_flagid=''where fld_btid = '$node' limit 1");
     echo '<script>history.go(-1)</script>';
  }

  function deleteHOInvoice() {
    $node =  $this->uri->segment(3);
    $node2 = $this->uri->segment(4);
    $this->db->query("delete from tbl_btd_faktur where fld_btid = '$node' limit 1");
     echo '<script>history.go(-1)</script>';
  }

   function deleteCommission() {
    $node =  $this->uri->segment(3);
    $node2 = $this->uri->segment(4);
    $this->db->query("delete from tbl_commission where fld_commissionid = '$node' limit 1");
     echo '<script>history.go(-1)</script>';
  }

  function message($message) {
    $display_string = $message;
    echo $display_string;
  }

 function message_out() {
    $location = $this->session->userdata('location');
    $booknum = $this->input->get('fld_btnoreff');
    $cek = $this->db->query("
    select
    count(1) 'count',
    t0.fld_btnoreff
    from tbl_bth t0
    where
    t0.fld_bttyid=18
    and
    t0.fld_btnoreff='$booknum'
    and t0.fld_btp50=$location
    ");
    $data = $cek->row();
      $display_string = "<b>Keluar yang ke $data->count dari Booking Nomer  $data->fld_btnoreff  !!!! </b>";
      echo $display_string;
  }

  function truckBillingOLD() {
   $node = $_POST["node"];
   echo "id=$node";
   exit();
   # print_r($_POST);
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $costumer_text = $_POST["fld_btp021"];
   /* $trans_no = $this->mkautono(1,26);
    $this->db->query("insert into tbl_bth
                      (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidc,fld_btamt,fld_btp01,fld_btp02,fld_baidp,fld_btiid)
                      values
		      (1,'$trans_no',26,now(),'$customer',0,'$costumer_text','old','$fld_baidp',1)");
    $last_insert_id = $this->db->insert_id();*/
    $tot_billing = 0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $do_number = $_POST["fld_btno$x"];
        $sell_price = $_POST["fld_btamt$x"];
        $bl_number = $_POST["fld_btp01$x"];
        $customer = $_POST["fld_btp02$x"];
        $container = $_POST["fld_container$x"];
    #    echo "The number is: $x <br>";
        $tot_billing = $tot_billing + $sell_price;
        /*$this->db->query("insert ignore into tbl_trk_billing
                      (fld_btidp,fld_btno,fld_btamt01,fld_btp01,fld_btp02,fld_btcmt,fld_container)
                      values
                      ($last_insert_id ,'$do_number',$sell_price,'$bl_number','$customer','old','$container')");
      */ }
    }
 # $this->db->query("update tbl_bth set fld_btamt=$tot_billing where fld_btid=$last_insert_id limit 1");
  $url = base_url() . "index.php/page/form/78000TRUCKING_BILLING/edit/$last_insert_id?act=edit";
  redirect($url);
  }

  function truckBilling() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $costumer_text = $_POST["fld_btp021"];
    $node =  $_POST["node"];
    $tot_billing = 0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $do_number = $_POST["fld_btno$x"];
        $sell_price = $_POST["price$x"];
        $bl_number = $_POST["fld_btp01$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $container = $_POST["fld_container$x"];
        $loading = $_POST["loading$x"];
        $overnight = $_POST["overnight$x"];
        echo "$do_number###<br>";
        $tot_billing = $tot_billing + $sell_price;
        $this->db->query("insert ignore into tbl_trk_billing
        (fld_btidp,fld_btno,fld_btdt,fld_btamt01,fld_btreffid,fld_btamt04,fld_btp05)
        values
        ($node ,'$do_number',CURDATE(),$sell_price,'$fld_btid','$loading','$overnight')");
      }
    }
    $this->db->query("update tbl_bth set fld_btamt=$tot_billing where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000TRUCKING_BILLING/edit/$node?act=edit";
    redirect($url);
  }

   function InvAging() {
    #echo "Please Contact IT Administrator...";
 #exit();
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $trans_no = $this->mkautono(2,41);
    $z =0;
    for ($x=1; $x<=$count; $x++){
      $customer = $_POST["Customer$x"];
      $aging = $_POST["aging$x"];
      $id = $_POST["id$x"];
      $fgid = $_POST["fgid$x"];
      if($_POST["rowdata$x"] == "on") {
           $z = $z+1;
         }
      }
    if ($z == 0){
     echo "<div align='center'>
         No Data Selected <br>  click <a href='javascript:history.back();refresh();'>here</a> to go back </div>";
     }else {

   if($fgid ==2){
    $this->db->query("delete t0.* from tbl_btd_finance t0 left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp  where t0.fld_btidp = $id and t1.fld_bttyid = 41 and t1.fld_btp38 = 2 and t1.fld_btstat !=3 ");
    $last_insert_id = $id;

    }else {

    if($fgid == 3){
    $last_insert_id = $id;
    }else {
    $this->db->query("insert into tbl_bth
                      (fld_baido,fld_btstat,fld_btno,fld_bttyid,fld_btdt,fld_baidc,fld_baidp,fld_btp38,fld_btdesc,fld_btloc)
                      values
                      (2,1,'$trans_no',41,now(),'$customer','$fld_baidp',2,'DENDA KETERLAMBATAN PEMBAYARAN','$location')");
    $last_insert_id = $this->db->insert_id();
    }
   }

    $tot_amount = 0;

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $amount = $_POST["Penalty$x"];
        $btno = $_POST["fld_btno$x"];
        $ttl = $_POST["Penalty$x"];
        $payLeft = $_POST["LeftPayment$x"];
        $tot_amount = $tot_amount + $amount;
        $z =$z+1;
        $this->db->query("insert ignore into tbl_btd_finance
                      (fld_btidp,fld_btqty01,fld_btuamt01,fld_btamt01,fld_btnoreff,fld_btreffid,fld_btdesc,fld_coaid,fld_btp08,fld_btp02)
                      values
                      ($last_insert_id,1,$amount,$ttl,'$btno','$fld_btid','DENDA KETERLAMBATAN PEMBAYARAN ',793,'$aging','$payLeft')");
      }
    }
  if($fgid == 3){
  $this->db->query("update tbl_bth set fld_btp38 = 2 where fld_btid ='$id' and fld_bttyid =41 and fld_btp38 = 3 limit 1");
  }
  $this->db->query("update tbl_bth set fld_btamt=$tot_amount where fld_btid=$last_insert_id limit 1");
  $url = base_url() . "index.php/page/form/78000INVOICE/edit/$last_insert_id?act=edit";
  redirect($url);
    }
  }
	function importPIB($id)
	{
		 $this->ffis->ImportJobOrder($id);
		 redirect('page/form/78000JOB_ORDER_IMP/edit/'.$id.'', refresh);
	}

  function importTPBOnline() {
    $fld_btid =  $this->uri->segment(3);
    $aju = $this->db->query("select fld_btnoalt from tbl_bth where fld_btid = $fld_btid limit 1")->row();
    $conn = mysql_connect("172.17.1.16", "dunex", "kurama");
    mysql_select_db( 'tpbdb_dunex' );
    // Check connection
    if(! $conn ) {
            die('Could not connect: ' . mysql_error());
         }
    $resh = mysql_query("select * from tpb_header where Nomor_Aju like '%$aju->fld_btnoalt%' order by ID desc limit 1 ");

    $datah = mysql_fetch_row($resh);
    $id = $datah['0'];
    $fld_btnoalt = $datah['100'];
    $fld_btp03 = $datah['91'];
    $fld_btp04 = $datah['110'];
    $fld_btp17 = $datah['126'];
    $fld_btp29 = $datah['73'];
    $fld_baidc = $datah['89'];
    $fld_btp01 = $datah['72'];
    $resc = mysql_query("select * from tpb_kontainer where ID_HEADER = '$id'");
    $resd = mysql_query("select * from tpb_dokumen where ID_HEADER = '$id'");

    ### Add Container Detail
    while($row1 =  mysql_fetch_array($resc)) {
      $fld_container = $row1['NOMOR_KONTAINER'];
      $fld_size = $row1['KODE_UKURAN_KONTAINER'];
      $fld_vehicle = $row1['NO_POLISI'];
      $fld_conttype = $row1['KODE_TIPE_KONTAINER'];
      $this->db->query("insert ignore into tbl_btd_container (fld_contnum,fld_btidp,fld_contsize,fld_btp04,fld_conttype) values ('$fld_container','$fld_btid','$fld_size','$fld_vehicle','$fld_conttype')");

    }
    ### Add Document Detail
    while($row2 =  mysql_fetch_array($resd)) {
      $fld_docnum = $row2['NOMOR_DOKUMEN'];
      $fld_docdt = $row2['TANGGAL_DOKUMEN'];
      $fld_doctype = $row2['KODE_JENIS_DOKUMEN'];
      $this->db->query("insert ignore into tbl_btd_document (fld_docnum,fld_btidp,fld_docdt,fld_doctype)
                       values ('$fld_docnum','$fld_btid','$fld_docdt','$fld_doctype')");

    }

    $this->db->query("update tbl_bth set fld_btnoalt = '$fld_btnoalt',fld_btp03 = '$fld_btp03', fld_btp04 = '$fld_btp04', fld_btp17 = '$fld_btp17',fld_btp01 = '$fld_btp01' where fld_btid = $fld_btid limit 1");

     $url = base_url() . "index.php/page/form/78000JOB_ORDER_IMP/edit/$fld_btid?act=edit";
  redirect($url);

  }
  function deleteJOeXim(){
     $group = $this->session->userdata('group');
     if($group == 36 || $group == 6){
      $this->delete_process();
    }
     $this->ffis->message("Access denied !! ");
    }

  function outstandingDLV($btid) {

$ctid= $this->session->userdata('ctid');
$ctnm = $this->session->userdata('ctnm');
$groupadd = $this->session->userdata('group_add');

/*if($groupadd != 15){
$this->intranet->message("You Cannot Have Acces To Insert Document");
}*/

$count = $_POST["count"];
     $node =  $_POST["node"];
     $date = date('Y-m-d');
     $flag = 19;
     for ($x=1; $x<=$count; $x++){
       if($_POST["rowdata$x"] == "on") {
         $fld_btnoreff = $_POST["fld_btno$x"];
         $fld_btreffid = $_POST["fld_btid$x"];

         $customer = $_POST["cust$x"];
         $attn = $_POST["attn$x"];
         $this->db->query("insert into tbl_btd_finance
               (fld_btidp,fld_btreffid,fld_bedivid,fld_btnoreff,fld_btcmt,fld_btdesc)
               values
               ('$node' ,'$fld_btreffid','$flag','$fld_btnoreff','$attn','$customer')");
       }
     }
     $url = base_url() . "index.php/page/form/78000SUBMIT_DELIVERY/edit/$node";
     redirect($url);
   }
   function recieveDLV($btid) {

$ctid= $this->session->userdata('ctid');
$ctnm = $this->session->userdata('ctnm');
$groupadd = $this->session->userdata('group_add');

/*if($groupadd != 15){
$this->intranet->message("You Cannot Have Acces To Insert Document");
}*/

$count = $_POST["count"];
     $node =  $_POST["node"];
     $date = date('Y-m-d');
     $flag = 20;
     for ($x=1; $x<=$count; $x++){
       if($_POST["rowdata$x"] == "on") {
         $fld_btnoreff = $_POST["fld_btno$x"];
         $fld_btreffid02 = $_POST["fld_btid$x"];
         $fld_btnoreff02 = $_POST["fld_btnoreff$x"];
         $customer = $_POST["cust$x"];
         $attn = $_POST["attn$x"];
         $this->db->query("insert into tbl_btd_finance
               (fld_btidp,fld_btreffid2,fld_bedivid,fld_btnoreff,fld_btnoreff02,fld_btcmt,fld_btdesc)
               values
               ('$node' ,'$fld_btreffid02','$flag','$fld_btnoreff','$fld_btnoreff02','$attn','$customer')");
       }
     }
     $url = base_url() . "index.php/page/form/78000RECIEVE_INVOICE/edit/$node";
     redirect($url);
   }
   //automaticBPJS
   function automaticDriverBpjs($fld_btid){
     //echo $fld_btid;
     $fld_btp52 = $this->session->userdata('ctid');
     $driver = $this->db->query("  select     t1.fld_empid, t2.fld_btp53,t2.fld_btp52,
                                  ifnull(t5.in,0) 'in',
                                  IFNULL(SUM(t6.fld_btamt01), 0) 'out',
                                  t4.fld_empnm 'postBy',
                                  t3.fld_empnm,
                                  t0.fld_driverid,
                                  t2.fld_btp01 'vehicle',
                                  sum(t0.fld_commission) 'komisi'
                                  from tbl_commission t0
                                  left join tbl_driver t1 on t1.fld_empid = t0.fld_empid and t1.fld_driverp09 = 1
                                  left join tbl_bth t2 on t2.fld_btid = t0.fld_postingid
                                  left join hris.tbl_truck_driver t3 on t3.fld_empid = t1.fld_empid
                                  left join hris.tbl_emp t4 on t4.fld_empid = t2.fld_btp52
                                  INNER JOIN (
                                   SELECT fld_empid, SUM(fld_btbalance) 'in'
                                      FROM tbl_bth WHERE fld_bttyid = 119
                                      GROUP BY fld_empid
                                   ) t5 ON t5.fld_empid = t1.fld_empid
                                 left JOIN tbl_btd_driver_insurance t6 ON t6.fld_driverid = t1.fld_driverid AND t6.fld_btflag = 8
                                  where
                                  t0.fld_postingid = $fld_btid
                                  group by t0.fld_empid
                                  ORDER BY t3.fld_empnm");
       if($driver->row()->fld_btp53 == 0){
         $update = 0;
       foreach ($driver->result() as $rdriver) {
         // code...
         $in = $rdriver->in;
         $out = $rdriver->out;
         $bayar = $in - $out;
         if($bayar > 0){
           if($rdriver->komisi < 100000 && $rdriver->komisi < $bayar){
                 $bayar = $rdriver->komisi;
           }
           if($bayar >= 100000){
             $bayar = 100000;
           }
         }else{
           $bayar = 0;
         }

         if ($rdriver->vehicle == 1) {
           $this->db->query("insert ignore into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid) values ('$fld_btid',8,'$rdriver->fld_driverid','$rdriver->fld_empid')");
           $id78 = $this->db->insert_id();
           $this->db->query("insert into tbl_btd_driver_insurance (fld_btreffid,fld_btflag,fld_driverid,fld_empid,fld_btamt01) values ('$id78',8,'$rdriver->fld_driverid','$rdriver->fld_empid',
                                      '$bayar')");
        // echo "masuk input " . $id78 . $rdriver->fld_empid ."<br>";
         }else{
           $this->db->query("insert ignore into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid,fld_btamt01) values ('$fld_btid',8,'$rdriver->fld_driverid','$rdriver->fld_empid','$bayar')");
         }

         $update++;
       }
       $this->db->query("update tbl_bth set fld_btp53 = '1', fld_btp52 = '$fld_btp52' where fld_btid='$fld_btid'");

       if ($rdriver->vehicle == 1) {
         $url = base_url() . "index.php/page/form/78000POSTING_COMMISSION_TRAILER/edit/$fld_btid";
       } else {
         $url = base_url() . "index.php/page/form/78000POSTING_COMMISSION/edit/$fld_btid";
       }
       if($update == count($driver->result())){
         $pesan = "Transaction is successful Updated !!";
         echo "<script type='text/javascript'>alert('$pesan');
                     window.location.href = '$url';
                     </script>";
         //echo "selesai input";
       }

       // redirect($url);
       }else{
         $by = $driver->row()->postBy;
         $this->dnxapps->message("This function is already used by $by");
       }
   }
     //get_repot_bpjs
     function getReportBpjs($id_driver = null, $type = 1){
       if($id_driver == null){
         $query_header = $this->db->query("
         SELECT t0.fld_empid,
               t1a.fld_tyvalnm 'branch',
           t4.fld_empnm 'driver_name',
           t31.fld_tyvalnm 'dirver_stat',
           t11.fld_btinm 'job_role',
           IFNULL(sum(t2.fld_btbalance),0) 'in'
           FROM tbl_driver t0
           left JOIN tbl_bth t2 ON t2.fld_empid = t0.fld_empid AND t2.fld_bttyid = 119
           inner join hris.tbl_truck_driver t4 ON t4.fld_empid = t0.fld_empid
           left join hris.tbl_tyval t31 on t31.fld_tyvalcd = t4.fld_empstat and t31.fld_tyid = 20
           left join hris.tbl_bti t11 on t11.fld_btiid = t4.fld_empjob
           left join tbl_tyval t1a on t1a.fld_tyvalcd=t0.fld_driverloc and t1a.fld_tyid=21
           #WHERE t0.fld_driverp09 = 1
           GROUP BY t0.fld_empid
           ORDER BY t4.fld_empnm asc")->result();
           $data_detail = array();
           $data_master = array();

           foreach ($query_header as $key=>$value) {
             // code...
             $tot_in = 0;
             $tot_out = 0;
             $tot_blnc = 0;
             $query_out = $this->db->query("
             SELECT ifnull(sum(i2.fld_btamt01),0) 'out', i.fld_empid
             FROM tbl_btd_driver_insurance i
             left join tbl_btd_driver_insurance i2 on i2.fld_btreffid = i.fld_btid
             WHERE i2.fld_btflag = 8 and i.fld_empid = $value->fld_empid
             and i2.fld_btreffid > 0
             GROUP BY i.fld_empid")->result();
             if($value->in > 0){
               $tot_in = $tot_in + $value->in;
             }
             foreach ($query_out as $rout) {
               // code...
               if($rout->out > 0){
                 $tot_out = $tot_out + $rout->out;
               }
             }

             $tot_blnc = $tot_out - $tot_out;
             $commisiion_no = "-";
             if($value->commisiion_no !== null){
               $commisiion_no = $value->commisiion_no;
             }
             $key_group = $value->fld_empid;

             if($tot_out > 0 ){
               $data_master[] = [
                 "driver_name" => $value->driver_name,
                 "branch" => $value->branch,
                 "dirver_stat" => $value->dirver_stat,
                 "job_role" => $value->job_role,
                 "in" => $tot_out,
                 "out" => $tot_out,
                 "balance" =>number_format($tot_blnc, 0, ',', ',') ,
                 "driver_id" => $key_group,
               ];
             }

           }
             $result["dataMaster"]=$data_master;
             echo json_encode($result);
       }
       //type1 = in(bpjs deduction payment)
       if($type == 12 && $id_driver !== null){
         $query_in = $this->db->query("
         SELECT t0.fld_empid,
   			t2.fld_btno 'bpjs_tr_no',
     	      t1a.fld_tyvalnm 'branch',
   			t4.fld_empnm 'driver_name',
   			t31.fld_tyvalnm 'dirver_stat',
   			t11.fld_btinm 'job_role',
   			#IFNULL(sum(t2.fld_btbalance),0) 'in'
   			IFNULL(t2.fld_btbalance,0) 'in'
         FROM tbl_driver t0
         inner JOIN tbl_bth t2 ON t2.fld_empid = t0.fld_empid AND t2.fld_bttyid = 119
         left join hris.tbl_truck_driver t4 ON t4.fld_empid = t0.fld_empid
         left join hris.tbl_tyval t31 on t31.fld_tyvalcd = t4.fld_empstat and t31.fld_tyid = 20
         left join hris.tbl_bti t11 on t11.fld_btiid = t4.fld_empjob
   		  left join tbl_tyval t1a on t1a.fld_tyvalcd=t0.fld_driverloc and t1a.fld_tyid=21
         WHERE t0.fld_driverp09 = 1
         and t0.fld_empid = $id_driver
         #GROUP BY t0.fld_driverid
         ORDER BY t4.fld_empnm asc
         ")->result();
         $items = array();
         $rowno=0;
         foreach ($query_in as $key => $value) {
           // code...
           $items[$rowno]->id_driver=$value->fld_empid;
           $items[$rowno]->bpjs_tr_no=$value->bpjs_tr_no;
           $items[$rowno]->branch=$value->branch;
           $items[$rowno]->driver_name=$value->driver_name;
           $items[$rowno]->dirver_stat=$value->dirver_stat;
           $items[$rowno]->job_role=$value->job_role;
           $items[$rowno]->in=number_format($value->in, 0, ',', ',');
           $rowno++;
         }
         $result["data"]=$items;
         echo json_encode($result);
       }elseif(($type == 2 || $type == 1)&& $id_driver !== null ){
         $query_out_det = $this->db->query("
         SELECT sum(i2.fld_btamt01) 'out',i.fld_empid, t1a.fld_tyvalnm 'branch', e.fld_empnm 'driver_name',
     t31.fld_tyvalnm 'dirver_stat',
     			t11.fld_btinm 'job_role',
     i.fld_driverid, b.fld_btno 'commission_no' FROM tbl_btd_driver_insurance i

     left join tbl_btd_driver_insurance i2 on i2.fld_btreffid = i.fld_btid
     LEFT JOIN tbl_bth b ON b.fld_btid = i.fld_btidp
     LEFT JOIN tbl_driver d ON d.fld_empid = i.fld_empid
     LEFT JOIN hris.tbl_truck_driver e ON e.fld_empid = d.fld_empid
     left join hris.tbl_tyval t31 on t31.fld_tyvalcd = e.fld_empstat and t31.fld_tyid = 20
     left join hris.tbl_bti t11 on t11.fld_btiid = e.fld_empjob
     left join tbl_tyval t1a on t1a.fld_tyvalcd=d.fld_driverloc and t1a.fld_tyid=21
     WHERE i2.fld_btflag = 8
     and i.fld_empid = $id_driver
     GROUP BY i2.fld_empid
         ")->result();
         $items = array();
         $rowno=0;
         foreach ($query_out_det as $key => $value) {
           // code...
           $items[$rowno]->id_driver=$value->fld_empid;
           if($type == 1){
             $items[$rowno]->bpjs_tr_no=$value->commission_no;
           }else{
             $items[$rowno]->commission_no=$value->commission_no;
           }
           $items[$rowno]->branch=$value->branch;
           $items[$rowno]->driver_name=$value->driver_name;
           $items[$rowno]->dirver_stat=$value->dirver_stat;
           $items[$rowno]->job_role=$value->job_role;
           if($type == 1){
           $items[$rowno]->in=number_format($value->out, 0, ',', ',');
           }else{
           $items[$rowno]->out=number_format($value->out, 0, ',', ',');
           }
           $rowno++;
         }
         $result["data"]=$items;
         echo json_encode($result);
       }
     }
 function automaticDriverInsurance() {
    $fld_btid =  $this->uri->segment(3);
    $driver = $this->db->query("select t1.fld_empid,
                                t3.fld_empnm,
                                t0.fld_driverid,
                                t1.fld_driverloan,
                                t1.fld_driverdeposit,
                                t2.fld_btp01 'vehicle',
                                PERIOD_DIFF(date_format(now(),'%Y%m'),if(t3.fld_empjoindt < date_format(now(),'%Y-01-01'),date_format(now(),'%Y01'),date_format(t3.fld_empjoindt,'%Y01'))) + 1 'qty',
                                t0.fld_commission,
                                if(t2.fld_btp01 in (1,3),
                                   if(sum(t0.fld_commission) >= 75000,75000,sum(t0.fld_commission))
                                     ,if(sum(t0.fld_commission) >= 100000,100000,sum(t0.fld_commission))
                                ) 'deduction'
                                from tbl_commission t0
                                left join tbl_driver t1 on t1.fld_driverid = t0.fld_driverid
                                left join tbl_bth t2 on t2.fld_btid = t0.fld_postingid
                                left join hris.tbl_truck_driver t3 on t3.fld_empid = t1.fld_empid
                                where
                                t0.fld_postingid = $fld_btid
                                and t1.fld_driverp08 = 1
                                and t0.fld_commission >= 30600
                                group by t0.fld_driverid

                                 ")->result();
 $premi = 30600;
    $month = 0;
    $nn = 0;
    foreach ($driver as $rdriver) {
      $month = $rdriver->qty;
      $nn = $premi * $month;
      if($rdriver->vehicle == 1) {
      $saldo =  $this->db->query("select sum(tz0.fld_btamt01) 'amount'
                                  from tbl_btd_driver_insurance tz0
                                  left join tbl_btd_driver_insurance tz1 on tz1.fld_btid = tz0.fld_btreffid
                                  left join tbl_bth tz2 on tz2.fld_btid = tz1.fld_btidp
                                  where
                                  tz0.fld_btflag = 1
                                  and tz0.fld_driverid = $rdriver->fld_driverid
                                  and date_format(tz2.fld_btdt,'%Y') = date_format(now(),'%Y')
                                 ")->row();
      $zz = $nn - $saldo->amount;
      if ($zz > 0) {
        echo "AAAA###$saldo->amount###$zz<br>";
         $this->db->query("insert ignore into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid) values ('$fld_btid',3,'$rdriver->fld_driverid','$rdriver->fld_empid')");
                   $id78 = $this->db->insert_id();
                   $this->db->query("insert into tbl_btd_driver_insurance (fld_btreffid,fld_btflag,fld_driverid,fld_empid,fld_btamt01) values ('$id78',1,'$rdriver->fld_driverid','$rdriver->fld_empid',
                                     '$zz')");
      }
    }
 }

    if($rdriver->vehicle == 1) {
      $url = base_url() . "index.php/page/form/78000POSTING_COMMISSION/edit/$fld_btid";
    } else {
       $url = base_url() . "index.php/page/form/78000POSTING_COMMISSION/edit/$fld_btid";
    }
    redirect($url);
  }

 function automaticDriverLoan() {
    $fld_btid =  $this->uri->segment(3);
    $driver = $this->db->query("select t1.fld_empid,
                                t0.fld_driverid,
                                t1.fld_driverloan,t1.fld_btp01,
                                t1.fld_driverdeposit,
			        t1.fld_driverinstallment 'cicilan',
                                if(t1.fld_driverdeposit between 1 and  1999999,1,0) 'deposit',
                                t2.fld_btp01 'vehicle',
                                t0.fld_commission,
                                if(t2.fld_btp01 in (1,3),
                                   if(sum(t0.fld_commission) >= 100000,100000,sum(t0.fld_commission))
                                     ,if(sum(t0.fld_commission) >= 100000,100000,sum(t0.fld_commission))
                                ) 'deduction',100000 'deduction2'
                                from tbl_commission t0
                                left join tbl_driver t1 on t1.fld_driverid = t0.fld_driverid
                                left join tbl_bth t2 on t2.fld_btid = t0.fld_postingid
                                where
                                t0.fld_postingid = $fld_btid
                                and (t1.fld_driverloan > 0 or (t1.fld_driverdeposit between 1 and  1999999))
                                and t0.fld_commission > 0
                                and if($fld_baidp = 315,1,t2.fld_btflag != 1)
                                group by t0.fld_driverid

                                 ")->result();
    ### Loan
    foreach ($driver as $rdriver) {
       if($rdriver->vehicle == 1 && $rdriver->fld_driverloan > 0 && $rdriver->fld_commission > 0) {
                   $this->db->query("insert ignore into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid) values ('$fld_btid',3,'$rdriver->fld_driverid','$rdriver->fld_empid')");
                   $id78 = $this->db->insert_id();
                   $this->db->query("insert into tbl_btd_driver_insurance (fld_btreffid,fld_btflag,fld_driverid,fld_empid,fld_btamt01) values ('$id78',3,'$rdriver->fld_driverid','$rdriver->fld_empid',
                                     if($rdriver->fld_driverloan >= $rdriver->deduction,$rdriver->deduction,$rdriver->fld_driverloan))");
      }
     /* if(($rdriver->vehicle == 2 || $rdriver->vehicle == 3) && $rdriver->fld_driverloan > 0) {
          $this->db->query("insert into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid,fld_btamt01) values ('$fld_btid',3,'$rdriver->fld_driverid','$rdriver->fld_empid',
                            if($rdriver->fld_driverloan >= $rdriver->deduction,$rdriver->deduction,$rdriver->fld_driverloan))");

      }*/
    }

    ### Deposit
    foreach ($driver as $rdriver) {
      if($rdriver->vehicle == 1 &&  $rdriver->fld_btp01 == 1 && $rdriver->fld_driverdeposit < 2000000 && $rdriver->fld_commission > 0) {
                   $this->db->query("insert ignore into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid) values ('$fld_btid',2,'$rdriver->fld_driverid','$rdriver->fld_empid')");
                   $id78 = $this->db->insert_id();
                   $this->db->query("insert into tbl_btd_driver_insurance (fld_btreffid,fld_btflag,fld_driverid,fld_empid,fld_btamt01) values ('$id78',2,'$rdriver->fld_driverid','$rdriver->fld_empid',
                                     50000)");
      }

   /*   if($rdriver->vehicle != 1 && $rdriver->fld_driverdeposit > 0 && $rdriver->fld_driverdeposit < 2000000) {
          $this->db->query("insert into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid,fld_btamt01) values ('$fld_btid',2,'$rdriver->fld_driverid','$rdriver->fld_empid',
                            50000)");

      }*/


    }

     #### installment

  foreach ($driver as $rdriver) {
       if($rdriver->vehicle == 1 && $rdriver->cicilan > 0) {
          $this->db->query("insert ignore into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid) values ('$fld_btid',5,'$rdriver->fld_driverid','$rdriver->fld_empid')");
          $id78 = $this->db->insert_id();
          $this->db->query("insert into tbl_btd_driver_insurance (fld_btreffid,fld_btflag,fld_driverid,fld_empid,fld_btamt01) values ('$id78',5,'$rdriver->fld_driverid','$rdriver->fld_empid',
                                     if($rdriver->cicilan >= $rdriver->deduction2,$rdriver->deduction2,$rdriver->cicilan))");

       }
       /*if(($rdriver->vehicle == 2 || $rdriver->vehicle == 3) && $rdriver->cicilan > 0) {
          $this->db->query("insert into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid,fld_btamt01,fld_bt02) values ('$fld_btid',5,'$rdriver->fld_driverid','$rdriver->fld_empid',
                            if($rdriver->cicilan >= $rdriver->deduction2,$rdriver->deduction2,$rdriver->cicilan),1)");

      }*/

   }

$this->db->query("update tbl_bth set fld_btflag = 1 where fld_btid = $fld_btid limit 1");
    $url = base_url() . "index.php/page/form/78000POSTING_COMMISSION/edit/$fld_btid";
    redirect($url);
  }

  function automaticDriverInstallment() {
    $fld_btid =  $this->uri->segment(3);
    $fld_baidp = $this->session->userdata('ctid');
    $driver = $this->db->query("select t1.fld_empid,
                                t0.fld_driverid,
                                t1.fld_driverloan,t1.fld_btp01,
                                t1.fld_driverdeposit,
                                t1.fld_driverinstallment 'cicilan',
                                if(t1.fld_driverdeposit between 1 and  1999999,1,0) 'deposit',
                                t2.fld_btp01 'vehicle',
                                t0.fld_commission,
                                if(t2.fld_btp01 in (1,3),
                                   if(sum(t0.fld_commission) >= 100000,100000,sum(t0.fld_commission))
                                     ,if(sum(t0.fld_commission) >= 100000,100000,sum(t0.fld_commission))
                                ) 'deduction',100000 'deduction2'
                                from tbl_commission t0

                                left join tbl_driver t1 on t1.fld_driverid = t0.fld_driverid
                                left join tbl_bth t2 on t2.fld_btid = t0.fld_postingid
                                where
                                t0.fld_postingid = $fld_btid
                                and t1.fld_driverinstallment > 0
                                and t0.fld_commission > 0
                                and if($fld_baidp = 99999999,1,t2.fld_btp04 != 1)
                                group by t0.fld_driverid

                                 ")->result();
   # installment
foreach ($driver as $rdriver) {
       if($rdriver->vehicle == 1 && $rdriver->cicilan > 0) {
          $this->db->query("insert ignore into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid) values ('$fld_btid',5,'$rdriver->fld_driverid','$rdriver->fld_empid')");
          $id78 = $this->db->insert_id();
          $this->db->query("insert into tbl_btd_driver_insurance (fld_btreffid,fld_btflag,fld_driverid,fld_empid,fld_btamt01) values ('$id78',5,'$rdriver->fld_driverid','$rdriver->fld_empid',
                                     if($rdriver->cicilan >= $rdriver->deduction2,$rdriver->deduction2,$rdriver->cicilan))");

       }
       /*if(($rdriver->vehicle == 2 || $rdriver->vehicle == 3) && $rdriver->cicilan > 0) {
          $this->db->query("insert into tbl_btd_driver_insurance (fld_btidp,fld_btflag,fld_driverid,fld_empid,fld_btamt01,fld_bt02) values ('$fld_btid',5,'$rdriver->fld_driverid','$rdriver->fld_empid',
                            if($rdriver->cicilan >= $rdriver->deduction2,$rdriver->deduction2,$rdriver->cicilan),1)");

      }*/
 # echo "id$rdriver->fld_driverid,val=$rdriver->cicilan<br>";

   }
#exit();
$this->db->query("update tbl_bth set fld_btp04 = 1 where fld_btid = $fld_btid limit 1");
    $url = base_url() . "index.php/page/form/78000POSTING_COMMISSION/edit/$fld_btid";
    redirect($url);
  }


  function cetak_nota($id)
  {
     $this->ffis->CetakNota($id);
     //$this->wkhtmltopdf("CetakNota",$id);
     //$this->load->view('print/tes');
  }

   function exportPODSubmit () {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->exportPODSubmit($fld_btid);
  }

  function wkhtmltopdf($modul='',$id='')
  {
     require_once('system/shared/wkhtmltopdf.php');
     $pdf= new WkHtmlToPdf;
     //print "a";
     switch ($modul)
     {
	case "CetakNota":
		$url = base_url() . "index.php/page/cetak_nota/$id";
		//$pdf->addPage($url);
		//$pdf->addPage('http://localhost/dunex-exim/index.php/page/cetak_nota/161');
		$pdf->addPage('google.co.id');
	   break;

     }
     //print $modul;
     if(!$pdf->send('nota.pdf')) throw new Exception('Could not create PDF: '.$pdf->getError());
  }
  function CetakSettlement($id)
  {
    $this->ffis->CetakSettlement($id);
  }

  function print_do($id)
  {
    $this->ffis->CetakDO($id);
  }

  function print_inv() {
    $fld_btid =  $this->uri->segment(3);
    $print_inv = $this->ffis->printINV($fld_btid);
    // flag = 4 [Printed Date]
    $ctid = $this->session->userdata('ctid');
    $one_action = $this->db->query("select t0.fld_btp42 from tbl_bth t0 where t0.fld_bttyid = 41 and t0.fld_btid = $fld_btid limit 1")->row()->fld_btp42;
    if($one_action == 0){
      $this->db->query("insert into tbl_trans_log (fld_baidp,fld_btidp,fld_log_tyid,fld_btdesc,fld_btdt) value('$ctid','$fld_btid',4,'[msg-system] PRINTED',now())");
      $last_insert_id = $this->db->insert_id();
      $this->db->query("update tbl_bth set fld_btp42 ='$last_insert_id' where fld_btid = '$fld_btid'  limit 1");
    }
  }


  function print_inv_merge() {
    $fld_btid =  $this->uri->segment(3);
    $print_inv = $this->ffis->printINVMerge($fld_btid);
  }

function printMemoInvoice(){
    $fld_btid = $this->uri->segment(3);
    $printMemoInvoice = $this->ffis->printMemoInvoice($fld_btid);
  }

 function printCreditTerm(){
    $fld_btid = $this->uri->segment(3);
    $printCreditTerm = $this->ffis->printCreditTerm($fld_btid);
  }

function batchprint_inv() {
    $fld_btid =  $this->uri->segment(3);
    $batchprint_inv = $this->ffis->batchprint_inv($fld_btid);
  }
  function print_inv2() {
    $fld_btid =  $this->uri->segment(3);
    $print_inv2 = $this->ffis->printINV2($fld_btid);
  }

  function printInvoiceChemco() {
    $fld_btid =  $this->uri->segment(3);
    $printInvoiceChemco = $this->ffis->printInvoiceChemco($fld_btid);
  }

    function printInvoiceMolten(){
    $fld_btid = $this->uri->segment(3);
     $cust = 100;
    $printInvoiceMolten = $this->ffis->printInvoiceMolten($fld_btid,$cust);
  }

  function print_inv_mapi() {
    $fld_btid =  $this->uri->segment(3);
    $cust = 100;
    $print_inv = $this->ffis->printINV_mapi($fld_btid,$cust);
  }

  function print_journal() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->print_journal($node);
  }

 function print_inv_penalty(){
    $fld_btid =  $this->uri->segment(3);
    $print_inv_penalty = $this->ffis->exportInv_penalty($fld_btid);
  }

 function print_bo_journal() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->print_bo_journal($node);
  }

 function print_journal2() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->print_journal2($node);
  }
  function print_journal_spi() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->print_journal_spi($node);
  }

  function print_truck_billing() {
    $fld_btid =  $this->uri->segment(3);
    $src = 0;
    $this->ffis->printTruckBilling($fld_btid,$src);
  }

  // function ProsesImport() {
  //   require_once('system/shared/excel_reader2.php');
  //   $FormName=$this->uri->segment(3);
  //   $id=$this->uri->segment(4);
  //   switch ($FormName) {
  //     case "78000TRUCKING_BILLING":
  //     $this->ffis->ProsesImport("78000TRUCKING_BILLING",$id);
  //     $url = base_url() . "index.php/page/form/$FormName/edit/$id?act=edit";
  //     break;
  //   }
  //   redirect($url);
  // }


  //Penambahan Juli MemoInvoice
    function memoinvoice() {
    $node =  $_POST["node"];
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $spi_id = $_POST["fld_btid$x"];
        $this->db->query("insert into tbl_btd_post
              (fld_btidp,fld_btiid,fld_btflag)
              values
              ($node,$spi_id,120)");
      }
    }
    $url = base_url() . "index.php/page/form/78000MEMO_INVOICE/edit/$node?act=edit";
    redirect($url);

  }

  //Penambahan Juli MemoCreditTerm
    function creditTerm() {
    $node =  $_POST["node"];
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $spi_id = $_POST["fld_btid$x"];
        $this->db->query("insert into tbl_btd_post
              (fld_btidp,fld_btiid,fld_btflag)
              values
              ($node,$spi_id,124)");
      }
    }
    $url = base_url() . "index.php/page/form/78000MEMO_CREDIT_TERM/edit/$node?act=edit";
    redirect($url);

  }



  //Penambahan Juli BatchMolten
    function BatchMolten() {
    $node =  $_POST["node"];
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $spi_id = $_POST["fld_btid$x"];
        $this->db->query("insert into tbl_btd_post
              (fld_btidp,fld_btiid,fld_btflag)
              values
              ($node,$spi_id,121)");
      }
    }
    $url = base_url() . "index.php/page/form/78000BATCH_PRINT_MOLTEN/edit/$node?act=edit";
    redirect($url);

  }

   //Penambahan Juli BatchChemco
    function BatchChemco() {
    $node =  $_POST["node"];
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $spi_id = $_POST["fld_btid$x"];
        $this->db->query("insert into tbl_btd_post
              (fld_btidp,fld_btiid,fld_btflag)
              values
              ($node,$spi_id,122)");
      }
    }
    $url = base_url() . "index.php/page/form/78000BATCH_PRINT_CHEMCO/edit/$node?act=edit";
    redirect($url);

  }


  function ProsesImport() {
    require_once('system/shared/PHPExcel.php');
    require_once ('system/shared/PHPExcel/IOFactory.php');
    $FormName=$this->uri->segment(3);
    $id=$this->uri->segment(4);
    switch ($FormName) {
      case "78000TRUCKING_BILLING":
        $FileName=$this->db->query("select fld_btcmt from tbl_bth where fld_btid='$id'")->row()->fld_btcmt;
        $objPHPExcel = PHPExcel_IOFactory::load("upload/".$FileName."");
        $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $row = count($allDataInSheet);
        $total_price = 0;
        for ($i=2; $i<=$row; $i++) {
          $do=$allDataInSheet[$i]["B"];
          $wo = $this->db->query("select t0.fld_btid,t1.fld_btnoreff
                                  from
                                  tbl_btd t0
                                  left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
                                  where t1.fld_bttyid = 80
                                  and t1.fld_btnoalt='$do'
          ")->result();
          $price = $allDataInSheet[$i]["C"];
          $bl = $allDataInSheet[$i]["D"];
          foreach ($wo as $rwo) {
            $sql="insert into tbl_trk_billing (fld_btidp, fld_btreffid, fld_btamt01, fld_btno, fld_btp02)
                  value ($id,$rwo->fld_btid,$price,'$bl','$rwo->fld_btnoreff')";
            $query=$this->db->query($sql);
            $total_price = $total_price + $price;
          }
        }
        $this->db->query("update tbl_bth set fld_btamt = $total_price where fld_btid = $id limit 1");
        $url = base_url() . "index.php/page/form/$FormName/edit/$id?act=edit";
      break;
    }
    redirect($url);
  }


  function GetTruckingDO() {
    // $fld_btid =   $this->uri->segment(3);
    // $data = $this->db->query("select * from tbl_bth t0 where t0.fld_btid =  $fld_btid");
    // $data = $data->row();
    // $do = $this->db->query("select t0.fld_btnoalt,t1.fld_btno,t1.fld_btid,
    //                         t0.fld_btbalance,t0.fld_btp16,t0.fld_btnoreff
    //                         from tbl_bth t0
    //                         left join tbl_btd t1 on t1.fld_btidp = t0.fld_btid
    //                         left join tbl_trk_billing t2 on t2.fld_btreffid = t1.fld_btid
    //                         where
    //                         t0.fld_bttyid = 80
    //                         and t0.fld_btstat = 3
    //                         and ifnull(t2.fld_btid,0) = 0
    //                         and t0.fld_baidc = $data->fld_baidc
    //                         and t0.fld_btnoreff like  '%$data->fld_btp01%' ");
    // $total = 0;
    // foreach($do->result() as $rdo) {
    //   $this->db->query("insert into tbl_trk_billing (fld_btidp,fld_btreffid,fld_btamt01,fld_btp02,fld_btp03) values ($fld_btid,$rdo->fld_btid,$rdo->fld_btbalance,'$rdo->fld_btnoreff','$rdo->fld_btp16')");
    //   $total = $total + $rdo->fld_btbalance;
    //  }
    // $this->db->query("update tbl_bth set fld_btamt = $total where fld_btid =  $fld_btid limit 1");
    // $url = base_url() . "index.php/page/form/78000TRUCKING_BILLING/edit/$fld_btid?act=edit";
    // redirect($url);
  }

  function exportTaxUpload() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->exportTaxUpload($fld_btid);
  }

  function exportTaxUpload2() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->exportTaxUpload2($fld_btid);
  }

  function exportTaxUploadIn() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->exportTaxUploadIn($fld_btid);
  }

  function exportJBP() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->exportJBP($node);
  }

  function CancelJOExp($id)
  {
    $this->ffis->CancelJOExp($id);
  }

  function ApproveJOExp($id)
  {
    $this->ffis->ApproveJOExp($id);
  }

  function CompleteJOCSExp($id)
  {
    $this->ffis->CompleteJOCSExp($id);
  }

  function CompleteJOImp($id)
  {
    $this->ffis->CompleteJOImp($id);
  }

  function CancelJOInter($id)
  {
    $this->ffis->CancelJOInter($id);
  }

  function ApproveJOInter($id)
  {
    $this->ffis->ApproveJOInter($id);
  }

  function printBatchInvoice() {
    $fld_btid =  $this->uri->segment(3);
    $print_inv = $this->ffis->printBatchInvoice($fld_btid);
  }

  function printBatchInvoiceMerge() {
    $fld_btid =  $this->uri->segment(3);
    $print_inv = $this->ffis->printBatchInvoiceMerge($fld_btid);
  }

  function printBatchJournal() {
    $fld_btid =  $this->uri->segment(3);
    $print_inv = $this->ffis->printBatchJournal($fld_btid);
  }

  function printBatchInvoiceBill() {
    $fld_btid =  $this->uri->segment(3);
    #batch_print_billing
    $idp =$this->db->query("select fld_btp01 from tbl_bth where fld_btid = '$fld_btid'")->row()->fld_btp01;
    $src = 78000;
    if($idp == 1 ){
    $print_bill = $this->ffis->printTruckBilling($fld_btid,$src);
    }else if ($idp == 2) {
    $print_bill = $this->ffis->printTruckBillingCC($fld_btid,$src);
    }else {
    $print_bill = $this->ffis->printTruckBillingBox($fld_btid,$src);
    }
  }

  function deleteTaxUpload() {
    $node =  $this->uri->segment(3);
    $this->db->query("update tbl_taxnumber set fld_taxnumberpostid = 0 where fld_taxnumberid = '$node' limit 1");
    echo '<script>history.go(-1)</script>';
  }

  function customerPayment() {
    $count = $_POST["count"];
    $node =  $_POST["node"];
    echo "$node@@";
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $inv_no = $_POST["fld_btno$x"];
        $inv_id = $_POST["fld_btid$x"];
        $inv_price = $_POST["fld_btamt$x"];
        $tax01 = $_POST["fld_btp01$x"];
        $tax02 = $_POST["fld_btp02$x"];
        $rate = $_POST["fld_btp03$x"];
        $fld_baidc = $_POST["fld_beid$x"];
        $currency = $_POST["fld_btflag$x"];
        $tot_tax01 = $tot_tax01 + $tax01;
        $tot_tax02 = $tot_tax02 + $tax02;
        $conv_amount = ($rate > 0) ? $inv_price * $rate : $inv_price * 1 ;
        $tot_invoice = $tot_invoice + $inv_price;
        $tot_convert =  $tot_convert + $conv_amount;
        $this->db->query("insert ignore into tbl_btd_finance
	      (fld_btidp,fld_btreffid,fld_btnoreff,fld_btamt01,fld_btp01,fld_btp02,fld_btqty02,fld_btp03,fld_locid)
	      values
	      ($node ,$inv_id,'$inv_no',$inv_price,'$tax01','$tax02','$rate','$conv_amount','$location')");
      }
    }
    $tot_payment = $tot_invoice - ($tot_tax01 + $tot_tax02);
    $this->db->query("update tbl_bth set fld_btamt=$tot_invoice,fld_btp01=$tot_tax01,fld_btp02=$tot_tax02,fld_btuamt=$tot_payment,
    fld_btflag=$currency,fld_btp05=$tot_convert
    where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000CUSTOMER_PAYMENT/edit/$node?act=edit";
    redirect($url);
  }

  function invoiceDelivery() {
    $node =  $_POST["node"];
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $inv_id = $_POST["fld_btid$x"];
        $this->db->query("insert ignore into tbl_btd_invdel
              (fld_btidp,fld_btiid,fld_btflag)
              values
              ($node,$inv_id,'58')");
      }
    }
    $url = base_url() . "index.php/page/form/78000INVOICE_DELIVERY/edit/$node?act=edit";
    redirect($url);

  }

   function invoiceFollowUp() {
    $node =  $_POST["node"];
    $count = $_POST["count"];

    $fup=$this->db->query("select
    date_format(t0.fld_btdtp,'%Y-%m-%d %H:%i') 'Payment',
    date_format(t0.fld_btdt,'%Y-%m-%d %H:%i') 'FupDate',
    t0.fld_btdesc 'desc'
    from tbl_bth t0
    where t0.fld_btid = $node
    and t0.fld_bttyid = 106
    ");
    $fup = $fup->row();


    $fld_baidp  = $this->session->userdata('ctid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $inv_id = $_POST["fld_btid$x"];
        $this->db->query("insert ignore into tbl_bth_arfup
              (fld_btidp,fld_btreffid,fld_btdesc,fld_btdt,fld_btdtp)
              values
              ($node,$inv_id,'$fup->desc','$fup->FupDate','$fup->Payment')");
      }
    }
    $url = base_url() . "index.php/page/form/78000ARFUP_MULTI/edit/$node?act=edit";
    redirect($url);

  }


  function invoiceRemove() {
    $count = $_POST["count"];
    $node =  $_POST["node"];
    echo "$node@@";
    $fld_baidp  = $this->session->userdata('ctid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $inv_no = $_POST["fld_btno$x"];
        $inv_id = $_POST["fld_btid$x"];
        $inv_price = $_POST["fld_btamt$x"];
        $tax01 = $_POST["fld_btp01$x"];
        $tax02 = $_POST["fld_btp02$x"];
        $rate = $_POST["fld_btp03$x"];
        $fld_baidc = $_POST["fld_beid$x"];
        $currency = $_POST["fld_btflag$x"];
        $tot_tax01 = $tot_tax01 + $tax01;
        $tot_tax02 = $tot_tax02 + $tax02;
        $conv_amount = ($rate > 0) ? $inv_price * $rate : $inv_price * 1 ;
        $tot_invoice = $tot_invoice + $inv_price;
        $tot_convert =  $tot_convert + $conv_amount;
        $this->db->query("insert ignore into tbl_btd_invdel
              (fld_btidp,fld_btiid,fld_btamt01,fld_btp02,fld_currency,fld_btflag)
              values
              ($node,$inv_id,'$conv_amount','705',$currency,'59')");
      }
    }

    $url = base_url() . "index.php/page/form/78000INVOICE_DEL/edit/$node?act=edit";
    redirect($url);
  }


  function CashAdvance() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $customer =  $_POST["fld_beid"];
    $trans_no = $this->mkautono(1,2);
    $this->db->query("insert into tbl_bth
              (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidc,fld_baidp,fld_btiid,fld_btstat)
              values
              (1,'$trans_no',2,now(),'$customer','$fld_baidp',1,1)");
    $last_insert_id = $this->db->insert_id();
    $TotIDR=0;
    $TotUSD=0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        //$inv_no = $_POST["fld_btno$x"];
        $inv_id = $_POST["fld_id$x"];
        $inv_price = $_POST["fld_total$x"];
        $qty = $_POST["fld_btqty01$x"];
        $btuamt = $_POST["fld_btuamt01$x"];
        $cost = $_POST["fld_costtype$x"];
	$currency = $_POST["fld_currency$x"];
	$tyvalcd=$_POST["fld_tyvalcd$x"];
        $total = $total + $inv_price;
	$fldbeid = $_POST["fld_beid$x"];
        $fldbtid = $_POST["fld_btid$x"];
	$bl=$_POST["bl$x"];
        if ($currency==1){
	  $TotIDR=$TotIDR + $inv_price;
	}
 	if ($currency==2){
          $TotUSD=$TotUSD + $inv_price;
        }
        $this->db->query("insert ignore into tbl_btd_cost
        (fld_btidp,fld_bt01, fld_costtype,fld_currency,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp01,fld_bt02,fld_bt03,fld_bt04)
        values
        ($last_insert_id ,'$inv_id','$cost','$currency','$btuamt','$qty','$inv_price','$tyvalcd','$fldbeid',$fldbtid,'$bl')");
      }
    }
    $this->db->query("update tbl_bth set fld_btamt=$TotIDR, fld_btp06=$TotUSD, fld_btp07=$TotUSD, fld_btp12=$TotIDR where fld_btid=$last_insert_id limit 1");
    $url = base_url() ."index.php/page/form/78000JOCASH_ADVANCE/edit/$last_insert_id?act=edit";
    redirect($url);

  }

  function bookingtruck() {
    $count = $_POST["count"];
    $de = $_POST["de"];
    $loc = $_POST["loc"];
    $fld_baidp  = $this->session->userdata('ctid');
    $trans_no = $this->mkautono(1,5);
    $this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat,fld_baidv,fld_btloc)
	      values(1,'$trans_no',5,now(),'$fld_baidp',1,14,'$loc')");
    $last_insert_id = $this->db->insert_id();
  //echo "tes";
//exit();
  for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $btno = $_POST["fld_btno$x"];
        $btid = $_POST["fld_btid$x"];
        $baidc = $_POST["fld_baidc$x"];
        $aju = $_POST["aju$x"];
	$c20 = $_POST["c20$x"];
	$c40 = $_POST["c40$x"];
	$c45 = $_POST["c45$x"];
        $poitermId = $_POST["poitermId$x"];
        $poidepoId = $_POST["poidepoId$x"];
	$poicustId = $_POST["poicustId$x"];
        $tujuan = $_POST["tujuan$x"];
	$demorage=$_POST["demorage$x"];
        $detention = $_POST["detention$x"];
        $this->db->query("insert into tbl_btd_truck
        (fld_btidp,fld_bt01, fld_baidc,fld_bt02,fld_bt03,fld_bt04,fld_bt05,fld_bt06,fld_bt07,fld_bt08,fld_btp03,fld_btp04,fld_bt09,fld_btp09,fld_btp08,fld_btp10)
        values
        ($last_insert_id,'$btid',$baidc,'$aju','$de','$c20','$c40','$tujuan','$demorage','$detention',1,1,'$c45','$poitermId','$poidepoId','$poicustId')");
      }
    }
    $url = base_url() . "index.php/page/form/78000FRM_BOOKING_TRUCK/edit/$last_insert_id?act=edit";
    redirect($url);

  }
  function addbookingtruck($fld_btid) {
    $count = $_POST["count"];
    $de = $_POST["de"];
    $fld_baidp  = $this->session->userdata('ctid');
    /*$trans_no = $this->mkautono(1,5);
      $this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat,fld_baidv)
              values(1,'$trans_no',5,now(),'$fld_baidp',1,14)");

    $last_insert_id = $this->db->insert_id();*/
    $fld_btid =  $this->uri->segment(3);
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $btno = $_POST["fld_btno$x"];
        $btiid = $_POST["fld_btid$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $baidc = $_POST["fld_baidc$x"];
        $aju = $_POST["aju$x"];
        $c20 = $_POST["c20$x"];
        $c40 = $_POST["c40$x"];
        $tujuan = $_POST["tujuan$x"];
        $demorage=$_POST["demorage$x"];
        $detention = $_POST["detention$x"];
        $this->db->query("insert into tbl_btd_truck
        (fld_btidp,fld_bt01, fld_baidc,fld_bt02,fld_bt03,fld_bt04,fld_bt05,fld_bt06,fld_bt07,fld_bt08,fld_btp03,fld_btp04)
        values
        ('$fld_btidp','$btiid',$baidc,$aju,'$de','$c20','$c40','$tujuan','$demorage','$detention',1,1)");
      }
    }
    $url = base_url() . "index.php/page/form/78000FRM_BOOKING_TRUCK/edit/$fld_btidp?act=edit";
    redirect($url);

  }

 function updEmpty($fld_btid) {
    $count = $_POST["count"];
    #  echo "id=$count";
    #exit();
    $node =  $_POST["node"];
    $user_group=$this->session->userdata('group');
    if($user_group == 13 || $user_group == 1){
    for ($x=1; $x<=$count; $x++){

        $btid = $_POST["fld_btid$x"];
        $date = $_POST["estimate$x"];
        $exc = $_POST["exception$x"];
         if($exc == "on"){
        $exc = 1;
        }
        $rmkexc = $_POST["rmkexc$x"];
    #    echo "id $exc <br> $rmkexc";
     #   exit();

       $this->db->query("update  tbl_bth set fld_btdtp='$date',fld_btp34 = '$rmkexc',fld_btp41 = '$exc' where fld_btid='$btid' and fld_bttyid = 77  limit 1");

    }
    $url = base_url() . "index.php/page/form/78000DELIVERY_ORDER_TRAILER/edit/$btid";
    redirect($url);
    }else {
    $this->ffis->message("Access denied !! ");
    }
  }

 function invoicefaktur($fld_btid) {
    $count = $_POST["count"];
    #$last_insert_id = $this->db->insert_id();
    #$fld_btid =  $this->uri->segment(3);
    $node =  $_POST["node"];
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $Customer = $_POST["Customer$x"];
        $btid=$_POST["btid$x"];
        $btidp=$_POST["btidp$x"];
        $Depo = $_POST["Depo$x"];
        $Inv_No = $_POST["Inv_No$x"];
        $node =  $_POST["node"];
        $efaktur =$_POST["efaktur$x"];
        $Inv_Date = $_POST["Inv_Date$x"];
        $Desc = $_POST["Desc$x"];
        $this->db->query("insert into tbl_btd_faktur
        (fld_btidp,fld_btreffid,fld_btp01,fld_inv01,fld_inv02,fld_dt01,fld_val01,fld_desc)
        values
        ('$btid','$btidp','$Customer','$Depo','$Inv_No','$Inv_Date','$efaktur','$Desc')");
      }
    }
    $url = base_url() . "index.php/page/form/78000HAND_OVER_FAKTUR/edit/$btid";
    redirect($url);

  }
  function depositreceipt() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $trans_no = $this->mkautono(1,50);
    $this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat,fld_baidv)
              values(2,'$trans_no',50,now(),'$fld_baidp',1,14)");
    $last_insert_id = $this->db->insert_id();
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $btno = $_POST["fld_btno$x"];
        $btid = $_POST["fld_btid$x"];
        $baidc = $_POST["fld_baidc$x"];
        $aju = $_POST["aju$x"];
        $c20 = $_POST["c20$x"];
        $master = $_POST["master$x"];
        $tujuan = $_POST["tujuan$x"];
        $cleaning=$_POST["cleaning$x"];
        $contdepidr = $_POST["contdepidr$x"];
        $contdepusd = $_POST["contdepusd$x"];
        $demmdepidr = $_POST["demmdepidr$x"];
        $demmdepusd = $_POST["demmdepusd$x"];
        $detdepidr = $_POST["detdepidr$x"];
        $detdepusd = $_POST["detdepusd$x"];

        $demmidr = $_POST["demmidr$x"];
        $demmusd = $_POST["demmusd$x"];
        $repidr = $_POST["repidr$x"];
        $repusd = $_POST["repusd$x"];
        $refund = $_POST["refund$x"];
        $house = $_POST["house$x"];

        $this->db->query("insert into tbl_btd_deposit
        (fld_btidp,fld_btp01,fld_customer,fld_sline,fld_blno,fld_btcost01,fld_btcost02,fld_btcost03,fld_btcost04,fld_btcost05,fld_btcost06,fld_btcost07,fld_btcost08,
        fld_btcost09,fld_btcost10,fld_btp02)
        values
        ($last_insert_id,'$btno','$baidc','$c20',if('$master'='','$house','$master'),if('$contdepusd' = 0,'$detdepusd','$contdepusd'),'$contdepidr','$demmdepusd','$demmdepidr',0,'$cleaning','$demmusd','$demmidr',
        '$repusd','$repidr','$refund')");
      }
    }
    $url = base_url() . "index.php/page/form/78000DEPOSIT_RECEIPT/edit/$last_insert_id?act=edit";
    redirect($url);

  }

  function costMemo() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $costumer_text = $_POST["fld_btp021"];
    $trans_no = $this->mkautono(1,53);
    $this->db->query("insert into tbl_bth
               (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_btflag,fld_btamt,fld_baidp,fld_btstat,fld_btdesc,fld_btiid)
               values (2,'$trans_no',53,now(),'1',0,'$fld_baidp',1,'','749')");
    $last_insert_id = $this->db->insert_id();
    $tot_purchase = 0;
    $tot_invoice = 0;
    $tot_tax01 = 0;
    $tot_tax02 = 0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btno = $_POST["fld_btno$x"];
        $fld_beid = $_POST["fld_beid$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $fld_coaid = $_POST["fld_coaid$x"];
        $fld_total = $_POST["fld_total$x"];
        $fld_bedivid = $_POST["fld_bedivid$x"];
        $fld_btdesc = $_POST["fld_btdesc$x"] . " @" . $_POST["fld_unit$x"] . " x " . $_POST["fld_qty$x"];
        $tot_purchase = $tot_purchase + $_POST["fld_total$x"];
        $this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid,fld_btnoreff,fld_btamt01,fld_coaid,fld_btdesc,fld_bedivid)
              values
              ('$last_insert_id' ,'$fld_btid','$fld_btno','$fld_total','$fld_coaid','$fld_btdesc','$fld_bedivid')");
      }
    }
    $this->db->query("update tbl_bth set fld_btamt=$tot_purchase,fld_baidv=$fld_beid  where fld_btid=$last_insert_id limit 1");
    $url = base_url() . "index.php/page/form/78000SUPPLIER_INVOICE/edit/$last_insert_id?act=edit";
    redirect($url);
  }


  function kpiplanner()

  {

     //print_r($_POST);

    //$costumer_text = $_POST["fld_btp021"];

    //print $costumer_text;

    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $trans_no = $this->mkautono(1,7);
    $this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat)
	      values(1,'$trans_no',5,now(),'$fld_baidp',1)");
    $last_insert_id = $this->db->insert_id();
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        //$inv_no = $_POST["fld_btno$x"];
        $btno = $_POST["fld_btno$x"];
        $btid = $_POST["fld_btid$x"];
        $baidc = $_POST["fld_baidc$x"];
        $aju = $_POST["aju$x"];
        $de = $_POST["de$x"];
	$c20 = $_POST["c20$x"];
	$c40 = $_POST["c40$x"];
	$tujuan = $_POST["tujuan$x"];
	$lcl=$_POST["lcl$x"];
        $hc = $_POST["hc$x"];
        //$tot_tax01 = $tot_tax01 + $tax01;
        //$tot_tax02 = $tot_tax02 + $tax02;
        $this->db->query("insert into tbl_btd_truck
        (fld_btidp,fld_bt01, fld_baidc,fld_bt02,fld_bt03,fld_bt04,fld_bt05,fld_bt06)
        values
        ($last_insert_id,'$btid',$baidc,'$c20','$c40','$hc','$lcl','$tujuan')");
      }
    }
    //$last_insert_id = $this->db->insert_id();
    $url = base_url() . "index.php/page/form/78000KPI_PLANNER/edit/$last_insert_id?act=edit";
    redirect($url);
  }

  function ApproveJOIMP()
  {
     $count = $_POST["count"];
     for ($x=1; $x<=$count; $x++){
       if($_POST["rowdata$x"] == "on") {
          $id = $_POST["fld_id$x"];
          $sql="update tbl_bth set fld_btstat=3 where fld_btid='$id'";
          $this->db->query($sql);
	}
     }
     $url = base_url() ."index.php/page/view/78000JOIMP_APPRV";
     redirect($url);
  }

  function bookingtruckexp() {
    $count = $_POST["count"];
    $de=$_POST["de"];
    $ltl = $_POST["ltl"];
    $loc = $_POST["loc"];
    $fld_baidp  = $this->session->userdata('ctid');
    $trans_no = $this->mkautono(1,5);
    $this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat,fld_baidv,fld_btloc,fld_btp01)
	      values(1,'$trans_no',5,now(),'$fld_baidp',1,13,'$loc','$ltl')");
    $last_insert_id = $this->db->insert_id();
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $btno = $_POST["fld_btno$x"];
        $btid = $_POST["fld_btid$x"];
        $baidc = $_POST["fld_baidc$x"];
        $SI = $_POST["SI$x"];
        $lcl = $_POST["lcl$x"];
	$c20 = $_POST["c20$x"];
	$c40hc = $_POST["c40hc$x"];
        $c40dc = $_POST["c40dc$x"];
	$tujuan = $_POST["tujuan$x"];
	$c45 = $_POST["c45$x"];
        $depo = $_POST["depo$x"];
        $dest = $_POST["dest$x"];
        $cbu = $_POST["cbu$x"];
        $termId = $_POST["termId$x"];
        $stuffId = $_POST["stuffId$x"];
        $depoId = $_POST["depoId$x"];
        $stuffing_id = $_POST["stuffing_id$x"];
        $stuffing_date = $_POST["stuffing_date$x"];
        $route_id = $_POST["route_id$x"];
        $this->db->query("insert into tbl_btd_truck
        (fld_btidp,fld_bt01, fld_baidc,fld_bt02,fld_bt03,fld_bt04,fld_bt05,fld_bt06,fld_bt07,fld_bt10,fld_bt09,fld_bt11,fld_bt12,fld_btp02,fld_btdt01,fld_btp03,fld_btp04,fld_btp05,fld_btp06,fld_btp10,fld_btp09,fld_btp11)
        values
        ($last_insert_id,'$btid','$baidc','$SI','$de','$c20','$c40hc','$tujuan','$depoId','$c40dc','$lcl','$c45','$dest','$stuffing_id','$stuffing_date',1,1,
'$route_id','$cbu','$stuffId','$termId','$depoId')");
      }
    }
    $url = base_url() . "index.php/page/form/78000FRM_BOOKING_TRUCK_EXP/edit/$last_insert_id?act=edit";
    redirect($url);

  }

  function completeJobOrder() {
    $count = $_POST["count"];
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $btid = $_POST["fld_btid$x"];
        $cek = $this->db->query("select * from tbl_bth t0 where t0.fld_btid = $btid limit 1")->row();
         if ($cek->fld_btp32 == '') {
         	$html2 .= "- PIB Response<br>";
          	$html1 = "Transaction failed : Please check the following field(s) :<br>";
                $this->ffis->message("$html1 $html2");
         }

         if ($cek->fld_bttax == 3 && $cek->fld_btp33 == 0) {
         	$html2 .= "- Break Bulk UoM<br>";
 		$html1 = "Transaction failed : Please check the following field(s) :<br>";
                $this->ffis->message("$html1 $html2");
         }

         if ($cek->fld_bttax == 1 && ($cek->fld_btqty == '' && $cek->fld_btp06 == '')) {
         	$html2 .= "- Container Party<br>";
		$html1 = "Transaction failed : Please check the following field(s) :<br>";
                $this->ffis->message("$html1 $html2");
         }

        $this->db->query("update tbl_bth set fld_btstat=3, fld_clsdt=now() where fld_btid = $btid limit 1");
      }
    }
    $url = base_url() . "index.php/page/view/78000IMP_JO_APPRV";
    redirect($url);
  }


  function kraniCOAdvance() {

  $count = $_POST["count"];

	for ($x=1;$x<=$count; $x++){
		if($_POST["rowdata$x"] == "on") {
			$joid = $_POST["jobid$x"];
			$sid = $_POST["id$x"];
			$doc_no = $_POST["doc_no$x"];


			$co_doc = $this->db->query("SELECT  * FROM tbl_btd_document WHERE fld_btidp ='$joid' and fld_doctype = 861");
			foreach($co_doc->result() as $codata){
				$fld_docnum=$codata->fld_docnum;
				$sql = "INSERT IGNORE INTO tbl_btd_cost
				        (fld_btidp, fld_bt01, fld_btp06, fld_costtype,fld_currency,fld_btp01,fld_btqty01,fld_btuamt01,fld_btamt01)
				        value ($sid,$joid,'$fld_docnum','5468',1,'8',1,10000,10000)";
				$this->db->query($sql);

			}
		}
	}
	$this->db->query("update tbl_bth set fld_btamt=(select sum(fld_btamt01) from tbl_btd_cost where fld_btidp='$sid') where fld_btid='$sid'");
	$url = base_url() . "index.php/page/form/78000JOCASH_ADVANCE_EXP/edit/".$sid."?act=edit";
    redirect($url);

  }

  function kraniMuatAdvance() {
    $count = $_POST["count"];
    $mode = $_POST["mode"];

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $f20 = $_POST["f20$x"];
        $f40 = $_POST["f40$x"];
        $price = $_POST["price$x"];
        $p20 = $_POST["p20$x"];
        $p40 = $_POST["p40$x"];
        $sid = $_POST["id$x"];
        $btid = $_POST["fld_btid$x"];
        $reffid = $_POST["reffid$x"];
        $prepaid = $_POST["prepaid$x"];
        $totalcont = $_POST["qty$x"];
        $depo = $_POST["depo$x"];
        $terminal = $_POST["terminal$x"];


        if($mode == 'muat') {
          $cost_id = 3490;
        } elseif ($mode == 'inter') {
          $cost_id = 3622;
          $qty = 1;
          $uom = 8;
        } elseif ($mode == 'inspect') {
          $cost_id = 3486;
          $qty = $f20 + $f40;
          echo "$qty = $f20 + $f40";
          $uom = 9;
        } elseif ($mode == 'lpbc') {
          $cost_id = 3487;
          $lpbc_count = $this->db->query("select ifnull(count(1),0) 'qty'  from tbl_btd_peb t0
                               where t0.fld_btidp = $btid
                               ");
          $lpbc_count = $lpbc_count->row();
          $qty = $lpbc_count->qty;
          $uom = 8;
        } elseif ($mode == 'lcl') {
          $cost_id = 3621;
          $qty = 1;
          $uom = 8;
        } elseif ($mode == 'kartu') {
        $cost_id = 3621;
        //$cek = $this->db->query("select ifnull(count(1),0) 'counting'  from tbl_btd_cost t0
        //                       where t0.fld_costtype=3472
        //                       and date_format(t0.fld_btp02,'%Y-%m-%d') =  date_format(now(),'%Y-%m-%d')");
        //$cek = $cek->row();
        //if($cek->counting == 0) {
        //  $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01,
        //                  fld_costtype, fld_currency,fld_btuamt01,fld_btamt01,fld_btflag,fld_btp02)
        //                  values ('$reffid','$btid','$sid','1','1','3472','1','1000000',1000000,'4',now())");
        //}

      } else {
        $cost_id = 0;
      }


        if($f20 > 0 && $mode == 'muat') {
          $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','1','$f20','$cost_id','1','$p20',$p20*$f20,'4',2)");
        }
        if($f40 > 0 && $mode == 'muat') {
           $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                             fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                             values ('$reffid','$btid','$sid','2','$f40','$cost_id','1','$p40',$p40*$f40,'4',2)");
        }

        //advance krani kartu
        if($totalcont > 0 && $mode == 'kartu') {
	  for ($a=1; $a<=$totalcont; $a++){
            if ($a==1) {
		if ($depo == 'DUNEX'){
			if($terminal == 1 || $terminal == 2) {
			$this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                             fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                             values ('$reffid','$btid','$sid',if($f20>0,1,2),'1','$cost_id','1','17000','17000','4',2)");
			}
                        //Terminal NPCT1
                        else if($terminal == 5) {
                        $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                             fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                             values ('$reffid','$btid','$sid',if($f20>0,1,2),'1','$cost_id','1',if($f20>0,700000,800000),if($f20>0,700000,800000),'4',2)");
                        }
			else {
			$this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                             fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                             values ('$reffid','$btid','$sid',if($f20>0,1,2),'1','$cost_id','1','22000','22000','4',2)");
			}
		}
		else {
			if ($terminal == 1 || $terminal == 2) {
			$this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                             fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                             values ('$reffid','$btid','$sid',if($f20>0,1,2),'1','$cost_id','1','22000','22000','4',2)");
			}
                        //Terminal NPCT1
                        else if($terminal == 5) {
                        $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                             fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                             values ('$reffid','$btid','$sid',if($f20>0,1,2),'1','$cost_id','1',if($f20>0,700000,800000),if($f20>0,700000,800000),'4',2)");
                        }

			else{
			$this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                             fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                             values ('$reffid','$btid','$sid',if($f20>0,1,2),'1','$cost_id','1','27000','27000','4',2)");
			}
		  }
		}
		else {

			$this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                             fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                             values ('$reffid','$btid','$sid',if($f20>0,1,2),'1','$cost_id','1','7000','7000','4',2)");
		}
	  }
        }

        if($mode == 'lcl' || $mode == 'inspect' || $mode == 'lpbc' || $mode == 'inter') {
          $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','$uom','$qty','$cost_id','1','$price',$price*$qty,'4',2)");
        }
        if($mode == 'do') {
          if($prepaid == 1) {
            if($f20 > 0) {
              ### OCEAN FREIGHT
              $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','1','$f20','1','1','$price',0,'4',2)");
              }
              if($f40 > 0) {
              ### OCEAN FREIGHT
              $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','2','$f40','1','1','$price',0,'4',2)");
              }
              ###DOC FEE
              $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','8',$f20 + $f40,'3470','1','$price',0,'4',2)");
            }
            if($prepaid == 2) {

              if($f20 > 0) {
              ###THC
              $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','1','$f20','6','1','$price',0,'4',2)");
              ###SEAL
              $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','1','$f20','68','1','$price',0,'4',2)");
              }
              if($f40 > 0) {
              ###THC
              $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','2','$f40','3573','1','$price',0,'4',2)");
              ###SEAL
              $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','2','$f40','68','1','$price',0,'4',2)");
              }


              ###DOC FEE
              $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','8',$f20 + $f40,'3469','1','$price',0,'4',2)");
              ###DOC FEE
              $this->db->query("insert into tbl_btd_cost (fld_btreffid,fld_bt01, fld_btidp, fld_btp01, fld_btqty01, fld_costtype, fld_currency,
                            fld_btuamt01,fld_btamt01,fld_btflag,fld_paytype)
                            values ('$reffid','$btid','$sid','8',$f20 + $f40,'3470','1','$price',0,'4',2)");
            }
        }

      }
    }
	$this->db->query("update tbl_bth set fld_btamt=(select sum(fld_btamt01) from tbl_btd_cost where fld_btidp='$sid') where fld_btid='$sid'");
	$url = base_url() . "index.php/page/form/78000JOCASH_ADVANCE_EXP/edit/".$sid."?act=edit";
    redirect($url);
  }

  function AddCashOutSpv() {
    $count = $_POST["count"];
    $grand_tot = 0;
    $fld_baidp  = $this->session->userdata('ctid');
    $trans_no = $this->mkautono(1,46);
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $sid= $_POST["sid$x"];
        $reffid= $_POST["fld_btid$x"];
        $fld_jo = $_POST["fld_jo$x"];
        $bl = $_POST["bl$x"];
        $crany = $_POST["crany$x"];
        $CusID = $_POST["CusID$x"];
        $TypeID = $_POST["TypeID$x"];
        $qty = $_POST["qty$x"];
        $TypeID = $_POST["TypeID$x"];
        $CurrID = $_POST["CurrID$x"];
        $amount = $_POST["amount$x"];
        $fld_btdesc = $_POST["cost_name$x"];
        $total = $_POST["total$x"];
        $fld_paytype = $_POST["fld_paytype$x"];
        $CostID = $_POST["CostID$x"];
        $fld_empid = $_POST["fld_empid$x"];
        $cst =  $_POST["cst$x"];
        $DivID =  $_POST["DivID$x"];


        $this->db->query("insert into tbl_btd_finance (fld_btreffid,fld_bedivid, fld_btidp, fld_btamt01, fld_btnoreff,fld_btdesc,fld_btdocreff,fld_empid)
        value ($reffid,$DivID,'$sid',$total,'$fld_jo',concat(substr('$cst',4,8),'-','$fld_btdesc',' / ',substr('$crany',1,7)),'$bl','$fld_empid')");
        $grand_tot = $grand_tot + $total;

        $this->db->query("update tbl_bth set fld_btamt=$grand_tot, fld_btflag='$CurrID' where fld_btid=$sid");
      }
    }
    //echo $notes;
    //exit();
    $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/".$sid."?act=edit";
    redirect($url);
  }


  function AddCOReimburse() {
    $count = $_POST["count"];
    $grand_tot = 0;
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $trans_no = $this->mkautono(1,46);
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $sid= $_POST["sid$x"];
        $reffid= $_POST["fld_btid$x"];
        $fld_btnoreff = $_POST["fld_btnoreff$x"];
        $fld_btdocreff = $_POST["fld_btdocreff$x"];
        $amount= $_POST["amount$x"];
        $div_id =  $_POST["div_id$x"];
        $emp_id =  $_POST["emp_id$x"];
        $cost_name = $_POST["cost$x"];
        $crany = $_POST["crany$x"];
        $notes = $_POST["notes$x"];
        $rim_no = $_POST["RimNo$x"];
        $company = $_POST["company$x"];
        $this->db->query("insert into tbl_btd_finance (fld_btreffid,fld_bedivid, fld_btidp, fld_btamt01, fld_btnoreff,fld_btdesc,fld_btdocreff,fld_empid,
                          fld_coaid,fld_locid)
        value ($reffid,$div_id,'$sid',$amount,'$fld_btnoreff',concat('$cost_name','','$fld_btnoreff','/',substr('$crany',1,8)),'$fld_btdocreff','$emp_id',
               '827',$location)");
        $grand_tot = $grand_tot + $amount;

        $this->db->query("update tbl_bth set fld_btamt=$grand_tot, fld_btflag=1,fld_btdesc='$notes',fld_btnoalt='$rim_no',fld_btp23='$company'
                          where fld_btid=$sid");
      }
    }
   // echo $emp;
   // exit();
    $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/".$sid."?act=edit";
    redirect($url);
  }

  function InsertDetailCO() {
    $count = $_POST["count"];
    $grand_tot = 0;
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $trans_no = $this->mkautono(1,51);
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $sid= $_POST["sid$x"];
        $reffid= $_POST["fld_btid$x"];
        $fld_btnoreff = $_POST["fld_btnoreff$x"];
        $fld_btdocreff = $_POST["fld_btdocreff$x"];
        $amount= $_POST["amount$x"];
        $div_id =  $_POST["div_id$x"];
        $empid =  $_POST["empid$x"];
        $employee = $_POST["employee$x"];
        $notes = $_POST["notes$x"];

        $this->db->query("insert into tbl_btd_finance (fld_btreffid,fld_bedivid, fld_btidp, fld_btamt01, fld_btnoreff,fld_btdesc,fld_btdocreff,fld_empid,fld_locid)
        value ($reffid,$div_id,'$sid',$amount,'$fld_btnoreff','$notes','$fld_btdocreff','$empid','$location')");

      }
    }

    $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/".$sid."?act=edit";
    redirect($url);
  }



  function supplierInvoice() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $costumer_text = $_POST["fld_btp021"];
    $customer =  $_POST["fld_beid1"];
    $trans_no = $this->mkautono(2,46);
    $node =  $_POST["node"];
    $tot_purchase = 0;
    $tot_invoice = 0;
    $tot_tax01 = 0;
    $tot_tax02 = 0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btnoreff = $_POST["fld_btnoreff$x"];
        $fld_btnodoc = $_POST["fld_btnodoc$x"];
        $data =  $this->db->query("select
                                   t0.fld_btid,
                                   t0.fld_btqty01 - ifnull(t7.fld_btqty01,0) - ifnull(t5.fld_btqty01,0) 'qty',
				   t0.fld_btuamt01,
                                   t0.fld_btqty01,
                                   t0.fld_btamt01,
                                   t0.fld_btqty01 * t0.fld_btuamt01 'subtotal',
                                   t3.fld_btno 'po_number',
                                   if(t0.fld_btiid in (2742,1972),t0.fld_btcmt,t1.fld_btinm) 'Item',
                                   if(t0.fld_btiid in (2742,1972),0,1132) 'coa',
                                   t2.fld_btp01,
				   t4.fld_unitnm,
                                   t8.fld_bticd
                                   from dnxapps.tbl_btd_purchase t0
                                   left join dnxapps.tbl_bti t1 on t1.fld_btiid=t0.fld_btiid
                                   left join dnxapps.tbl_btd_purchase t2 on t2.fld_btid = t0.fld_btreffid
                                   left join dnxapps.tbl_bth t3 on t3.fld_btid=t2.fld_btidp
				   left join dnxapps.tbl_unit t4 on t4.fld_unitid=t0.fld_unitid
                                   left join tbl_btd_finance t5 on t5.fld_btreffid=t0.fld_btid
                                   left join tbl_bth t6 on t6.fld_btid=t5.fld_btidp and t6.fld_bttyid=53
                                   left join dnxapps.tbl_btd_purchase t7 on t7.fld_btp02 = t0.fld_btid
				   left join dnxapps.tbl_bti t8 on t8.fld_btiid=t2.fld_btp01
                                   where
                                   ifnull(t6.fld_btid,0) = 0
                                   and
                                   t0.fld_btqty01 - ifnull(t7.fld_btqty01,0) - ifnull(t5.fld_btqty01,0) > 0
				   and
				   t0.fld_btidp='$fld_btid'
                                   ");
        $data = $data->result();
        foreach($data as $rdata) {
          $fld_btdesc = $rdata->Item . " @ " . $rdata->fld_btuamt01 . " x " . $rdata->qty . " " . $rdata->fld_unitnm . " "  . $rdata->fld_bticd;
          $this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid,fld_btnodoc,fld_btnoreff,fld_btqty01,fld_btuamt01,fld_btamt01,fld_coaid,fld_btdesc,fld_bedivid,fld_btiid,fld_locid)
              values
              ('$node' ,$rdata->fld_btid,if('$rdata->po_number' = '','$fld_btnodoc','$rdata->po_number'),'$fld_btnoreff',$rdata->qty,'',$rdata->subtotal,'$rdata->coa','$fld_btdesc',
               '$fld_bedivid','$rdata->fld_btp01','$location')");
        }
      }
    }
    $this->db->query("update tbl_bth set fld_btbalance=(select sum(ifnull(fld_btamt01,0)) from tbl_btd_finance where fld_btidp='$node')
                      where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000SUPPLIER_INVOICE/edit/$node?act=edit";
    redirect($url);
  }

  function CreditReceipt() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $node =  $_POST["node"];
    $blno = '';

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $data =  $this->db->query("select
                                   t1.fld_btid,
                                   t1.fld_btno 'jo',
                                   t3.fld_benm 'shipper',
                                   t2.fld_btinm 'cost_name',
                                   t0.fld_btamt01 'amount',
                                   t0.fld_btqty01 'qty',
                                   if(t1.fld_btp07 !='',t1.fld_btp07,t1.fld_btp08) 'bl'
                                   from tbl_btd_cost t0
                                   left join tbl_bth t1 on t1.fld_btid = t0.fld_bt01 and t1.fld_bttyid in(1,6,10)
                                   left join tbl_bti t2 on t2.fld_btiid = t0.fld_costtype and t2.fld_bticid = 1 and t2.fld_btip01=1
                                   left join dnxapps.tbl_be t3 on t3.fld_beid = t1.fld_baidc and t3.fld_betyid=5 and t3.fld_bestat = 1
                                   where
				   t0.fld_btidp='$fld_btid'
                                   ");
        $data = $data->result();
        foreach($data as $rdata) {
          $this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid,fld_btnodoc,fld_btnoreff,fld_btamt01,fld_btdesc,fld_locid)
              values
              ('$node' ,$rdata->fld_btid,'$rdata->bl','$rdata->jo',$rdata->amount*$rdata->qty,concat('$rdata->shipper','-','$rdata->cost_name'),'$location')");
        }
      }
    }
    $this->db->query("update tbl_bth set fld_btamt=(select sum(ifnull(fld_btamt01,0)) from tbl_btd_finance where fld_btidp='$node')
                      where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000CREDIT_TERM/edit/$node?act=edit";
    $databl =  $this->db->query("select t0.fld_btnodoc 'bl'
                                from tbl_btd_finance t0
                                where
				t0.fld_btidp='$node'
                                ");
    $databl = $databl->result();

		foreach($databl as $rdata2) {
		  $cek_bl =$this->db->query("select count(t0.fld_btnodoc) 'blno' from tbl_btd_finance t0
		                             left join tbl_bth t1 on t1.fld_btid=t0.fld_btidp and t1.fld_bttyid = 54
				             where t0.fld_btnodoc = '$rdata2->bl' group by t0.fld_btnodoc
					   ");
		  $cek_bl = $cek_bl->row();
			if($cek_bl->blno > 1) {
                            $blno.= $rdata2->bl.",";

			}
		}
		if($cek_bl->blno > 1) {
			$this->ffis->message2("BL number already exist on other Credit Term. No BL : $blno");
        }

    redirect($url);
  }


  function insertKWE() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $node =  $_POST["node"];
    $blno = '';

     for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $bl = $_POST["BL$x"];
        $total = $_POST["Total$x"];
        $fld_btdesc = substr($_POST["Customer$x"],0,15) .'-'.'LOLO'.'-'. $_POST["Shipper$x"];
        $this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid,fld_btnodoc,fld_btamt01,fld_coaid,fld_btdesc,fld_locid)
              values
              ('$node' ,'$fld_btid','$bl','$total','705','$fld_btdesc','$location')");
      }
    }
    $this->db->query("update tbl_bth set fld_btamt=(select sum(ifnull(fld_btamt01,0)) from tbl_btd_finance where fld_btidp='$node')
                      where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000CREDIT_TERM/edit/$node?act=edit";

    $databl =  $this->db->query("select t0.fld_btnodoc 'bl'
                                from tbl_btd_finance t0
                                where
                                t0.fld_btidp='$node'
                                ");
    $databl = $databl->result();

     foreach($databl as $rdata2) {
                  $cek_bl =$this->db->query("select t0.fld_btno 'jo' from tbl_bth t0
                                             where t0.fld_btp08 = '$rdata2->bl' and t0.fld_bttyid in (1,65)
                                           ");
                  $cek_bl = $cek_bl->row();
                        if($cek_bl->jo != '') {
                             $this->db->query("update tbl_btd_finance set fld_btnoreff='$cek_bl->jo'
                                               where
                                               fld_btnodoc='$rdata2->bl'
                                               and
                                               fld_btidp='$node'
                                               limit 1");
                        }
                }

    redirect($url);
  }

 function insertKWECOJ() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $node =  $_POST["node"];
    $blno = '';

     for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $bl = $_POST["BL$x"];
        $total = $_POST["Total$x"];
        $fld_btdesc = substr($_POST["Customer$x"],0,15) .'-'.'LOLO'.'-'. $_POST["Shipper$x"];
        $this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid,fld_btnodoc,fld_btamt01,fld_coaid,fld_btdesc,fld_locid)
              values
              ('$node' ,'$fld_btid','$bl','$total','705','$fld_btdesc','$location')");
      }
    }
    $this->db->query("update tbl_bth set fld_btamt=(select sum(ifnull(fld_btamt01,0)) from tbl_btd_finance where fld_btidp='$node')
                      where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/$node?act=edit";

    $databl =  $this->db->query("select t0.fld_btnodoc 'bl'
                                from tbl_btd_finance t0
                                where
                                t0.fld_btidp='$node'
                                ");
    $databl = $databl->result();

    foreach($databl as $rdata2) {
                  $cek_bl =$this->db->query("select t0.fld_btno 'jo' from tbl_bth t0
                                             where t0.fld_btp08 = '$rdata2->bl' and t0.fld_bttyid in (1,65)
                                           ");
                  $cek_bl = $cek_bl->row();
                        if($cek_bl->jo != '') {
                             $this->db->query("update tbl_btd_finance set fld_btnoreff='$cek_bl->jo'
                                               where
                                               fld_btnodoc='$rdata2->bl'
                                               and
                                               fld_btidp='$node'
                                               limit 1");
                        }
                }

    redirect($url);
  }

  function insertDEPCOJ(){
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $node =  $_POST["node"];
    $blno = '';

     for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $bl = $_POST["BL$x"];
        $flag = $_POST["flag$x"];
        $total = $_POST["Total$x"];
        if($flag == 44){
        $fld_btdesc = substr($_POST["Customer$x"],0,15) .'-'.'BIAYA JAMINAN DETENTION'.'-'. $_POST["Shipper$x"];
        } else {
        $fld_btdesc = substr($_POST["Customer$x"],0,15) .'-'.'BIAYA JAMINAN REPAIR'.'-'. $_POST["Shipper$x"];
        }
	$this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid,fld_btdocreff,fld_btamt01,fld_coaid,fld_btdesc,fld_locid)
              values
              ('$node' ,'$fld_btid','$bl','$total','703','$fld_btdesc','$location')");
      }
    }
    $this->db->query("update tbl_bth set fld_btamt=(select sum(ifnull(fld_btamt01,0)) from tbl_btd_finance where fld_btidp='$node')
                      where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/$node?act=edit";

    $databl =  $this->db->query("select t0.fld_btdocreff 'bl'
                                from tbl_btd_finance t0
                                where
                                t0.fld_btidp='$node'
                                ");
    $databl = $databl->result();

    foreach($databl as $rdata2) {
                  $cek_bl =$this->db->query("select t0.fld_btno 'jo' from tbl_bth t0
                                             where t0.fld_btp08 = '$rdata2->bl' and t0.fld_bttyid in (1,65)
                                           ");
                  $cek_bl = $cek_bl->row();
                        if($cek_bl->jo != '') {
                             $this->db->query("update tbl_btd_finance set fld_btnoreff='$cek_bl->jo'
                                               where
                                               fld_btdocreff='$rdata2->bl'
                                               and
                                               fld_btidp='$node'
                                               limit 1");
                        }
                }

    redirect($url);
  }

  function insertinvDET() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $node =  $_POST["node"];

     for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $desc = $_POST["Desc$x"];
        $total = $_POST["Total$x"];
	$reffno = $_POST["reffno$x"];
	$reffdoc = $_POST["reffdoc$x"];

        $this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid01,fld_btnoreff,fld_btnodoc,fld_btamt01,fld_coaid,fld_btdesc,fld_locid)
              values
              ('$node' ,'$fld_btid','$reffno','$reffdoc','$total','705','$desc','$location')");
      }
    }
    $this->db->query("update tbl_bth set fld_btamt=(select sum(ifnull(fld_btamt01,0)) from tbl_btd_finance where fld_btidp='$node')
                      where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000CREDIT_TERM/edit/$node?act=edit";
    redirect($url);
  }


  function AddCashOutCRT() {
    $count = $_POST["count"];
    $grand_tot = 0;
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $trans_no = $this->mkautono(1,46);
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $sid= $_POST["sid$x"];
        $reffid= $_POST["fld_btid$x"];
        $amount= $_POST["amount$x"];
        $crt_no = $_POST["crtno$x"];

        // $this->db->query("insert into tbl_btd_finance (fld_btreffid, fld_btidp, fld_btamt01, fld_btnoreff,fld_btdesc,fld_coaid,fld_locid)
        //                 value ($reffid,'$sid',$amount,'$crt_no','LOLO DET','749',$location)");
        $this->db->query("insert into tbl_btd_finance (fld_btreffid, fld_btidp, fld_btamt01, fld_btnoreff,fld_btdesc,fld_coaid,fld_locid)
                        value ($reffid,'$sid',$amount,'$crt_no','LOLO DET','737',$location)");
        $grand_tot = $grand_tot + $amount;

        $this->db->query("update tbl_bth set fld_btamt=$grand_tot where fld_btid=$sid");
      }
    }
    $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/".$sid."?act=edit";
    redirect($url);
  }
function updateDateinvoice(){
        $btid = $this->uri->segment(3);
        $date_now = date('Y-m-d');
      /*  $btdt = $this->db->query("select fld_btdt from tbl_bth where fld_btid = $btid and fld_bttyid = 108 ")->row();
        $fld_btdt = $btdt->fld_btdt;
*/
        $btdfinance = $this->db->query("select fld_btreffid from tbl_btd_finance where fld_btidp = $btid")->result();
        foreach($btdfinance as $tbl_btd_finance){
          $reffid = $tbl_btd_finance->fld_btreffid;

          $this->db->query("update tbl_bth set fld_btp04='$date_now' where fld_btid = $reffid and fld_bttyid = 58");
        }
        $url = base_url() . "index.php/page/form/78000SUBMIT_DELIVERY/edit/$btid";
        redirect($url);
  }
  function updateRecieveDate(){
        $btid = $this->uri->segment(3);
        $date_now = date('Y-m-d');
        $btdfinance = $this->db->query("select fld_btreffid2 from tbl_btd_finance where fld_btidp = $btid")->result();
        foreach($btdfinance as $tbl_btd_finances){
          $reffid2 = $tbl_btd_finances->fld_btreffid2;

          $this->db->query("update tbl_bth set fld_btdtp='$date_now' where fld_btid = $reffid2 and fld_bttyid = 58");
        }
        $url = base_url() . "index.php/page/form/78000RECIEVE_INVOICE/edit/$btid";
        redirect($url);
  }
  function AddSettleEDC() {
    $count = $_POST["count"];
    $grand_tot = 0;

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $sid= $_POST["sid$x"];
        $reffid= $_POST["fld_btid$x"];
        $amount= $_POST["amount$x"];
        $desc= $_POST["desc$x"];

        $this->db->query("insert into tbl_btd_cost (fld_btreffid2, fld_btidp,fld_costtype,fld_currency, fld_btuamt01, fld_btqty01,fld_btamt01)
                        value ($reffid,'$sid','15','1',$amount,'1',$amount)");
        $grand_tot = $grand_tot + $amount;

        $this->db->query("update tbl_bth set fld_btamt=$grand_tot where fld_btid=$sid limit 1");
      }
    }
    $url = base_url() . "index.php/page/form/78000JO_SETTLEMENT/edit/".$sid."?act=edit";
    redirect($url);
  }

  function AddBankOutEDC() {
    $count = $_POST["count"];
    $grand_tot = 0;

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $sid= $_POST["sid$x"];
        $reffid= $_POST["fld_btid$x"];
        $amount= $_POST["amount$x"];
        $desc= $_POST["desc$x"];

        $this->db->query("insert into tbl_btd_finance (fld_btreffid2, fld_btidp,fld_btamt01)
                        value ($reffid,'$sid',$amount)");
        $grand_tot = $grand_tot + $amount;

        $this->db->query("update tbl_bth set fld_btamt=$grand_tot where fld_btid=$sid limit 1");
      }
    }
    $url = base_url() . "index.php/page/form/78000BANK_OUT/edit/".$sid."?act=edit";
    redirect($url);
  }

  function paymentSupplier() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $costumer_text = $_POST["fld_btp021"];
    $customer =  $_POST["fld_beid1"];
    $node =  $_POST["node"];
    $tot_purchase = 0;
    $tot_invoice = 0;
    $tot_tax01 = 0;
    $tot_tax02 = 0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btamt = $_POST["fld_btamt$x"];
        $fld_bedivid = $_POST["fld_bedivid$x"];
        $fld_btdesc = substr($_POST["supplier$x"],0,25) .' - ' .   $_POST["fld_btno$x"];
        $account = $_POST["account$x"];
        $this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid,fld_btnoreff,fld_btamt01,fld_coaid,fld_btdesc,fld_bedivid,fld_btiid,fld_locid)
              values
              ('$node' ,'$fld_btid','$purchase_no','$fld_btamt','$account','$fld_btdesc','$fld_bedivid','$fld_btp01','$location')");
      }
    }

    $this->db->query("update tbl_bth set
                      fld_btamt=(select sum(ifnull(fld_btamt01,0)) from tbl_btd_finance where fld_btidp='$node'),
                      fld_btbalance = ifnull(fld_btamt,0) + ifnull(fld_btuamt,0)
                      where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000SUPPLIER_PAYMENT/edit/$node?act=edit";
    redirect($url);
  }

  public function ajax_list(){
        $this->load->helper('url');
	$list = $this->testjs->get_datatables();
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $monitoring) {
		$no++;
		$row = array();
		$row[] = $no;
                $row[] = $monitoring->fld_btid;
		$row[] = '<a href="javascript:void(0)" onclick="edit_monitoring('."'".$monitoring->fld_btid."'".')" title="Edit"><i class="fa fa-pencil-square fa-2x danger" style="font-size:18px; color:blue"></i>&nbsp;</a>
		         <a href="javascript:void(0)" onclick="delete_monitoring('."'".$monitoring->fld_btid."'".')" title="Delete"><i class="fa fa-trash-o " style="font-size:18px; color:red"></i>&nbsp;</a>';

	       $data[] = $row;
	}

	$output = array(
		"draw" => $_POST['draw'],
		"recordsTotal" => $this->testjs->count_all(),
		"recordsFiltered" => $this->testjs->count_filtered(),
		"data" => $data,
		);
	//output to json format
	echo json_encode($output);
 }

  function paymentShipping() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $location = $this->session->userdata('location');
    $costumer_text = $_POST["fld_btp021"];
    $customer =  $_POST["fld_beid1"];
    $node =  $_POST["node"];
    $tot_purchase = 0;
    $tot_invoice = 0;
    $tot_tax01 = 0;
    $tot_tax02 = 0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btamt = $_POST["fld_btamt$x"];
        $fld_bedivid = $_POST["fld_bedivid$x"];
        $fld_btnoreff = $_POST["fld_btnoreff$x"];
        $fld_btnodoc = $_POST["fld_btnodoc$x"];
        $fld_btdesc = substr($_POST["shipping$x"],0,25) .' - ' .   $_POST["fld_btno$x"];
        $account = $_POST["account$x"];
        $this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid,fld_btnoreff,fld_btnodoc,fld_btamt01,fld_coaid,fld_btdesc,fld_bedivid,fld_btiid,fld_locid)
              values
              ('$node' ,'$fld_btid','$fld_btnoreff','$fld_btnodoc','$fld_btamt','$account','$fld_btdesc','$fld_bedivid','$fld_btp01','$location')");
      }
    }

    $this->db->query("update tbl_bth set
                      fld_btamt=(select sum(ifnull(fld_btamt01,0)) from tbl_btd_finance where fld_btidp='$node'),
                      fld_btbalance = ifnull(fld_btamt,0) + ifnull(fld_btuamt,0)
                      where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000SHIPPING_PAYMENT/edit/$node?act=edit";
    redirect($url);
  }

  function CopyDataImp($id)
  {
		$sql="select fld_btno, fld_baidc, fld_btiid, fld_btp15, fld_btuamt, fld_bttyid, fld_baido, fld_baidv
		from tbl_bth where fld_btid=$id";
		$dt=$this->db->query($sql)->row();
		$trans_no = $this->mkautono(2,1);
		$tgl=date("Y-m-d H:i:s");
		$fld_baidp=$this->session->userdata('ctid');
		$this->db->query("insert into tbl_bth (fld_btno, fld_baidc, fld_btiid, fld_btp15, fld_btuamt, fld_bttyid, fld_baido, fld_baidv, fld_btstat,fld_btdt, fld_baidp)
        value ('$trans_no','$dt->fld_baidc',$dt->fld_btiid,'$dt->fld_btp15','$dt->fld_btuamt','1','$dt->fld_baido','$dt->fld_baidv',1, '$tgl','$fld_baidp')");

        $last_insert_id = $this->db->insert_id();
        $url = base_url() . "index.php/page/form/78000JOB_ORDER_IMP/edit/".$last_insert_id."?act=edit";
		redirect($url);
  }


  function OverPayment() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $Jusd=0; $Jidr=0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btno = $_POST["fld_btno$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $fld_btamt01 = $_POST["OverUsd$x"];
        $fld_btamt02 = $_POST["OverIdr$x"];
        $fld_btid = $_POST["fld_btid$x"];

        $this->db->query("insert into tbl_btd_over_payment
        (fld_btidp,fld_btreffid, fld_btno, fld_btamt01,fld_btamt02)
        values
        ($fld_btidp, '$fld_btid', '$fld_btno','$fld_btamt01','$fld_btamt02')");

	$Jusd=$Jusd+$fld_btamt01;
	$Jidr=$Jidr+$fld_btamt02;
      }
    }
    $this->db->query("update tbl_bth
                      set
                      fld_btp14= ifnull((select sum(fld_btamt01) from tbl_btd_over_payment where fld_btidp = $fld_btidp),0),
                      fld_btp10= ifnull((select sum(fld_btamt02) from tbl_btd_over_payment where fld_btidp = $fld_btidp),0),
                      where fld_btid=$fld_btidp");

    $url = base_url() . "index.php/page/form/78000JO_SETTLEMENT/edit/$fld_btidp?act=edit";
    redirect($url);
  }
   function createPODTrailer () {
    $btid = $this->uri->segment(3);
    $tynextid = $this->uri->segment(4);
    $tynextform = $this->uri->segment(5);
    $nextformid = $this->uri->segment(6);
    $plan = $this->uri->segment(8);
    $formfield = $this->form->getformfieldbyName($tynextform);
    $nextsubform =  $this->form->getsubform($nextformid);
    $subform =  $this->form->getsubform(432);

    ###Prepare Data
      $data =  $this->form->getdatafup($btid);
      foreach ($formfield as $rformfield) {
        if ($rformfield->fld_formfieldcopyval != "") {
          if (substr($rformfield->fld_formfieldcopyval,0,4) == "fld_") {
            $data[0][$rformfield->fld_formfieldnm] = $data[0][$rformfield->fld_formfieldcopyval];
          } else {
            $data[0][$rformfield->fld_formfieldnm] = $rformfield->fld_formfieldcopyval;
          }
        }
        if ($rformfield->fld_formfieldcopy == 1) {
          $data[0][$rformfield->fld_formfieldnm] = '';
        }
      }
      $data[0]['fld_btid'] = '';
      $data[0]['fld_btstat'] = '1';
      $data[0]['fld_bttyid'] = $tynextid;
      $data[0]['fld_btno'] = $this->mkautono($data[0]['fld_baido'],$tynextid);
      $data[0]['fld_baidp'] =  $this->session->userdata('ctid');
      $data[0]['fld_btloc'] =  $this->session->userdata('location');
      $data[0]['fld_btdt'] = date('Y-m-d H:i:s');

      $cek = $this->db->query("select count(1) 'cnt' from tbl_btr t0 where t0.fld_btrsrc = $btid and fld_btrdsttyid = 80");

      if ($cek->row()->cnt > 0) {
        echo "FAIL";
      } else {
       ###Insert Data
        $sfinsert = $this->form->getforminsert('tbl_bth', $data[0]);
        $fup_lid = $this->db->insert_id();

        ### Add Subform
        $this->db->query("insert into tbl_btd (fld_btidp, fld_btreffid, fld_btno, fld_btiid, fld_btuamt01, fld_btqty01, fld_btamt01, fld_btdesc, fld_btdt, fld_btdtsa, fld_btdtso, fld_btp01, fld_btp02, fld_btp03)
                      select $fup_lid, 0, t0.fld_btno, t0.fld_btiid, t0.fld_btuamt01, t0.fld_btqty01, t0.fld_btamt01, t0.fld_btdesc, t0.fld_btdt, t0.fld_btdtsa, t0.fld_btdtso, t0.fld_btp01, t0.fld_btp02, t0.fld_btp03
                      from tbl_btd t0 where t0.fld_btidp = $btid");
        $this->db->query("insert into tbl_btd_truck_cost (fld_btidp, fld_btflag, fld_btiid, fld_btdt, fld_btamt01, fld_btcmt, fld_btp01)
                      select $fup_lid, t0.fld_btflag, t0.fld_btiid, t0.fld_btdt, t0.fld_btamt01, t0.fld_btcmt, t0.fld_btp01 from tbl_btd_truck_cost t0 where t0.fld_btidp = $btid");

        $data_log = array(
          'fld_acclogtyid' => '5' ,
          'fld_accloghost' => $_SERVER['REMOTE_ADDR'] ,
          'fld_acclogdt' => date('Y-m-d H:i:s'),
          'fld_acclogcmt' => 'User ' . $this->session->userdata('usernm') . ' Follow Up record number from ' . $btid . ' to ' . $fup_lid . ' on table ' . $fld_tblnm
          );
        $this->db->insert('tbl_acclog', $data_log);

        ###Insert BTR
        $query = $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) values($btid,$fup_lid,$tynextid)");
        $fup_subform = $this->form->getsubform($formid);
        foreach ($fup_subform as $rfup_subform) {
          $datasf =  $this->form->getdatafupsub($rfup_subform->fld_formrelc,$rfup_subform->fld_formrelp,$btid,$rfup_subform->fld_tblnm);
          $count = count($datasf);
          for ($i=0; $i<$count; ++$i) {
        ###Prepare Data
            $datasf[$i]['fld_btid'] = '';
            $datasf[$i][$rfup_subform->fld_formrelc] = $fup_lid;
            $datasf[$i]['fld_bttyid'] = $tynextid;
            ###Insert Data
            $sfinsert = $this->form->getforminsert($rfup_subform->fld_tblnm, $datasf[$i]);
          }
        }
      }
      $query = $this->db->query("update dnxapps.tbl_bti t0
                               left join tbl_bth t1 on t1.fld_btp12 = t0.fld_btiid
                               left join hris.tbl_emp t2 on t2.fld_empid = t1.fld_btp11
                               left join hris.tbl_emp_osrc t3 on t3.fld_empid = t1.fld_btp11
                               set t0.fld_btip12 = 1,
                               t2.fld_empactstat = 1,
                               t3.fld_empactstat = 1
                               where t1.fld_btid = $btid");
      if ($plan == 'TSO') {
        $fup_url = base_url() . "index.php/page/view/78000POD_CHECKLIST2";
      } else {
        $fup_url = base_url() . "index.php/page/view/78000POD_CHECKLIST";
      }
      redirect($fup_url);
    }




   function OverPaymentCA() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $Jusd=0; $Jidr=0;

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
            $sid= $_POST["sid$x"];
        $fld_btno = $_POST["fld_btno$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $fld_btamt01 = $_POST["OverUsd$x"];
        $fld_btamt02 = $_POST["OverIdr$x"];
        $fld_btid = $_POST["fld_btid$x"];

        $comp = ($_POST["comp$x"] == 'REMA') ? '1' : '' ;

    $tyid = $this->db->query("select fld_bttyid 'tyid' from tbl_bth where fld_btid = $sid ")->row()->tyid;

    if ($tyid==2){
        $this->db->query("insert into tbl_btd_over_payment
        (fld_btidp,fld_btreffid, fld_btno, fld_btamt01,fld_btamt02)
        values
        ('$sid', '$fld_btid', '$fld_btno','$fld_btamt01','$fld_btamt02')");

        $Jusd=$Jusd+$fld_btamt01;
        $Jidr=$Jidr+$fld_btamt02;
      }
    }

    }

    if ($tyid==2) {
    $this->db->query("update tbl_bth
                      set
                      fld_btp14= ifnull((select sum(fld_btamt01) from tbl_btd_over_payment where fld_btidp = $sid),0),
                      fld_btp13= ifnull((select sum(fld_btamt02) from tbl_btd_over_payment where fld_btidp = $sid),0),
                      fld_btp23= '$comp'
                      where fld_btid=$sid");

    $url = base_url() . "index.php/page/form/78000JOCASH_ADVANCE/edit/$sid?act=edit";
    redirect($url);
    }
    else {

    $this->db->query("update tbl_bth
                      set
                      fld_btp04 = '$fld_btno',
                      fld_btbalance = if($fld_btamt02 = 0,$fld_btamt01,$fld_btamt02),
                      fld_btp23= '$comp'
                      where fld_btid=$sid");

    $url = base_url() . "index.php/page/form/78000CLOSING_OP/edit/$sid?act=edit";
    redirect($url);

    }

  }

  function OverPaymentCAEXP() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $Jusd=0; $Jidr=0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
            $sid= $_POST["sid$x"];
        $fld_btno = $_POST["fld_btno$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $fld_btamt01 = $_POST["OverUsd$x"];
        $fld_btamt02 = $_POST["OverIdr$x"];
        $fld_btid = $_POST["fld_btid$x"];

        $this->db->query("insert into tbl_btd_over_payment
        (fld_btidp,fld_btreffid, fld_btno, fld_btamt01,fld_btamt02)
        values
        ('$sid', '$fld_btid', '$fld_btno','$fld_btamt01','$fld_btamt02')");

        $Jusd=$Jusd+$fld_btamt01;
        $Jidr=$Jidr+$fld_btamt02;
      }
    }
    $this->db->query("update tbl_bth
                      set
                      fld_btp14= ifnull((select sum(fld_btamt01) from tbl_btd_over_payment where fld_btidp = $sid),0),
                      fld_btp13= ifnull((select sum(fld_btamt02) from tbl_btd_over_payment where fld_btidp = $sid),0)
                      where fld_btid=$sid");

    $url = base_url() . "index.php/page/form/78000JOCASH_ADVANCE_EXP/edit/$sid?act=edit";
    redirect($url);
  }

  /*
  function advanceApproval() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $trans_no = $this->mkautono(2,8);
    $this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat)
         values(2,'$trans_no',8,now(),'$fld_baidp',1)");
    $last_insert_id = $this->db->insert_id();
    $Jusd=0; $Jidr=0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        //$inv_no = $_POST["fld_btno$x"];
        $fld_btno = $_POST["fld_btno$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $fld_btamt01 = $_POST["idr$x"];
        $fld_btamt02 = $_POST["usd$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btp01 = $_POST["krani$x"];
        $fld_empid = $_POST["kraniid$x"];
        $fld_baidv = $_POST["fld_baidv$x"];

        $this->db->query("insert into tbl_btd_advaprv
        (fld_btidp,fld_btreffid, fld_btno, fld_btamt01,fld_btamt02,fld_btp01)
        values
        ($last_insert_id, '$fld_btid', '$fld_btno','$fld_btamt01','$fld_btamt02','$fld_btp01')");

        $Jusd=$Jusd+$fld_btamt02;
        $Jidr=$Jidr+$fld_btamt01;
      }
    }
    $this->db->query("update tbl_bth set fld_baidv=$fld_baidv , fld_btp01 = $fld_empid , fld_btamt='$Jidr', fld_btuamt='$Jusd'  where fld_btid=$last_insert_id");
    $url = base_url() . "index.php/page/form/78000ADVANCE_APPROVAL/edit/$last_insert_id?act=edit";
    redirect($url);
  }
*/

  function advanceApproval() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');
    $trans_no = $this->mkautono(2,8);
    $this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat,fld_btloc)
         values(2,'$trans_no',8,now(),'$fld_baidp',1,'$location')");
    $last_insert_id = $this->db->insert_id();
    $Jusd=0; $Jidr=0; $totopusd=0; $totopidr=0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        //$inv_no = $_POST["fld_btno$x"];
        $fld_btno = $_POST["fld_btno$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $fld_btamt01 = $_POST["advidr$x"];
        $fld_btamt02 = $_POST["advusd$x"];
        $fld_btamt03 = $_POST["opidr$x"];
        $fld_btamt04 = $_POST["opusd$x"];
        $fld_btamt05 = $_POST["payidr$x"];
        $fld_btamt06 = $_POST["payusd$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btp01 = $_POST["krani$x"];
        $fld_empid = $_POST["kraniid$x"];
        $fld_baidv = $_POST["fld_baidv$x"];
        $fld_userid =  $_POST["userid$x"];

        // Cek ASM
	if($location == 1 && $fld_userid != 1) {
 	$cek_asm = $this->db->query("select t0.fld_btidp from tbl_btd_doc t0 where t0.fld_btiid = $fld_btid");
    	if ($cek_asm->num_rows() > 0) {
        $stat = $this->db->query("select t0.fld_btstat 'status',t0.fld_btno 'asmno'
		                  from tbl_bth t0
				  left join tbl_btd_doc t1 on t1.fld_btidp = t0.fld_btid
				  where t1.fld_btiid = $fld_btid limit 1 ")->row();
        if($stat->status != 3){
              $this->ffis->message("Can't create APV! Please approve ASM transaction ($stat->asmno) first!");
	      exit();
         }
        }
	else{
		$this->ffis->message("Can't create APV! Please make ASM Transaction before!");
		exit();
	}
       }

        $this->db->query("insert into tbl_btd_advaprv
        (fld_btidp,fld_btreffid, fld_btno, fld_btamt01,fld_btamt02,fld_btamt03,fld_btamt04,fld_btamt05,fld_btamt06,fld_btp01)
        values
        ($last_insert_id, '$fld_btid', '$fld_btno','$fld_btamt01','$fld_btamt02','$fld_btamt03','$fld_btamt04','$fld_btamt05','$fld_btamt06','$fld_btp01')");

        $Jusd=$Jusd+$fld_btamt06;
        $Jidr=$Jidr+$fld_btamt05;
        $totopusd=$totopusd+$fld_btamt04;
        $totopidr=$totopidr+$fld_btamt03;
        $totadvusd=$totadvusd+$fld_btamt02;
        $totadvidr=$totadvidr+$fld_btamt01;
      }
    }
    $this->db->query("update tbl_bth set fld_baidv=$fld_baidv , fld_btp01 = $fld_empid , fld_btamt='$totadvidr', fld_btuamt='$totadvusd',
                      fld_btamt01='$totopidr', fld_btamt02='$totopusd', fld_btp02='$Jidr', fld_btp03='$Jusd' , fld_btp23=ifnull('$fld_userid',0)
                      where fld_btid=$last_insert_id");
    $url = base_url() . "index.php/page/form/78000ADVANCE_APPROVAL/edit/$last_insert_id?act=edit";
    redirect($url);
  }


  function aprvAPVBatch() {
    $count = $_POST["count"];
    $userid = $this->session->userdata('userid');
    $groupid =$this->session->userdata('group');
    $group_add =$this->session->userdata('group_add');
    $fld_aprvtktno = date('YmdHis');
    $z =0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btno = $_POST["btno$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $z =$z+1;

        $this->db->query("update tbl_bth set fld_btstat = 2 where fld_btid = '$fld_btid' and fld_bttyid = 8 limit 1");
        $this->db->query("insert into tbl_aprvtkt (fld_aprvtktno,fld_usergrpid,fld_aprvroleid,fld_aprvruleroleord,fld_btid,fld_aprvtktstat)
          value ($fld_aprvtktno,$groupid,3,1,'$fld_btid',1) ");
           	//advance approve
                $data = $this->db->query("select t1.fld_btid from tbl_btd_advaprv t0
                              left join tbl_bth t1 on t1.fld_btid = t0.fld_btreffid where t0.fld_btidp = '$fld_btid'");
                $data = $data->result();
                foreach ($data as $rdata) {
                        $query = $this->db->query("update tbl_bth set fld_btstat=3,fld_btdtsa =now() where fld_btid=$rdata->fld_btid limit 1");
                        $query1 = $this->db->query("update tbl_aprvtkt set fld_aprvtktstat=2 , fld_userid=$userid ,fld_aprvtktmoddt = now()
                        where fld_btid=$rdata->fld_btid and fld_aprvroleid=3 and fld_usergrpid in (ifnull('$groupid',0),ifnull('$group_add',0)) limit 1");
                }

           }
        }
    if ($z == 0){
     echo "<div align='center'>
         No Transaction Selected <br> , click <a href='javascript:history.back();refresh();'>here</a> to go back </div>";
     }else
    {

    echo "<div align='center'>
          Transaction Successfull<br> , click <a href='javascript:history.back();refresh();'>here</a> to go back </div>";
    }
  }

  function revINVBatch() {
    $count = $_POST["count"];
    $userid = $this->session->userdata('userid');
    $groupid =$this->session->userdata('group');
    $group_add =$this->session->userdata('group_add');
    $fld_aprvtktno = date('YmdHis');
    $z =0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btno = $_POST["btno$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $z =$z+1;

        $query = $this->db->query("update tbl_bth set fld_btstat=2 where fld_btid=$fld_btid limit 1");
        $query = $this->db->query("update tbl_aprvtkt set fld_aprvtktstat=1 where fld_btid=$fld_btid and fld_usergrpid=$groupid limit 1");
        ### Delete Journal Record
        $this->db->query("delete from tbl_journal where fld_btid=$fld_btid");



        }
      }
    if ($x == 0){
     echo "<div align='center'>
         No Transaction Selected <br> , click <a href='javascript:history.back();refresh();'>here</a> to go back </div>";
     }else
    {

 echo "<div align='center'>
          Transaction Successfull<br> , click <a href='javascript:history.back();refresh();'>here</a> to go back </div>";
    }
  }

  function settlementReceipt() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');
    $trans_no = $this->mkautono(2,9);
    //$this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat)
    //     values(2,'$trans_no',9,now(),'$fld_baidp',1)");
    //$last_insert_id = $this->db->insert_id();
    $Jusd=0; $Jidr=0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {

        $sid= $_POST["sid$x"];
        $fld_btno = $_POST["fld_btno$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btp01 = $_POST["krani$x"];
        $fld_empid = $_POST["kraniid$x"];
        $fld_baidv = $_POST["fld_baidv$x"];
        $fld_btp12 = $_POST["fld_btp12$x"];
        $fld_btamt = $_POST["fld_btamt$x"];
        $fld_btp05 = $_POST["fld_btp05$x"];
        $fld_btp13 = $_POST["fld_btp13$x"];
        $fld_btp08 = $_POST["fld_btp08$x"];
        $fld_btp07 = $_POST["fld_btp07$x"];
        $fld_btp06 = $_POST["fld_btp06$x"];
        $fld_btp09 = $_POST["fld_btp09$x"];
        $fld_userid = $_POST["userid$x"];
        $paytype = $_POST["paytype$x"];

//echo $paytype;
//exit();

        $this->db->query("insert into tbl_btd_receipt
        (fld_btidp,fld_btreffid, fld_btno, fld_btamt01,fld_btamt02,fld_btamt03,fld_btamt04,fld_btamt05,fld_btamt06,fld_btamt07,fld_btamt08,fld_btp01,fld_btiid)
         values
        ($sid, '$fld_btid', '$fld_btno','$fld_btp12','$fld_btamt','$fld_btp05','$fld_btp13','$fld_btp08','$fld_btp07','$fld_btp06','$fld_btp09','$fld_btp01','$paytype')");


      }
    }
    $this->db->query("update tbl_bth set fld_baidv=$fld_baidv
                      where fld_btid=$sid");
    $url = base_url() . "index.php/page/form/78000SETTLE_RECEIPT/edit/$sid?act=edit";
    redirect($url);
  }


  function settlementApproval() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');
    $trans_no = $this->mkautono(2,9);
    //$this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat)
    //     values(2,'$trans_no',9,now(),'$fld_baidp',1)");
    //$last_insert_id = $this->db->insert_id();
    $Jusd=0; $Jidr=0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {

        $sid= $_POST["sid$x"];
        $fld_btno = $_POST["fld_btno$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btp01 = $_POST["krani$x"];
        $fld_empid = $_POST["kraniid$x"];
        $fld_baidv = $_POST["fld_baidv$x"];
        $fld_btp12 = $_POST["fld_btp12$x"];
        $fld_btamt = $_POST["fld_btamt$x"];
        $fld_btp05 = $_POST["fld_btp05$x"];
	$fld_btp13 = $_POST["fld_btp13$x"];
	$fld_btp08 = $_POST["fld_btp08$x"];
	$fld_btp07 = $_POST["fld_btp07$x"];
	$fld_btp06 = $_POST["fld_btp06$x"];
	$fld_btp09 = $_POST["fld_btp09$x"];
        $fld_userid = $_POST["userid$x"];


        $this->db->query("insert into tbl_btd_advaprv
        (fld_btidp,fld_btreffid, fld_btno, fld_btamt01,fld_btamt02,fld_btamt03,fld_btamt04,fld_btamt05,fld_btamt06,fld_btamt07,fld_btamt08,fld_btp01)
        values
        ($sid, '$fld_btid', '$fld_btno','$fld_btp12','$fld_btamt','$fld_btp05','$fld_btp13','$fld_btp08','$fld_btp07','$fld_btp06','$fld_btp09','$fld_btp01')");

        $adv_idr=$adv_idr+$fld_btp12;
        $spent_idr=$spent_idr+$fld_btamt;
        $op_idr=$Jusd+$fld_btp05;
        $remain_idr=$remain_idr+$fld_btp13;

        $adv_usd=$adv_usd+$fld_btp08;
        $spent_usd=$spent_usd+$fld_btp07;
        $op_usd=$op_usd+$fld_btp06;
        $remain_usd=$remain_usd+$fld_btp09;

      }
    }
    $this->db->query("update tbl_bth set fld_baidv=$fld_baidv , fld_btp01 = $fld_empid ,
                      fld_btp12='$adv_idr',
                      fld_btamt='$spent_idr',
                      fld_btp05='$op_idr',
                      fld_btp13='$remain_idr',
                      fld_btp08='$adv_usd',
                      fld_btp07='$spent_usd',
                      fld_btp06='$op_usd',
                      fld_btp09='$remain_usd',
		      fld_btloc='$location',
                      fld_btp23='$fld_userid'
                      where fld_btid=$sid");
    $url = base_url() . "index.php/page/form/78000SETTLEMENT_APPROVAL/edit/$sid?act=edit";
    redirect($url);
  }

  function settlementApproval2() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');
    #$trans_no = $this->mkautono(2,95);
    #echo $trans_no ;
#exit();
    //$this->db->query("insert into tbl_bth (fld_baido,fld_btno,fld_bttyid,fld_btdt,fld_baidp,fld_btstat)
    //     values(2,'$trans_no',9,now(),'$fld_baidp',1)");
    //$last_insert_id = $this->db->insert_id();
    $Jusd=0; $Jidr=0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {

        $sid= $_POST["sid$x"];
        $fld_btno = $_POST["fld_btno$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btp01 = $_POST["krani$x"];
        $fld_empid = $_POST["kraniid$x"];
        $fld_baidv = $_POST["fld_baidv$x"];
        $fld_btp12 = $_POST["fld_btp12$x"];
        $fld_btamt = $_POST["fld_btamt$x"];
        $fld_btp05 = $_POST["fld_btp05$x"];
        $fld_btp13 = $_POST["fld_btp13$x"];
        $fld_btp08 = $_POST["fld_btp08$x"];
        $fld_btp07 = $_POST["fld_btp07$x"];
        $fld_btp06 = $_POST["fld_btp06$x"];
        $fld_btp09 = $_POST["fld_btp09$x"];
        // $jstid = $_POST["jstid$x"];



        $this->db->query("insert into tbl_btd_finance
        (fld_btidp,fld_btamt01,fld_btnoreff,fld_btreffid,fld_btreffid2,fld_btdesc,fld_empid,fld_coaid,fld_btdocreff,fld_bedivid,fld_btflag,fld_locid,fld_btp12)
        select '$sid',t0.fld_btamt01,t2.fld_btno,'$fld_btid',ifnull(t0.fld_bt03,0),
        concat(t3.fld_benm,'-',t1.fld_btinm),'$fld_empid',705,if(t2.fld_baidv = 13,t2.fld_btp23,t2.fld_btp08),'$fld_baidv',5,'$location',t0.fld_costtype
        from tbl_btd_cost t0
        left join tbl_bti t1 on t1.fld_btiid = t0.fld_costtype and t1.fld_bticid =1
        left join tbl_bth t2 on t2.fld_btid = t0.fld_bt01 and t2.fld_bttyid in (6,10,1,65)
        left join dnxapps.tbl_be t3 on t3.fld_beid =t2.fld_baidc and t3.fld_betyid=5 #and t3.fld_bestat = 1
        where t0.fld_btidp = $fld_btid ");

        $adv_idr=$adv_idr+$fld_btp12;
        $spent_idr=$spent_idr+$fld_btamt;
        $op_idr=$Jusd+$fld_btp05;
        $remain_idr=$remain_idr+$fld_btp13;

        $adv_usd=$adv_usd+$fld_btp08;
        $spent_usd=$spent_usd+$fld_btp07;
        $op_usd=$op_usd+$fld_btp06;
        $remain_usd=$remain_usd+$fld_btp09;

        #update status settlement
        $this->db->query("update tbl_bth set fld_btstat = 3, fld_btdtsa = now()
                          where fld_btid ='$fld_btid' limit 1");

   }
    }
   /* $this->db->query("update tbl_bth set fld_baidv=$fld_baidv , fld_btp01 = $fld_empid ,
                      fld_btp12='$adv_idr',
                      fld_btamt='$spent_idr',
                      fld_btp05='$op_idr',
                      fld_btp13='$remain_idr',
                      fld_btp08='$adv_usd',
                      fld_btp07='$spent_usd',
                      fld_btp06='$op_usd',
                      fld_btp09='$remain_usd'
                      where fld_btid=$sid");*/
     $amount = $this->db->query("select  sum(t0.fld_btamt01) 'price', t1.fld_btuamt 'advance' from tbl_btd_finance t0
                                 left join tbl_bth t1 on t1.fld_btid = t0.fld_btidp
				 where t0.fld_btidp =  '$sid'")->row();
     $total_adv = 0;
     #$total_adv = $amount->price + $amount->advance + $total_adv;

     $this->db->query("update tbl_bth set fld_btamt = $amount->price,fld_btp01 = (fld_btuamt-fld_btamt)   where fld_btid ='$sid' limit 1");
    $url = base_url() . "index.php/page/form/78000BANK_OUT/edit/$sid?act=edit";
    redirect($url);
  }

  function settlementApprovalGJL() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');

    $Jusd=0; $Jidr=0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {

        $sid= $_POST["sid$x"];
        $fld_btno = $_POST["fld_btno$x"];
        $fld_btidp = $_POST["fld_btidp$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btp01 = $_POST["krani$x"];
        $fld_empid = $_POST["kraniid$x"];
        $fld_baidv = $_POST["fld_baidv$x"];
        $fld_btp12 = $_POST["fld_btp12$x"];
        $fld_btamt = $_POST["fld_btamt$x"];
        $fld_btp05 = $_POST["fld_btp05$x"];
        $fld_btp13 = $_POST["fld_btp13$x"];
        $fld_btp08 = $_POST["fld_btp08$x"];
        $fld_btp07 = $_POST["fld_btp07$x"];
        $fld_btp06 = $_POST["fld_btp06$x"];
        $fld_btp09 = $_POST["fld_btp09$x"];
        $company = $_POST["company$x"];
        // $jstid = $_POST["jstid$x"];

	 $this->db->query("insert into tbl_btd_finance
        (fld_btidp,fld_btamt01,fld_btnoreff,fld_btreffid2,fld_btdesc,fld_empid,fld_coaid,fld_btdocreff,fld_bedivid,fld_locid,fld_btp12)
        select '$sid',t0.fld_btamt01,t2.fld_btno,'$fld_btid',
        concat(t3.fld_benm,'-',t1.fld_btinm),'$fld_empid',705,if(t2.fld_baidv = 13,t2.fld_btp23,t2.fld_btp08),'$fld_baidv',1,t0.fld_costtype
        from tbl_btd_cost t0
        left join tbl_bti t1 on t1.fld_btiid = t0.fld_costtype and t1.fld_bticid =1
        left join tbl_bth t2 on t2.fld_btid = t0.fld_bt01 and t2.fld_bttyid in (6,10,1,65)
        left join dnxapps.tbl_be t3 on t3.fld_beid =t2.fld_baidc and t3.fld_betyid=5 and t3.fld_bestat = 1
        where t0.fld_btidp = $fld_btid ");

	$this->db->query("insert into tbl_btd_finance
        (fld_btidp,fld_btamt01,fld_btnoreff,fld_btreffid2,fld_btdesc,fld_empid,fld_coaid,fld_btdocreff,fld_bedivid,fld_locid,fld_btp12)
        select '$sid',concat('-',t0.fld_btamt01),t2.fld_btno,'$fld_btid',
        concat(t3.fld_benm,'-',t1.fld_btinm),'$fld_empid',1313,if(t2.fld_baidv = 13,t2.fld_btp23,t2.fld_btp08),'$fld_baidv',1,t0.fld_costtype
        from tbl_btd_cost t0
        left join tbl_bti t1 on t1.fld_btiid = t0.fld_costtype and t1.fld_bticid =1
        left join tbl_bth t2 on t2.fld_btid = t0.fld_bt01 and t2.fld_bttyid in (6,10,1,65)
        left join dnxapps.tbl_be t3 on t3.fld_beid =t2.fld_baidc and t3.fld_betyid=5 and t3.fld_bestat = 1
        where t0.fld_btidp = $fld_btid ");

         #update status settlement
        $this->db->query("update tbl_bth set fld_btstat = 3, fld_btdtsa = now()
        	          where fld_btid ='$fld_btid' limit 1");


                 }
    }

  $amount = $this->db->query("select  t0.fld_btamt 'total_spent', t0.fld_btp12 'advance',t0.fld_btp13 'remain',t0.fld_btp05 'op'
                              from tbl_bth t0
                              where t0.fld_btid =  '$fld_btid'")->row();

  if($amount->op > 0) {
  $this->db->query("insert into tbl_btd_finance
        (fld_btidp,fld_btamt01,fld_btnoreff,fld_btreffid2,fld_btdesc,fld_empid,fld_coaid,fld_btdocreff,fld_bedivid,fld_locid)
        select '$sid','$amount->op',t0.fld_btno,'$fld_btid',
        'OVER PAYMENT','$fld_empid',698,'','$fld_baidv',1
        from tbl_bth t0
        where t0.fld_btid = $fld_btid ");

  $this->db->query("insert into tbl_btd_finance
        (fld_btidp,fld_btamt01,fld_btnoreff,fld_btreffid2,fld_btdesc,fld_empid,fld_coaid,fld_btdocreff,fld_bedivid,fld_locid)
        select '$sid',concat('-','$amount->op'),t0.fld_btno,'$fld_btid',
        'PEMBALIK OVER PAYMENT','$fld_empid',1316,'','$fld_baidv',1
        from tbl_bth t0
        where t0.fld_btid = $fld_btid ");
  }

   #update header GJL
   $this->db->query("update tbl_bth set fld_btp08 = '$amount->total_spent',fld_btuamt = '$amount->advance',
                     fld_btp07 = '$amount->remain', fld_btp09 = '$amount->op' , fld_btdesc = '$fld_btno', fld_btp23 = '$company'
                     where fld_btid ='$sid' limit 1");


    $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$sid?act=edit";
    redirect($url);
  }


  function JOPartOff() {
    $fld_baidp = $this->session->userdata('ctid');
    $fld_btid =  $this->uri->segment(3);
    $location = $this->session->userdata('location');
    $cek = $this->db->query("select count(1) 'cnt' from tbl_btr where fld_btrsrc = $fld_btid and fld_btrdsttyid=65")->row()->cnt;
    $cek_partoff = $this->db->query("select fld_btidp 'partoff' from tbl_bth where fld_btid = $fld_btid and fld_bttyid = 1")->row()->partoff;
    if($cek_partoff != 1) {
     echo "Part Off flag doesn't checked!<br>Please Check Part Off flag!";
     exit();
    }

    $query = $this->db->query("insert into tbl_bth (fld_btstat,fld_baido,fld_baidv,fld_btnoctr,fld_btno,fld_btdt,fld_bttaxno,fld_btp27,fld_bttax,fld_btiid,fld_btflag,
                               fld_baidc,fld_btp01,fld_btdtso,fld_btp02,fld_btdtsa,fld_btp13,fld_btqty,fld_btnoreff,fld_btp06,fld_btp17,fld_btp10,fld_btp07,
                               fld_btp14,fld_btp08,fld_btp09,fld_btp03,fld_btuamt,fld_btp04,fld_btp05,fld_btidp,fld_btp15,fld_clsdt,fld_btp18,fld_btp20,
                               fld_btp23,fld_btp12,fld_btdesc,fld_baidp,fld_lup,fld_btnoalt,fld_bttyid,fld_btloc)
                               select fld_btstat,fld_baido,fld_baidv,$cek + 1,concat(substr(fld_btno,1,18),' - ',$cek+1),fld_btdt,fld_bttaxno,
                               fld_btp27,fld_bttax,fld_btiid,fld_btflag,fld_baidc,fld_btp01,fld_btdtso,fld_btp02,fld_btdtsa,fld_btp13,fld_btqty,fld_btnoreff,
                               fld_btp06,fld_btp17,fld_btp10,fld_btp07,fld_btp14,fld_btp08,fld_btp09,fld_btp03,fld_btuamt,fld_btp04,fld_btp05,fld_btidp,
                               fld_btp15,fld_clsdt,fld_btp18,fld_btp20,fld_btp23,fld_btp12,fld_btdesc,$fld_baidp,fld_lup,fld_btnoalt,65,$location
                               from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    $last_insert_id = $this->db->insert_id();
    //$this->db->query("insert into tbl_btd_container (fld_btidp,fld_contnum,fld_btp06,fld_conttype,fld_contsize,fld_btp10,fld_btp01,fld_btp03,fld_btp04,
    //                  fld_btp05,fld_btp07,fld_btp02)
    //                  select $last_insert_id, fld_contnum,fld_btp06,fld_conttype,fld_contsize,fld_btp10,fld_btp01,fld_btp03,fld_btp04,
    //                  fld_btp05,fld_btp07,fld_btp02 from tbl_btd_container where fld_btidp = $fld_btid");
    $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) value ($fld_btid,$last_insert_id,65)");
    $url = base_url() . "index.php/page/form/78000JOB_ORDER_IMP/edit/$last_insert_id?act=edit";
    redirect($url);
  }

   function JobCBU() {
    $fld_baidp = $this->session->userdata('ctid');
    $fld_btid =  $this->uri->segment(3);
    $cek = $this->db->query("select count(1) 'cnt' from tbl_btr where fld_btrsrc = $fld_btid and fld_btrdsttyid=65")->row()->cnt;
    $cek_cbu = $this->db->query("select fld_bttax 'cbu' from tbl_bth where fld_btid = $fld_btid and fld_bttyid = 1")->row()->cbu;
    if($cek_cbu != 4) {
     echo "Not a CBU job! Please Check Import Type Field.";
     exit();
    }

    $query = $this->db->query("insert into tbl_bth (fld_btstat,fld_baido,fld_baidv,fld_btnoctr,fld_btno,fld_btdt,fld_bttaxno,fld_btp27,fld_bttax,fld_btiid,fld_btflag,
                               fld_baidc,fld_btp01,fld_btdtso,fld_btp02,fld_btdtsa,fld_btp13,fld_btqty,fld_btnoreff,fld_btp06,fld_btp17,fld_btp10,fld_btp07,
                               fld_btp14,fld_btp08,fld_btp09,fld_btp03,fld_btuamt,fld_btp04,fld_btp05,fld_btidp,fld_btp15,fld_clsdt,fld_btp18,fld_btp20,
                               fld_btp23,fld_btp12,fld_btdesc,fld_baidp,fld_lup,fld_btnoalt,fld_bttyid)
                               select fld_btstat,fld_baido,fld_baidv,$cek + 1,concat(substr(fld_btno,1,18),' - ','CBU',$cek+1),fld_btdt,fld_bttaxno,
                               fld_btp27,fld_bttax,fld_btiid,fld_btflag,fld_baidc,fld_btp01,fld_btdtso,fld_btp02,fld_btdtsa,fld_btp13,fld_btqty,fld_btnoreff,
                               fld_btp06,fld_btp17,fld_btp10,fld_btp07,fld_btp14,fld_btp08,fld_btp09,fld_btp03,fld_btuamt,fld_btp04,fld_btp05,fld_btidp,
                               fld_btp15,fld_clsdt,fld_btp18,fld_btp20,fld_btp23,fld_btp12,fld_btdesc,$fld_baidp,fld_lup,fld_btnoalt,65
                               from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    $last_insert_id = $this->db->insert_id();

    $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) value ($fld_btid,$last_insert_id,65)");
    $url = base_url() . "index.php/page/form/78000JOB_ORDER_IMP/edit/$last_insert_id?act=edit";
    redirect($url);
  }

   function JOPartOffExp() {
    $fld_baidp = $this->session->userdata('ctid');
    $fld_btid =  $this->uri->segment(3);
    $location = $this->session->userdata('location');
    $cek = $this->db->query("select count(1) 'cnt' from tbl_btr where fld_btrsrc = $fld_btid and fld_btrdsttyid=93")->row()->cnt;
    $cek_partoff = $this->db->query("select fld_btidp 'partoff' from tbl_bth where fld_btid = $fld_btid and fld_bttyid = 6")->row()->partoff;
    if($cek_partoff != 1) {
     echo "Part Off flag doesn't checked!<br>Please Check Part Off flag!";
     exit();
    }

    $query = $this->db->query("insert into tbl_bth (fld_btstat,fld_baido,fld_baidv,fld_btnoctr,fld_btno,fld_btdt,fld_bttaxno,fld_btp27,fld_bttax,fld_btiid,fld_btflag,
                               fld_baidc,fld_btp01,fld_btdtso,fld_btp02,fld_btdtsa,fld_btp13,fld_btqty,fld_btnoreff,fld_btp06,fld_btp17,fld_btp10,fld_btp07,
                               fld_btp14,fld_btp08,fld_btp09,fld_btp03,fld_btuamt,fld_btp04,fld_btp05,fld_btidp,fld_btp15,fld_clsdt,fld_btp18,fld_btp20,
                               fld_btp23,fld_btp12,fld_btdesc,fld_baidp,fld_lup,fld_btnoalt,fld_btp25,fld_btp16,fld_btp28,fld_btp29,fld_btp24,fld_bttyid,fld_btloc)
                               select fld_btstat,fld_baido,fld_baidv,$cek + 1,concat(substr(fld_btno,1,18),' - ',$cek+1),fld_btdt,fld_bttaxno,
                               fld_btp27,fld_bttax,fld_btiid,fld_btflag,fld_baidc,fld_btp01,fld_btdtso,fld_btp02,fld_btdtsa,fld_btp13,fld_btqty,fld_btnoreff,
                               fld_btp06,fld_btp17,fld_btp10,fld_btp07,fld_btp14,fld_btp08,fld_btp09,fld_btp03,fld_btuamt,fld_btp04,fld_btp05,fld_btidp,
                               fld_btp15,fld_clsdt,fld_btp18,fld_btp20,fld_btp23,fld_btp12,fld_btdesc,$fld_baidp,fld_lup,fld_btnoalt,fld_btp25,fld_btp16,
                               fld_btp28,fld_btp29,fld_btp24,93,$location
                               from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    $last_insert_id = $this->db->insert_id();
    //$this->db->query("insert into tbl_btd_container (fld_btidp,fld_contnum,fld_btp06,fld_conttype,fld_contsize,fld_btp10,fld_btp01,fld_btp03,fld_btp04,
    //                  fld_btp05,fld_btp07,fld_btp02)
    //                  select $last_insert_id, fld_contnum,fld_btp06,fld_conttype,fld_contsize,fld_btp10,fld_btp01,fld_btp03,fld_btp04,
    //                  fld_btp05,fld_btp07,fld_btp02 from tbl_btd_container where fld_btidp = $fld_btid");
    $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) value ($fld_btid,$last_insert_id,93)");
    $url = base_url() . "index.php/page/form/78000EXT_JOB_ORDER/edit/$last_insert_id?act=edit";
    redirect($url);
  }


  function InvoiceMemo() {
    $fld_baidp = $this->session->userdata('ctid');
    $fld_btid =  $this->uri->segment(3);
    $cek = $this->db->query("select count(1) 'cnt' from tbl_btr where fld_btrsrc = $fld_btid and fld_btrdsttyid=68")->row()->cnt;
    //$cek_memo = $this->db->query("select fld_btidp 'memo' from tbl_bth where fld_btid = $fld_btid and fld_bttyid = 41")->row()->partoff;
    //if($cek_memo != 1) {
    // echo "Part Off flag doesn't checked!<br>Please Check Part Off flag!";
    // exit();
    //}

    $query = $this->db->query("insert into tbl_bth (fld_btstat,fld_baido,fld_baidv,fld_btnoctr,fld_btno,fld_btdt,fld_bttaxno,fld_btp15,fld_btflag,
                               fld_baidc,fld_baidp,fld_lup,fld_btnoalt,fld_btp03,fld_btamt,fld_btuamt,fld_btbalance,fld_btnoreff,fld_bttyid)
                               select fld_btstat,fld_baido,fld_baidv,$cek + 1,concat(substr(fld_btno,1,18),' - ',$cek+1),fld_btdt,fld_bttaxno,
                               fld_btp15,fld_btflag,fld_baidc,fld_baidp,fld_lup,fld_btnoalt,fld_btp03,fld_btamt,fld_btuamt,fld_btbalance,fld_btnoreff,68
                               from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    $last_insert_id = $this->db->insert_id();
    //$this->db->query("insert into tbl_btd_finance (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btflag,fld_btamt01,fld_btnoreff,fld_coaid)
    //                  select $last_insert_id, fld_btdesc,fld_btuamt01,fld_btqty01,fld_btflag,fld_btamt01,fld_btnoreff,fld_coaid
    //                  from tbl_btd_finance where fld_btidp = $fld_btid");
    $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) value ($fld_btid,$last_insert_id,68)");
    $url = base_url() . "index.php/page/form/78000INVOICE/edit/$last_insert_id?act=edit";
    redirect($url);
  }




  function invoiceroutine() {
    $fld_baidp  = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $trans_no = $this->mkautono(2,41);
    $fld_baidc  =  $this->uri->segment(3);
    $bill_id = $this->uri->segment(4);

    ### Insert Header Data

      $this->db->query("insert into tbl_bth
                      (fld_baido,fld_btno,fld_baidc,fld_bttyid,fld_btdt,fld_baidp,fld_btstat) VALUES
                      (2,'$trans_no','$fld_baidc',41,now(),$fld_baidp,1)");

	$last_insert_id = $this->db->insert_id();

    ### Update flag customer
      $this->db->query("update dnxapps.tbl_bebill set fld_bebillp01='$last_insert_id' where fld_bebillid = $bill_id and fld_bebillflag = 1 limit 1");

	$url = base_url() . "index.php/page/form/78000INVOICE/edit/$last_insert_id?act=edit";
    redirect($url);
  }
  function hidejobToinvoice(){
  $btid = $fname =  $this->uri->segment(3);
  $this->db->query("update tbl_bth set fld_btp38 = 5 where fld_btid = '$btid' and fld_bttyid in (1,6,10) limit 1");
  $url = base_url() . "index.php/page/view/78000JOBTOINVOICE";
    redirect($url);
  }

  function hidejobToinvoiceGJL(){
    $btid = $fname =  $this->uri->segment(3);
    $jobs = $this->db->query("
      SELECT
      t1.fld_btid 'dsvid',
      t1.fld_btno 'dsvno',
      t1.fld_btnoreff 'dsvreffno',
      t0.fld_btnoreff 'dsvjobno',
      t3.fld_btno 'jobno',
      t3.fld_btid 'jobid'

      FROM tbl_btd_finance t0
      LEFT JOIN tbl_bth t1 on t1.fld_btid=t0.fld_btidp
      LEFT JOIN tbl_bth t3 on t3.fld_btno=t0.fld_btnoreff

      WHERE
      t1.fld_bttyid=41
      and t1.fld_btid='$btid'
      and t0.fld_btnoreff not in(t1.fld_btnoreff)
      and t3.fld_bttyid in (1,6,10)
      and t3.fld_btid is not null

      GROUP BY t3.fld_btid
    ")->result();
    foreach ($jobs as $key => $item) {
      // echo '<br>' . $item->jobid . ' ' . $item->jobno;
      $this->db->query("update tbl_bth set fld_btp38 = 5 where fld_btid = '$item->jobid' and fld_bttyid in (1,6,10) limit 1");
    }
    // exit();
    $url = base_url() . "index.php/page/form/78000INVOICE/edit/$btid";
    redirect($url);
  }

  function jobToinvoice() {
    $fld_baidp  = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $btid = $fname =  $this->uri->segment(3);
    $location = $this->session->userdata('location');
    $trans_no = $this->mkautono(2,41);
    $data = $this->db->query("select
                              t0.fld_btid,
                              t0.fld_bttyid,
                              t0.fld_baidc,
                              t0.fld_btno,
                              t0.fld_baidv,
                              t0.fld_btiid,
                              t0.fld_bttax,
                              t0.fld_btp27 'edi',
                              t0.fld_btqty 'feet20',
                              t0.fld_btdt,
                              if(t0.fld_bttyid = 6,t0.fld_btp06 + t0.fld_btuamt,t0.fld_btp06) 'feet40',
                              t0.fld_btp10 'cbu',
                              t0.fld_btidp 'part_off',
                              t0.fld_btp09 'cbm',
                              t0.fld_btuamt 'weight',
                              t0.fld_btp34 'inspect',
                              t0.fld_btp37 'karantina'
                              from
                              tbl_bth t0
                              where
                              t0.fld_btid = $btid
                             ")->row();

   ## check JO
    $cek = $this->db->query("select * from tbl_bth t0 where t0.fld_btnoreff = '$data->fld_btno' and t0.fld_bttyid =41 and t0.fld_btstat !=5");
       if ($cek->num_rows() > 0) {

         #$message("Cannot create Invoice! Invoice Transaction was made before from this JO Number.");
         $this->ffis->message("Transaction failed : Invoice for this Job Number is already made.");
         exit();
       }


    ### Insert Header Data
    if($data->fld_baidv == 14 || $data->fld_baidv == 18) {
      ### Import
      $this->db->query("insert into tbl_bth
                      (fld_baido,fld_btno,fld_baidc,fld_btnoreff,fld_btnoalt,fld_bttyid,fld_btdt,fld_baidp,fld_btstat,fld_btidp,fld_baidv,
                       fld_btp15,fld_btp30,fld_btp04,fld_btp11,fld_btp24,fld_btp05,fld_btp06,fld_btp23,fld_btp02,fld_btp22,fld_btloc,fld_btp38
                      )
                      select 2,'$trans_no',t0.fld_baidc,t0.fld_btno,t0.fld_btp08,41,now(),
                      $fld_baidp,1,$btid,3,2,
                      t0.fld_btp13,t0.fld_btnoalt,t0.fld_btp09,
                      concat(t0.fld_btp03, ' / ', t0.fld_btp04),
                      t0.fld_btp31,t0.fld_btp29,
                      (select tx0.fld_docnum from tbl_btd_document tx0  where tx0.fld_btidp = $btid and tx0.fld_doctype = 380 limit 1),
                      (select tx0.fld_docdt from tbl_btd_document tx0  where tx0.fld_btidp = $btid and tx0.fld_doctype = 705 limit 1),
                      t0.fld_btp30,'$location',0
                      from tbl_bth t0 where t0.fld_btid = $btid");
    } else {
      ### Export
      $this->db->query("insert into tbl_bth
                      (fld_baido,fld_btno,fld_baidc,fld_btnoreff,fld_btnoalt,fld_bttyid,fld_btdt,fld_baidp,fld_btstat,fld_btidp,fld_baidv,
                       fld_btp15,fld_btp30,fld_btp04,fld_btp11,fld_btp24,fld_btp05,fld_btp06,fld_btp23,fld_btp02,
                       fld_btp22,
                       fld_btp20,
                       fld_btp18,fld_btloc,fld_btp38
                      )
                      select 2,'$trans_no',t0.fld_baidc,t0.fld_btno,t0.fld_btp08,41,now(),
                      $fld_baidp,1,$btid,3,if($data->fld_bttyid =10,4,1),
                      t0.fld_btp04,'',t0.fld_btp09,
                      t0.fld_btp03,
                      t0.fld_btp31,
                      '',
                      t0.fld_btp01,
                      (select tx0.fld_docdt from tbl_btd_document tx0  where tx0.fld_btidp = $btid and tx0.fld_doctype = 705 limit 1),
                      t0.fld_btp28,
                      t0.fld_btp01,
                      t0.fld_btp23,'$location',0
                      from tbl_bth t0
                      where t0.fld_btid = $btid");

    }

    $last_insert_id = $this->db->insert_id();
    ###Quotation
    $quo = $this->db->query("select t0.fld_btid,
                             t1.fld_btiid,t0.fld_btno,
                             t2.fld_btinm,
                             t1.fld_btflag 'exim',
                             t1.fld_btp02 'cont_size',
                             t1.fld_btamt01 'tariff',
                             t0.fld_btqty 'qty20',
                             t0.fld_btp06 'qty40'
                             from
			     dnxapps.tbl_bth t0
			     left join dnxapps.tbl_btd_quo_exp t1 on t1.fld_btidp = t0.fld_btid
			     left join dnxapps.tbl_bti t2 on t2.fld_btiid = t1.fld_btiid
			     left join dnxapps.tbl_tyval t3 on t3.fld_tyvalcd = t1.fld_btflag and t3.fld_tyid = 60
			     left join dnxapps.tbl_tyval t4 on t4.fld_tyvalcd = t1.fld_btp02 and t4.fld_tyid = 28
			     where
			     t0.fld_bttyid in (33,86)
			     and
                             ifnull(t2.fld_btiid,0) != 0
			     and
                             #date_format('$data->fld_btdt','%Y-%m-%d') between t0.fld_btdtsa and t0.fld_btdtso
                             t0.fld_btstat = 3
                             and
                             t0.fld_btdtp != '0000-00-00'
			     and t0.fld_baidc = $data->fld_baidc
                             #and if($data->fld_baidv = 13,1,t1.fld_btp01 = $data->fld_btiid)
                             and if($data->fld_baidv = 13,1,if($data->fld_btiid in (1,2,5),1,t1.fld_btp01 = $data->fld_btiid))
                             and t1.fld_btflag = if($data->fld_baidv = 13,1,2)
                             and t1.fld_btp03 = $data->fld_bttax
                             #group by t1.fld_btiid
                             order by t1.fld_btid
                             ");
    $quo = $quo->result();

    ### Terms and Conditions
    $sql = ("select t0.fld_btid,t2.fld_btp01 'terms_id',t0.fld_btno
                             from
                             dnxapps.tbl_bth t0
                             left join dnxapps.tbl_btd_quo_exp t1 on t1.fld_btidp = t0.fld_btid
                             left join dnxapps.tbl_btd_quo_terms t2 on t2.fld_btidp = t0.fld_btid
                             where
                             t0.fld_bttyid in (33,86)
                             and t2.fld_btp01 in (10468,14798,14822)
                             and
                             date_format('$data->fld_btdt','%Y-%m-%d') between t0.fld_btdtsa and t0.fld_btdtso
                             and t0.fld_baidc = $data->fld_baidc
                             #and if($data->fld_baidv = 13,1,t1.fld_btp01 = $data->fld_btiid)
                             and if($data->fld_baidv = 13,1,1)
                             and t1.fld_btflag = if($data->fld_baidv = 13,1,2)
                             and
                             t0.fld_btdtp != '0000-00-00'
                             and t1.fld_btp03 = $data->fld_bttax
                             group by t0.fld_btid
                             order by t0.fld_btid desc");

    $terms = $this->db->query($sql)->row();

    $prod = $this->db->query("select * from
                              dnxapps.tbl_bti t0
                              where
                              t0.fld_bticid = 4
                              and (t0.fld_btip03 = 1 or t0.fld_btip02=1)
                             ");
      foreach ($prod->result() as $rprod) {

        ### Handling Container
        if ($rprod->fld_btiid == 9300) {
          $tariff = 0;
          ### 20 Feet
          if($data->feet20 > 0) {
            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
             if ($rquo->fld_btiid == $rprod->fld_btiid && $rquo->cont_size == 1) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Handling container 20 feet',$tariff,$data->feet20,$tariff * $data->feet20,'$quotation_number','$data->fld_btno')");
            }
          }
          ### 40 Feet
          if($data->feet40 > 0) {
            $tariff = 0;
            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
              if ($rquo->fld_btiid == $rprod->fld_btiid && $rquo->cont_size == 2) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Handling container 40 feet',$tariff,$data->feet40,$tariff * $data->feet40,'$quotation_number','$data->fld_btno')");
            }
          }

           ### CBU
          if($data->cbu > 0) {
            $tariff = 0;
            $qty_cbu = 0;
            $qty_part = 0;

            $cek_part = $this->db->query("select count(*) as partoff from tbl_btr t0 where t0.fld_btrsrc = $btid and t0.fld_btrdsttyid=65")->row();
            $qty_part = $cek_part->partoff;


            if($qty_part > 0) {
            $cek = $this->db->query("select (t0.fld_btp10 + sum(t2.fld_btp10)) as cbu
                                     from tbl_bth t0
                                     left join tbl_btr t1 on t1.fld_btrsrc=t0.fld_btid
                                     left join tbl_bth t2 on t2.fld_btid = t1.fld_btrdst
                                     where t0.fld_btid = $btid and t1.fld_btrdsttyid = 65")->row();

            	$qty_cbu = $cek->cbu;
            }
            else {
                $qty_cbu = 0;

            }

            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
              if ($rquo->fld_btiid == $rprod->fld_btiid) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Handling Charge',$tariff,if($qty_cbu=0,$data->cbu,$qty_cbu),
                              $tariff * if($qty_cbu=0,$data->cbu,$qty_cbu), '$quotation_number','$data->fld_btno')");
            }
          }
        }

        ### Handling  Container First
        if ($rprod->fld_btiid == 9732) {

           ### 20 Feet
          if($data->feet20 > 0) {
             $tariff = 0;

            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
             if ($rquo->fld_btiid == $rprod->fld_btiid && $rquo->cont_size == 1) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Handling first container 20 feet',$tariff,1,$tariff * 1,'$quotation_number','$data->fld_btno')");
            }
          }
          ### 40 Feet
          if($data->feet40 > 0) {
            $tariff = 0;
            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
             if ($rquo->fld_btiid == $rprod->fld_btiid && $rquo->cont_size == 2) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Handling first container 40 feet',$tariff,1,$tariff * 1,'$quotation_number','$data->fld_btno')");
            }
          }

        }

        ### Handling  Container Next and Above
        if ($rprod->fld_btiid == 9733) {
           ### 20 Feet
           if($data->feet20 > 1 || $data->part_off == 1) {
            $tariff = 0;
            $qty_20 = 0;
            $qty_part = 0;

            $cek_part = $this->db->query("select count(*) as partoff from tbl_btr t0 where t0.fld_btrsrc = $btid and t0.fld_btrdsttyid=65")->row();
            $qty_part = $cek_part->partoff;

            if($qty_part >0){
            $cek = $this->db->query("select (sum(t2.fld_btqty)-sum(t0.fld_btqty)) as cont20
                                     from tbl_bth t0
                                     left join tbl_btr t1 on t1.fld_btrsrc=t0.fld_btid
                                     left join tbl_bth t2 on t2.fld_btid = t1.fld_btrdst
                                     where t0.fld_btid = $btid and t1.fld_btrdsttyid = 65")->row();

                $qty_20 = $cek->cont20;
            }

            else{
                $qty_20 = 0;
            }

            if ($data->feet20 > 1) {
            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
             if ($rquo->fld_btiid == $rprod->fld_btiid && $rquo->cont_size == 1) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Handling next container 20 feet',$tariff,if($qty_20=0,$data->feet20-1,$qty_20),
                              $tariff * if($qty_20=0,$data->feet20-1,$qty_20),'$quotation_number','$data->fld_btno')");
            }
           }
          }

	  ### 40 Feet
          if($data->feet40 > 1 || $data->part_off == 1) {
            $tariff = 0;
            $qty_40 = 0;
            $qty_part = 0;

            $cek_part = $this->db->query("select count(*) as partoff from tbl_btr t0 where t0.fld_btrsrc = $btid and t0.fld_btrdsttyid=65")->row();
            $qty_part = $cek_part->partoff;

            if($qty_part >0){
            $cek = $this->db->query("select (sum(t2.fld_btp06+t2.fld_btp19)-sum(t0.fld_btp06+t0.fld_btp19)) as cont40
                                     from tbl_bth t0
                                     left join tbl_btr t1 on t1.fld_btrsrc=t0.fld_btid
                                     left join tbl_bth t2 on t2.fld_btid = t1.fld_btrdst
                                     where t0.fld_btid = $btid and t1.fld_btrdsttyid = 65")->row();

                $qty_40 = $cek->cont40;
            }

            else{
                $qty_40 = 0;
            }

            if ($data->feet40 > 1) {
            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
             if ($rquo->fld_btiid == $rprod->fld_btiid && $rquo->cont_size == 2) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Handling next container 40 feet',$tariff,if($qty_40=0,$data->feet40-1,$qty_40),
                              $tariff * if($qty_40=0,$data->feet40-1,$qty_40),'$quotation_number','$data->fld_btno')");
            }
          }
         }
        }


        ### Form A Fee
        if ($rprod->fld_btiid == 14820) {
          $tariff = 0;
          $qty_cbu = 0;
          $qty_part = 0;

            $cek_part = $this->db->query("select count(*) as partoff from tbl_btr t0 where t0.fld_btrsrc = $btid and t0.fld_btrdsttyid=65")->row();
            $qty_part = $cek_part->partoff;

            if($qty_part >0){
            $cek = $this->db->query("select (t0.fld_btp10 + sum(t2.fld_btp10)) as cbu
                                     from tbl_bth t0
                                     left join tbl_btr t1 on t1.fld_btrsrc=t0.fld_btid
                                     left join tbl_bth t2 on t2.fld_btid = t1.fld_btrdst
                                     where t0.fld_btid = $btid and t1.fld_btrdsttyid = 65")->row();

	            $qty_cbu = $cek->cbu;
             }

            else{
                    $qty_cbu = 0;
            }



          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Form A Fee',$tariff,if($qty_cbu=0,$data->cbu,$qty_cbu),$tariff * if($qty_cbu=0,$data->cbu,$qty_cbu),
                             '$quotation_number','$data->fld_btno')");
          }

        }


        ### EDI Charge
        if ($rprod->fld_btiid == 9734) {
          $tariff = 0;
          $edi_qty = 0;
          $qty_part = 0;

          $edi_check = $this->db->query("select count(*) as edi from tbl_btd_peb t0 where t0.fld_btidp = $btid and t0.fld_btp01 !=''")->row();
          $edi_qty = $edi_check->edi;

          $cek_part = $this->db->query("select count(*) as partoff from tbl_btr t0 where t0.fld_btrsrc = $btid and t0.fld_btrdsttyid=65")->row();
          $qty_part = $cek_part->partoff;


          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->edi == 1) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'EDI Charge',$tariff,if($qty_part>0,$qty_part+1,if($data->fld_baidv=14,1,$edi_qty)),
                            $tariff * if($qty_part>0,$qty_part+1,if($data->fld_baidv=14,1,$edi_qty)),'$quotation_number','$data->fld_btno')");
          }

        }

        ### COO Form B
        if ($rprod->fld_btiid == 14846) {
          $tariff = 0;
          $coo_qty = 0;
          $coo_check = $this->db->query("select count(*) as coo from tbl_btd_document t0
                                         where t0.fld_btidp = $btid and t0.fld_doctype = 861 and t0.fld_btp03 = 2")->row();
          $coo_qty = $coo_check->coo;


          if ($coo_qty > 0) {
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'COO Form B',$tariff,$coo_qty,$tariff * $coo_qty,'$quotation_number','$data->fld_btno')");
          }

         }
        }

        # COO Form ABCD
        if ($rprod->fld_btiid == 16396) {
          $tariff = 0;
          $coo_qty = 0;
          $coo_check = $this->db->query("select count(*) as coo from tbl_btd_document t0
                                         where t0.fld_btidp = $btid and t0.fld_doctype = 861 and t0.fld_btp03 in (1,2,3)")->row();
          $coo_qty = $coo_check->coo;


          if ($coo_qty > 0) {
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'COO Form A B C D',$tariff,$coo_qty,$tariff * $coo_qty,'$quotation_number','$data->fld_btno')");
          }

         }
        }

         # COO Form ABD
        if ($rprod->fld_btiid == 14850 || $rprod->fld_btiid == 18744) {
          $tariff = 0;
          $coo_qty = 0;
          $coo_check = $this->db->query("select count(*) as coo from tbl_btd_document t0
                                         where t0.fld_btidp = $btid and t0.fld_doctype = 861 and t0.fld_btp03 in (1,2,3)")->row();
          $coo_qty = $coo_check->coo;


          if ($coo_qty > 0) {
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'COO Form A B D',$tariff,$coo_qty,$tariff * $coo_qty,'$quotation_number','$data->fld_btno')");
          }

         }
        }


         # COO Form ABCD & IJEPA
        if ($rprod->fld_btiid == 18796) {
          $tariff = 0;
          $coo_qty = 0;
          $coo_check = $this->db->query("select count(*) as coo from tbl_btd_document t0
                                         where t0.fld_btidp = $btid and t0.fld_doctype = 861 and t0.fld_btp03 in (1,2,3,7)")->row();
          $coo_qty = $coo_check->coo;


          if ($coo_qty > 0) {
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                          values ($last_insert_id,'COO Form A B C D dan IJEPA',$tariff,$coo_qty,$tariff * $coo_qty,'$quotation_number','$data->fld_btno')");
          }

         }
        }

        # COO Form ANZ
        if ($rprod->fld_btiid == 18798) {
          $tariff = 0;
          $coo_qty = 0;
          $coo_check = $this->db->query("select count(*) as coo from tbl_btd_document t0
                                         where t0.fld_btidp = $btid and t0.fld_doctype = 861 and t0.fld_btp03 = 9")->row();
          $coo_qty = $coo_check->coo;


          if ($coo_qty > 0) {
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                          values ($last_insert_id,'COO Form ANZ',$tariff,$coo_qty,$tariff * $coo_qty,'$quotation_number','$data->fld_btno')");
          }

         }
        }

        # COO Form IJEPA 1 Page
        if ($rprod->fld_btiid == 14851) {
          $tariff = 0;
          $coo_qty = 0;
          $coo_check = $this->db->query("select count(*) as coo from tbl_btd_document t0
                                         where t0.fld_btidp = $btid and t0.fld_doctype = 861 and t0.fld_btp03 = 7")->row();
          $coo_qty = $coo_check->coo;


          if ($coo_qty > 0) {
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'COO Form IJEPA',$tariff,$coo_qty,$tariff * $coo_qty,'$quotation_number','$data->fld_btno')");
          }

         }
        }

         ## Handling LCL 0 - 3 FT
         if ($rprod->fld_btiid == 9737 || $rprod->fld_btiid == 19844) {
          $tariff = 0;
          $qty = 0;
          $qty2 = 0;

          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->fld_bttax == 2) {

                $qty = $data->cbm;

                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Handling LCL 0-3 FT',$tariff,1,$tariff,'$quotation_number','$data->fld_btno')");
          }
        }

        ## Handling LCL > 3 FT to be added
        if ($rprod->fld_btiid == 9738 || $rprod->fld_btiid  == 14889) {
          $tariff = 0;
          $qty1 = 0;
          $qty_add = 0;
          $price = 0;

          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->fld_bttax == 2 ) {
                $price = $rquo->tariff;

                $qty1 = $data->cbm;

                if($qty1 > 3) {
                       $qty_add = $qty1 - 3;
                       $tariff = $rquo->tariff * $qty_add;
                }
                else {
                       $tariff = 0;
                }

                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Handling LCL >3 FT to be added',$price,$qty_add,$tariff,'$quotation_number','$data->fld_btno')");
          }
        }


         ## Handling LCL 0 - 4 CBM
         if ($rprod->fld_btiid == 14844) {
          $tariff = 0;
          $qty = 0;
          $qty2 = 0;

          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->fld_bttax == 2) {

                $qty = $data->cbm;

                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Handling LCL 0-4 CBM',$tariff,1,$tariff,'$quotation_number','$data->fld_btno')");
          }
        }

        ## Handling LCL > 4 CBM to be added
        if ($rprod->fld_btiid == 14845) {
          $tariff = 0;
          $qty1 = 0;
          $qty_add = 0;
          $price = 0;

          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->fld_bttax == 2 ) {
                $price = $rquo->tariff;

                $qty1 = $data->cbm;

                if($qty1 > 4) {
                       $qty_add = $qty1 - 4;
                       $tariff = $rquo->tariff * $qty_add;
                }
                else {
                       $tariff = 0;
                }

                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Handling LCL > 4 CBM to be added',$price,$qty_add,$tariff,'$quotation_number','$data->fld_btno')");
          }
        }

        ### Administration Fee
        if ($rprod->fld_btiid == 10755) {
          $tariff = 0;
          if ($terms->terms_id == 10468) {
            $tariff = 20000;
          }
          if ($terms->terms_id == 14798) {
            $tariff = 15000;
          }
          if ($terms->terms_id == 14822) {
            $tariff = 10000;
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Administration Fee',$tariff,1,$tariff * 1,'$terms->fld_btno','$data->fld_btno')");
          }
        }

        ### Part Of Document Fee
        if ($rprod->fld_btiid == 12562) {
          $tariff = 0;
          $qty_part = 0;

          $cek_part = $this->db->query("select count(*) as partoff from tbl_btr t0 where t0.fld_btrsrc = $btid and t0.fld_btrdsttyid=65")->row();
          $qty_part = $cek_part->partoff;

          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->part_off == 1) {
               $tariff = $rquo->tariff;
               $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'part Of Document Charge',$tariff,$qty_part,$tariff * $qty_part,'$quotation_number','$data->fld_btno')");
          }
         }

        ### Document Fee (Chemco Import)
        if ($rprod->fld_btiid == 18738) {
          $tariff = 0;
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->fld_baidv == 14 && $data->fld_baidc == 5063) {
               $tariff = $rquo->tariff;
               $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Document Fee',$tariff,1,$tariff * 1,'$quotation_number','$data->fld_btno')");
          }
         }

        ### Arrangement Custom Inspection
        if ($rprod->fld_btiid == 16397) {
          $tariff = 0;
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->inspect == 1) {
               $tariff = $rquo->tariff;
               $quotation_number = $rquo->fld_btno;
             }
          }

          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Arrangement Custom Inspection',$tariff,1,$tariff * 1,'$quotation_number','$data->fld_btno')");
          }
         }

        ### Arrangement Charge for DG Cargo
        if ($rprod->fld_btiid == 14829) {
          $tariff = 0;
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->fld_btiid == 1) {
               $tariff = $rquo->tariff;
               $quotation_number = $rquo->fld_btno;
             }
          }

          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Arrangement Charge for DG Cargo',$tariff,1,$tariff * 1,'$quotation_number','$data->fld_btno')");
          }
         }

        ### Pelepasan Karantina
        if ($rprod->fld_btiid == 10364) {
          $tariff = 0;
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $data->karantina > 0) {
               $tariff = $rquo->tariff;
               $quotation_number = $rquo->fld_btno;
             }
          }

          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Pelepasan Karantina',$tariff,1,$tariff * 1,'$quotation_number','$data->fld_btno')");
          }
        }

        #Bahandle Karantina
        if ($rprod->fld_btiid == 10493) {

          ### 20 Feet
          if($data->feet20 > 0) {
           $tariff = 0;
            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
             if ($rquo->fld_btiid == $rprod->fld_btiid && $data->karantina > 0) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Bahandle Karantina',$tariff,$data->feet20,$tariff * $data->feet20,'$quotation_number',
                              '$data->fld_btno')");
            }
          }
          ### 40 Feet
          if($data->feet40 > 0) {
            $tariff = 0;
            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
             if ($rquo->fld_btiid == $rprod->fld_btiid && $data->karantina > 0) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Bahandle Karantina',$tariff,$data->feet40,$tariff * $data->feet40,'$quotation_number',
                              '$data->fld_btno')");
            }
          }
        }

        ## Extra Movement Quarantine
        if ($rprod->fld_btiid == 10494) {

          ### 20 Feet
          if($data->feet20 > 0) {
           $tariff = 0;
            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
             if ($rquo->fld_btiid == $rprod->fld_btiid && $data->karantina > 0) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Extra Movement Quarantine',$tariff,$data->feet20,$tariff * $data->feet20,'$quotation_number',
                              '$data->fld_btno')");
            }
          }
          ### 40 Feet
          if($data->feet40 > 0) {
            $tariff = 0;
            ### Find Quotation Tariff
            foreach ($quo as $rquo) {
             if ($rquo->fld_btiid == $rprod->fld_btiid && $data->karantina > 0) {
               $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
              }
            }
            if($tariff > 0) {
              $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                              values ($last_insert_id,'Extra Movement Quarantine',$tariff,$data->feet40,$tariff * $data->feet40,'$quotation_number',
                              '$data->fld_btno')");
            }
          }
        }


        # Stuffing Charge Export
        if ($rprod->fld_btiid == 18745) {
          ### 20 Feet
          if($data->feet20 > 0) {
          $tariff = 0;
          $stuff_qty = 0;
          $stuff_check = $this->db->query("select count(*) as stuffing from tbl_btd_stuffing t0 where t0.fld_btidp = $btid and t0.fld_btp10 =1")->row();
          $stuff_qty = $stuff_check->stuffing;

          if ($stuff_qty > 0) {
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $rquo->cont_size == 1) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Stuffing Charge 20FT',$tariff,$stuff_qty,$tariff * $stuff_qty,'$quotation_number','$data->fld_btno')");
          }

         }
        }

          ### 40 Feet
          if($data->feet40 > 0) {
          $tariff = 0;
          $stuff_qty = 0;
          $stuff_check = $this->db->query("select count(*) as stuffing from tbl_btd_stuffing t0 where t0.fld_btidp = $btid and t0.fld_btp10 =1")->row();
          $stuff_qty = $stuff_check->stuffing;

          if ($stuff_qty > 0) {
          foreach ($quo as $rquo) {
            if ($rquo->fld_btiid == $rprod->fld_btiid && $rquo->cont_size == 2) {
                $tariff = $rquo->tariff;
                $quotation_number = $rquo->fld_btno;
             }
          }
          if($tariff > 0) {
            $this->db->query("insert into tbl_btd_billing (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btp03,fld_btnoreff)
                            values ($last_insert_id,'Stuffing Charge 40FT',$tariff,$stuff_qty,$tariff * $stuff_qty,'$quotation_number','$data->fld_btno')");
          }

         }
        }

	}

      }

    $url = base_url() . "index.php/page/form/78000INVOICE/edit/$last_insert_id?act=edit";
    redirect($url);
  }

  function getWhatsApp () {
    $mesage = $this->db->query("select * from tbl_wa t0
                                left join tbl_user t1 on t1.fld_userp03=t0.fld_from where t0.fld_status = 0");
    $message = $mesage->result();
    foreach($message as $rmessage) {
      $groupid = $rmessage->fld_usergrpid;
      $btid = $rmessage->fld_btid;
      $btno = $rmessage->fld_btno;
      $split = explode(" ", $rmessage->fld_msg);
      switch ($split[0]) {
        case "chbis":
        if($split[1] == 'aprv') {
          $btid = $split[2];
          if($btid) {
            echo "Processing Status ...\n";
            $data = $this->db->query("SELECT count(1) 'number'
				     FROM tbl_bth t0
				     LEFT JOIN tbl_btty t1 ON t1.fld_bttyid=t0.fld_bttyid
				     LEFT JOIN tbl_aprvtkt t2 ON t2.fld_btid=t0.fld_btid
				     LEFT JOIN tbl_btty t3 ON t3.fld_bttyid=t0.fld_bttyid
				     WHERE t2.fld_usergrpid ='$groupid'
				     and t2.fld_aprvtktstat = 1
				     AND
				     if(t2.fld_aprvruleroleord = 1,1,(select count(1) from tbl_aprvtkt tx0
			             where
				     tx0.fld_btid=t0.fld_btid and tx0.fld_aprvtktstat=1 and tx0.fld_aprvruleroleord < t2.fld_aprvruleroleord) = 0)
				     and t0.fld_btid = $btid");
            $data = $data->row();
            if($data->number > 0) {
              $message = "You don't have permission to approve this transaction ...";
              $fup_url = base_url() . "index.php/whatsapp/send/628128404406/$message";
              echo "OK";
            } else {
              $message = "You don't have permission to approve this transaction ...";
              $fup_url = base_url() . "index.php/whatsapp/send/628128404406/$message";
              redirect($fup_url);
            }
          } else {
            echo "Unknown Format";
          }
        }
        break;
        default:
        echo "Unknown format<br>";
      }
    }
  }

  function TruckCharge() {
    $count = $_POST["count"];
    $tot_price = 0;
    $tot_loading = 0;
    $tot_over = 0;
                //Trucking Charge
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $tot_price += $_POST["price$x"];
                                $job = $_POST["jobNo$x"];
                        }
                }
                if($tot_price > 0){
                $this->db->query("insert into tbl_btd_finance
                                (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btnoreff,fld_coaid)
                                 values
                                ($parentID ,'TRUCKING CHARGE',$tot_price,1,$tot_price,'$job',701)");
                }
                //Loading Charge
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $tot_loading += $_POST["loading$x"];
                                $job = $_POST["jobNo$x"];
                        }
                }
                if($tot_loading > 0){
                $this->db->query("insert into tbl_btd_finance
                                (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btnoreff,fld_coaid)
                                 values
                                ($parentID ,'LOADING CHARGE',$tot_loading,1,$tot_loading,'$job',701)");
                }
                //Overnight Charge
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $tot_over += $_POST["over$x"];
                                $job = $_POST["jobNo$x"];
                        }
                }
                if($tot_over > 0){
                $this->db->query("insert into tbl_btd_finance
                                (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btnoreff,fld_coaid)
                                 values
                                ($parentID ,'OVERNIGHT CHARGE',$tot_over,1,$tot_over,'$job',701)");
                }
                $url = base_url() . "index.php/page/form/78000INVOICE/edit/$parentID?act=edit";
                redirect($url);
        }

  function ImportDepContainer() {
    $count = $_POST["count"];
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $ship = $_POST["ship$x"];
                                $bl_number = $_POST["bl$x"];
                                $house_bl = $_POST["house_bl$x"];
                                $desc = $_POST["desc$x"];
                                $curr = $_POST["currid$x"];
                                $amount = $_POST["deposit$x"];
                                $fld_btid = $_POST["fld_btid$x"];
                                $joid= $_POST["joid$x"];
                                $cojno = $_POST["cojno$x"];
                                $userid= $_POST["userid$x"];
                                $compid = $_POST["compid$x"];
                                #    echo "The number is: $x <br>";
                                $tot_billing = $tot_billing + $sell_price;
                                $this->db->query("insert into tbl_btd_deposit
                                (fld_btidp,fld_btnoreff,fld_blno,fld_btp01,fld_btreffid,fld_btamt01,fld_btuamt01,fld_btdesc,fld_btp06,fld_btp02)
                                values
                              ($parentID ,'$bl_number','$house_bl','$ship',$fld_btid,if($curr=1,$amount,0),if($curr=2,$amount,0),'$desc','$joid','$cojno')");
                        }
                }

                 $update_comp = $this->db->query("update tbl_bth t0
                                      set t0.fld_btp23 = if('$userid'>1,'$compid','$userid')
                                      where t0.fld_btid = $parentID limit 1");

                $url = base_url() . "index.php/page/form/78000DEPOSIT_ENTRY/edit/$parentID?act=edit";
                redirect($url);
        }

  function ImportDepShip() {
    $count = $_POST["count"];
		$fld_baidp  = $this->session->userdata('ctid');
		for ($x=1; $x<=$count; $x++){
			if($_POST["rowdata$x"] == "on") {
				$parentID = $_POST["parentID$x"];
				$depID = $_POST["depID$x"];
				$bl_number = $_POST["bl$x"];
				$house_bl = $_POST["house$x"];
                                $customer = $_POST["fld_baidc$x"];
				//$fld_btid = $_POST["fld_btid$x"];
				$joID = $_POST["joID$x"];

				#    echo "The number is: $x <br>";
				$tot_billing = $tot_billing + $sell_price;
				$this->db->query("insert into tbl_btd_deposit
						  (fld_btidp,fld_btp02,fld_blno,fld_btp03,fld_btreffid,fld_btflag)
						  values
						  ($parentID ,'$bl_number','$house_bl','$joID',$depID,'1')");
			}
		}
		$url = base_url() . "index.php/page/form/78000DEPOSIT_SHIP/edit/$parentID?act=edit";
		redirect($url);
	}

  function print_deliveryReceipt() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->printdeliveryReceipt($fld_btid);
  }

  function exportFakturPajak() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->exportFakturPajak($fld_btid);
  }

  function print_deliveryReceipt2() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->printdeliveryReceipt2($fld_btid);
  }

  function print_deliveryReceipt3() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->printdeliveryReceipt3($fld_btid);
  }

  function print_deliveryReceipt4() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->printdeliveryReceipt4($fld_btid);
  }

  function print_deliveryReceipt5() {
    $fld_btid =  $this->uri->segment(3);
    $cust = $this->input->get('fld_baidc');
    $this->ffis->printdeliveryReceipt5($fld_btid,$cust);
  }

  function print_Invoice_detail() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->print_Invoice_detail($fld_btid);
  }

  function print_Payment_detail() {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->print_Payment_detail($fld_btid);
  }

  function printBonusA () {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->printBonusA($fld_btid);
  }

  function printBonusB () {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->printBonusB($fld_btid);
  }

 function printBonusC () {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->printBonusC($fld_btid);
  }

  function exportBonusA () {
    $fld_btid =  $this->uri->segment(3);
    $this->ffis->exportBonusA($fld_btid);
  }

  function InsertEIR() {
		$count = $_POST["count"];
		$fld_baidp  = $this->session->userdata('ctid');
		for ($x=1; $x<=$count; $x++){
			if($_POST["rowdata$x"] == "on") {
				$parentID = $_POST["parentID$x"];
				$depID = $_POST["depID$x"];
				$bl_number = $_POST["bl$x"];
                                $house_bl  = $_POST["housebl$x"];
				$customer = $_POST["fld_baidc$x"];
				$joID = $_POST["joID$x"];
				$ship = $_POST["ship$x"];
				$container = $_POST["ContID$x"];
				$this->db->query("insert into tbl_btd_deposit
						  (fld_btidp,fld_btp02,fld_blno,fld_btp01,fld_btreffid,fld_btflag)
						  values
						  ($parentID ,'$bl_number','$house_bl','$ship',$depID,'1')");
			}
		}
		$url = base_url() . "index.php/page/form/78000EIR_ENTRY/edit/$parentID?act=edit";
		redirect($url);
	}

         function InsertSettleEIR() {
                $count = $_POST["count"];
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $depID = $_POST["depID$x"];
                                $bl_number = $_POST["master$x"];
                                $house_bl  = $_POST["house$x"];
                                $customer = $_POST["cust$x"];
                                $ship = $_POST["shipid$x"];
                                $dtl_eir = $_POST["dtlID$x"];
                                $this->db->query("insert into tbl_btd_deposit
                                                  (fld_btidp,fld_btp02,fld_blno,fld_btp01,fld_btreffid,fld_btflag,fld_btp07)
                                                  values
                                                  ($parentID ,'$bl_number','$house_bl','$ship','$depID','1',$dtl_eir)");
                        }
                }
                $url = base_url() . "index.php/page/form/78000EIR_SETTLEMENT/edit/$parentID?act=edit";
                redirect($url);
        }



	function ImportDepositRef($id)
	{
		$data['id']=$id;
		$data['content'] = 'template/view/78000REFOUNDENTRY';
		$this->load->view('page_view',$data);
	}

	function InsertRf() {
		$count = $_POST["count"];
		$fld_baidp  = $this->session->userdata('ctid');
		for ($x=1; $x<=$count; $x++){
			if($_POST["rowdata$x"] == "on") {
				$parentID = $_POST["parentID$x"];
				$depID = $_POST["depID$x"];
				$bl_number = $_POST["bl$x"];
				$customer = $_POST["fld_baidc$x"];
				//$fld_btid = $_POST["fld_btid$x"];
				$joID = $_POST["joID$x"];
				$ship = $_POST["ship$x"];
				#    echo "The number is: $x <br>";
				$tot_billing = $tot_billing + $sell_price;
				$this->db->query("insert into tbl_btd_deposit
						  (fld_btidp,fld_btp02,fld_btp01,fld_btreffid,fld_btflag)
						  values
						  ($parentID ,'$bl_number','$ship',$depID,'1')");
			}
		}
		$url = base_url() . "index.php/page/form/78000REFOUND_ENTRY/edit/$parentID?act=edit";
		redirect($url);
	}

	function ImportDepositSetRef($id)
	{
		$data['id']=$id;
		$data['content'] = 'template/view/78000SETRFENTRY';
		$this->load->view('page_view',$data);
	}

	function InsertDepositSetRef() {
		$count = $_POST["count"];
		$fld_baidp  = $this->session->userdata('ctid');
		for ($x=1; $x<=$count; $x++){
			if($_POST["rowdata$x"] == "on") {
				$parentID = $_POST["parentID$x"];
				$depID = $_POST["depID$x"];
				$bl_number = $_POST["master$x"];
                                $house_bl = $_POST["house$x"];
				$customer = $_POST["fld_baidc$x"];
				//$fld_btid = $_POST["fld_btid$x"];
				$joID = $_POST["joID$x"];
				$ship = $_POST["shipid$x"];
				#    echo "The number is: $x <br>";
				$tot_billing = $tot_billing + $sell_price;
				$this->db->query("insert into tbl_btd_deposit
						  (fld_btidp,fld_btdesc,fld_btp02,fld_blno,fld_btp01,fld_btreffid,fld_btflag)
						  values
						  ($parentID ,'REFUND JAMINAN','$bl_number','$house_bl','$ship',$depID,'1')");
			}
		}
		$url = base_url() . "index.php/page/form/78000SETRFUND_ENTRY/edit/$parentID?act=edit";
		redirect($url);
	}


        function ImportDepositRefund($id)
        {
                $data['id']=$id;
                $data['content'] = 'template/view/78000SET_REFUND';
                $this->load->view('page_view',$data);
        }


        function InsertDepositRefund() {
                $count = $_POST["count"];
                $fld_baidp  = $this->session->userdata('ctid');
                $location = $this->session->userdata('location');
                $companyid = '';
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $dtlID = $_POST["dtlID$x"];
                                $depNo = $_POST["depNo$x"];
                                $amount = $_POST["amount$x"];
                                $empid = $_POST["empid$x"];
                                $jo = $_POST["jo$x"];
                                $master = $_POST["master$x"];
                                $house = $_POST["house$x"];
                                $cust_name = $_POST["cust_name$x"];
                                $desc = $_POST["desc$x"];
                                $refno = $_POST["refno$x"];
                                $companyid = ($_POST["companyid$x"] == 1) ? $_POST["companyid$x"] : '' ;

                                $this->db->query("insert into tbl_btd_finance
                                                  (fld_btidp,fld_btdesc,fld_btamt01,fld_empid,fld_btnoreff,fld_btp10,fld_btdocreff,fld_btp03,fld_locid)
                                                  values
                                                  ($parentID ,concat('$desc','-',substr('$cust_name',1,12)),'$amount','$empid','$jo','$dtlID','$master',
                                                   '$refno','$location')");
                                $totrefund = $totrefunf+$amount;
                        }
                }
                $this->db->query("update tbl_bth set fld_btamt='$totrefund',fld_btdesc='REFUND JAMINAN',fld_btp05='$jo', fld_btp23='$companyid'
                                  where fld_btid=$parentID");

                $url = base_url() . "index.php/page/form/78000SETTLEMENT_DEPOSIT/edit/$parentID?act=edit";
                redirect($url);
        }


  function getPPHInvoice($id)
        {
                $data['id']=$id;
                $data['content'] = 'template/view/78000LIST_PPH_INV';
                $this->load->view('page_view',$data);
        }

  function getPPHInvoice2($id)
        {
                $data['id']=$id;
                $data['content'] = 'template/view/78000LIST_PPH_INV2';
                $this->load->view('page_view',$data);
        }

  function InsertPPH() {
                $count = $_POST["count"];
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $dtlID = $_POST["dtlID$x"];
                                $InvNo = $_POST["InvNo$x"];
                                $pph23 = $_POST["pph23$x"];
                                $pphfinal = $_POST["pphfinal$x"];
                                $dpp = $pph23/0.02;

                                $this->db->query("insert into tbl_btd_pph
                                                  (fld_btidp,fld_btnoreff,fld_btp01,fld_btp02,fld_btp03,fld_btreffid)
                                                  values
                                                  ($parentID ,'$InvNo','$pph23','$pphfinal','$dpp',$dtlID)");
                                $totpph23 +=$pph23;
				$totpphfinal +=$pphfinal;
                                $totdpp +=$dpp;
                        }
                }


                $this->db->query("update tbl_bth set fld_btamt='$totpph23',fld_btamt01='$totpphfinal',fld_btamt02='$totdpp'
                                  where fld_btid=$parentID limit 1");

                $url = base_url() . "index.php/page/form/78000SUBMIT_BP/edit/$parentID?act=edit";
                redirect($url);
        }

  function getPPHAdd($id)
        {
                $data['id']=$id;
                $data['content'] = 'template/view/78000LIST_PPH_ADD';
                $this->load->view('page_view',$data);
        }


  function InsertPPHAdd() {
                $count = $_POST["count"];
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $dtlID = $_POST["dtlID$x"];
                                $InvNo = $_POST["InvNo$x"];
                                $pph23 = $_POST["pph23$x"];
                                $pphfinal = $_POST["pphfinal$x"];
                                $dpp = $pph23/0.02;

                                $this->db->query("insert into tbl_btd_pph
                                                  (fld_btidp,fld_btnoreff,fld_btp01,fld_btp02,fld_btp03,fld_btreffid)
                                                  values
                                                  ($parentID ,'$InvNo','$pph23','$pphfinal','$dpp',$dtlID)");
                                $totpph23 +=$pph23;
                                $totpphfinal +=$pphfinal;
                                $totdpp +=$dpp;
                        }
                }


                $this->db->query("update tbl_bth set fld_btamt='$totpph23',fld_btamt01='$totpphfinal',fld_btamt02='$totdpp'
                                  where fld_btid=$parentID limit 1");

                $url = base_url() . "index.php/page/form/78000SUBMIT_BP/edit/$parentID?act=edit";
                redirect($url);
        }


  function InsertTPK() {
                $count = $_POST["count"];
                $node =  $_POST["node"];
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $dtlID = $_POST["dtlID$x"];
                                $cojno = $_POST["cojno$x"];
                                $cojid = $_POST["cojid$x"];
                                $jobno = $_POST["jobno$x"];
                                $bl = $_POST["bl$x"];
                                $cust = $_POST["cust$x"];
                                $desc = $_POST["desc$x"];
                                $costtype = $_POST["costtype$x"];
                                $amount = $_POST["amount$x"];


                                $this->db->query("insert into tbl_btd_tpk
                                (fld_btidp,fld_coaid,fld_btno,fld_btnoreff,fld_bl,fld_cust,fld_btdesc,fld_btamt,fld_btreffid,fld_costtype,fld_btreffid2)
                                 values
                                ($node ,'705','$cojno','$jobno','$bl','$cust','$desc',$amount,$dtlID,'$costtype',$cojid)");

                        }
                }

                $url = base_url() . "index.php/page/form/78000TPK/edit/$node?act=edit";
                redirect($url);
        }


  function InsertTPK2() {
                $count = $_POST["count"];
                $node =  $_POST["node"];
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $dtlID = $_POST["dtlID$x"];
                                $cojno = $_POST["cojno$x"];
                                $cojid = $_POST["cojid$x"];
                                $jobno = $_POST["jobno$x"];
                                $bl = $_POST["bl$x"];
                                $cust = $_POST["cust$x"];
                                $desc = $_POST["desc$x"];
                                $costtype = $_POST["costtype$x"];
                                $amount = $_POST["amount$x"];


                                $this->db->query("insert into tbl_btd_tpk
                                (fld_btidp,fld_coaid,fld_btno,fld_btnoreff,fld_bl,fld_cust,fld_btdesc,fld_btamt,fld_btreffid,fld_costtype,fld_btreffid2)
                                values
                                ($node ,'703','$cojno','$jobno','$bl','$cust','$desc',$amount,$dtlID,'$costtype','$cojid')");

                        }
                }

                $url = base_url() . "index.php/page/form/78000TPK/edit/$node?act=edit";
                redirect($url);
        }


  function InsertTPK3() {
                $count = $_POST["count"];
                $node =  $_POST["node"];
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $dtlID = $_POST["dtlID$x"];
                                $cojno = $_POST["cojno$x"];
                                $cojid = $_POST["cojid$x"];
                                $jobno = $_POST["jobno$x"];
                                $bl = $_POST["bl$x"];
                                $cust = $_POST["cust$x"];
                                $desc = $_POST["desc$x"];
                                $costtype = $_POST["costtype$x"];
                                $amount = $_POST["amount$x"];


                                $this->db->query("insert into tbl_btd_tpk
                                (fld_btidp,fld_coaid,fld_btno,fld_btnoreff,fld_bl,fld_cust,fld_btdesc,fld_btamt,fld_btreffid,fld_costtype,fld_btreffid2)
                                 values
                                ($node ,'701','$cojno','$jobno','$bl','$cust','$desc',$amount,$dtlID,'$costtype',$cojid)");

                        }
                }

                $url = base_url() . "index.php/page/form/78000TPK/edit/$node?act=edit";
                redirect($url);
        }


  function UpdateTPK() {
                $count = $_POST["count"];
                $fld_baidp  = $this->session->userdata('ctid');
                for ($x=1; $x<=$count; $x++){
                        if($_POST["rowdata$x"] == "on") {
                                $parentID = $_POST["parentID$x"];
                                $dtlID = $_POST["dtlID$x"];
                                $cojno = $_POST["cojno$x"];
                                $jobno = $_POST["jobno$x"];
                                $bl = $_POST["bl$x"];
                                $cust = $_POST["cust$x"];
                                $desc = $_POST["desc$x"];
                                $amount = $_POST["amount$x"];
                                $ctid = $_POST["ctid$x"];


                                $this->db->query("update tbl_btd_tpk set fld_status = $ctid, fld_submit = now()
                                                  where fld_btid = $dtlID and fld_status = 0 limit 1");

                                ##approve TPK when all detail already received
                                $cek_tpk = $this->db->query("select * from tbl_btd_tpk where fld_btidp = $parentID");
                                $cek_tpk_count = $cek_tpk->num_rows();
                                $cek_tpk2 = $this->db->query("select * from tbl_btd_tpk where fld_btidp = $parentID and fld_status !=0");
                                $cek_tpk2_count = $cek_tpk2->num_rows();

                                if($cek_tpk_count ==  $cek_tpk2_count) {

                                        $this->db->query("update tbl_bth set fld_btstat = 3
                                                          where fld_btid = $parentID limit 1");


                                }

                        }
                }

                $url = base_url() . "index.php/page/view/78000CHECKLIST_TTPK";
                redirect($url);
        }


        function InsertPPA() {
          $count = $_POST["count"];
          $node =  $_POST["node"];

            for ($x=1; $x<=$count; $x++){
              if($_POST["rowdata$x"] == "on") {
                $fld_btid = $_POST["fld_btid$x"];
                  $this->db->query("INSERT INTO tbl_btd_doc (fld_btidp, fld_btiid)
                                    VALUES ($node, $fld_btid)");
              }
            }

            $url = base_url() . "index.php/page/form/78000ADVANCE_SUBMIT/edit/$node";
            redirect($url);
        }

        function InsertPPASettle() {
          $count = $_POST["count"];
          $node =  $_POST["node"];

          $from = ($_POST['from'] == NULL || $_POST['from'] == '') ? '%' : $_POST['from'];
          $until = ($_POST['until'] == NULL || $_POST['until'] == '') ? '%' : $_POST['until'];
          $jstno = ($_POST['jstno'] == NULL || $_POST['jstno'] == '') ? '%' : $_POST['jstno'];

          $listcheck=array();
          for ($x=1; $x<=$count; $x++){
            if($_POST["rowdata$x"] == "on") {
              $apvid = $_POST["apvid$x"];
              $apv[$apvid] = $apv[$apvid] + 1;
              array_push($listcheck,$apvid);
            }
          }
          $inlistcheck = implode(",",$listcheck);

          $checkcountapv = $this->db->query("
            SELECT
            res.*

            FROM(
                SELECT
                t0.fld_btid,
                t0.fld_btno,
                t16.fld_btid 'apvid',
                t16.fld_btno 'apvno',
                COUNT(t16.fld_btid) 'countapv'

                FROM tbl_bth t0
                LEFT JOIN tbl_btd_doc t1 ON t0.fld_btid = t1.fld_btiid
                left join tbl_user t13 on t13.fld_userid = t0.fld_btp23
                left join tbl_tyval t14 on t14.fld_tyvalcd = t0.fld_btp18 and t14.fld_tyid=66
                left join tbl_tyval t15 on t15.fld_tyvalcd = t0.fld_btstat and t15.fld_tyid=2
                left join tbl_btr t10 on t10.fld_btrdst=t0.fld_btid
                left join tbl_bth t11 on t11.fld_btid=t10.fld_btrsrc and t11.fld_bttyid=2
                left join tbl_btd_advaprv t15a on t15a.fld_btreffid=t11.fld_btid
                left join tbl_bth t16 on t16.fld_btid=t15a.fld_btidp and t16.fld_bttyid=8

                WHERE
                t1.fld_btiid IS NULL AND
                t0.fld_bttyid=4 AND
                (
                DATE_FORMAT(t0.fld_btdt, '%Y-%m-%d') between if('$from'='%', DATE_FORMAT(now(), '%Y-%m-01'), '$from') and if('$until'='%', LAST_DAY(DATE_FORMAT(now(), '%Y-%m-%d')), '$until')
                and if('$jstno'='%', 1, t0.fld_btno like concat('%', '$jstno', '%'))
                or
                t0.fld_btid = 1263911
                )
                and t13.fld_usercomp != 1
                and t0.fld_btstat in(6,3)
                and t0.fld_btloc = 1
                and t16.fld_btid in($inlistcheck)

                GROUP BY t16.fld_btid
                ORDER BY t0.fld_btid DESC
            ) res

            WHERE
            res.countapv > 1
          ")->result();

          $nomachapv = array();
          foreach ($apv as $keyapv => $itemapv) {
            foreach ($checkcountapv as $key => $item) {
              if ($keyapv == $item->apvid) {
                if ($itemapv == $item->countapv) {
                  // echo "ok";
                } else {
                  echo "no";
                  array_push($nomachapv,$item->apvno);
                }
              }
            }
          }

          // echo "Sorry, this transaction is currently under maintenance :) <br><br><br>";
          // print_r($checkcountapv);
          // echo "<br>";
          // print_r($listcheck);
          // echo "<br>";
          // $nomachapv = array_unique($nomachapv);
          // print_r($nomachapv);
          // exit();

          if (count($nomachapv) > 0) {
            $nomachapv = array_unique($nomachapv);
            $nomachapv = implode(",",$nomachapv);
            $this->ffis->message("Sorry, You Cannot Process This Transaction. Please Select All $nomachapv .");
          } else {
            // echo "Sorry, this transaction is currently under maintenance :) <br><br><br>";
            // exit();
            for ($x=1; $x<=$count; $x++){
              if($_POST["rowdata$x"] == "on") {
                $fld_btid = $_POST["fld_btid$x"];
                  $this->db->query("INSERT INTO tbl_btd_doc (fld_btidp, fld_btiid)
                                    VALUES ($node, $fld_btid)");
              }
            }

            $url = base_url() . "index.php/page/form/78000ADVANCE_SUBMIT/edit/$node";
            redirect($url);
          }
        }

        function RemovePPA() {
            $node =  $this->uri->segment(4);

            $fld_btid =  $this->uri->segment(3);
              $this->db->query("DELETE FROM tbl_btd_doc WHERE fld_btid=$fld_btid limit 1");

          $url = base_url() . "index.php/page/form/78000ADVANCE_SUBMIT/edit/$node";
          redirect($url);
        }

        function createBillPIC() {
          $count = $_POST["count"];
          $node =  $_POST["node"];
          // var_dump($count);
          // var_dump($node);
          for ($x=1; $x<=$count; $x++){
            if($_POST["rowdata$x"] == "on") {
              $fld_trk_settlementid = $_POST["fld_trk_settlementid$x"];
              $bill_pic = $_POST["bill_pic$x"];
              $pic2 = $_POST["pic2$x"];
              $do_number = $_POST["DO_Number$x"];
              // var_dump($fld_trk_settlementid);
              // var_dump($bill_pic);
              // var_dump($pic2);
              // var_dump($do_number);
                $this->db->query("update  tbl_trk_settlement set fld_btreffid2 = '$node',fld_bill_pic='$bill_pic',fld_bill_pic2='$pic2' where fld_trk_settlementid='$fld_trk_settlementid' limit 1");
            }
          }

          $url = base_url() . "index.php/page/form/78000JO_BILL_PIC/edit/$node";
          redirect($url);
        }


  function batchPrint() {
    $node =  $_POST["node"];
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $inv_id = $_POST["fld_btid$x"];
        $this->db->query("insert ignore into tbl_btd_invdel
              (fld_btidp,fld_btiid,fld_btflag)
              values
              ($node,$inv_id,'72')");
      }
    }
    $url = base_url() . "index.php/page/form/78000BATCH_PRINT/edit/$node?act=edit";
    redirect($url);
  }

  function changeTaxNumber() {
    $fld_btid =  $this->uri->segment(3);
    $data = $this->db->query("select date_format(fld_btdt,'%Y-%m-%d') 'date' from tbl_bth  where fld_btid = $fld_btid");
    $data = $data->row();
    if($data->date < '2015-08-01') {
      $fld_btidp = 28;
    } else {
      $fld_btidp = 0;
    }

    if ($this->session->userdata('ctid') == 190 || $this->session->userdata('ctid') == 996 || $this->session->userdata('ctid') == 478 || $this->session->userdata('ctid') == 553) {
       echo "OK";
    } else {
      $this->ffis->message("Permission Denied");
    }
    $this->db->query("update tbl_taxnumber set fld_btid=0,fld_btno = concat(fld_btno,'-changed') where fld_btid = $fld_btid");
    $this->ffis->getTaxNumber($fld_btid,$fld_btidp);
  }

  function changeTaxNumberBackup() {
    $fld_btid =  $this->uri->segment(3);
    $data = $this->db->query("select date_format(fld_btdt,'%Y-%m-%d') 'date' from tbl_bth  where fld_btid = $fld_btid");
    $data = $data->row();
    if($data->date < '2015-08-01') {
      $fld_btidp = 28;
    } else {
      $fld_btidp = 0;
    }

    if ($this->session->userdata('ctid') == 190 || $this->session->userdata('ctid') == 996 || $this->session->userdata('ctid') == 478 || $this->session->userdata('ctid') == 553) {
       echo "OK";
    } else {
      $this->ffis->message("Permission Denied");
    }
    $this->db->query("update tbl_taxnumber set fld_btid=0,fld_btno = concat(fld_btno,'-changed') where fld_btid = $fld_btid");
    $this->ffis->getTaxNumberBackup($fld_btid,$fld_btidp);
  }


   function unlockInvoice() {
      if($this->session->userdata('group') == 10) {
      $fld_invid = $this->uri->segment(3);
      #echo "$fld_invid";
      #exit();
      $this->db->query("update tbl_bth set fld_btp37 = 0 where fld_btid = $fld_invid limit 1");
      $url = base_url() . "index.php/page/form/78000INVOICE/edit/$fld_invid";
      redirect($url);
       } else {
      $this->ffis->message2("You don't have authorized to continue this process..");
      exit();
    }

  }

 function editTaxpostid(){
  $btid = $_POST["id"];
  $count = $_POST["count"];
  $loc = $this->session->userdata('location');
  for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
      $btno = $_POST["fld_btno$x"];
      $iddms = $_POST["iddms$x"];
      ##editpostid
      $this->db->query("update tbl_taxnumber set fld_taxnumberpostid = '$btid' where fld_btno = '$btno' limit 1");
      }

   }

 $url = base_url() . "index.php/page/form/78000TAX_UPLOAD2/edit/$btid?act=edit";
  redirect($url);
 }

 function unlockInvoiceList() {
     # if($this->session->userdata('group') == 10) {
      $fld_invid = $this->uri->segment(3);
      $date_now = date('Y-m-d H:i:s');
     # echo "$fld_invid";
      #exit();
      $this->db->query("update tbl_bth set fld_btp37 = 0, fld_btdt2 = '$date_now' where fld_btid = $fld_invid limit 1");
      $this->ffis->message('Unlocked Success..!! ');
      $url = base_url() . "index.php/page/view/78000INVOICE";
      redirect($url);
     #  } else {
     # $this->ffis->message2("You don't have authorized to continue this process..");
     # exit();
   # }

  }


  function printDOTTruck() {
    $fld_btid =  $this->uri->segment(3);
    $printDOTTruck = $this->ffis->printDOTTruck($fld_btid);
  }

  function add_portcharge() {
    $user_group=$this->session->userdata('group');
 //   $userid = $this->session->userdata('userid');
     $fld_baidp = $this->session->userdata('ctid');
    if ($user_group == 13) {
    $fld_btid =  $this->uri->segment(3);
    echo "Success $fld_btid";
    $date_trans = date("ym");
    $year_trans = date("y");
    $query = $this->db->query("select t0.fld_btno,t0.fld_btuamt,t0.fld_btp01 from tbl_bth t0
    where t0.fld_bttyid='78' and t0.fld_baido = '2' and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
    foreach ($query->result() as $row) {
    }
    $get_seq_number = (substr($row->fld_btno,13,5)+1);
    $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
    $fld_btno = "DEX/TCA/" . $date_trans . "/" . $seq_number;
    $fld_btstat = 3 ;

    $query = $this->db->query("select fld_btiid,fld_btival from dnxapps.tbl_bti where fld_btiid=11589");
    foreach ($query->result() as $row){
}
    $a = $row->fld_btival;
    $as = $a;
    $query = $this->db->query("insert into tbl_bth (fld_baidp,fld_baido,fld_baidc,fld_btflag,fld_btloc,fld_bttyid,fld_btstat,fld_btiid,fld_btno,fld_btdt,fld_btp09,fld_btamt,fld_btdtsa,fld_btnoalt,fld_btdesc,fld_btp12,fld_btp04,fld_btp05,fld_btp21,fld_btp17,fld_btp15,fld_btp03,fld_lup)
                               select $fld_baidp,fld_baido,fld_baidc,fld_btflag,1,78,fld_btstat,fld_btiid,'$fld_btno',now(),fld_btp09,$as,fld_btdtsa,fld_btno,12,fld_btp12,fld_btp04,fld_btp05,fld_btp21,fld_btp17,fld_btp15,fld_btp03,fld_lup  from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    $last_insert_id = $this->db->insert_id();
    $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid,fld_lup)
                      values ($fld_btid, $last_insert_id,78,now())");

   $query = $this->db->query("select tz0.fld_btuamt, tz2.fld_btamt,tz0.fld_btid,tz0.fld_btno,tz0.fld_btp01 from tbl_bth tz0
   left join tbl_btr tz1 on tz1.fld_btrsrc=tz0.fld_btid and tz1.fld_btrdsttyid=78
   left join tbl_bth tz2 on tz2.fld_btnoalt=tz0.fld_btno and tz2.fld_btdesc=12
   where tz0.fld_btid= $fld_btid");

    foreach ($query->result() as $row) {
    }
   $get_pcharge=$row->fld_btamt;
   $pcharge=$get_pcharge;
   $get_add_amount=$row->fld_btuamt;
   $add_amount=$get_add_amount + $pcharge;
   $get_opr_cost=$row->fld_btp01;
   $opr_cost=$get_opr_cost + $pcharge;

   $this->db->query("update tbl_bth set fld_btuamt=$add_amount,fld_btp01=$opr_cost where fld_btid=$fld_btid limit 1");
    }
    else {
   echo "<p>You don't have permission to process this transaction!</p>";
       exit();

   }


    $url = base_url() . "index.php/page/form/78000DELIVERY_ORDER_TRAILER/edit/$fld_btid";
    redirect($url);
  }

  function insertEximContainer () {
    $fld_btid  =  $this->uri->segment(3);
    $fld_btidp = $this->uri->segment(4);
    $cont_size = $this->uri->segment(5);
    if ($fld_btid==0)
    {
		 $this->db->query("insert into tbl_btd (fld_btidp,fld_btno,fld_btp03,fld_btreffid) select $fld_btidp,null, '$cont_size', null from dual");
	} else {
		    $this->db->query("insert into tbl_btd (fld_btidp,fld_btno,fld_btp03,fld_btreffid) select  $fld_btidp,if(length(fld_contnum) = 11, fld_contnum,''),'$cont_size',fld_btid
                      from exim.tbl_btd_container where fld_btid = $fld_btid");
    }
    echo '<script>history.go(-2)</script>';



  }

  function truckingSettlement(){
    $node =  $_POST["node"];
    $count = $_POST["count"];
    $location = $this->session->userdata('location');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $fld_btno = $_POST["fld_btno$x"];
        $flag = $_POST["flag$x"];
        $flag2 = $_POST["flag2$x"];
        $vtype = $_POST["vtype$x"];
        if ($flag == 1) {
        $data = $this->db->query("select t0.*,t1.fld_coaid,
          if((select group_concat(tz0.fld_btid) from tbl_btd tz0 where tz0.fld_btidp = t4.fld_btid)>0,
              concat(if(t0.fld_trk_settlementtype = 'LoLo','LOLO ','TRAYEK '),t0.fld_vehicle,' ',t0.fld_btno, ' ', t0.fld_customer , ' (' , (select group_concat(tz0.fld_btno) from tbl_btd tz0 where tz0.fld_btidp = t4.fld_btid) , ')'),
             concat(if(t0.fld_trk_settlementtype = 'LoLo','LOLO ','TRAYEK '),t0.fld_vehicle,' ',t0.fld_btno, ' ', t0.fld_customer)
          )'desc',
                                  t3.fld_btiid 'vehicle',
				  sum(t0.fld_trk_settlementamt) 'amount',
				  t4.fld_btnoreff 'job_num',
                                  if(t0.fld_trk_settlementtype = 'LoLo','705','829') 'account'
                                  from tbl_trk_settlement t0
                                  left join tbl_coa t1 on t1.fld_coacd=t0.fld_account
                                  left join dnxapps.tbl_bti t3 on t3.fld_bticd=t0.fld_vehicle and t3.fld_bticid=2
                                  left join tbl_bth t4 on t4.fld_btno = t0.fld_btno
                                  where t0.fld_btidp = $fld_btid
                                  group by t0.fld_btno, t0.fld_trk_settlementamt");

        } else {
        $data = $this->db->query("select t0.*,t1.fld_coaid,
                                  concat('BIAYA LOLO ',t0.fld_vehicle,' ',t0.fld_btno, ' ', t0.fld_customer , ' (' , (select group_concat(tz0.fld_btno) from dnxapps.tbl_btd tz0 where tz0.fld_btidp = t5.fld_btid) , ')') 'desc',
                                  t3.fld_btiid 'vehicle',t0.fld_trk_settlementamt 'amount' , t4.fld_btnoreff 'job_num',
                                  if(t0.fld_trk_settlementtype = 'LOLO Charge','705','829') 'account'
                                  from dnxapps.tbl_trk_settlement t0
				  left join tbl_coa t1 on t1.fld_coacd=t0.fld_account
				  left join dnxapps.tbl_bti t3 on t3.fld_bticd=t0.fld_vehicle
                                  left join dnxapps.tbl_bth t4 on t4.fld_btid = t0.fld_btreffid
                                  left join dnxapps.tbl_bth t5 on t5.fld_btno = t0.fld_btno
				  where
                                  t0.fld_btidp = $fld_btid group by t0.fld_btno");
        }
        $data = $data->result();
        foreach ($data as $rdata) {
          echo "###$rdata->desc<br>";
          #echo "insert ignore into tbl_btd_finance
          #    (fld_btidp,fld_btreffid,fld_btnoreff,fld_btamt01,fld_coaid,fld_btdesc,fld_bedivid,fld_btiid)
          #    values
          #    ('$node' ,'$rdata->fld_btreffid','$rdata->job_num','$rdata->amount','$rdata->fld_coaid','$rdata->desc','11','$rdata->vehicle')<br>";
          $this->db->query("insert ignore into tbl_btd_finance
              (fld_btidp,fld_btreffid,fld_btnoreff,fld_btamt01,fld_btdesc,fld_bedivid,fld_btiid,fld_coaid,fld_locid,fld_btp12)
              values
              ('$node' ,'$rdata->fld_btreffid','$rdata->job_num','$rdata->amount','$rdata->desc','11','$rdata->vehicle','$rdata->account',$location,'5453')");
        }
      }
    }
   #exit();
    $this->db->query("update tbl_bth set fld_btamt=(select sum(ifnull(fld_btamt01,0)) from tbl_btd_finance where fld_btidp='$node'),fld_btnoalt='$fld_btno' ,fld_btp37 = ifnull('$vtype',0)
                      where fld_btid=$node limit 1");
    $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/$node?act=edit";
    redirect($url);

  }

  function reviseInvoice() {
    $fld_baidp = $this->session->userdata('ctid');
    $fld_btid = $_POST['fld_btid'];
    $desc = $_POST['desc'];
    $location = $this->session->userdata('location');
    //if($this->session->userdata('group') != 10) {
    //   $this->ffis->message("You don't have permission to revise this transaction , Please call your IT Administrator");
    //}
    #$fld_btid =  $this->uri->segment(3);
    $query = $this->db->query("insert into tbl_bth (fld_btidp, fld_baidp, fld_baido, fld_baidc, fld_baidv, fld_btiid, fld_btno, fld_btnoalt, fld_btnoreff, fld_bttyid, fld_btqty, fld_btamt, fld_bttax, fld_bttaxno,
                               fld_btuamt, fld_btbalance, fld_btdesc, fld_btcmt, fld_btflag, fld_btstat, fld_btdtsa, fld_btdt, fld_bttime, fld_btdtp, fld_btloc, fld_btp01, fld_btp02, fld_btp03, fld_btp04, fld_btp05,
                               fld_btp06, fld_btp07, fld_btp08, fld_btp09, fld_btp10, fld_btp11, fld_btp12, fld_btp13, fld_btp14, fld_btp15, fld_lup)
                               select fld_btidp,  $fld_baidp, fld_baido, fld_baidc, fld_baidv, fld_btiid + 1, concat(substr(fld_btno,1,18),' - ',fld_btiid + 1), fld_btnoalt, fld_btnoreff, 82, fld_btqty, fld_btamt, fld_bttax, fld_bttaxno,
                               fld_btuamt, fld_btbalance, fld_btdesc, fld_btcmt, fld_btflag, 1, '', now(), fld_bttime, fld_btdtp, fld_btloc, '', fld_btp02, fld_btp03, fld_btp04, fld_btp05, fld_btp06, fld_btp07,
                               fld_btp08, fld_btp09, fld_btp10, fld_btp11, fld_btp12, fld_btp13, fld_btp14, fld_btp15, fld_lup  from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    $last_insert_id = $this->db->insert_id();
    $this->db->query("insert into tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid, fld_btnoreff, fld_btnodoc, fld_btiid, fld_coaid, fld_btqty01, fld_btqty02, fld_btflag, fld_btcmt,
                      fld_btdesc, fld_unitid, fld_btuamt01, fld_btamt01, fld_btp01, fld_btp02, fld_btp03, fld_lup,fld_locid)
                      select $last_insert_id, fld_baidp, fld_bedivid, fld_btreffid, fld_btnoreff, fld_btnodoc, fld_btiid,fld_coaid, fld_btqty01, fld_btqty02, fld_btflag, fld_btcmt, fld_btdesc, fld_unitid,
                      fld_btuamt01, fld_btamt01, fld_btp01, fld_btp02, fld_btp03, fld_lup,'$location' from tbl_btd_finance where fld_btidp = $fld_btid
                      order by fld_btid ASC");

    #$this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) value ($fld_btid,$last_insert_id,67)");

    $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) value ($fld_btid,0,67)");

    # translog
    $this->db->query("insert into tbl_trans_log (fld_btidp,fld_baidp,fld_btdt01,fld_btreffid,fld_log_tyid,fld_btdesc)
                      value ($fld_btid,$fld_baidp,now(),$last_insert_id,5, '$desc')");

    /*$url = base_url() . "index.php/page/form/78000INVOICE/edit/$last_insert_id?act=edit";
    redirect($url);*/
  }


  function revise2(){
  $count = $_POST["count"];
  $userid = $this->session->userdata('userid');
  $groupid = $this->session->userdata('group');
   for ($x = 1; $x <= $count; $x++) {
      if ($_POST["rowdata$x"] == "on") {
        $id_header = $_POST["fld_btid$x"];
        $last_insert_id = $_POST["fld_btreffid$x"];
        $flag = $_POST["fld_btrdsttyid$x"];

    }
  }
        $this->db->query("UPDATE tbl_btr set fld_btrdst='$last_insert_id' where fld_btrsrc='$id_header' and fld_btrdsttyid='$flag' and fld_btrdst = 0 LIMIT 1");
        $url = base_url() . "index.php/page/view/78000REVISE_INVOICE_UPDATE";
        redirect($url);
        exit();
}

  function reviseInvoiceToday()
  {
    $baidp = $this->session->userdata('ctid');
    $btid = $_POST['fld_btid'];
    $desc = $_POST['desc'];
    $loc = $this->session->userdata('location');

    # translog
    $this->db->query("insert into tbl_trans_log (fld_btidp,fld_baidp,fld_btdtso,fld_log_tyid,fld_btp05,fld_flag)
      value ($btid,$baidp,now(),10,'$desc',1)");
  }

  function revisemonthly(){
  $count = $_POST["count"];
  $userid = $this->session->userdata('userid');
  $groupid = $this->session->userdata('group');
   for ($x = 1; $x <= $count; $x++) {
      if ($_POST["rowdata$x"] == "on") {
        $id_header = $_POST["fld_btid$x"];
        $flag = $_POST["fld_log_tyid$x"];
    }
  }
      $this->db->query("UPDATE tbl_trans_log set fld_flag = 2 where fld_btidp ='$id_header'
          and fld_log_tyid='$flag' LIMIT 1");
      $url = base_url() . "index.php/page/view/78000REVISE_INVOICE_UPDATE_MONTHLY";
      redirect($url);
      exit();
}


  /*function InsertInvoice() {
		$count = $_POST["count"];
		$id = $_POST["count"];
		$fld_baidp  = $this->session->userdata('ctid');
		for ($i=1; $i<=$count; $i++){
			$amount = 0;
			$number = 0;
			$Sid='';
			for ($x=1; $x<=$count; $x++){
				if($_POST["rowdata$x"] == $i) {
					$number = 1;
					$parentID = $_POST["parentID$x"];
                                        if($_POST["fld_sell_price$x"] > 0) {
                                          $price = $_POST["fld_sell_price$x"];
                                        } else {
                                          $price = $_POST["amount$x"];
                                        }
					$code = $_POST["code$x"];
					$reffno = $_POST["reffno$x"];
					$amount = $amount + $price;
					$fld_journaldesc = $_POST["fld_journaldesc$x"];
                    $fld_journalid = $_POST["fld_journalid$x"];
					//print $_POST["rowdata$x"];
					if ($fld_journalid > 1)
					{
						$Sid .=$fld_journalid;
						$Sid .=",";
					}
				}
			}
			if ($number > 0)
			{
				$sid=substr_replace($Sid, "", -1);
				$this->db->query("insert into tbl_btd_finance
						  (fld_btidp,fld_btdesc,fld_btuamt01,fld_btamt01,fld_btnoreff,fld_coaid)
						  values
						  ($parentID ,'$fld_journaldesc','$amount','$amount','$reffno','$code')");
			        $last_insert_id = $this->db->insert_id();
                    $this->db->query("update tbl_journal set fld_btreffid = $last_insert_id where fld_journalid in ($sid)");
                        }
		}
		$url = base_url() . "index.php/page/form/78000INVOICE/edit/$parentID?act=edit";
		redirect($url);
	}*/
  function InsertInvoice() {
    $count = $_POST["count"];
	$id = $_POST["count"];
	$fld_baidp  = $this->session->userdata('ctid');
        $location = $this->session->userdata('location');
	for ($i=1; $i<=$count; $i++){
	  $amount = 0;
          $unit_amount = 0;
	  $number = 0;
	  $Sid='';
	  for ($x=1; $x<=$count; $x++){
	    if($_POST["rowdata$x"] == $i) {
		  $number = 1;
		  $parentID = $_POST["parentID$x"];
          if($_POST["fld_sell_price$x"] > 0) {
            $price = $_POST["fld_sell_price$x"];
            $fld_btuamt01 = $_POST["fld_sell_price$x"];
          } else {
            $price = $_POST["amount$x"];
            $fld_btuamt01 = $_POST["fld_btuamt01$x"];
          }
	  $code = $_POST["code$x"];
          $type = $_POST["type$x"];
	  $reffno = $_POST["reffno$x"];
          $fld_btqty01 = $_POST["fld_btqty01$x"];
          $fld_btid = $_POST["fld_btid$x"];
          $amount = $amount + $price;
          $unit_amount = $unit_amount + $fld_btuamt01;
	  $fld_journaldesc = $_POST["fld_journaldesc$x"];
          $fld_journalid = $_POST["fld_journalid$x"];
	  //print $_POST["rowdata$x"];
          if ($fld_journalid > 1) {
		    $Sid .=$fld_journalid;
			$Sid .=",";
		  } else if ($fld_btid > 1) {
		    $Sid .=$fld_btid;
			$Sid .=",";
		  }
		}
	  }

          ## check TPK with account coa 108.009
          $cek = $this->db->query("select * from tbl_btd_tpk t0 where t0.fld_btnoreff = '$reffno' and t0.fld_status = 0 and t0.fld_coaid != 703");
                if ($cek->num_rows() > 0) {
                        $this->ffis->message("Can't process to detail Invoice! Check TPK transaction before.");
                        exit();
                }

	  if ($number > 0) {
	    $sid=substr_replace($Sid, "", -1);
		$this->db->query("insert into tbl_btd_finance
						  (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btnoreff,fld_coaid,fld_locid)
						  values
						  ($parentID ,'$fld_journaldesc','$unit_amount','$fld_btqty01','$amount','$reffno','$code','$location')");
	    $last_insert_id = $this->db->insert_id();
        if($type == 1) {
          $this->db->query("update tbl_journal set fld_btreffid = $last_insert_id where fld_journalid in ($sid)");
        } else {
          $this->db->query("update tbl_btd_billing set fld_btreffid = $last_insert_id where fld_btid in ($sid)");
        }
      }
	}
	$url = base_url() . "index.php/page/form/78000INVOICE/edit/$parentID?act=edit";
	redirect($url);
  }

  function InsertAdvPrice() {
    $count = $_POST["count"];
        $fld_baidp  = $this->session->userdata('ctid');
          for ($x=1; $x<=$count; $x++){
            if($_POST["rowdata$x"] > 0) {
          $desc = $_POST["desc$x"];
          $amount = $_POST["amount$x"];
          $reffno = $_POST["division$x"];
          $rowdata = $_POST["rowdata$x"];
          $parentID = $_POST["parentID$x"];
          $btiid = $_POST["btiid$x"];
 	  $div = $_POST["division$x"];
          //print $_POST["rowdata$x"];

          #if ($number > 0) {
                $this->db->query("insert into tbl_btd_advprice
                                                  (fld_btidp,fld_btiid,fld_btp02,fld_btp01)
                                                  values
                                                  ('$parentID','$btiid','$rowdata','$div')");
            $last_insert_id = $this->db->insert_id();
      #   echo "$btiid.$desc <br>";
     }

    }
   # exit();
        $url = base_url() . "index.php/page/form/78000ADVSELL_PRICE/edit/$parentID";
        redirect($url);
  }

  function rcvAdvPrice() {
    $count = $_POST["count"];
          $fld_baidp  = $this->session->userdata('ctid');
          for ($x=1; $x<=$count; $x++){
            if($_POST["rowdata$x"] =="on") {
          $desc = $_POST["desc$x"];
          $idp = $_POST["idp$x"];
          $amount = $_POST["amount$x"];
          $reffno = $_POST["division$x"];
          $rowdata = $_POST["rowdata$x"];
          $parentID = $_POST["parentID$x"];
          //print $_POST["rowdata$x"];

          #if ($number > 0) {
        # echo "$idp<br>";
          $this->db->query("update tbl_btd_advprice set fld_btnoreff2=$parentID,fld_btflag = 2 where fld_btid = $idp and fld_btflag=1 limit 1 ");

     }

    }
        $url = base_url() . "index.php/page/form/78000RCV_ADVSELL_PRICE/edit/$parentID";
        redirect($url);
  }


  function InsertGJL() {
    $count = $_POST["count"];
        $id = $_POST["count"];
        $fld_baidp  = $this->session->userdata('ctid');
        $loc = $this->session->userdata('location');
        for ($i=1; $i<=$count; $i++){
          $amount = 0;
          $unit_amount = 0;
          $number = 0;
          $Sid='';
          for ($x=1; $x<=$count; $x++){
            if($_POST["rowdata$x"] == $i) {
                  $number = 1;
                  $parentID = $_POST["parentID$x"];
          if($_POST["fld_sell_price$x"] > 0) {
            $price = $_POST["fld_sell_price$x"];
            $fld_btuamt01 = $_POST["fld_sell_price$x"];
          } else {
            $price = $_POST["amount$x"];
            $fld_btuamt01 = $_POST["fld_btuamt01$x"];
          }
          $code = $_POST["code$x"];
          $type = $_POST["type$x"];
          $reffno = $_POST["reffno$x"];
          $fld_btqty01 = $_POST["fld_btqty01$x"];
          $fld_btid = $_POST["fld_btid$x"];
          $amount = $amount + $price;
          $unit_amount = $unit_amount + $fld_btuamt01;
          $fld_journaldesc = $_POST["fld_journaldesc$x"];
          $fld_journalid = $_POST["fld_journalid$x"];
          //print $_POST["rowdata$x"];
          if ($fld_journalid > 1) {
                    $Sid .=$fld_journalid;
                        $Sid .=",";
                  } else if ($fld_btid > 1) {
                    $Sid .=$fld_btid;
                        $Sid .=",";
                  }
                }
          }
 if ($number > 0) {
            $sid=substr_replace($Sid, "", -1);
                $this->db->query("insert into tbl_btd_finance
                                                  (fld_btidp,fld_btdesc,fld_btuamt01,fld_btqty01,fld_btamt01,fld_btnoreff,fld_coaid,fld_locid)
                                                  values
                                                  ($parentID ,'$fld_journaldesc','$unit_amount','$fld_btqty01','$amount','$reffno','$code','$loc')");
            $last_insert_id = $this->db->insert_id();
        if($type == 1) {
          $this->db->query("update tbl_journal set fld_btreffid = $last_insert_id where fld_journalid in ($sid)");
        } else {
          $this->db->query("update tbl_btd_billing set fld_btreffid = $last_insert_id where fld_btid in ($sid)");
        }
      }
        }
        $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$parentID?act=edit";
        redirect($url);
  }

  function ImportXls($module,$id) {
    require_once('system/shared/PHPExcel.php');
    require_once ('system/shared/PHPExcel/IOFactory.php');
    switch ($module) {
      case "78000GENERAL_JOURNAL":
      $FileName=$this->db->query("select fld_btp05 from tbl_bth where fld_btid='$id'")->row()->fld_btp05;
      $objPHPExcel = PHPExcel_IOFactory::load("upload/".$FileName."");
      $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
      $row = count($allDataInSheet);
      $loc = $this->session->userdata('location');
      for ($i=2; $i<=$row; $i++) {
        $coa=$allDataInSheet[$i]["A"];
	$CoaID=$this->db->query("select fld_coaid from tbl_coa where fld_coacd='$coa'")->row()->fld_coaid;
	$data['fld_coaid'] = $CoaID;
	$data['fld_btamt01'] = $allDataInSheet[$i]["B"];
	$data['fld_btdesc'] = $allDataInSheet[$i]["C"];
	$data['fld_btdocreff'] = $allDataInSheet[$i]["D"];
	$data['fld_btnoreff'] = $allDataInSheet[$i]["E"];
  $data['fld_btnodoc'] = $allDataInSheet[$i]["G"];
	$data['fld_btidp'] = $id;
        $data['fld_locid'] = $loc;
	$this->db->insert('tbl_btd_finance',$data);
      }
      $url = base_url() . "index.php/page/form/$module/edit/$id?act=edit";
      break;

      case "78000UPLOAD_EDC":
      $FileName=$this->db->query("select fld_btp01 from tbl_bth where fld_btid='$id'")->row()->fld_btp01;
      $objPHPExcel = PHPExcel_IOFactory::load("upload/".$FileName."");
      $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
      $row = count($allDataInSheet);
      for ($i=2; $i<=$row; $i++) {
        $data['fld_btdt'] = $allDataInSheet[$i]["A"];
        $data['fld_btdesc'] = $allDataInSheet[$i]["B"];
        $data['fld_btamt01'] = $allDataInSheet[$i]["C"];
        $data['fld_btidp'] = $id;
        $this->db->insert('tbl_btd_edc',$data);
      }
      $url = base_url() . "index.php/page/form/$module/edit/$id?act=edit";
      break;

      case "78000UPLOAD_DEPOSIT":
      $FileName=$this->db->query("select fld_btp01 from tbl_bth where fld_btid='$id'")->row()->fld_btp01;
      $objPHPExcel = PHPExcel_IOFactory::load("upload/".$FileName."");
      $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
      $row = count($allDataInSheet);
      for ($i=2; $i<=$row; $i++) {
        $data['fld_btdt'] = $allDataInSheet[$i]["A"];
        $data['fld_btp01'] = $allDataInSheet[$i]["B"];
        $data['fld_btp02'] = $allDataInSheet[$i]["C"];
        $data['fld_btflag'] = $allDataInSheet[$i]["D"];
        $data['fld_btamt01'] = $allDataInSheet[$i]["E"];
        $data['fld_btamt02'] = $allDataInSheet[$i]["F"];
        $data['fld_btidp'] = $id;
        $this->db->insert('tbl_btd_upload_deposit',$data);
      }
      $url = base_url() . "index.php/page/form/$module/edit/$id?act=edit";
      break;

      case "78000TREX_CHANGE":
      $this->dnxapps->ProsesImport("78000TREX_CHANGE",$id);
      $url = base_url() . "index.php/page/form/$FormName/edit/$id?act=edit";
      break;
    }
    redirect ($url);
  }
  function closeReqLand () {
  $fld_btid =  $this->uri->segment(3);
  #echo $fld_btid;
  #exit();
  $this->db->query("update tbl_bth set fld_btp32 = 1,fld_btstat =2  where fld_btid ='$fld_btid' and fld_btp32 != 1 limit 1");
  $url = base_url() . "index.php/page/form/78000CONTAINER_LANDING_R/edit/$fld_btid?act=edit";
    redirect($url);
  }

  function getContDtl($fld_btid){
  $data = $this->db->query("select fld_btid,fld_btp32 from tbl_bth where fld_btid ='$fld_btid'and fld_btp32 = 1");
   #echo "$data->num_rows () > 0";
   #exit();
  if ($data->num_rows() > 0 ){
  $this->ffis->message('Transaction Request Landing has Closed ');
  }
  $url = base_url() . "index.php/page/view/78000CONTAINER_LANDING?fld_btid=$fld_btid";
  redirect($url);
  }

  function reviseLanding($fld_btid){
 $count = $_POST["count"];
  $process =  $this->uri->segment(4);
  $do = $_POST["do"];
  if ($do == ''|| $do ==0){
  $this->ffis->message('Failed ,DO Number Cant Blank.. ');
  }
  $ctid  = $this->session->userdata('ctid');
  $emp =$this->db->query("select t0.fld_empnm 'emp' from hris.tbl_emp t0 where t0.fld_empid ='$ctid' limit 1")->row()->emp;
   if ($process == 2){

   $iddo = $this->db->query("select t0.fld_btid from tbl_bth t0 where t0.fld_btp02 ='$do' and t0.fld_bttyid = 92 order by t0.fld_btdt desc limit 1")->row();
   if ($fld_btid == $iddo->fld_btid){

    $this->ffis->message('Failed ,DO Number destination Cant same.. ');
   }
   if($iddo->fld_btid ==''){

  $this->ffis->message('DO Destination Not Found.. ');
  }

 $trx_dms= $this->db->query("select t0.fld_btid from dms.tbl_bth t0 where t0.fld_btidp ='$iddo->fld_btid' and t0.fld_bttyid = 12 limit 1")->row();

  for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btid = $_POST["fld_btid$x"];
        $no = 1;
        $this->db->query("update tbl_btd_container set fld_btp22=1,fld_btnoreffid ='$iddo->fld_btid',fld_btdesc = concat(fld_btdesc,'',concat('rev landing by','$emp',' on ',date_format(now(),'%Y-%m-%d'))) where fld_btid = $fld_btid limit 1");
        $this->db->query("update dms.tbl_btd_weekly_billing t0
			left join dms.tbl_full_cont_hpm t1 on t1.fld_btreffid =t0.fld_btid
			left join dms.tbl_bth t2 on t2.fld_btid =t0.fld_btidp and t2.fld_bttyid =12  set t0.fld_btidp ='$trx_dms->fld_btid' where t1.fld_btiid = '$fld_btid'");
        $this->db->query("update dms.tbl_full_cont_hpm t0
                        left join dms.tbl_bth t1 on t1.fld_btid =t0.fld_btidp and t1.fld_bttyid =12
 set t0.fld_btidp ='$trx_dms->fld_btid',t0.fld_btcmt =concat(fld_btdesc,'',concat('rev landing by','$emp',' on ',date_format(now(),'%Y-%m-%d'))) where t0.fld_btiid = '$fld_btid'  ");


      }
     }
   $noA = $noA + $no;
   #echo "do :$noA" ;
   #exit();
   }
  if ($ctid = 499 || $ctid = 558|| $ctid =315){

  $ctid =  $this->session->userdata('ctid');
  $url = base_url() . "index.php/page/view/78000REVISE_LANDING?fld_btid=$fld_btid";
  redirect($url);
   }else
   {
   $this->ffis->message('Access Denied!!  for Revice This Container Landing Request.. ');
   }
  }

  function containerLanding () {
    $fld_btid =  $this->uri->segment(3);
    $data = $this->db->query("select fld_btdtso from tbl_bth where fld_btid ='$fld_btid' limit 1")->row();
    $close_cy = $data->fld_btdtso;
    $cek = $this->db->query("select count(1) 'cnt' from tbl_btr t0 where t0.fld_btrsrc = $fld_btid and fld_btrdsttyid = 92");
      if ($cek->row()->cnt > 0) {
          $this->ffis->message('Container Landing Transaction already exist !! ');
    }else{
    #echo $close_cy;
   # exit();
    $fld_baidp =  $this->session->userdata('ctid');
    $trans_no = $this->mkautono(2,92);
      $date_trans = date("ym");
      $year_trans = date("y");
      $query = $this->db->query("select t0.fld_btno  from dms.tbl_bth t0  where t0.fld_bttyid=12 and MID(t0.fld_btno , 9, 2 )=$year_trans order by t0.fld_btid desc limit 1");
      foreach ($query->result() as $row) {
      }
      $get_seq_number = (substr($row->fld_btno,13,5)+1);
      $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
      $vno = DET . "/" . FCI . "/" . $date_trans . "/" . $seq_number;
     # echo"$vno";

    $this->db->query("insert into tbl_bth (fld_btidp, fld_baidp, fld_baido, fld_baidc, fld_baidv, fld_btiid, fld_btno, fld_btnoctr, fld_btnoalt, fld_btnoreff, fld_bttaxno, fld_bttyid, fld_btqty, fld_btamt, fld_btamt01,
                      fld_btamt02, fld_bttax, fld_btuamt, fld_btbalance, fld_btdesc, fld_btcmt, fld_btflag, fld_btstat, fld_btdtsa, fld_btdtso, fld_btdt, fld_bttime, fld_btdtp, fld_btloc, fld_btp01, fld_btp02, fld_btp03, fld_btp04,
                      fld_btp05, fld_btp06, fld_btp07, fld_btp08, fld_btp10, fld_btp11, fld_btp12, fld_btp09, fld_btqty01, fld_btp13, fld_btp14, fld_btp15, fld_lup, fld_btp16, fld_btp17, fld_btp18, fld_btp19, fld_btp20, fld_btp21,
                      fld_btp22, fld_clsdt, fld_btp23, fld_btp24, fld_btp25, fld_btp26, fld_btp27, fld_btp28, fld_btp29, fld_btp30, fld_btp31, fld_btp32, fld_btp33)
                      select fld_btidp, $fld_baidp, fld_baido, fld_baidc, fld_baidv, fld_btiid, '$trans_no', fld_btnoctr, fld_btnoalt, fld_btnoreff, '', 92, fld_btqty, 0, fld_btamt01, fld_btamt02, 0,
                      0, 0, fld_btdesc, fld_btcmt, '', 1, '', fld_btdtso, now(), fld_bttime, fld_btdtp, fld_btloc, if(fld_baidv = 13,fld_btp01,''), if(fld_baidv = 13,fld_btp23,''), '', '', fld_btp05, fld_btp06,
                      fld_btp07, fld_btp08, fld_btp10, fld_btp11, fld_btp12, fld_btp09, fld_btqty01, fld_btp13, fld_btp14, fld_btp15, '', fld_btp16, fld_btp17, fld_btp18, fld_btp19, fld_btp20, fld_btp21, fld_btp22, fld_clsdt,
                      fld_btp23, fld_btp24, fld_btp25, fld_btp26, 0,'','', fld_btp30, fld_btp31,0, fld_btp33  from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    $last_insert_id = $this->db->insert_id();
    $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) value ($fld_btid,$last_insert_id,92)");
    $detail = $this->db->query("select fld_btid from tbl_btd_container where fld_btidp ='$fld_btid'");
    foreach($detail->result() as $rdetail){
   # echo "$rdetail->fld_btid<br>";
     $this->db->query("update tbl_btd_container set fld_btpdt07 = '$close_cy' where fld_btid = '$rdetail->fld_btid'  ");
    }


    $data = $this->db->query("select t0.fld_bttyid,t0.fld_baidp,concat(t1.fld_empnm,' [' , t1.fld_empnip, ']') 'name' from tbl_bth t0 left join hris.tbl_emp t1 on t1.fld_empid=t0.fld_baidp and t1.fld_emporg in (1,2) where t0.fld_btid ='$fld_btid'");
    $rdata = $data->row();
   if($rdata->fld_bttyid==1){
    echo"IMPORT";
    #exit();
    $this->db->query("insert into dms.tbl_bth (fld_btidp,fld_baido,fld_btp80,fld_bttyid,fld_btno,fld_btp32,fld_btp30,fld_btp26,fld_btstat,fld_btp27,fld_btflag,fld_btp50) select '$last_insert_id',1,fld_baidc,12,'$vno',fld_btp08,fld_btnoalt,'IMPORT',1,'$rdata->name',3,1 from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    }
    if($rdata->fld_bttyid==6){
    echo"EXPORT";
   # exit();
    $this->db->query("insert into dms.tbl_bth (fld_btidp,fld_btp80,fld_bttyid,fld_btno,fld_btp32,fld_btp30,fld_btp26,fld_btstat,fld_btp27,fld_btflag,fld_baido,fld_btp50) select '$last_insert_id',fld_baidc,12,'$vno',fld_btp08,fld_btp23,'EXPORT',1,'$rdata->name',3,1,1 from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    }

    $url = base_url() . "index.php/page/form/78000CONTAINER_LANDING_R/edit/$last_insert_id?act=edit";
    redirect($url);
   }
  }


    function containerLanding2 () {
    $fld_btid =  $this->uri->segment(3);
    $fld_baidp =  $this->session->userdata('ctid');
    $trans_no = $this->mkautono(2,92);
    $this->db->query("insert into tbl_bth (fld_btidp, fld_baidp, fld_baido, fld_baidc, fld_baidv, fld_btiid, fld_btno, fld_btnoctr, fld_btnoalt, fld_btnoreff, fld_bttaxno, fld_bttyid, fld_btqty, fld_btamt, fld_btamt01,
                      fld_btamt02, fld_bttax, fld_btuamt, fld_btbalance, fld_btdesc, fld_btcmt, fld_btflag, fld_btstat, fld_btdtsa, fld_btdtso, fld_btdt, fld_bttime, fld_btdtp, fld_btloc, fld_btp01, fld_btp02, fld_btp03, fld_btp04,
                      fld_btp05, fld_btp06, fld_btp07, fld_btp08, fld_btp10, fld_btp11, fld_btp12, fld_btp09, fld_btqty01, fld_btp13, fld_btp14, fld_btp15, fld_lup, fld_btp16, fld_btp17, fld_btp18, fld_btp19, fld_btp20, fld_btp21,
                      fld_btp22, fld_clsdt, fld_btp23, fld_btp24, fld_btp25, fld_btp26, fld_btp27, fld_btp28, fld_btp29, fld_btp30, fld_btp31, fld_btp32, fld_btp33)
                      select fld_btidp, $fld_baidp, fld_baido, fld_baidc, fld_baidv, fld_btiid, '$trans_no', fld_btnoctr, fld_btnoalt, fld_btnoreff, '', 92, fld_btqty, 0, fld_btamt01, fld_btamt02, 0,
                      0, 0, fld_btdesc, fld_btcmt, '', 1, '', fld_btdtso, now(), fld_bttime, fld_btdtp, fld_btloc, if(fld_baidv = 13,fld_btp01,''), if(fld_baidv = 13,fld_btp23,''), '', '', fld_btp05, fld_btp06,
                      fld_btp07, fld_btp08, fld_btp10, fld_btp11, fld_btp12, fld_btp09, fld_btqty01, fld_btp13, fld_btp14, fld_btp15, '', fld_btp16, fld_btp17, fld_btp18, fld_btp19, fld_btp20, fld_btp21, fld_btp22, fld_clsdt,
                      fld_btp23, fld_btp24, fld_btp25, fld_btp26, 0,'','', fld_btp30, fld_btp31, fld_btp32, fld_btp33  from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    $last_insert_id = $this->db->insert_id();
    $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) value ($fld_btid,$last_insert_id,92)");
    $url = base_url() . "index.php/page/form/78000CONTAINER_LANDING_R/edit/$last_insert_id?act=edit";
    redirect($url);

  }

  function addContainerLanding() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $node =  $_POST["node"];
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btidp2 = $_POST["fld_btidp2$x"];
        $estimate = $_POST["estimate$x"];
        $estimate2 = $_POST["estimate2$x"];
        $fld_btid = $_POST["fld_btid$x"];
        if($_POST["charge$x"]=='on'){
        $charge = 1;
        }else
     {
        $charge = 0;
     }
        $size = $_POST["size$x"];
        if ($size == 1 || $size == 20){
          $val_size = 1;
        }
        else if ($size == 2 || $size == 40){
          $val_size = 2;
        }
        else if ($size == 3 || $size == '40 HC'){
          $val_size = 3;
        }
        else if ($size == 4 || $size == 45){
          $val_size = 4;
        }
        else{
          $val_size = 0;
        }

        $this->db->query("update tbl_btd_container set fld_btflag2 = 1,fld_btnoreffid = $fld_btidp2,fld_btpdt07 = '$estimate2',fld_btpdt03 = '$estimate',fld_btp20 ='$val_size',fld_btp14=$charge where fld_btid = $fld_btid limit 1");
      # $location = base_url() . "index.php/page/form/78000EXT_JOB_ORDER/edit/529614";
      #echo $location;
#exit();
 #      echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
       # header("Refresh: 0; url='$location'");
      }
    }
    $url = base_url() . "index.php/page/form/78000CONTAINER_LANDING_R/edit/$fld_btidp2?act=edit";
    redirect($url);
  }

 function aprvContainerLanding() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $node =  $_POST["node"];
    $division  = $this->session->userdata('divid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btidp2 = $_POST["fld_btidp2$x"];
        $estimate = $_POST["estimate$x"];
        $estimate2 = $_POST["estimate2$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $size = $_POST["size$x"];
        $fld_contnum = $_POST["fld_contnum$x"];
        $post = $_POST["post$x"];
        $Division = $_POST["Division$x"];
        $doc = $_POST["doc$x"];
        if ($size == 1 || $size == 20){
          $val_size = 1;
        }
        else if ($size == 2 || $size == 40){
          $val_size = 2;
        }
        else if ($size == 3 || $size == '40 HC'){
          $val_size = 3;
        }
        else if ($size == 4 || $size == 45){
          $val_size = 4;
        }
        else{
          $val_size = 0;
        }


        $this->db->query("update tbl_btd_container set fld_btflag2 = 2 where fld_btid = $fld_btid limit 1");
       $no++;
       $ConNumber.="<br> $no.".$fld_contnum." || Posted by : ".$post." || DO Number/ Closing Date : ".$doc."<br>";
      }
    }
     if($fld_btid ==""){
          $this->ffis->message("Can not be empty ..");
         }

     $message.="<p>Dear Trucking Admin,</p>";
     $message.="<p>Please follow up this Request Container Landing :</p>";
     $message.="<p>Division: ".$Division."</p>";
     $message.="<p>Request Number: <br>".$ConNumber."</p>";
	 if($division == 13){
     $this->ffis->sendmailexp("[Dunex Exim System Alert] Container Landing Request",$message);
     $this->ffis->message("Email has been sent ..");
	 }else
	 {
     $this->ffis->sendmailimp("[Dunex Exim System Alert] Container Landing Request",$message);
     $this->ffis->message("Email has been sent ..");
	 }
    $url = base_url() . "index.php/page/view/78000REQ_CONTAINER_LANDING";
    redirect($url);
  }

  function cancContainerLanding() {
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    $node =  $_POST["node"];
	$division  = $this->session->userdata('divid');

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $fld_btidp2 = $_POST["fld_btidp2$x"];
        $estimate = $_POST["estimate$x"];
        $estimate2 = $_POST["estimate2$x"];
        $fld_btid = $_POST["fld_btid$x"];
        $size = $_POST["size$x"];
        $fld_contnum = $_POST["fld_contnum$x"];
        $post = $_POST["post$x"];
        $Division = $_POST["Division$x"];
        $doc = $_POST["doc$x"];
        if ($size == 1 || $size == 20){
          $val_size = 1;
        }
        else if ($size == 2 || $size == 40){
          $val_size = 2;
        }
        else if ($size == 3 || $size == '40 HC'){
          $val_size = 3;
        }
        else if ($size == 4 || $size == 45){
          $val_size = 4;
        }
        else{
          $val_size = 0;
        }


        $this->db->query("update tbl_btd_container set fld_btnoreffid = '' and fld_btflag2 = 1 where fld_btid = $fld_btid limit 1");
       $no++;
       $ConNumber.="<br> $no.".$fld_contnum." || Posted by : ".$post." || DO Number/ Closing Date : ".$doc."<br>";
      }
    }
     if($fld_btid ==""){
        $this->ffis->message("Can not be empty ..");
         }

     $message.="<p>Dear Trucking Admin,</p>";
     $message.="<p>Sorry, this Container list bellow cancel request Landing :</p>";
     $message.="<p>Division: ".$Division."</p>";
     $message.="<p>Request Number: <br>".$ConNumber."</p>";

	   if($division == 13){
     $this->ffis->sendmailexp("[Dunex Exim System Alert] Container Landing Request",$message);
     $this->ffis->message("Email has been sent ..");
	 }else
	 {
     $this->ffis->sendmailimp("[Dunex Exim System Alert] Container Landing Request",$message);
     $this->ffis->message("Email has been sent ..");
	 }

    $url = base_url() . "index.php/page/view/78000CAN_CONTAINER_LANDING";
    redirect($url);
  }
  function email() {
   echo "test";
   exit();
    $data = $this->db->query("select t0.fld_baidv, t1.fld_bedivnm,t0.fld_btflag,t0.fld_btno,t0.fld_btdtso,
				if(t0.fld_baidv = 13,'DO Number/ Closing Date','AJU Number/ FT Demurage') 'title',
				if(t0.fld_baidv = 13,concat(t0.fld_btp02,'/',date_format(t0.fld_btdtso,'%d-%m-%Y %H:%i')),concat(t0.fld_btnoalt,'/',date_format(t0.fld_btdtso,'%d-%m-%Y %H:%i'))) 'doc',t2.fld_empnm 'postby'
				from tbl_bth t0
				left join hris.tbl_bediv t1 on t1.fld_bedivid = t0.fld_baidv
				left join hris.tbl_emp t2 on t2.fld_empid =t0.fld_baidp
				where t0.fld_btid = '$fld_btid' and t0.fld_bttyid = 92 and t0.fld_btp33 = 0  limit 1");
    #$data = $data->row();
    $data1 = $this->db->query("select count(t0.fld_btid)'count',(select count(t0.fld_btid) from tbl_btd_container t0 where t0.fld_btidp2 = '$fld_btid' and t0.fld_btflag2 = 1) 'count2' from tbl_btd_container t0 where t0.fld_btidp2 = '$fld_btid'")->row();
#    $data2 = $this->db->query("select count(t0.fld_btid)'count2' from tbl_btd_container t0 where t0.fld_btidp2 = '$fld_btid' and t0.fld_btflag2 = 1 ")->row();
    #echo "#$data1->count <br>";
    #echo "!$data2->count2 <br>";
    #exit();
   if ($data->num_rows() > 0 ){

    $this->db->query("update tbl_bth t0
                        set
                        t0.fld_btp33 =1,t0.fld_btstat =2
                        where t0.fld_btid = '$fld_btid' and t0.fld_bttyid = 92 and t0.fld_btstat=1 and t0.fld_btp33= 0 limit 1");
    $this->db->query("update tbl_btd_container t0
			set t0.fld_btflag2 = 1 where t0.fld_btidp2 = '$fld_btid'");
    $message="";
                       foreach ($data->result() as $row) {
                                        $message.="<p>Dear Trucking Admin,</p>";
                                        $message.="<p>Please follow up this Request Container Landing :</p>";
                                        $message.="<p>Request Number: ".$row->fld_btno."</p>";
					$message.="<p>Division: ".$row->fld_bedivnm."</p>";
					$message.="<p>" .$row->title." : ".$row->doc."</p>";
					$message.="<p>Request by : ".$row->postby."</p>";
                        }
                        $this->ffis->sendmail("[Dunex Exim System Alert] Container Landing Request",$message);
                         if($row->fld_baidv == 13){
                        $this->ffis->sendmailexp("[Dunex Exim System Alert] Container Landing Request",$message);
                         }else{
			            $this->ffis->sendmailimp("[Dunex Exim System Alert] Container Landing Request",$message);
			             }
    $this->ffis->message("Email has been sent ..");
    }else
    {
     if($data1->count2 < $data1->count){
          $this->db->query("update tbl_btd_container t0
                        set t0.fld_btflag2 = 1 where t0.fld_btidp2 = '$fld_btid'and t0.fld_btflag = 0");
          $this->ffis->sendmail("[Dunex Exim System Alert] Container Landing Request","Additional Request Container Landing");
          $this->ffis->message("Add Req Cont Landing has been sent ..");
	}else
       {
     $this->ffis->message(" Email already sent to trucking ..");
       }
    }
  }



  function emailexim($fld_btid) {
  $this->ffis->sendmailexim("trisnanto@dunextr.com","[Dunex Apps System Alert] Container Landing Request","test mail");
  }

  function emailDOT($fld_btid){
  $data = $this->db->query("select * from tbl_bth t0 where t0.fld_btid = '$fld_btid' limit 1");
  $data = $data->row();
  $message = "Dear Sir / Madam,<br><br>
              Currently Trucking Application PT. Dunia Express status is locked. <br> Reason : DO Number $data->fld_btno  made without quotation since  $data->fld_btdt  please click this <a href='http://dunex-exim.com/index.php/api/unlock'>link</a> to unlock the system. ";
  $this->ffis->sendmailtrk("trisnanto@dunextr.com","Trucking Application DE Unlock Request Approval","$message");
  $this->ffis->sendmailtrk("mona@dunextr.com","Trucking Application DE Unlock Request Approval","$message");
  $this->ffis->message("Email has been sent ..");
  }

  function delContLand($fld_btid) {
  $node = $this->uri->segment (3);
  $node2 = $this->uri->segment(4);
  $this->db->query("update tbl_btd_container set fld_btnoreffid ='',fld_btflag2 = 0 where fld_btid = '$node2' and fld_btflag2 in (0,1) limit 1");

  echo '<script>history.go(-1)</script>';
  }

  function delAdvprice($fld_btid){
  $btid = $this->uri->segment (4);
  $stat = $this->uri->segment (5);
  $fld_baidp =  $this->session->userdata('ctid');
  $tyid = $this->db->query("select fld_bttyid from tbl_bth where fld_btid = '$btid'")->row()->fld_bttyid;
  $cek =$this->db->query("select fld_btnoreff,fld_btnoreff2,fld_btflag from tbl_btd_advprice where fld_btid ='$fld_btid'")->row();
     $noreff = $cek->fld_btnoreff;
     $noreff2 = $cek->fld_btnoreff2;
     $flag = $cek->fld_btflag;
  if ($tyid == 97){
    if ($noreff !=0 || $noreff2 !=0|| $flag !=0){
    $this->ffis->message(" Cant delete this Advance detail !! .. ");
    }else
    {
   $this->db->query("delete from tbl_btd_advprice where fld_btid = '$fld_btid' limit 1");
    }
    }else if($tyid ==98){
      if ($noreff2 !=0|| $flag != 1 || $stat == 3 ){
     $this->ffis->message(" Cant delete this Advance detail !!, ..");
     }else
     {
   $this->db->query("update tbl_btd_advprice set fld_btnoreff ='',fld_btflag = 0 where fld_btid = '$fld_btid' limit 1");
     }
    }else
    {
  $this->db->query("update tbl_btd_advprice set fld_btnoreff2 ='',fld_btflag =1 where fld_btid = '$fld_btid' limit 1");
    }
  echo '<script>history.go(-1)</script>';
  }

  function revCharge($fld_btid) {
  $node = $this->uri->segment (3);
  $node2 = $this->uri->segment(4);
  $this->db->query("update tbl_btd_container set fld_btp14 =if(fld_btp14 in ('',0),1,0) where fld_btid = '$node2' and fld_btflag2 in (0,1,2) limit 1");

  echo '<script>history.go(-1)</script>';
  }

  function sendContLand($fld_btid) {
  $node = $this->uri->segment (3);
  $node2 = $this->uri->segment(4);
 # echo $node;
 # exit();
  $this->db->query("update tbl_btd_container set fld_btflag2 =1  where fld_btid = '$node2' and fld_btflag2 = 0 limit 1");

  echo '<script>history.go(-1)</script>';
  }

  private function check_validation($FrmName) {
    switch ($FrmName) {
      case "78000DELIVERY_ORDER_TRAILER":
        $query=$this->db->query("select t0.fld_btid,t0.fld_btno, t0.fld_btp16,
                               date_format(t0.fld_btdt,'%Y-%m-%d') 'dtsa',
                               date_format(now(),'%Y-%m-%d') 'dtso',
	                       DATEDIFF(now(),t0.fld_btdt) 'diff'
			       from tbl_bth t0
			       where t0.fld_bttyid in (77)
                               and t0.fld_btstat = 3
                               and date_format(t0.fld_btdt,'%Y-%m-%d') > '2020-03-01'
                               and t0.fld_btdt > (DATE_SUB(now(), INTERVAL 60 DAY))
			       and t0.fld_btbalance=0
                               and t0.fld_btp17 not in (11)
			       and t0.fld_baidv != 1
                               and t0.fld_baidc != 13523
                               order by t0.fld_btdt limit 10");
         $data = $query->result();
         ### Get total holiday in salary periode

    	 ####
         foreach($data as $rdata) {
           if($rdata->diff > 0) {
             $start = strtotime($rdata->dtsa);
             $stop = strtotime($rdata->dtso);
             $days = abs(($stop -  $start) / 86400) + 1;
             $tat = 0;
             for ($i=1; $i<$days; ++$i) {
               $tmp_day = strtotime("+$i day", $start);
               $day = strftime("%A",$tmp_day);
               $tmp = strftime("%Y-%m-%d",$tmp_day);
               if(($day != "Sunday") && (!in_array($tmp, $holidayList)) ) {
                 $tat = $tat + 1;
               }
               if($tat > 3) {
                 $fld_btid =  $rdata->fld_btid;
                 $fld_btno = $rdata->fld_btno;
                 $fld_btdt = $rdata->dtsa;
                 break(2);
               }
             }
           }
         }
         $query2=$this->db->query("select * from tbl_btty t0
	                         where fld_bttyid in (77)
				 and date_format(t0.fld_lup,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')
				 and t0.fld_bttystat=1");
	   if ($query2->num_rows() < 1) {
             if ($tat > 3) {
	     $this->db->query("update tbl_btty set fld_bttystat = 0 where fld_bttyid in (77) limit 1");
	     $this->ffis->message("This transaction has been locked...!! <br>Reason : DO Number $fld_btno made without quotation since  $fld_btdt<br><br> Click this <a href='http://dunex-exim.com/index.php/page/emailDOT/$fld_btid'>link</a> to send email to Management ");
             $data_log = array(
             'fld_acclogtyid' => '7' ,
             'fld_accloghost' => $_SERVER['REMOTE_ADDR'] ,
             'fld_acclogdt' => date('Y-m-d H:i:s'),
             'fld_acclogcmt' => 'Transaction has been locked...!! Reason : DO Number ' . $fld_btno  . ' made without quotation since ' .   $fld_btdt
             );
             $this->db->insert('tbl_acclog', $data_log);
	   }
	 }
    break;


   case "green":
   $this->dnxapps->message("Acces Denied...");
   break;
    }
  }

  function additionalInvoice () {
    $fld_btid =  $this->uri->segment(3);
    $fld_baidp =  $this->session->userdata('ctid');
    $fgid = $this->db->query("select fld_btp38 from tbl_bth where fld_btid = '$fld_btid'")->row()->fld_btp38;
    echo "###$fgid";
    $trans_no = $this->mkautono(2,41);
    echo "##$trans_no!!";
    $this->db->query("insert into tbl_bth (fld_btidp, fld_baidp, fld_baido, fld_baidc, fld_baidv, fld_btiid, fld_btno, fld_btnoctr, fld_btnoalt, fld_btnoreff, fld_bttaxno, fld_bttyid, fld_btqty, fld_btamt, fld_btamt01,
                      fld_btamt02, fld_bttax, fld_btuamt, fld_btbalance, fld_btdesc, fld_btcmt, fld_btflag, fld_btstat, fld_btdtsa, fld_btdt, fld_bttime, fld_btdtp, fld_btloc, fld_btp01, fld_btp02, fld_btp03, fld_btp04,
                      fld_btp05, fld_btp06, fld_btp07, fld_btp08, fld_btp10, fld_btp11, fld_btp12, fld_btp09, fld_btqty01, fld_btp13, fld_btp14, fld_btp15, fld_lup, fld_btp16, fld_btp17, fld_btp18, fld_btp19, fld_btp20, fld_btp21,
                      fld_btp22, fld_clsdt, fld_btp23, fld_btp24, fld_btp25, fld_btp26, fld_btp27, fld_btp28, fld_btp29, fld_btp30, fld_btp31, fld_btp32, fld_btp33,fld_btp38)
                      select fld_btidp, $fld_baidp, fld_baido, fld_baidc, fld_baidv, fld_btiid, '$trans_no', fld_btnoctr, fld_btnoalt, fld_btnoreff, '',fld_bttyid, fld_btqty, 0, fld_btamt01, fld_btamt02, 0,
                      0, 0, fld_btdesc, fld_btcmt, fld_btflag, 1, '', now(), fld_bttime, fld_btdtp, fld_btloc, '', fld_btp02, fld_btp03, fld_btp04, fld_btp05, fld_btp06,
                      fld_btp07, fld_btp08, fld_btp10, fld_btp11, fld_btp12, fld_btp09, fld_btqty01, fld_btp13, fld_btp14, fld_btp15, '', fld_btp16, fld_btp17, fld_btp18, fld_btp19, fld_btp20, fld_btp21, fld_btp22, fld_clsdt,
                      fld_btp23, fld_btp24, fld_btp25, fld_btp26, 0,'','', fld_btp30, fld_btp31, fld_btp32, fld_btp33,if('$fgid' =2,3,0)  from tbl_bth t0  where t0.fld_btid = $fld_btid limit 1");
    $last_insert_id = $this->db->insert_id();
    $this->db->query("insert into tbl_btr (fld_btrsrc,fld_btrdst,fld_btrdsttyid) value ($fld_btid,$last_insert_id,67)");
    $url = base_url() . "index.php/page/form/78000INVOICE/edit/$last_insert_id?act=edit";
    redirect($url);

  }

  function completePODsubcon($fld_btid) {
    $this->ffis->cekCashApproval($fld_btid);
    $this->db->query("update tbl_bth set fld_btstat=3 where fld_btid=$fld_btid limit 1");
    $url = base_url() . "index.php/page/view/78000POD_SUBCON_UNCOMPLETE?ucl=1";
    redirect($url);
  }

  function exportLoanA($fld_btid) {
    $filename = 'Driver-Balance-Summarry-'.date('Ymd') . '.csv';
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");
    $query="SELECT t2.fld_btno,t1.fld_empnm ,t0.fld_empid,t0.fld_btdesc,t0.fld_btdt,t0.fld_btamt01, t0.fld_btflag
      from tbl_btd_driver_insurance t0
      left join hris.tbl_truck_driver t1 on t1.fld_empid   = t0.fld_empid
      left join tbl_bth t2 on t2.fld_btid=t0.fld_btidp
      where t0.fld_btidp=$fld_btid";
    $data = $this->db->query($query);
    $post_data=$data->row();

    // echo $query;
    $location = $this->session->userdata('location');
    $loan = $data->result_array();

    // $query_loan="";

    echo " DRIVER BALANCE - ".$loan[0]['fld_btno']."\n\n";
    echo "No,Nama,Description,Date,Amount,Loan Type\n";
    $no=1;
    $total=0;
    foreach($loan as $rloan) {
    echo "\"" . $no . "\",\"" . $rloan['fld_empnm'] . "\",\"" . $rloan['fld_btdesc'] . "\",\"" . $rloan['fld_btdt'] . "\",\"" . $rloan['fld_btamt01'] ."\",\"" . $rloan['fld_btflag'] . "\"\n";
     $total = $total + $rloan['fld_btamt01'];
     $no++;
    }
  }


  function InsertDOReturnDoc() {
    $count = $_POST["count"];
    $node =  $_POST["node"];
    $baidp =  $_POST["baidp"];

    $header = $this->db->query("select fld_btdt 'header_date' from tbl_bth where fld_btid = '$node'")->row();

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $id = $_POST["id$x"];
        $remark = $_POST["remark$x"];
        $flagVehGroup = $_POST["flagVehGroup$x"];
          $this->db->query("INSERT INTO tbl_btd_do (fld_btidp, fld_btiid, fld_btflag, fld_unitid, fld_baidp, fld_btdtsa01,fld_btdtso01, fld_btdesc)
                            VALUES ($node, $id, 1, $flagVehGroup, $baidp, date_format('$header->header_date', '%Y-%m-%d'),
                            date_format('$header->header_date', '%Y-%m-%d'), '$remark')");
      }
    }

    $url = base_url() . "index.php/page/form/78000RETURN_DOCUMENT/edit/$node";
    redirect($url);

  }


/*  function InsertDOReturnDoc() {
    $count = $_POST["count"];
    $node =  $_POST["node"];
    $baidp =  $_POST["baidp"];

    $header = $this->db->query("select fld_btdt 'header_date' from tbl_bth where fld_btid = '$node'")->row();

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $id = $_POST["id$x"];
        $remark = $_POST["remark$x"];
        $flagVehGroup = $_POST["flagVehGroup$x"];
          $this->db->query("INSERT INTO tbl_btd_do (fld_btidp, fld_btiid, fld_btflag, fld_unitid, fld_baidp, fld_btdtsa01, fld_btdesc)
                            VALUES ($node, $id, 1, $flagVehGroup, $baidp, date_format('$header->header_date', '%Y-%m-%d'), '$remark')");
      }
    }

    $url = base_url() . "index.php/page/form/78000RETURN_DOCUMENT/edit/$node";
    redirect($url);

  }
*/

  function UpdateDOReturnDoc() {
    $count = $_POST["count"];
    $node =  $_POST["node"];
    $baidp =  $_POST["baidp"];
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $id = $_POST["id$x"];
          $this->db->query("UPDATE tbl_btd_do set fld_btdtso01=NOW(), fld_btp01=$baidp where fld_btiid =$id LIMIT 1");
      }
    }

    $url = base_url() . "index.php/page/view/78000RETURN_DOCUMENT_OUTS";
    redirect($url);

  }

  function exportReturnDoc() {
    $fld_btid =  $this->uri->segment(3);
    $this->dnxapps->exportReturnDoc($fld_btid);
  }

  function getp2hticket($fld_btid){
    $fld_btid =  $fld_btid;
    #$location = $this->session->userdata('location');
    $groupid = $this->session->userdata('group');
    if('$fld_btid'==''){
      $fld_btid =  $this->uri->segment(3);
    }
    // echo $id;
    // $cek_p2h=$this->db->query("SELECT t0.fld_btid, t0.fld_btp12, ifnull(t1.fld_btip31,0) as fld_btip31 from tbl_bth t0
    //   left join dnxapps.tbl_bti t1 on t1.fld_btiid=t0.fld_btp12 and (t1.fld_btip30=1 or t1.fld_btip30=3) and t1.fld_btip31 not in(SELECT fld_btreffid from tbl_toh where fld_btflag=30 )
    //   where t0.fld_btid=$fld_btid and t0.fld_bttyid in (77) and date_format(t0.fld_btdt,'%Y-%m-%d %H:%i') >= '2021-03-09 13:00' limit 1");


    $cek_p2h=$this->db->query("SELECT t0.fld_btid,ifnull(t2.fld_baidp,0) as post, t0.fld_btp12, ifnull(t1.fld_p2h_id,0) as fld_btip31 from tbl_bth t0
      left join dnxapps.tbl_p2h_v_log t1 on t1.fld_btiid=t0.fld_btp12 and
		(t1.fld_p2h_status=1 or t1.fld_p2h_status=3) and t1.fld_p2h_id
		not in(SELECT fld_btreffid from tbl_toh where fld_btflag=30 )
      left join dnxapps.tbl_bth_checklist t2 on t2.fld_btid = t1.fld_p2h_id and t2.fld_bttyid = 15
      where t0.fld_btid=$fld_btid and t0.fld_bttyid in (77) and date_format(t0.fld_btdt,'%Y-%m-%d %H:%i') >= '2021-03-09 13:00' limit 1
      ");

    $d_p2h = $cek_p2h->result();

    if($d_p2h[0]->fld_btip31>0){
        $q_ins_tp2h=" INSERT INTO tbl_toh (fld_btidp,fld_btflag,fld_btreffid,fld_btdesc,fld_btdt,fld_baidp) values ($fld_btid,30,".$d_p2h[0]->fld_btip31.",'P2H Checklist',now()," .$d_p2h[0]->post.");";

        $ins_tp2h=$this->db->query($q_ins_tp2h);
        if($ins_tp2h){
          // echo '<script>history.go(-1)</script>';
        }


    }else{
       if($groupid == 21 || $groupid == 22 || $groupid == 35 || $groupid == 13) {
       echo '<script>alert("P2H Ticket Not Found. ");history.go(-1);</script>';
          exit();
      } else {
      echo '<script>alert("DO tanpa P2H. ");</script>';
          #exit();

     }
      // echo
    }


  }

  function cekp2hticket($fld_btiid){
    $fld_btiid =  $fld_btiid;
    // echo $id;
    if($fld_btiid!=''){


      // $cek_p2h=$this->db->query("SELECT ifnull(t1.fld_btip31,0) as fld_btip31 from dnxapps.tbl_bti t1
      //   where t1.fld_btiid=$fld_btiid and (t1.fld_btip30=1 or t1.fld_btip30=3) and t1.fld_btip31 not in(SELECT fld_btreffid from tbl_toh where fld_btflag=30 ) limit 1");
      $cek_p2h=$this->db->query("SELECT ifnull(t1.fld_p2h_id,0) as fld_btip31 from dnxapps.tbl_p2h_v_log t1
          where t1.fld_btiid=$fld_btiid and (t1.fld_p2h_status=1 or t1.fld_p2h_status=3) and t1.fld_p2h_id not in(SELECT fld_btreffid from tbl_toh where fld_btflag=30 ) limit 1");

      $d_p2h = $cek_p2h->result();

      if($d_p2h[0]->fld_btip31>0){

      }else{
        if($groupid == 21 || $groupid == 22 || $groupid == 35 || $groupid == 13) {
        // echo '<script>alert("P2H Ticket Not Found. ");history.back();</script>';
        echo '<script>alert("P2H Ticket Not Found. ");history.go(-1);</script>';
            exit();
        }
      }

    }else{
        // echo '<script>alert("Truck Number Tidak Terbaca. ");history.back();</script>';
        echo '<script>alert("Truck Number Tidak Terbaca. ");history.go(-1);</script>';
            exit();
    }
  }

  function printBarcodePODSubmit() {
      error_reporting(E_ALL);
      ini_set('display_errors', '1');
      require_once('system/shared/fpdf183/code128.php');

      require_once('system/shared/fpdf183/fpdf.php');
      $id =  $this->uri->segment(3);
      $query=$this->db->query("
          select distinct
          t0.fld_trk_settlementid 'id',
          t3.fld_btno 'DONumber',
          t0.fld_btno 'PODNumber',
          t3.fld_btid 'DoId',
          t1.fld_btid 'PodId'

          from
          tbl_trk_settlement t0
          left join tbl_bth t1 on t1.fld_btid=t0.fld_btreffid
          left join tbl_btr t2 on t2.fld_btrdst = t1.fld_btid
          left join tbl_bth t3 on t3.fld_btid = t2.fld_btrsrc and t3.fld_bttyid in (77,112)
          left join tbl_aprvtkt t8 on t8.fld_btid = t1.fld_btid and t8.fld_aprvroleid = 3

          where
          t0.fld_btidp=$id
          order by t8.fld_lup ASC
      ");

      if($query->num_rows()>0) {
          $items = $query->result();

          $pdf=new PDF_Code128('L','cm',array(2.4,5.4));
          $pdf->SetFont('Arial','',11);
          $code='CODE 128';

          $pdf->SetAutoPageBreak(false);

          foreach ($items as $key => $item) {
              $pdf->AddPage();
              $pdf->Code128(0.5,0.6,$item->DoId,4.3,1);
              $pdf->Cell(3.7,1.7,$item->DONumber,0,0,'R',0);
          }

          $pdf->Output();
      }
  }


  //Trailer Billing Scan
  function ScanCheckBtidDOTrailer() {
    $btid =  $_POST['btid'];
    $baidc =  $_POST['baidc'];
    if ($baidc == false || $baidc == null || $baidc == '') {
        $baidc = 0;
    }
    $doid =  $_POST['inptscn_doid'];
    $check_do=$this->db->query("
        SELECT
        t0.fld_btid 'do_id',
        t1.fld_btid 'pod_id',
        t0.fld_btno 'do_no',
        t1.fld_btno 'pod_no',
        t3.fld_btid 'btd_id'

        FROM tbl_bth t0
        LEFT JOIN tbl_bth t1 on t1.fld_btnoalt = t0.fld_btno AND t1.fld_bttyid = 80 AND t1.fld_btstat =3
        LEFT JOIN tbl_btd t3 ON t3.fld_btidp=t1.fld_btid
        LEFT JOIN tbl_trk_billing t2 ON t2.fld_btreffid=t3.fld_btid

        WHERE
        t0.fld_btid = '$doid'
        AND t0.fld_bttyid in(77,112)
        AND t0.fld_btstat = 3
        AND ifnull(t2.fld_btid,0) = 0
        LIMIT 1
    ")->row();

    if ($check_do->do_id > 0) {
      $data=$this->db->query("
          SELECT
          t8.fld_btid 'id',
          t0.fld_btid 'id_jo',
          t1.fld_benm 'customer',
          t0.fld_btnoalt 'number',
          t0.fld_btnoreff 'jo_number',
          date_format(t0.fld_btdt,'%Y-%m-%d')'date',
          t0.fld_btbalance 'price',
          concat(t10.fld_areanm , '  ' , ' > ' ,'  ' , t11.fld_areanm) 'route',
          t8.fld_btno 'container',
          t2.fld_tyvalnm 'vehicle',
          t7.fld_bticd 'vehicle_number'
          from tbl_bth t0
          left join dnxapps.tbl_be t1 on t1.fld_beid=t0.fld_baidc
          left join dnxapps.tbl_tyval t2 on t2.fld_tyvalcd=t0.fld_btflag and t2.fld_tyid=19
          left join tbl_route t3 on t3.fld_routeid =t0.fld_btp09
          left join dnxapps.tbl_bti t7 on t7.fld_btiid=t0.fld_btp12
          left join tbl_btd t8 on t8.fld_btidp = t0.fld_btid
          left join tbl_trk_billing t6 on t6.fld_btreffid=t8.fld_btid
          LEFT JOIN dnxapps.tbl_route t9 ON t0.fld_btp09=t9.fld_routeid
          left join dnxapps.tbl_area t10 on t10.fld_areaid=t9.fld_routefrom
          left join dnxapps.tbl_area t11 on t11.fld_areaid=t9.fld_routeto
          where
          t0.fld_bttyid = 80
          and ifnull(t6.fld_btreffid,1) = 1
          and t0.fld_baidc = '$baidc'
          and date_format(t0.fld_btdt,'%Y-%m-%d') > '2020-10-01'
          and t0.fld_btid = '$check_do->pod_id'
          limit 1
      ")->row();
      echo json_encode($data);
    } else {
      echo "0";
    }
  }

  function ScanAddBtidDOTrailer() {
    $number =  $_POST['number'];
    $add = $this->db->query("SELECT t0.fld_btamt,
                             t0.fld_btdesc,
                             date_format(t0.fld_btdt,'%Y-%m-%d')'date',
                             datediff(t0.fld_btdtso, t0.fld_btdtsa) 'overnight'
                             from tbl_bth t0
                             where t0.fld_bttyid = 78
                             and t0.fld_baidv != 1
                             and t0.fld_btdt > '2020-10-01 00:00:00'
                             and t0.fld_btnoalt ='$rviewrs->number'")->result();
    $data['loading'] = 0;
    $data['lolo'] = 0;
    $data['overnight'] = 0;
    $data['overnight_charge'] = 0;

    foreach ($add as $radd) {
      if($radd->fld_btdesc == 5 && $radd->date > '2020-10-01') {
        $data['loading'] = $data['loading'] + $radd->fld_btamt;
      }
      if($radd->fld_btdesc == 11) {
        $data['overnight'] = $data['overnight'] + $radd->overnight;
        $data['overnight_charge'] = $data['overnight_charge'] + $radd->fld_btamt;
      }

    }

    echo json_encode($data);
  }


  function scanTruckBillingTrailer() {
      $count = $_POST["count"];
      $fld_baidp  = $this->session->userdata('ctid');
      $division = $this->session->userdata('divid');
      $groupid =$this->session->userdata('group');
      $loc = $this->session->userdata('location');
      $node =  $_POST["node"];
      $total = 0;
      $lolo_charge = 0;

      if ($_POST["order"] == 0) {

          for ($x=1; $x<=$count; $x++){
              $index = $x - 1;
              $fld_btid = $_POST["fld_btid"][$index];
              $do_number = $_POST["fld_btno"][$index];
              $overnight = $_POST["overnight"][$index];
              $lolo = $_POST["lolo"][$index];
              $loading = $_POST["loading"][$index];
              $overnight_charge = $_POST["overnight_charge"][$index];
              $fld_btbalance = $_POST["fld_btbalance"][$index];
              $fld_btnoreff = $_POST["fld_btnoreff"][$index];
              $fld_btp16 = $_POST["fld_btp16"][$index];

              $keterangan_arr = array();

              $check=$this->db->query("SELECT
                t0.fld_btid 'btid',
                t1.fld_btno 'podnumber',
                t1.fld_btnoalt 'donumber',
                t3.fld_btno 'jonumber',
                date_format(t3.fld_btdt, '%Y-%m-%d') 'jodate'

                FROM tbl_btd t0
                LEFT JOIN tbl_bth t1 ON t1.fld_btid=t0.fld_btidp
                LEFT JOIN tbl_trk_settlement t2 ON t2.fld_btreffid=t1.fld_btid
                LEFT JOIN tbl_bth t3 ON t3.fld_btid=t2.fld_btreffid2

                WHERE t0.fld_btid='$fld_btid'
                AND date_format(t3.fld_btdt, '%Y-%m-%d') >= date_format('2021-03-24', '%Y-%m-%d')
                LIMIT 1
              ")->row();

              if ($check->dorid > 0) {
                  $keterangan = "$check->donumber | $check->podnumber <br>";
                  array_push($keterangan_arr, $keterangan);
              } else {
                $this->db->query("insert ignore into tbl_trk_billing
                (fld_btidp,fld_btno,fld_btdt,fld_btamt01,fld_btreffid,fld_btamt02,fld_btamt04,fld_btp05)
                values
                ($node,'$do_number',CURDATE(),$fld_btbalance,'$fld_btid','$overnight_charge','$lolo','$overnight')");
                $total = $total + $fld_btbalance;
              }
          }
          $this->db->query("update tbl_bth set fld_btamt = $total where fld_btid =  $node limit 1");

      }

      if (count($keterangan_arr) > 0) {
          $keterangan = implode("<li>", $keterangan_arr);
          $this->dnxapps->message("PIC Billing has not received the documents <br> $keterangan");
      } else {
          $url = base_url() . "index.php/page/form/78000TRUCKING_BILLING/edit/$node?act=edit";
          redirect($url);
      }
  }

  function getTruckingBillingTrailer() {
    $count = $_POST["count"];
    $dsvid =  $_POST["dsvid"];

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $billid = $_POST["id$x"];
        $btdt = date("Y-m-d H:i:s");

          $this->db->query("
            INSERT INTO `exim`.`tbl_btr` (`fld_btrsrc`, `fld_btrdst`, `fld_btrdsttyid`, `fld_lup`) VALUES ('$dsvid', '$billid', '67', '$btdt')
          ");
          $this->db->query("
            UPDATE `exim`.`tbl_bth` SET `fld_btidp` = '$dsvid' WHERE `tbl_bth`.`fld_btid` = '$billid' LIMIT 1
          ");
      }
    }

    $url = base_url() . "index.php/page/form/78000INVOICE/edit/$dsvid";
    redirect($url);
  }


  function chargeItemListPIB() {
    $count = $_POST["count"];
    $baidp =  $_POST["baidp"];
    $node =  $_POST["node"];

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        echo $jono = $_POST["jono$x"];
        echo $apvtotpay = $_POST["apvtotpay$x"];
        echo $btdt = date("Y-m-d H:i:s");
          $this->db->query("INSERT INTO `exim`.`tbl_btd_finance` (`fld_btidp`, `fld_baidp`, `fld_bedivid`, `fld_btreffid`, `fld_btreffid01`, `fld_btreffid2`, `fld_btnoreff`, `fld_btnoreff2`, `fld_btnodoc`, `fld_btdocreff`, `fld_btiid`, `fld_empid`, `fld_coaid`, `fld_btqty01`, `fld_btqty02`, `fld_btflag`, `fld_btcmt`, `fld_btdesc`, `fld_unitid`, `fld_btuamt01`, `fld_btamt01`, `fld_btp01`, `fld_btp02`, `fld_btp03`, `fld_btp04`, `fld_btp05`, `fld_btp06`, `fld_btp07`, `fld_btp08`, `fld_btp09`, `fld_btp10`, `fld_btp11`, `fld_btp12`, `fld_locid`, `fld_lup`) VALUES ('$node', '0', '0', '0', NULL, NULL, '$jono', '', '', '', '0', '0', '705', '1', '0', '0', '', 'PEMBAYARAN PIB ( RE-IMBURSE )', '0', '$apvtotpay', '$apvtotpay', '0', '0', '0', '0', '0', '', '', '', '', '0', '0', '0', '1', '$btdt')
          ");
      }
    }

    $url = base_url() . "index.php/page/form/78000INVOICE/edit/$node";
    redirect($url);
  }


  function cancelBatchGJL() {
    $count = $_POST["count"];
    $userid = $this->session->userdata('userid');

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        echo $crud = $_POST["crud$x"];
        echo $btdt = date("Y-m-d H:i:s");
          $this->db->query("update tbl_bth set fld_btidp=0, fld_btnoreff='', fld_btstat = 5,fld_btp38 = '$userid' where fld_btid = '$crud' and fld_bttyid=51 and fld_btstat in (1,2) limit 1 ");
         $this->db->query("delete from tbl_btr  where fld_btrsrc = '$crud' or fld_btrdst ='$crud' limit 1 ");
      }
    }

    $url = base_url() . "index.php/page/view/78000GJL_CANCEL?statpro=1";
    redirect($url);
  }

  function reviseBatchGJL() {
    $count = $_POST["count"];
    $userid = $this->session->userdata('userid');
    $groupid =$this->session->userdata('group');
    $group_add =$this->session->userdata('group_add');
    $fld_aprvtktno = date('YmdHis');
    $z =0;
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $number = $_POST["number$x"];
        $crud = $_POST["crud$x"];
        $z =$z+1;

        $query = $this->db->query("update tbl_bth set fld_btstat=2 where fld_btid=$crud limit 1");
        $query = $this->db->query("update tbl_aprvtkt set fld_aprvtktstat=1 where fld_btid=$crud and fld_usergrpid=$groupid limit 1");
      }
    }
    if ($x == 0){
      $url = base_url() . "index.php/page/view/78000GJL_REVISE?statpro=2";
    }else{
      $url = base_url() . "index.php/page/view/78000GJL_REVISE?statpro=1";
    }
    redirect($url);
  }

  function cancelBatchINV() {
    $count = $_POST["count"];
    $userid = $this->session->userdata('userid');

    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        echo $btid = $_POST["fld_btid$x"];
        echo $btdt = date("Y-m-d H:i:s");
          $this->db->query("update tbl_bth set fld_btidp=0, fld_btnoreff='', fld_btstat = 5,fld_btp38 = '$userid' where fld_btid = '$btid' and fld_bttyid in (41,82) and fld_btstat in (1,2) limit 1 ");
         $this->db->query("delete from tbl_btr  where fld_btrsrc = '$btid' or fld_btrdst ='$btid' limit 1 ");
      }
    }

    $url = base_url() . "index.php/page/view/78000INV_CANCEL?statpro=1";
    redirect($url);
  }


  function saveEstimatedRptBill() {
    $ctid = $this->session->userdata('ctid');
    $joids = $this->input->post('joid');
    $joupdts = $this->input->post('joupdt');


    $types = $this->db->query("select fld_tyvalp03,fld_tyvalcd,fld_tyvalnm,fld_tyvalp01,fld_tyvalp02,fld_tyvalcfg from tbl_tyval where fld_tyid = 121 order by fld_tyvalp03 asc");
    foreach ($types->result() as $key => $type) {
      ${$type->fld_tyvalp01 . "s"} = $this->input->post($type->fld_tyvalp01);
    }

    foreach ($joids as $index => $joid) {
      $query = '';
      if ($joupdts[$index] == 1) {
        $btp01 = ($btp01s[$index] > 0) ? $btp01s[$index] : 0;
        // $btp02 = ($btp02s[$index] > 0) ? $btp02s[$index] : 0;
        $btp03 = ($btp03s[$index] > 0) ? $btp03s[$index] : 0;
        $btp04 = ($btp04s[$index] > 0) ? $btp04s[$index] : 0;
        $btp05 = ($btp05s[$index] > 0) ? $btp05s[$index] : 0;
        $btp06 = ($btp06s[$index] > 0) ? $btp06s[$index] : 0;
        $btp07 = ($btp07s[$index] > 0) ? $btp07s[$index] : 0;
        $btp08 = ($btp08s[$index] > 0) ? $btp08s[$index] : 0;
        $btp09 = ($btp09s[$index] > 0) ? $btp09s[$index] : 0;
        $btp10 = ($btp10s[$index] > 0) ? $btp10s[$index] : 0;
        $btp11 = ($btp11s[$index] > 0) ? $btp11s[$index] : 0;
        $btp12 = ($btp12s[$index] > 0) ? $btp12s[$index] : 0;
        $btp13 = ($btp13s[$index] > 0) ? $btp13s[$index] : 0;
        $btp14 = ($btp14s[$index] > 0) ? $btp14s[$index] : 0;
        $btp15 = ($btp15s[$index] > 0) ? $btp15s[$index] : 0;
        $query = "UPDATE tbl_btd_finance_rpt SET fld_btp01 = " . $btp01 . ", fld_btp03 = " . $btp03 . ", fld_btp04 = " . $btp04 . ", fld_btp05 = " . $btp05 . ", fld_btp06 = " . $btp06 . ", fld_btp07 = " . $btp07 . ", fld_btp08 = " . $btp08 . ", fld_btp09 = " . $btp09 . ", fld_btp10 = " . $btp10 . ", fld_btp11 = " . $btp11 . ", fld_btp12 = " . $btp12 . ", fld_btp13 = " . $btp13 . ", fld_btp14 = " . $btp14 . ", fld_btp15 = " . $btp15 . " WHERE fld_btidp = '$joid' AND fld_baidp = '$ctid' AND fld_btflag = 1 LIMIT 1";

        $this->db->query($query);
      } else {
        $query = "INSERT INTO exim.tbl_btd_finance_rpt (fld_btidp, fld_baidp, fld_btflag, fld_btdt, fld_btp01, fld_btp03, fld_btp04, fld_btp05, fld_btp06, fld_btp07, fld_btp08, fld_btp09, fld_btp10, fld_btp11, fld_btp12, fld_btp13, fld_btp14, fld_btp15, fld_lup) VALUES ('$joid', '$ctid', '1', now()";
        foreach ($types->result() as $key => $type) {
          $query = $query . ", " . ${$type->fld_tyvalp01 . "s"}[$index] = (${$type->fld_tyvalp01 . "s"}[$index] > 0) ? ${$type->fld_tyvalp01 . "s"}[$index] : 0 ;
        }
        $query = $query . ", now())";

        $this->db->query($query);
      }

    }

    $url = base_url() . "index.php/page/view/78000ESTIMATED_RPT_NY_BILLING_SUBMIT?stat=1";
    redirect($url);
  }


  function addLoloDepoCOJ() {
    $count = $_POST["count"];
    $sid = $_POST["sid"];
    $userid = $this->session->userdata('userid');
    $ctid = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');

    $cek =  $this->db->query("SELECT fld_btloc, fld_btamt  FROM tbl_bth WHERE fld_btid='$sid' LIMIT 1")->row();

    $comp = '';
    $tot_amount = 0;
    for ($x=1; $x<=$count; $x++){
      $desc = '';
      if($_POST["rowdata$x"] == "on") {
        $jstid = $_POST["jstid$x"];
        $jono = $_POST["jono$x"];
        $jocno = $_POST["jocno$x"];
        $jstno = $_POST["jstno$x"];
        $company = $_POST["company$x"];
        $customer = $_POST["customer$x"];
        $costdesc = $_POST["costdesc$x"];
        $jstdtltot = $_POST["jstdtltot$x"];
        $jstdtlinv = $_POST["jstdtlinv$x"];
        $masterbl = $_POST["masterbl$x"];

        $comp = $company;
        $desc = $customer . '-' . $costdesc . '#';
        $tot_amount = $tot_amount + $jstdtltot;

        $this->db->query("INSERT INTO exim.tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid, fld_btnoreff, fld_btdocreff, fld_empid, fld_coaid, fld_btdesc, fld_btamt01, fld_locid, fld_btflag,fld_btnodoc) VALUES ('$sid', '0', '$division', '$jstid', '$jono', '$masterbl', '$ctid', '705', '$desc', '$jstdtltot', '$cek->fld_btloc', '6', '$jstno')");

        #update status settlement
        $this->db->query("update tbl_bth set fld_btstat = 3, fld_btdtsa = now()
                          where fld_btid ='$jstid' limit 1");

      }
    }

    $tot_amount = $tot_amount + $cek->fld_btamt;
    if ($comp != '' && $comp == 'REMA') {
      $this->db->query("UPDATE tbl_bth set fld_btiid='1313', fld_btp23='1', fld_btamt = '$tot_amount' where fld_btid = '$sid' limit 1");
    } else {
      $this->db->query("UPDATE tbl_bth set fld_btiid='5', fld_btp23='', fld_btamt = '$tot_amount' where fld_btid = '$sid' limit 1");
    }

    $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/$sid";
    redirect($url);
  }

  function addSettleDeposit() {
    $count = $_POST["count"];
    $sid = $_POST["sid"];
    $userid = $this->session->userdata('userid');
    $ctid = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');

    $cek =  $this->db->query("SELECT fld_btloc, fld_btamt  FROM tbl_bth WHERE fld_btid='$sid' LIMIT 1")->row();

    $comp = '';
    $tot_amount = 0;
    for ($x=1; $x<=$count; $x++){
      $desc = '';
      if($_POST["rowdata$x"] == "on") {
        $btid = $_POST["btid$x"];
        $jstno = $_POST["jstno$x"];
        $jstid = $_POST["jstid$x"];
        $jono = $_POST["jono$x"];
        $cost = $_POST["cost$x"];
        $customer = $_POST["customer$x"];
        $credit = $_POST["credit$x"];
        $invno = $_POST["invno$x"];
        $opr = $_POST["opsid$x"];
        $iddtldep = $_POST["iddtldep$x"];
        $bl = $_POST["bl$x"];
        $comp = $company;
        $desc = $customer . '-' . $cost . '#';
        $tot_amount = $tot_amount + $credit;

        $this->db->query("INSERT INTO exim.tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid2, fld_btnoreff, fld_empid, fld_coaid,
        fld_btdesc, fld_btamt01, fld_locid, fld_btflag, fld_btreffid01, fld_btdocreff, fld_btnodoc)
        VALUES ('$sid', '0', '$division', '$btid', '$jono', '$opr', '705', '$desc', '$credit', '$cek->fld_btloc', '15', '$iddtldep', '$bl', '$invno')");

        $this->db->query("INSERT INTO exim.tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid2,fld_btnoreff, fld_empid, fld_coaid,fld_btdesc, fld_btamt01, fld_locid, fld_btflag, fld_btreffid01, fld_btdocreff, fld_btnodoc)
        VALUES ('$sid', '0', '$division', '$btid', '$jono', '$opr', '698', '$desc', concat('-',$credit), '$cek->fld_btloc', '15', '$iddtldep', '$bl', '$invno')");

         $this->db->query("UPDATE tbl_btd_upload_deposit set fld_btflag2='$sid' where fld_btp02 = '$invno' limit 1");
         #update status settlement
         $this->db->query("update tbl_bth set fld_btstat = 3, fld_btdtsa = now()
                           where fld_btid ='$jstid' limit 1");

      }
    }
	 $tot_amount = $tot_amount + $cek->fld_btamt;

      $this->db->query("UPDATE tbl_bth set fld_btiid='1313', fld_btp23='0', fld_btamt = '$tot_amount' where fld_btid = '$sid' limit 1");


    $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$sid";
    redirect($url);
  }

  function addSettleDepositCOJ() {
    $count = $_POST["count"];
    $sid = $_POST["sid"];
    $userid = $this->session->userdata('userid');
    $ctid = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');

    $cek =  $this->db->query("SELECT fld_btloc, fld_btamt  FROM tbl_bth WHERE fld_btid='$sid' LIMIT 1")->row();

    $comp = '';
    $tot_amount = 0;
    for ($x=1; $x<=$count; $x++){
      $desc = '';
      if($_POST["rowdata$x"] == "on") {
        $btid = $_POST["btid$x"];
        $jstno = $_POST["jstno$x"];
        $jstid = $_POST["jstid$x"];
        $jono = $_POST["jono$x"];
        $cost = $_POST["cost$x"];
        $customer = $_POST["customer$x"];
        $credit = $_POST["credit$x"];
        $invno = $_POST["invno$x"];
        $opr = $_POST["opsid$x"];
        $iddtldep = $_POST["iddtldep$x"];
        $bl = $_POST["bl$x"];
        $comp = $company;
        $desc = $customer . '-' . $cost . '#';
        $tot_amount = $tot_amount + $credit;

        $this->db->query("INSERT INTO exim.tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid2, fld_btnoreff, fld_empid, fld_coaid,
        fld_btdesc, fld_btamt01, fld_locid, fld_btflag, fld_btreffid01, fld_btdocreff, fld_btnodoc)
        VALUES ('$sid', '0', '$division', '$btid', '$jono', '$opr', '705', '$desc', '$credit', '$cek->fld_btloc', '7', '$iddtldep', '$bl', '$invno')");

         $this->db->query("INSERT INTO exim.tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid2,fld_btnoreff, fld_empid, fld_coaid,fld_btdesc, fld_btamt01, fld_locid, fld_btflag, fld_btreffid01, fld_btdocreff, fld_btnodoc)
        VALUES ('$sid', '0', '$division', '$btid', '$jono', '$opr', '698', '$desc', concat('-',$credit), '$cek->fld_btloc', '7', '$iddtldep', '$bl', '$invno')");

         $this->db->query("UPDATE tbl_btd_upload_deposit set fld_btflag2='$sid' where fld_btp02 = '$invno' limit 1");
         #update status settlement
         $this->db->query("update tbl_bth set fld_btstat = 3, fld_btdtsa = now()
                           where fld_btid ='$jstid' limit 1");

      }
    }
         $tot_amount = $tot_amount + $cek->fld_btamt;

      $this->db->query("UPDATE tbl_bth set fld_btiid='1313', fld_btp23='0', fld_btamt = '$tot_amount' where fld_btid = '$sid' limit 1");


    $url = base_url() . "index.php/page/form/78000CASH_OUT/edit/$sid";
    redirect($url);
  }

  function addUploadDeposit() {
    $count = $_POST["count"];
    $sid = $_POST["sid"];
    $userid = $this->session->userdata('userid');
    $ctid = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');

    $cek =  $this->db->query("SELECT fld_btloc, fld_btamt  FROM tbl_bth WHERE fld_btid='$sid' LIMIT 1")->row();

    $tot_amount = 0;
    for ($x=1; $x<=$count; $x++){
      $desc = '';
      if($_POST["rowdata$x"] == "on") {
        $btid = $_POST["btid$x"];
        $depno = $_POST["depno$x"];
        $depot = $_POST["depot$x"];
        $credit = $_POST["credit$x"];
        $invno = $_POST["invno$x"];
        $consignee = $_POST["consignee$x"];
        $desc = $consignee . '-' . $depot . '#';
        $tot_amount = $tot_amount + $credit;

        $this->db->query("INSERT INTO exim.tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid2, fld_btnoreff, fld_empid, fld_coaid,
                                                  fld_btdesc, fld_btamt01, fld_locid, fld_btflag, fld_btdocreff)
                          VALUES ('$sid', '0', '$division', '$btid', '$depno', '$ctid', '698', '$desc', '$credit', '$cek->fld_btloc', '9', '$invno')");

                $this->db->query("UPDATE tbl_btd_upload_deposit set fld_btflag2='$sid' where fld_btp02 = '$invno' and fld_btid = '$btid' limit 1");

      }
    }

    $tot_amount = $tot_amount + $cek->fld_btamt;

	$this->db->query("UPDATE tbl_bth set fld_btiid='5', fld_btamt = '$tot_amount',
                        fld_btdesc = concat('DEPOSIT','-','$depno')
                        where fld_btid = '$sid' limit 1");


    $url = base_url() . "index.php/page/form/78000CASH_IN/edit/$sid";
    redirect($url);
  }

  function addUploadDepositBOJ() {
    $count = $_POST["count"];
    $sid = $_POST["sid"];
    $userid = $this->session->userdata('userid');
    $ctid = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');

    $cek =  $this->db->query("SELECT fld_btloc, fld_btamt  FROM tbl_bth WHERE fld_btid='$sid' LIMIT 1")->row();

    $tot_amount = 0;
    for ($x=1; $x<=$count; $x++){
      $desc = '';
      if($_POST["rowdata$x"] == "on") {
        $btid = $_POST["btid$x"];
        $depno = $_POST["depno$x"];
        $depot = $_POST["depot$x"];
        $debit = $_POST["debit$x"];
        $invno = $_POST["invno$x"];
        $desc = $depno . '-' . $depot . '#';
        $tot_amount = $tot_amount + $credit;

        $this->db->query("INSERT INTO exim.tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid2, fld_btnoreff, fld_empid, fld_coaid,
                                                  fld_btdesc, fld_btamt01, fld_locid, fld_btflag)
                          VALUES ('$sid', '0', '$division', '$btid', '$depno', '$ctid', '698', '$desc', '$debit', '$cek->fld_btloc', '10')");

                $this->db->query("UPDATE tbl_btd_upload_deposit set fld_btflag2='$sid' where fld_btp02 = '$invno' limit 1");

      }
    }

 $tot_amount = $tot_amount + $cek->fld_btamt;

      $this->db->query("UPDATE tbl_bth set fld_btiid='1201', fld_btp23='1', fld_btamt = '$tot_amount' where fld_btid = '$sid' limit 1");


    $url = base_url() . "index.php/page/form/78000BANK_OUT/edit/$sid";
    redirect($url);
  }

  function addUploadDepositGJL() {
    $count = $_POST["count"];
    $sid = $_POST["sid"];
    $userid = $this->session->userdata('userid');
    $ctid = $this->session->userdata('ctid');
    $division  = $this->session->userdata('divid');
    $location = $this->session->userdata('location');

    $cek =  $this->db->query("SELECT fld_btloc, fld_btamt  FROM tbl_bth WHERE fld_btid='$sid' LIMIT 1")->row();

 #   $tot_amount = 0;
    for ($x=1; $x<=$count; $x++){
      $desc = '';
      if($_POST["rowdata$x"] == "on") {
        $btid = $_POST["btid$x"];
        $depno = $_POST["depno$x"];
        $depot = $_POST["depot$x"];
        $credit = $_POST["credit$x"];
        $invno = $_POST["invno$x"];
        $consignee = $_POST["consignee$x"];
        $desc = $consignee . '-' . $depot . '#';
  #      $tot_amount = $tot_amount + $credit;

        $this->db->query("INSERT INTO exim.tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid2, fld_btnoreff, fld_empid, fld_coaid,
                                                  fld_btdesc, fld_btamt01, fld_locid, fld_btflag, fld_btdocreff,fld_btnodoc)
                          VALUES ('$sid', '0', '$division', '$btid', '$depno', '$ctid', '705', '$desc', '$credit', '$cek->fld_btloc', '11', '$invno', '$invno')");

	$this->db->query("INSERT INTO exim.tbl_btd_finance (fld_btidp, fld_baidp, fld_bedivid, fld_btreffid2, fld_btnoreff, fld_empid, fld_coaid,
                                                  fld_btdesc, fld_btamt01, fld_locid, fld_btflag, fld_btdocreff,fld_btnodoc)
                          VALUES ('$sid', '0', '$division', '$btid', '$depno', '$ctid', '698', '$desc', concat('-',$credit), '$cek->fld_btloc', '11', '$invno', '$invno')");

                $this->db->query("UPDATE tbl_btd_upload_deposit set fld_btflag2='$sid' where fld_btp02 = '$invno' and fld_btid = '$btid' limit 1");

      }
 }


        $this->db->query("UPDATE tbl_bth set
                        fld_btdesc = concat('DEPOSIT','-','$depno')
                        where fld_btid = '$sid' limit 1");


    $url = base_url() . "index.php/page/form/78000GENERAL_JOURNAL/edit/$sid";
    redirect($url);
  }

  function linkBillInv() {
    $billings = $_POST['billno'];
    $invoices = $_POST['invno'];
    $billnotfound=array();
    $invnotfound=array();

    foreach ($billings as $key => $billno) {
      $checkbillno = $this->db->query("SELECT fld_btid 'billid'  FROM tbl_bth WHERE fld_btno LIKE '$billno' AND fld_bttyid in(26) LIMIT 1")->row();
      $checkinvno = $this->db->query("SELECT fld_btid 'dsvid'  FROM tbl_bth WHERE fld_btno LIKE '$invoices[$key]' AND fld_bttyid in(41) LIMIT 1")->row();

      if ($checkbillno->billid == NULL) {
        array_push($billnotfound, $billno);
      }
      if ($checkinvno->dsvid == NULL) {
        array_push($invnotfound, $invoices[$key]);
      }

      if ($checkbillno->billid != NULL && $checkinvno->dsvid != NULL) {
        $this->db->query("
            INSERT INTO `exim`.`tbl_btr` (`fld_btrsrc`, `fld_btrdst`, `fld_btrdsttyid`, `fld_lup`) VALUES ('$checkinvno->dsvid', '$checkbillno->billid', '67', '$btdt')
          ");
        $this->db->query("
          UPDATE `exim`.`tbl_bth` SET `fld_btidp` = '$checkinvno->dsvid' WHERE `tbl_bth`.`fld_btid` = '$checkbillno->billid' LIMIT 1
        ");
      }

    }

    if (count($billnotfound) > 0 || count($invnotfound) > 0) {
      $list_bill = join(', ', $billnotfound);
      $list_inv = join(', ', $invnotfound);
      $this->dnxapps->message($list_bill . "Biling Not Found. <br>" . $list_inv . "Invoice Not Found.");
    } else {
      $url = base_url() . "index.php/page/view/78000LINK_BILL_INV?stat=1";
      redirect($url);
    }

  }


  function exportInvGetInv() {
    $node =  $_POST["node"];
    $count = $_POST["count"];
    $fld_baidp  = $this->session->userdata('ctid');
    for ($x=1; $x<=$count; $x++){
      if($_POST["rowdata$x"] == "on") {
        $inv_id = $_POST["fld_btid$x"];
        $this->db->query("insert ignore into tbl_btd_invdel
              (fld_btidp,fld_btiid,fld_btflag, fld_btdt)
              values
              ($node,$inv_id,'118', now())");
      }
    }
    $url = base_url() . "index.php/page/form/78000EXPORT_INV/edit/$node?act=edit";
    redirect($url);
  }


  function exportInvToExcel() {
    $node =  $this->uri->segment(3);
    $export = $this->ffis->exportInvToExcel($node);
  }

  //POINT TMS VISUAL MAPS
  function getLocationPoiAndActualTms()
  {
    $fld_btidp = $this->input->post('fld_btidp');
    log_message('info', "test");
    log_message('info', $fld_btidp);
    $query = $this->db->query("
        SELECT
        t0.fld_btid 'id',
        t0.fld_btdt 'date',
        t0.fld_locvalid 'valid',
         p.fld_radius 'radius',
        if(t0.fld_btflag in (1),t0.fld_btdesc,
                               IFNULL(t6.fld_tyvalnm,'')) 'status',
        if(t0.fld_btflag in (1,3,5,30,17),'',
        if(t0.fld_btflag in (50,51),t0.fld_btnote,
        if(t0.fld_locvalid = 1,'lokasi TMS sesuai',if(t0.fld_locvalid = 2,'refferensi POI tidak ada','lokasi TMS diluar radius'))
        )
        )'remarks'
        , t0.fld_lat 'lat_act',
        t0.fld_long 'lng_act',
        p.fld_gpslat 'lat_poi',
        p.fld_gpslong 'lng_poi'
        from tbl_toh t0
        inner JOIN dnxapps.tbl_poi p ON p.fld_poiid = t0.fld_locreffid
        left join dnxapps.tbl_tyval t6 on t6.fld_tyvalcd = SUBSTRING(t0.fld_btflag,1,2) and t6.fld_tyid = 248
        WHERE t0.fld_btidp = $fld_btidp
        order by t0.fld_btid ASC
            ");
    $marker = array();
    foreach ($query->result() as $key => $value) {
      // code...

      $marker[] = [
        'id' => $value->id,
        'valid' => $value->valid,
        'date' => $value->date,
        'radius' => $value->radius,
        'status' => $value->status,
        'remarks' => $value->remarks,
        'lat_act' => $value->lat_act,
        'lng_act' => $value->lng_act,
        'lat_poi' => $value->lat_poi,
        'lng_poi' => $value->lng_poi,
      ];
    }

    log_message('info', "marker");
    log_message('info', print_r($marker, TRUE));
    if (count($marker) > 0) {
      echo json_encode($marker);
      log_message('info', "marker1");
      log_message('info', json_encode($marker));
    } else {
      echo 0;
    }

   }

   function receivechecking(){
    $count = $_POST["count"];
    $date_now = date('Y-m-d');
    $ctid = $this->session->userdata('ctid');
    $loc = $this->session->userdata('location');
    for ($x = 1; $x <= $count; $x++) {
      if ($_POST["rowdata$x"] == "on") {
        $btid = $_POST["fld_btid$x"];
        $this->db->query("insert into tbl_trans_log(fld_btidp,fld_baidp,fld_log_tyid,fld_btdt) values ('$btid','$ctid',1,now())");
      }
    }

    $url = base_url() . "index.php/page/view/78000INV_RECEIVE_CHECKING";
    redirect($url);
    exit();
  }

  function donechecking (){
    $count = $_POST["count"];
    $date_now = date('Y-m-d');
    $ctid = $this->session->userdata('ctid');
    $loc = $this->session->userdata('location');
    for ($x = 1; $x <= $count; $x++) {
      if ($_POST["rowdata$x"] == "on") {
        $btid = $_POST["fld_btid$x"];
        $this->db->query("insert into tbl_trans_log(fld_btidp,fld_baidp,fld_log_tyid,fld_btdtsa) values ('$btid','$ctid',2,now())");
      }
    }
    $url = base_url() . "index.php/page/view/78000DONE_CHECKING";
    redirect($url);
    exit();
  }

  function rejectinvoice (){
    $count = $_POST["count"];
    $date_now = date('Y-m-d');
    $ctid = $this->session->userdata('ctid');
    $loc = $this->session->userdata('location');
    for ($x = 1; $x <= $count; $x++) {
      if ($_POST["rowdata$x"] == "on") {
        $btid = $_POST["fld_btid$x"];
        $fld_btdesc = $_POST["fld_btdesc$x"];
        $this->db->query("insert into tbl_trans_log(fld_btidp,fld_baidp,fld_log_tyid,fld_btdesc,fld_btdtsa) values ('$btid','$ctid',3,'$fld_btdesc',now())");
      }
    }
    $url = base_url() . "index.php/page/view/78000INV_REJECT_CHECKING";
    redirect($url);
    exit();
  }

  function donereceive (){
    $count = $_POST["count"];
    $date_now = date('Y-m-d');
    $ctid = $this->session->userdata('ctid');
    $loc = $this->session->userdata('location');
    for ($x = 1; $x <= $count; $x++) {
      if ($_POST["rowdata$x"] == "on") {
        $btid[] = $_POST["fld_btid$x"];
      }
    }
    $_btid = implode(",", $btid);
    $this->db->query("update tbl_trans_log set fld_btdt = now(), fld_log_tyid = 30 where fld_btidp in($_btid) and fld_log_tyid = 3 ");
    $this->db->query("delete from tbl_trans_log where fld_btidp in($_btid) and fld_log_tyid = 1 ");

    $url = base_url() . "index.php/page/view/78000INV_RECEIVE_CHECKING";
    redirect($url);
    exit();
  }

    function invreason (){
  $count = $_POST["count"];
  $date_now = date('Y-m-d');
  $ctid = $this->session->userdata('ctid');
  $loc = $this->session->userdata('location');
  for ($x = 1; $x <= $count; $x++) {
    if ($_POST["rowdata$x"] == "on") {
      $btid = $_POST["fld_btid$x"];
      $fld_btdesc = $_POST["fld_btdesc$x"];
      $this->db->query("insert into tbl_trans_log(fld_btidp,fld_baidp,fld_log_tyid,fld_btdesc,fld_btdtsa) values ('$btid','$ctid',6,'$fld_btdesc',now())");
    }
  }
  $url = base_url() . "index.php/page/view/78000INV_REASON";
  redirect($url);
  exit();
}

/*function cancelinv(){
  $count = $_POST["count"];
  $date_now = date('Y-m-d');
  $ctid = $this->session->userdata('ctid');
  $loc = $this->session->userdata('location');
  $btid = $this->uri->segment(3);

  $this->db->query("insert into tbl_trans_log(fld_btidp,fld_baidp,fld_log_tyid,fld_btdesc,fld_btdt) values ('$btid','$ctid',30,'[cancel] from done checking',now())");
  $this->db->query("delete from tbl_trans_log where fld_btidp = $btid and fld_log_tyid = 1 ");

  $url = base_url() . "index.php/page/view/78000DONE_CHECKING";
  redirect($url);
  exit();
}*/

  function cancelinv(){
  $count = $_POST["count"];
  $date_now = date('Y-m-d');
  $ctid = $this->session->userdata('ctid');
  $loc = $this->session->userdata('location');
  // $btid = $this->uri->segment(3);
  $btid = $this->input->post('fld_btid');
  $reason = $this->input->post('desc');

  $_insert = $this->db->query("insert into tbl_trans_log(fld_btidp,fld_baidp,fld_log_tyid,fld_btdesc,fld_btdt) values ('$btid','$ctid',30,'[cancel] $reason',now())");
  #$_delete = $this->db->query("delete from tbl_trans_log where fld_btidp = $btid and fld_log_tyid = 1 ");

/*  if($_insert && $_delete){
    $_result = true; $_msg = "Update Berhasil";
  }else{
    $_result = false; $_msg = "Update Tidak Berhasil";
  }*/
  header("Content-Type: application/json");
  echo json_encode(array("error" => $_result, "message" => $_msg, "btid" => $btid, "reason" => $reason));
  /* $url = base_url() . "index.php/page/view/78000DONE_CHECKING";
  redirect($url);
  exit(); */
}


  function InvoiceDate() {
    $ctid = $this->session->userdata('ctid');
     $data = $this->db->query("select t2.fld_btno,t2.fld_btp42,t2.fld_btid, t0.fld_btdt
                       from tbl_bth t0
                       left join tbl_btd_invdel t1 on t1.fld_btidp = t0.fld_btid
                       left join tbl_bth t2 on t2.fld_btid = t1.fld_btiid
                       left join tbl_trans_log t3 on t3.fld_btidp = t2.fld_btid
                       where t0.fld_bttyid = 72
                       and
                       t2.fld_btdt like '%2022%'
                       and ifnull(t3.fld_btid,0)= 0
                       ")->result();
    foreach($data as $rdata) {
      echo "$rdata->fld_btno<br>";
      $this->db->query("insert into tbl_trans_log (fld_baidp,fld_btidp,fld_log_tyid,fld_btdesc,fld_btdt)
                       value('$ctid','$rdata->fld_btid',4,'[msg-system] PRINTED BATCH','$rdata->fld_btdt')");
      $last_insert_id = $this->db->insert_id();
      $this->db->query("update tbl_bth set fld_btp42 ='$last_insert_id' where fld_btid = '$rdata->fld_btid'  limit 1");

    }
  }

  function invunlock() {
    $ctid = $this->session->userdata('ctid');
    $loc = $this->session->userdata('location');
    $date_now = date('Y-m-d H:i:s');

    $count = $_POST["count"];
    for ($x = 1; $x <= $count; $x++) {
      if ($_POST["rowdata$x"] == "on") {
        $fld_invid[] = $_POST["fld_btid$x"];
      }
    }

    $_update = implode(",",$fld_invid);
    $this->db->query("update tbl_bth set fld_btp37 = 0, fld_btdt2 = '$date_now' where fld_bttyid = 41 and fld_btid in($_update)");
    $this->ffis->message('Unlocked Success..!! ');
    $url = base_url() . "index.php/page/view/78000INV_UNLOCK";
    redirect($url);
  }

}
