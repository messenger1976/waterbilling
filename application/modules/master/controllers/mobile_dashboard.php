<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class mobile_dashboard extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/mobile_header'; 
	
	public $listPage = 'mobile_dashboard'; 
	public function __construct() {
        parent::__construct();
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
		/*if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
			//echo '<pre>';print_r($this->head['roleResponsible']);exit;
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(	array_key_exists('dashboard',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin' ){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['dashboard']);
		}*/
		
    }
	public function index($mode =''){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['msg'] ='';
		//*****  View Loading  *****//

		//$header['host'] = $this->comm_model->get_single_record();				
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$data['customer_listing'] = $this->customer_model->get_all_records();

		
		
		
		
		//echo '<pre>';print_r($data);exit;
		$this->load->view($this->listPage,$data);
	}

	
}
?>