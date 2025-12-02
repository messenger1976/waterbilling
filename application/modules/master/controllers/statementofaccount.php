<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class statementofaccount extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	public $publicHeaderPage = '../../views/admin-includes/public_header'; 
	public $viewPage = 'statementofaccount';     //*****  View page   *****//
	public $listPage_redirect = '/master/statementofaccount';		  //*****  Redirect View  *****//
	
	public function __construct() {
        parent::__construct();
  		$this->load->model('statementofaccount_model','my_model');   //*****    Model Loading     *****//	
		$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 	
		ini_set('date.timezone', 'Asia/Manila');				
		$this->load->model('adminheader_model','top_model');
		$this->load->model('addcustomer_model','customer_model');
    }
	
	public function index($customer_id=''){ 		 //*****  View Loading  *****//
		// Use public header - no authentication required
		
		// Require customer ID - redirect to search if not provided
		if($customer_id == ''){
			redirect('/master/statementofaccount/search');
		}
		
		// Get customer information
		$data['customer_info'] = $this->my_model->get_customer_info($customer_id);
		
		if(empty($data['customer_info'])){
			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Customer not found!</div>');
			redirect('/master/statementofaccount/search');
		}
		
		// Get all ledger entries (readings and payments combined)
		$ledger_entries = $this->my_model->get_customer_ledger($customer_id);
		
		// Calculate running balance
		$ledger_entries = $this->my_model->calculate_running_balance($ledger_entries);
		
		// Separate billing and payment entries
		$data['billing_entries'] = array();
		$data['payment_entries'] = array();
		
		foreach($ledger_entries as $entry) {
			if($entry['type'] == 'billing') {
				$data['billing_entries'][] = $entry;
			} elseif($entry['type'] == 'payment') {
				$data['payment_entries'][] = $entry;
			}
		}
		
		// Get current balance
		$data['current_balance'] = $this->my_model->get_current_balance($customer_id);
		
		// Calculate totals
		$data['total_billing'] = array_sum(array_column($data['billing_entries'], 'debit'));
		$data['total_payment'] = array_sum(array_column($data['payment_entries'], 'credit'));
		
		// Use public header (no authentication)
		$this->load->view($this->publicHeaderPage);
		$this->load->view($this->viewPage,$data);
	}
	
	/** Search Function - Customer ID Entry **/
	public function search(){ 
		$data['msg'] = '';
		
		// Handle form submission (if any)
		if($this->input->post('search') != ''){
			$customer_id = $this->input->post('customer_id');
			if($customer_id != ''){
				redirect('/master/statementofaccount/index/'.$customer_id);
			} else {
				$data['msg'] = '<div class="alert alert-danger">Please enter a Customer ID!</div>';
			}
		}
		
		// Use public header (no authentication)
		$this->load->view($this->publicHeaderPage);
		$this->load->view('statementofaccount_search',$data);
	}
	
	/** API Function - Returns JSON data **/
	public function api($customer_id=''){ 
		// Set JSON header
		header('Content-Type: application/json');
		
		// Check if customer ID is provided
		if($customer_id == ''){
			$customer_id = $this->input->get('customer_id');
		}
		
		if($customer_id == ''){
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Customer ID is required',
				'data' => null
			), JSON_PRETTY_PRINT);
			return;
		}
		
		// Get customer information
		$customer_info = $this->my_model->get_customer_info($customer_id);
		
		if(empty($customer_info)){
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Customer not found',
				'data' => null
			), JSON_PRETTY_PRINT);
			return;
		}
		
		// Get all ledger entries (readings and payments combined)
		$ledger_entries = $this->my_model->get_customer_ledger($customer_id);
		
		// Calculate running balance
		$ledger_entries = $this->my_model->calculate_running_balance($ledger_entries);
		
		// Separate billing and payment entries
		$billing_entries = array();
		$payment_entries = array();
		
		foreach($ledger_entries as $entry) {
			if($entry['type'] == 'billing') {
				$billing_entries[] = $entry;
			} elseif($entry['type'] == 'payment') {
				$payment_entries[] = $entry;
			}
		}
		
		// Get current balance
		$current_balance = $this->my_model->get_current_balance($customer_id);
		
		// Format customer info for JSON
		$customer_data = array(
			'customer_id' => $customer_info['customer_id'],
			'name' => trim($customer_info['last_name'].', '.$customer_info['first_name'].' '.$customer_info['middle_name']),
			'first_name' => $customer_info['first_name'],
			'middle_name' => $customer_info['middle_name'],
			'last_name' => $customer_info['last_name'],
			'address' => $customer_info['address'],
			'meter_number' => isset($customer_info['meter_number']) ? $customer_info['meter_number'] : '',
			'zone' => isset($customer_info['zone']) ? $customer_info['zone'] : '',
			'classification' => isset($customer_info['class_name']) ? $customer_info['class_name'] : '',
			'account_type' => isset($customer_info['cust_type_name']) ? $customer_info['cust_type_name'] : '',
			'email' => isset($customer_info['email_id']) ? $customer_info['email_id'] : '',
			'mobile1' => isset($customer_info['mobile1']) ? $customer_info['mobile1'] : '',
			'mobile2' => isset($customer_info['mobile2']) ? $customer_info['mobile2'] : ''
		);
		
		// Format billing entries for JSON
		$formatted_billings = array();
		foreach($billing_entries as $entry) {
			$formatted_billings[] = array(
				'date' => date('Y-m-d', strtotime($entry['date'])),
				'date_display' => date('d-m-Y', strtotime($entry['date'])),
				'refno' => $entry['refno'],
				'description' => $entry['description'],
				'amount' => floatval($entry['debit']),
				'balance' => floatval($entry['balance']),
				'reading' => isset($entry['reading']) ? $entry['reading'] : '',
				'previous_reading' => isset($entry['previous_reading']) ? $entry['previous_reading'] : '',
				'consumed' => isset($entry['consumed']) ? $entry['consumed'] : '',
				'unit_price' => isset($entry['unit_price']) ? floatval($entry['unit_price']) : 0,
				'penalty' => isset($entry['penalty']) ? floatval($entry['penalty']) : 0,
				'sc_discount' => isset($entry['sc_discount']) ? floatval($entry['sc_discount']) : 0,
				'arrears' => isset($entry['arrears']) ? floatval($entry['arrears']) : 0,
				'maintenance_fee' => isset($entry['maintenance_fee']) ? floatval($entry['maintenance_fee']) : 0,
				'due_date' => isset($entry['due_date']) ? $entry['due_date'] : ''
			);
		}
		
		// Format payment entries for JSON
		$formatted_payments = array();
		foreach($payment_entries as $entry) {
			$formatted_payments[] = array(
				'date' => date('Y-m-d', strtotime($entry['date'])),
				'date_display' => date('d-m-Y', strtotime($entry['date'])),
				'or_number' => $entry['refno'],
				'description' => $entry['description'],
				'amount' => floatval($entry['credit']),
				'balance' => floatval($entry['balance']),
				'payment_count' => isset($entry['payment_count']) ? intval($entry['payment_count']) : 1,
				'billing_periods' => isset($entry['billing_periods']) ? $entry['billing_periods'] : ''
			);
		}
		
		// Calculate totals
		$total_billing = array_sum(array_column($billing_entries, 'debit'));
		$total_payment = array_sum(array_column($payment_entries, 'credit'));
		
		// Prepare JSON response
		$response = array(
			'status' => 'success',
			'message' => 'Statement of account retrieved successfully',
			'data' => array(
				'customer' => $customer_data,
				'current_balance' => floatval($current_balance),
				'total_billing' => floatval($total_billing),
				'total_payment' => floatval($total_payment),
				'billing_entries' => $formatted_billings,
				'payment_entries' => $formatted_payments,
				'billing_count' => count($formatted_billings),
				'payment_count' => count($formatted_payments),
				'generated_at' => date('Y-m-d H:i:s')
			)
		);
		
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
}
?>

