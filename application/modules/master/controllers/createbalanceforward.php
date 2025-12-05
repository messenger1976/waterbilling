<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class createbalanceforward extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $table_name = 'tbl_billing_period';	  //*****  Table name  *****//
	public $listPage = 'createbalanceforward';		   //*****  View page   *****//
	public $createbalanceforward_search_ajax = 'createbalanceforward_ajax';

	public $listPage_redirect = '/master/createbalanceforward';		  //*****  Redirect View  *****//
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
  		$this->load->model('createbalanceforward_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->load->helper('common');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }

	public function index(){ 		 
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(	array_key_exists('createbalanceforward',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin' ){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['createbalanceforward']);
		}
		//*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->my_model->get_zone_records();
		$data['billingperiod'] = $this->my_model->get_month_billingperiod_records();	
		$data['zone_listing'] = $this->my_model->get_zone_listing_records();	
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}

	public function processbalanceforward(){
		$data['msg'] = '';
		
		ini_set('memory_limit', '512M'); // or '512M' if needed
		
		$billperiodforward = explode(' ',$this->input->post('billingperiodforward'));
		$currentbillingperiod = explode(' ',$this->input->post('currentbillingperiod'));
		$zone_listing = $this->input->post('zone_listing');
		$billperiodforward_month = $billperiodforward[0];
		$billperiodforward_year = $billperiodforward[1];

		$currentbillingperiod_month = $currentbillingperiod[0];
		$currentbillingperiod_year = $currentbillingperiod[1];
		
		$result = customerbillingperiod($billperiodforward_month,$billperiodforward_year,$currentbillingperiod_month,$currentbillingperiod_year,$zone_listing);
		
		if($result){
			$data['success'] = true;
			$data['message'] = 'Balance Forward processed successfully!';
		}else{
			$data['success'] = false;
			$data['message'] = 'Balance Forward processing failed!';
		}
		
		// Return JSON response
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function getbalanceforwardresults(){
		$data['msg'] = '';
		
		$billperiodforward = explode(' ',$this->input->post('billingperiodforward'));
		$zone_listing = $this->input->post('zone_listing');
		$billperiodforward_month = $billperiodforward[0];
		$billperiodforward_year = $billperiodforward[1];
		
		$data['record'] = $this->my_model->get_balanceforward_results($billperiodforward_month, $billperiodforward_year, $zone_listing);
		$data['statistics'] = $this->my_model->get_member_statistics($billperiodforward_month, $billperiodforward_year, $zone_listing);
		$this->load->view($this->createbalanceforward_search_ajax,$data);
	}
	
}
?>

