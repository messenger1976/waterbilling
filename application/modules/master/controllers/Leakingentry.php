<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class leakingentry extends CI_Controller{
    public $headerPage = '../../views/admin-includes/header';  //Header template

    public $listPage = 'leakingentry';
    public function __construct(){
        parent::__construct();
        $this->load->model('leakingentry_model','my_model');   //*****    Model Loading     *****//		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 	
        $this->load->model('common_model','comm_model');			
		$this->load->model('adminheader_model','top_model');
		$this->load->model('addcustomer_model','customer_model');
		$this->load->model('addmetercustomerreading_model','meterreading_model');
    }

    public function index(){
        //if($this->session->userdata('usertype') == 'subadmin'){
			//$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
			
		//}else{
		//	$this->head['roleResponsible'] = array();
		//}
		/*if(	array_key_exists('leakingentry',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin' ){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['leakingentry']);
		}*/
		//*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
        $header['record_info'] = $this->top_model->get_last_login_details(1);
        $data['record'] = $this->my_model->get_all_records();
		$data['customer_listing'] = $this->customer_model->get_all_records();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
        
    }

	public function get_customer_meter_reading(){
		$customer_id = $this->input->post('customer_id');
		if($customer_id != ''){
			$result = $this->meterreading_model->get_addcustomer_meterreading_records($customer_id);
			echo json_encode($result);
			
		}else{
			echo '{}';
		}

		
	}

	public function get_customer_meter_reading_detail(){
		$meterreading_id = $this->input->post('meterreading_id');
		if($meterreading_id != ''){
			$result = $this->meterreading_model->get_single_record($meterreading_id);
			echo json_encode($result);
			
		}else{
			echo '{}';
		}
	}
}