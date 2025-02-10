<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reports extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	
	public $listPage = 'adddailyreport_add';
    public $monthlyBillingReportPage = 'monthly_billing_report';		   //*****  View page   *****//
	public $searchPage ='adddaily_search _ajax';
    public $monthlybillingreport_ajaxPage ='monthly_billing_report_ajax';
	public $printtopdfPage ='adddailyreport_printtopdf';

	public function __construct() {
        parent::__construct();
        $this->load->model('addbillingperiod_model','billingperiod_model');   //*****    Model Loading     *****//	
  		$this->load->model('adddailyreport_model','my_model');   //*****    Model Loading     *****//	
        $this->load->model('reports_model');   //*****    Model Loading     *****//	
        $this->load->model('common_model','comm_model');
		$this->load->model('addcustomer_model','customer_model');	
		$this->load->model('addmetercustomerreading_model','meterreading_model'); 
        $this->load->model('addbillingperiod_model','billingperiod_model');   //*****    Model Loading     *****//	
		$this->load->library('form_validation');
		$this->load->library('Pdf');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }
	public function index(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}

    public function monthly_billing_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
        $data['billingperiod'] = $this->billingperiod_model->get_month_billingperiod_records();	
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->monthlyBillingReportPage,$data);
	}

    public function aging_ar_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}

	public function printtopdf($trans_date,$zone='',$preparedby='',$verifiedby='',$approvedby=''){
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->my_model->get_zone($zone);
		$data['trans_date'] = date('M d, Y', strtotime($trans_date));
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);
		//$this->load->view($this->headerPage,$header);
		$this->load->view($this->printtopdfPage,$data);
	}
	
	public function getmonthlyreportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			
			
            $zone = $this->input->post('zone');
            $billingperiod = $this->input->post('billingperiod');
            
            $status = $this->input->post('status');
            
            $data['record'] = $this->reports_model->get_monthly_billing_report_records($zone,$billingperiod,$status);
            
            $this->load->view($this->monthlybillingreport_ajaxPage,$data);
				
	}	
	
}
?>