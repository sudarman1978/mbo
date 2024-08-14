<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller
{
    public function __construct ()
    {
        parent::__construct();
        $this->load->model('order_model');

        // if (!$this->session->userdata('email'))
        //     redirect('login');
    }

    public function index ()
    {
        $data['title'] = 'Sales Orders';

        $data['model'] = $this->order_model->getAll();

        $this->template->set('title', $data['title']);
        $this->template->load('template', 'contents', 'order/index', $data);
    }

    public function beaCukai ()
    {
        $data['title'] = 'Bea Cukai';

        $data['model'] = $this->order_model->getAll();

        $this->template->set('title', $data['title']);
        $this->template->load('template', 'contents', 'order/beacukai', $data);
    }

    public function grab ()
    {
        $this->order_model->crawl();
    }
}