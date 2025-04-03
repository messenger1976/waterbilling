<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class mobile_dashboard extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/mobile_header'; 
	public $login_redirect = '/master/app_login';
	public $listPage = 'mobile_dashboard'; 
	public function __construct() {
        parent::__construct();
		$this->load->helper('common');
  		$this->load->model('dashboard_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
		$this->load->model('addcustomer_model','customer_model');
		$this->load->model('addmetercustomerreading_model','meterreading_model');
		
		
    }
	public function index($mode =''){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['msg'] ='';
		//*****  View Loading  *****//
		if(!isset($_SESSION['current_billingperiod'])){
			$data['current_billingperiod'] = $this->comm_model->get_billingperiod_record();
			
			$_SESSION['current_billingperiod'] = getMonthName($data['current_billingperiod'][0]['bp_period_month'])[0]->month_name.' '.$data['current_billingperiod'][0]['bp_period_year'];
			$data['bp_month'] = $data['current_billingperiod'][0]['bp_period_month'];
			$data['bp_year'] = $data['current_billingperiod'][0]['bp_period_year'];
			$_SESSION['bp_month'] = $data['bp_month'];
			$_SESSION['bp_year'] = $data['bp_year'];
		}
		
		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$data['customer_listing'] = $this->customer_model->get_all_records();
		//echo '<pre>';print_r($data);exit;
		$this->load->view($this->listPage,$data);
	}

	public function logout(){
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('usertype');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user_email');
		$this->session->unset_userdata('user_fullname');
		$this->session->sess_destroy();
		session_destroy();
		unset($_SESSION['current_billingperiod']);
		redirect($this->login_redirect);
		
	}

	public function get_customer_meter_reading(){
		$customer_id = $this->input->post('customer_id');
		$bp_month = $this->input->post('bp_month');
		$bp_year = $this->input->post('bp_year');
		
		if($customer_id != ''){
			$result = $this->meterreading_model->get_addcustomer_meterreading_records($customer_id,$bp_month,$bp_year);
			echo json_encode($result);
			
		}else{
			echo '{}';
		}
	}

	

	
}
?>