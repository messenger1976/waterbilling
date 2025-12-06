<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class mobilenotifications extends CI_Controller {
	
	public $headerPage = '../../views/admin-includes/header'; 
	public $table_name = 'tbl_sms_notifications';
	public $listPage = 'mobilenotifications';
	public $settingsPage = 'mobilenotifications_settings';
	public $dashboardPage = 'mobilenotifications_dashboard';
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
  		$this->load->model('mobilenotifications_model','my_model');
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
		if(array_key_exists('mobile_notifications',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin'){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['mobile_notifications']);
		}
		
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['notifications'] = $this->my_model->get_all_records(50, 0);
		$data['stats'] = $this->my_model->get_notification_stats();
		$data['billing_periods'] = $this->my_model->get_billing_periods();
		$data['zones'] = $this->my_model->get_zones();
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->listPage,$data);
	}
	
	/** Settings Page **/
	public function settings(){
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(array_key_exists('mobile_notifications',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin'){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['mobile_notifications']);
		}
		
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['settings'] = $this->my_model->get_sms_settings();
		$data['msg'] = '';
		
		if($this->input->post('save_settings')){
			$settings_data = array(
				'api_code' => $this->input->post('api_code'),
				'api_password' => $this->input->post('api_password'),
				'sender_id' => $this->input->post('sender_id')
			);
			
			$this->my_model->update_sms_settings($settings_data);
			$data['msg'] = 'Settings saved successfully!';
			$data['settings'] = $this->my_model->get_sms_settings();
		}
		
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->settingsPage,$data);
	}
	
	/** Dashboard/Analytics Page **/
	public function dashboard(){
		if($this->session->userdata('usertype') == 'subadmin'){
			$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		}else{
			$this->head['roleResponsible'] = array();
		}
		if(array_key_exists('mobile_notifications',$this->head['roleResponsible']) && $this->session->userdata('usertype') == 'subadmin'){
			$this->top_model->get_responsibilities_conditions($this->head['roleResponsible']['mobile_notifications']);
		}
		
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$date_from = $this->input->get('date_from') ? $this->input->get('date_from') : date('Y-m-01');
		$date_to = $this->input->get('date_to') ? $this->input->get('date_to') : date('Y-m-d');
		
		$data['stats'] = $this->my_model->get_notification_stats($date_from, $date_to);
		$data['notifications'] = $this->my_model->get_notifications_by_date($date_from, $date_to);
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		
		// Get daily stats for chart
		$data['daily_stats'] = $this->get_daily_stats($date_from, $date_to);
		
		$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->dashboardPage,$data);
	}
	
	/** Send Billing Statement Notifications **/
	public function send_billing_statements(){
		header('Content-Type: application/json');
		
		$billing_period_id = $this->input->post('billing_period_id');
		$zone_id = $this->input->post('zone_id');
		
		$customers = $this->my_model->get_customers_for_billing($billing_period_id, $zone_id);
		$sent = 0;
		$failed = 0;
		
		foreach($customers as $customer){
			$mobile = !empty($customer['mobile1']) ? $customer['mobile1'] : $customer['mobile2'];
			if(empty($mobile)) continue;
			
			$total_amount = $customer['amount'] + $customer['penalty'] + $customer['maintenance_fee'];
			$month_name = date('F', mktime(0, 0, 0, $customer['month'], 1));
			
			$message = "Dear " . $customer['customer_name'] . ", your water bill for " . $month_name . " " . $customer['year'] . " is P" . number_format($total_amount, 2) . ". Due date: " . date('M d, Y', strtotime($customer['bp_due_date'])) . ". Thank you!";
			
			$result = $this->send_sms($mobile, $message);
			
			$notification_data = array(
				'customer_id' => $customer['customer_id'],
				'mobile_number' => $mobile,
				'message_type' => 'billing_statement',
				'message' => $message,
				'status' => $result['status'],
				'itexmo_response' => $result['response'],
				'itexmo_code' => $result['code']
			);
			
			if($result['status'] == 'sent'){
				$notification_data['sent_at'] = date('Y-m-d H:i:s');
				$sent++;
			} else {
				$failed++;
			}
			
			$this->my_model->save_notification($notification_data);
		}
		
		echo json_encode(array(
			'success' => true,
			'sent' => $sent,
			'failed' => $failed,
			'total' => count($customers)
		));
	}
	
	/** Send Due Account Notifications **/
	public function send_due_accounts(){
		header('Content-Type: application/json');
		
		$days_before = $this->input->post('days_before') ? $this->input->post('days_before') : 3;
		
		$customers = $this->my_model->get_due_accounts($days_before);
		$sent = 0;
		$failed = 0;
		
		foreach($customers as $customer){
			$mobile = !empty($customer['mobile1']) ? $customer['mobile1'] : $customer['mobile2'];
			if(empty($mobile)) continue;
			
			$total_amount = $customer['amount'] + $customer['penalty'] + $customer['maintenance_fee'];
			$days = $customer['days_until_due'];
			
			$message = "Dear " . $customer['customer_name'] . ", your water bill of P" . number_format($total_amount, 2) . " is due in " . $days . " day(s). Due date: " . date('M d, Y', strtotime($customer['bp_due_date'])) . ". Please pay on time. Thank you!";
			
			$result = $this->send_sms($mobile, $message);
			
			$notification_data = array(
				'customer_id' => $customer['customer_id'],
				'mobile_number' => $mobile,
				'message_type' => 'due_account',
				'message' => $message,
				'status' => $result['status'],
				'itexmo_response' => $result['response'],
				'itexmo_code' => $result['code']
			);
			
			if($result['status'] == 'sent'){
				$notification_data['sent_at'] = date('Y-m-d H:i:s');
				$sent++;
			} else {
				$failed++;
			}
			
			$this->my_model->save_notification($notification_data);
		}
		
		echo json_encode(array(
			'success' => true,
			'sent' => $sent,
			'failed' => $failed,
			'total' => count($customers)
		));
	}
	
	/** Send Disconnection Notifications **/
	public function send_disconnection_notices(){
		header('Content-Type: application/json');
		
		$days_overdue = $this->input->post('days_overdue') ? $this->input->post('days_overdue') : 30;
		
		$customers = $this->my_model->get_disconnection_accounts($days_overdue);
		$sent = 0;
		$failed = 0;
		
		foreach($customers as $customer){
			$mobile = !empty($customer['mobile1']) ? $customer['mobile1'] : $customer['mobile2'];
			if(empty($mobile)) continue;
			
			$total_amount = $customer['amount'] + $customer['penalty'] + $customer['maintenance_fee'];
			$days = $customer['days_overdue'];
			
			$message = "URGENT: Dear " . $customer['customer_name'] . ", your account is overdue by " . $days . " days. Amount due: P" . number_format($total_amount, 2) . ". Your service will be disconnected if payment is not received. Please pay immediately. Thank you!";
			
			$result = $this->send_sms($mobile, $message);
			
			$notification_data = array(
				'customer_id' => $customer['customer_id'],
				'mobile_number' => $mobile,
				'message_type' => 'disconnection',
				'message' => $message,
				'status' => $result['status'],
				'itexmo_response' => $result['response'],
				'itexmo_code' => $result['code']
			);
			
			if($result['status'] == 'sent'){
				$notification_data['sent_at'] = date('Y-m-d H:i:s');
				$sent++;
			} else {
				$failed++;
			}
			
			$this->my_model->save_notification($notification_data);
		}
		
		echo json_encode(array(
			'success' => true,
			'sent' => $sent,
			'failed' => $failed,
			'total' => count($customers)
		));
	}
	
	/** Send Custom Message **/
	public function send_custom_message(){
		header('Content-Type: application/json');
		
		$mobile = $this->input->post('mobile');
		$message = $this->input->post('message');
		$customer_id = $this->input->post('customer_id');
		
		if(empty($mobile) || empty($message)){
			echo json_encode(array('success' => false, 'message' => 'Mobile number and message are required'));
			return;
		}
		
		$result = $this->send_sms($mobile, $message);
		
		$notification_data = array(
			'customer_id' => $customer_id,
			'mobile_number' => $mobile,
			'message_type' => 'custom',
			'message' => $message,
			'status' => $result['status'],
			'itexmo_response' => $result['response'],
			'itexmo_code' => $result['code']
		);
		
		if($result['status'] == 'sent'){
			$notification_data['sent_at'] = date('Y-m-d H:i:s');
		}
		
		$notification_id = $this->my_model->save_notification($notification_data);
		
		echo json_encode(array(
			'success' => $result['status'] == 'sent',
			'notification_id' => $notification_id,
			'status' => $result['status'],
			'message' => $result['status'] == 'sent' ? 'Message sent successfully' : 'Failed to send message: ' . $result['response']
		));
	}
	
	/** ITEXMO SMS Sending Function **/
	private function send_sms($mobile, $message){
		$settings = $this->my_model->get_sms_settings();
		
		if(!$settings || empty($settings['api_code']) || empty($settings['api_password'])){
			return array(
				'status' => 'failed',
				'response' => 'ITEXMO API settings not configured',
				'code' => 'NO_CONFIG'
			);
		}
		
		// Clean mobile number (remove spaces, dashes, etc.)
		$mobile = preg_replace('/[^0-9]/', '', $mobile);
		
		// ITEXMO API endpoint
		$url = 'https://www.itexmo.com/php_api/api.php';
		
		$data = array(
			'1' => $mobile,
			'2' => $message,
			'3' => $settings['api_code'],
			'passwd' => $settings['api_password']
		);
		
		if(!empty($settings['sender_id'])){
			$data['6'] = $settings['sender_id'];
		}
		
		// Send via cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		// ITEXMO Response Codes
		// 0 = Success
		// Other codes = Error
		if($response == '0'){
			return array(
				'status' => 'sent',
				'response' => 'Message sent successfully',
				'code' => '0'
			);
		} else {
			$error_messages = array(
				'1' => 'Invalid Number',
				'2' => 'Number prefix not supported',
				'3' => 'Invalid API Code',
				'4' => 'Maximum Message per day reached',
				'5' => 'Maximum allowed characters for message reached',
				'6' => 'System Offline',
				'7' => 'Expired API Code',
				'8' => 'iTexMo Error',
				'9' => 'Invalid function parameters',
				'10' => 'Recipient\'s number is blocked',
				'11' => 'Recipient\'s number is invalid',
				'12' => 'Invalid sender ID',
				'13' => 'Sender ID not allowed'
			);
			
			$error_msg = isset($error_messages[$response]) ? $error_messages[$response] : 'Unknown error';
			
			return array(
				'status' => 'failed',
				'response' => $error_msg,
				'code' => $response
			);
		}
	}
	
	/** Get daily stats for chart **/
	private function get_daily_stats($date_from, $date_to){
		$stats = array();
		$current = strtotime($date_from);
		$end = strtotime($date_to);
		
		while($current <= $end){
			$date = date('Y-m-d', $current);
			$day_stats = $this->my_model->get_notification_stats($date, $date);
			$stats[] = array(
				'date' => $date,
				'sent' => $day_stats['total_success'],
				'failed' => $day_stats['total_failed']
			);
			$current = strtotime('+1 day', $current);
		}
		
		return $stats;
	}
	
	/** Get notification details **/
	public function get_notification_details(){
		header('Content-Type: application/json');
		$id = $this->input->post('id');
		
		$notification = $this->my_model->get_record_by_id($id);
		
		if($notification){
			echo json_encode(array('success' => true, 'data' => $notification));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Notification not found'));
		}
	}
	
	/** Get customers list for custom message **/
	public function get_customers_list(){
		header('Content-Type: application/json');
		
		$this->load->model('addcustomer_model', 'customer_model');
		$customers = $this->customer_model->get_all_records();
		
		$customer_list = array();
		foreach($customers as $customer){
			$mobile = !empty($customer['mobile1']) ? $customer['mobile1'] : $customer['mobile2'];
			if(!empty($mobile)){
				$customer_list[] = array(
					'customer_id' => $customer['customer_id'],
					'name' => $customer['first_name'] . ' ' . $customer['last_name'],
					'mobile' => $mobile
				);
			}
		}
		
		echo json_encode(array('success' => true, 'customers' => $customer_list));
	}
	
}
?>

