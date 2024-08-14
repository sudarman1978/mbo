<?
// session_start();
error_reporting(0);
class Login extends CI_Controller {
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
  $userName = $this->input->post('username');
  $password = $this->input->post('password');
  $chkAuth = $this->login->checkAuth($userName,$password,'NOT');
  if($chkAuth){
    redirect('/page'); //load cpanel file – authentication successful
  } else {
    redirect('/page'); //failed auth – return to the login form
  }
}

public function logout(){
  $this->session->sess_destroy();
  redirect('/page');
}

}
