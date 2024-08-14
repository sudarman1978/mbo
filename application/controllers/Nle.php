<?
// session_start();
error_reporting(0);
class Nle extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('url');
    $this->load->model('login_model','login',TRUE);
}

function login_form() {
  $this->load->view('/login_view');
}

function index() {
  /* if the form is submitted – check whether the user is already logged in or not */
  if($this->login->check_session()){
    redirect('/page');
  }
    $username =  $_GET['username'];
    $password =  $_GET['username'];
    $npwp =  $_GET['npwp'];
    // $NLE =  'NLE';
    // echo $username.'a';
    $chkAuth = $this->login->checkAuth($username,$password,'NLE',$npwp);
    // echo $chkAuth;
    if($chkAuth){
      redirect('/page'); //load cpanel file – authentication successful
    } else {
      $this->load->view('/login_view');
    }
}

public function logout(){
  $this->session->sess_destroy();
  redirect('/page');
}

}
