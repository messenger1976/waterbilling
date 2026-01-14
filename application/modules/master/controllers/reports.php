<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reports extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $listPage = 'adddailyreport_add';
	public $agingARreportPage = 'aging_ar_report';
    public $leakingARreportPage = 'leaking_ar_report';
    public $monthlyBillingReportPage = 'monthly_billing_report';		   //*****  View page   *****//
    public $customerReportPage = 'customer_report';		   //*****  View page   *****//
	public $searchPage ='adddaily_search _ajax';
    public $monthlybillingreport_ajaxPage ='monthly_billing_report_ajax';
    public $customerreport_ajaxPage ='customer_report_ajax';
	public $agingARreport_ajaxPage ='aging_ar_report_ajax';
	public $printtopdfPage ='monthlybillingreport_printtopdf';
	public $customerprinttopdfPage ='customerreport_printtopdf';
	public $agingprinttopdfPage ='agingarreport_printtopdf';
	public $leakingarreport ='leakingarreport_printtopdf';
	public function __construct() {
        parent::__construct();
        $this->load->model('addbillingperiod_model','billingperiod_model');   //*****    Model Loading     *****//	
  		$this->load->model('adddailyreport_model','my_model');   //*****    Model Loading     *****//	
        
        // Load Report_model - handle case sensitivity for Linux/Windows compatibility
        // MX Loader converts model names to lowercase when searching for files
        // On Linux, it looks for 'report_model.php' but file is 'Report_model.php'
        $model_file_lower = APPPATH . 'modules/master/models/report_model.php';
        $model_file_upper = APPPATH . 'modules/master/models/Report_model.php';
        
        if (file_exists($model_file_upper)) {
            // File exists with uppercase R, manually load it to handle case sensitivity
            require_once($model_file_upper);
            if (class_exists('Report_model')) {
                $this->report_model = new Report_model();
            } else {
                log_message('error', 'Report_model class not found after loading file');
                show_error('Unable to load Report_model. Class not found.');
            }
        } elseif (file_exists($model_file_lower)) {
            // File exists with lowercase name, use standard loading
            $this->load->model('report_model','report_model');
        } else {
            // Try standard loading as fallback
            $this->load->model('Report_model','report_model');
        }
        
        $this->load->model('common_model','comm_model');
		$this->load->model('leakingentry_model');
		$this->load->model('addcustomer_model','customer_model');	
		$this->load->model('addmetercustomerreading_model','meterreading_model'); 
        $this->load->model('addbillingperiod_model','billingperiod_model');   //*****    Model Loading     *****//	
        $this->load->helper('common');
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

    public function customer_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->customerReportPage,$data);
	}

    public function aging_ar_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->agingARreportPage,$data);
	}

	public function leaking_ar_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->leakingARreportPage,$data);
	}

	public function printtopdf($billingperiod,$status,$zone='',$preparedby='',$verifiedby='',$approvedby=''){
		//$header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['zone'] = $this->my_model->get_zone($zone);
		$data['zone'] = $this->my_model->get_zone($zone);
		$billing_period = explode(' ',urldecode($billingperiod));
		$data['billingperiod_month'] = $billing_period[0];
        $data['billingperiod_year'] = $billing_period[1];
        $data['billingperiod_month_name'] = getMonthName($billing_period[0])[0]->month_name;
        $data['billingperiod'] = $billingperiod;
        $data['status'] = ($status=='99')?'':$status;
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);


        //$zone = $this->input->post('zone');
            //$billingperiod = $this->input->post('billingperiod');
            
            //$status = $this->input->post('status');
            
        //$data['record'] = $this->reports_model->get_monthly_billing_report_records($zone,$billingperiod,$status);

		//$this->load->view($this->headerPage,$header);
		$this->load->view($this->printtopdfPage,$data);
	}

	public function exporttoexcel($billingperiod,$status,$zone='',$preparedby='',$verifiedby='',$approvedby=''){
		// Increase execution time and memory limit for large exports
		set_time_limit(600); // 10 minutes
		ini_set('memory_limit', '512M');
		
		// Clean any previous output to prevent corruption
		while (ob_get_level()) {
			ob_end_clean();
		}
		
		// Prevent any output before headers
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}
		
		$this->load->helper('excel');
		
		// Decode and parse billing period
		$billingperiod = urldecode($billingperiod);
		$billing_period = explode(' ', $billingperiod);
		$billingperiod_month = $billing_period[0];
		$billingperiod_year = isset($billing_period[1]) ? $billing_period[1] : '';
		$billingperiod_month_name = getMonthName($billingperiod_month)[0]->month_name;
		$status_filter = ($status=='99')?'':$status;
		
		// Get zone data
		$zones = $this->my_model->get_zone($zone);
		
		// Prepare export data array
		$export_data = array();
		
		// Add header rows
		$export_data[] = array('MONTHLY BILLING REPORT');
		$export_data[] = array($billingperiod_month_name . ' ' . $billingperiod_year);
		$export_data[] = array(''); // Empty row
		
		// Add column headers
		$export_data[] = array(
			'SN #',
			'Concessionaires',
			'Cust Acct No.',
			'Meter No.',
			'Bill No.',
			'Consumed',
			'Metered Sales',
			'Penalty Charges',
			'Total Amount',
			'Status'
		);
		
		// Initialize grand totals
		$grand_total_amount = 0;
		$grand_total_penalty = 0;
		$grand_total_reading = 0;
		$grand_total_billamount = 0;
		
		// Initialize category tracking
		$class_category = array();
		$no_of_customer_1 = 0;
		$no_of_customer_2 = 0;
		$no_of_customer_3 = 0;
		$no_of_customer_4 = 0;
		
		$no_of_consumption_1 = 0;
		$no_of_consumption_2 = 0;
		$no_of_consumption_3 = 0;
		$no_of_consumption_4 = 0;
		
		$metered_sales_1 = 0;
		$metered_sales_2 = 0;
		$metered_sales_3 = 0;
		$metered_sales_4 = 0;
		
		$penalty_1 = 0;
		$penalty_2 = 0;
		$penalty_3 = 0;
		$penalty_4 = 0;
		
		$index = 0;
		
		// Process each zone
		if(count($zones) > 0){
			foreach($zones as $key => $row){
				// Add zone header
				$export_data[] = array('', stripslashes($row['zone']), '', '', '', '', '', '', '', '');
				
				// Get daily transactions for this zone
				$get_dailytrans = $this->report_model->get_monthly_billing_report_records($row['id'], $billingperiod, $status_filter);
				
				// Initialize zone totals
				$total_amount_zone = 0;
				$total_penalty_zone = 0;
				$total_reading_zone = 0;
				$total_billamount_zone = 0;
				
				// Process each transaction
				foreach($get_dailytrans as $key => $gdailytrans){
					$gross_total = $gdailytrans['grand_total'] + $gdailytrans['vat_amount'];
					
					$current_date = date('Y-m-d');
					$pdate = stripslashes($gdailytrans['payment_date']);
					$date = stripslashes($gdailytrans['due_date']);
					$penalty = 0;
					
					if($pdate > $date){
						$penalty = $gdailytrans['penalty'] - $gdailytrans['amount'];
					}
					
					if($gdailytrans['invoice_id'] == ''){
						$total_payment = $gdailytrans['amount'];
						$date1 = $gdailytrans['due_date'];
						if($current_date > $date1){
							$penalty = $gdailytrans['penalty'] - $gdailytrans['amount'];
							$total_payment = $gdailytrans['penalty'];
						} else {
							$penalty = 0;
						}
					} else {
						$total_payment = $gdailytrans['payment_amount'];
					}
					
					// Determine status
					if($gdailytrans['invoice_id'] != ''){
						$status_msg = "Paid";
					} elseif($gdailytrans['customer_status'] == '2'){
						$status_msg = "Disconnected";
					} elseif($gdailytrans['customer_status'] == '1' && ($gdailytrans['reading'] == '' || is_null($gdailytrans['reading']))){
						$status_msg = "No Reading";
					} else {
						$status_msg = "Unpaid";
					}
					
					$index++;
					
					// Add transaction row
					$export_data[] = array(
						$index,
						$gdailytrans['last_name'] . ', ' . $gdailytrans['first_name'] . ' ' . $gdailytrans['middle_name'],
						$gdailytrans['customer_id'],
						$gdailytrans['meter_number'],
						sprintf('%07d', $gdailytrans['refno']),
						number_format($gdailytrans['consumed'], 0),
						number_format($gdailytrans['amount'], 2),
						number_format($penalty, 2),
						number_format($total_payment, 2),
						$status_msg
					);
					
					// Accumulate zone totals
					$total_amount_zone += $gdailytrans['amount'];
					$total_penalty_zone += $penalty;
					$total_reading_zone += $gdailytrans['consumed'];
					$total_billamount_zone += $total_payment;
					
					// Category tracking
					$keyToSearch = "class_cat_id";
					$valueToFind = $gdailytrans['class_cat_id'];
					$found = false;
					
					$newArray = array(
						"class_cat_id" => $gdailytrans['class_cat_id'],
						"class_cat_name" => $gdailytrans['class_cat_name'],
					);
					
					if($gdailytrans['class_cat_id'] == '1'){
						$no_of_customer_1++;
						$no_of_consumption_1 += $gdailytrans['consumed'];
						$metered_sales_1 += $gdailytrans['amount'];
						$penalty_1 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_1,
							"no_of_consumption" => $no_of_consumption_1,
							"metered_sales" => $metered_sales_1,
							"penalty" => $penalty_1
						);
					} elseif($gdailytrans['class_cat_id'] == '2'){
						$no_of_customer_2++;
						$no_of_consumption_2 += $gdailytrans['consumed'];
						$metered_sales_2 += $gdailytrans['amount'];
						$penalty_2 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_2,
							"no_of_consumption" => $no_of_consumption_2,
							"metered_sales" => $metered_sales_2,
							"penalty" => $penalty_2
						);
					} elseif($gdailytrans['class_cat_id'] == '3'){
						$no_of_customer_3++;
						$no_of_consumption_3 += $gdailytrans['consumed'];
						$metered_sales_3 += $gdailytrans['amount'];
						$penalty_3 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_3,
							"no_of_consumption" => $no_of_consumption_3,
							"metered_sales" => $metered_sales_3,
							"penalty" => $penalty_3
						);
					} elseif($gdailytrans['class_cat_id'] == '4'){
						$no_of_customer_4++;
						$no_of_consumption_4 += $gdailytrans['consumed'];
						$metered_sales_4 += $gdailytrans['amount'];
						$penalty_4 += $penalty;
						$newArray1 = array(
							"no_of_customer" => $no_of_customer_4,
							"no_of_consumption" => $no_of_consumption_4,
							"metered_sales" => $metered_sales_4,
							"penalty" => $penalty_4
						);
					}
					
					$newArray = array_merge($newArray, $newArray1);
					
					// Update or add category
					foreach ($class_category as &$class_category_key) {
						if (array_key_exists($keyToSearch, $class_category_key) && $class_category_key[$keyToSearch] === $valueToFind) {
							if($gdailytrans['class_cat_id'] == '1'){
								$class_category_key["no_of_customer"] = $no_of_customer_1;
								$class_category_key["no_of_consumption"] = $no_of_consumption_1;
								$class_category_key["metered_sales"] = $metered_sales_1;
								$class_category_key["penalty"] = $penalty_1;
							} elseif($gdailytrans['class_cat_id'] == '2'){
								$class_category_key["no_of_customer"] = $no_of_customer_2;
								$class_category_key["no_of_consumption"] = $no_of_consumption_2;
								$class_category_key["metered_sales"] = $metered_sales_2;
								$class_category_key["penalty"] = $penalty_2;
							} elseif($gdailytrans['class_cat_id'] == '3'){
								$class_category_key["no_of_customer"] = $no_of_customer_3;
								$class_category_key["no_of_consumption"] = $no_of_consumption_3;
								$class_category_key["metered_sales"] = $metered_sales_3;
								$class_category_key["penalty"] = $penalty_3;
							} elseif($gdailytrans['class_cat_id'] == '4'){
								$class_category_key["no_of_customer"] = $no_of_customer_4;
								$class_category_key["no_of_consumption"] = $no_of_consumption_4;
								$class_category_key["metered_sales"] = $metered_sales_4;
								$class_category_key["penalty"] = $penalty_4;
							}
							$found = true;
						}
					}
					
					if (!$found) {
						$class_category[] = $newArray;
					}
				}
				
				// Add zone total row
				$export_data[] = array(
					'',
					'',
					'',
					'',
					'TOTAL',
					number_format($total_reading_zone, 0),
					number_format($total_amount_zone, 2),
					number_format($total_penalty_zone, 2),
					number_format($total_billamount_zone, 2),
					''
				);
				
				// Accumulate grand totals
				$grand_total_amount += $total_amount_zone;
				$grand_total_penalty += $total_penalty_zone;
				$grand_total_reading += $total_reading_zone;
				$grand_total_billamount += $total_billamount_zone;
				
				// Add empty row between zones
				$export_data[] = array('');
			}
		}
		
		// Add Grand Total row
		$export_data[] = array(
			'',
			'',
			'',
			'',
			'GRAND TOTAL',
			number_format($grand_total_reading, 0),
			number_format($grand_total_amount, 2),
			number_format($grand_total_penalty, 2),
			number_format($grand_total_billamount, 2),
			''
		);
		
		// Add empty row
		$export_data[] = array('');
		$export_data[] = array('BREAKDOWN OF METERED SALES');
		$export_data[] = array('');
		
		// Add breakdown headers
		$export_data[] = array(
			'CATEGORY',
			'No. of Consumer',
			'Consumption',
			'Amount',
			'Penalty'
		);
		
		// Add breakdown data
		$grand_no_of_customer = 0;
		$grand_no_of_consumption = 0;
		$grand_metered_sales = 0;
		$grand_penalty_breakdown = 0;
		
		foreach ($class_category as $person) {
			if($person["class_cat_name"] != ''){
				$export_data[] = array(
					$person["class_cat_name"],
					$person["no_of_customer"],
					$person["no_of_consumption"],
					number_format($person["metered_sales"], 2),
					number_format($person["penalty"], 2)
				);
				$grand_no_of_customer += $person["no_of_customer"];
				$grand_no_of_consumption += $person["no_of_consumption"];
				$grand_metered_sales += $person["metered_sales"];
				$grand_penalty_breakdown += $person["penalty"];
			}
		}
		
		// Add breakdown grand total
		$export_data[] = array(
			'GRAND TOTAL',
			$grand_no_of_customer,
			$grand_no_of_consumption,
			number_format($grand_metered_sales, 2),
			number_format($grand_penalty_breakdown, 2)
		);
		
		// Add signature section
		$preparedby_data = $this->my_model->get_employee($preparedby);
		$verifiedby_data = $this->my_model->get_employee($verifiedby);
		$approvedby_data = $this->my_model->get_employee($approvedby);
		
		$export_data[] = array('');
		$export_data[] = array('Prepared by:', '', 'Verified by:');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($preparedby_data[0]) && isset($verifiedby_data[0])){
			$preparedby_name = strtoupper($preparedby_data[0]['first_name'] . ' ' . $preparedby_data[0]['middle_name'] . ' ' . $preparedby_data[0]['last_name']);
			$verifiedby_name = strtoupper($verifiedby_data[0]['first_name'] . ' ' . $verifiedby_data[0]['middle_name'] . ' ' . $verifiedby_data[0]['last_name']);
			$export_data[] = array($preparedby_name, '', $verifiedby_name);
			$export_data[] = array($preparedby_data[0]['jobtitle'], '', $verifiedby_data[0]['jobtitle']);
		}
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('Approved by:');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($approvedby_data[0])){
			$approvedby_name = strtoupper($approvedby_data[0]['first_name'] . ' ' . $approvedby_data[0]['middle_name'] . ' ' . $approvedby_data[0]['last_name']);
			$export_data[] = array($approvedby_name, '', 'Date/Time printed: ' . date('Y-m-d H:i:s'));
			$export_data[] = array($approvedby_data[0]['jobtitle']);
		}
		
		// Generate filename
		$filename = 'Monthly_Billing_Report_' . $billingperiod_month_name . '_' . $billingperiod_year . '.xls';
		$filename = str_replace(' ', '_', $filename);
		
		// Export to Excel (exit is handled in array_to_excel function)
		array_to_excel($export_data, $filename);
	}

	public function customerprinttopdf($status,$zone='',$preparedby='',$verifiedby='',$approvedby=''){
		//$header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['zone'] = $this->my_model->get_zone($zone);
		$data['zone'] = $this->my_model->get_zone($zone);
        $data['status'] = ($status=='99')?'':$status;
		$data['record'] = $this->report_model->get_customer_report_records($zone,$data['status']);
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);

		//$this->load->view($this->headerPage,$header);
		$this->load->view($this->customerprinttopdfPage,$data);
	}

	public function agingprinttopdf($asofdate,$zone,$status,$preparedby='',$verifiedby='',$approvedby=''){
		//$header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['zone'] = $this->my_model->get_zone($zone);
		$data['zone'] = $this->my_model->get_zone($zone);
		//$billing_period = explode(' ',urldecode($billingperiod));
		
        $data['asofdate'] = $asofdate;
        $data['status'] = ($status=='99')?'':$status;
		$data['record'] = array(); // Initialize record array to prevent undefined variable error
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);


        //$zone = $this->input->post('zone');
            //$billingperiod = $this->input->post('billingperiod');
            
            //$status = $this->input->post('status');
            
        //$data['record'] = $this->reports_model->get_monthly_billing_report_records($zone,$billingperiod,$status);

		//$this->load->view($this->headerPage,$header);
		$this->load->view($this->agingprinttopdfPage,$data);
	}

	public function exporttoexcel_aging($asofdate,$zone,$status,$preparedby='',$verifiedby='',$approvedby=''){
		// Increase execution time and memory limit for large exports
		set_time_limit(600); // 10 minutes
		ini_set('memory_limit', '512M');
		
		// Clean any previous output to prevent corruption
		while (ob_get_level()) {
			ob_end_clean();
		}
		
		// Prevent any output before headers
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send CSV file.");
		}
		
		$this->load->helper('csv');
		
		// Convert date format from dd-mm-yyyy to Y-m-d for database query
		$date_parts = explode('-', $asofdate);
		if(count($date_parts) == 3){
			$asofdate_mysql = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
		} else {
			$asofdate_mysql = date('Y-m-d', strtotime($asofdate));
		}
		
		$asofdate_formatted = date('M d, Y', strtotime($asofdate_mysql));
		$status_filter = ($status=='99')?'':$status;
		
		// Get zone data
		$zones = $this->my_model->get_zone($zone);
		
		// Prepare export data array
		$export_data = array();
		
		// Add header rows
		$export_data[] = array('AGING OF ACCOUNT RECEIVABLE REPORT');
		$export_data[] = array('As of ' . $asofdate_formatted);
		
		// Add status description
		$status_text = '';
		if($status_filter == 1){
			$status_text = 'Active Members';
		} elseif($status_filter == 2){
			$status_text = 'Disconnected Members';
		} elseif($status_filter == 0){
			$status_text = 'Inactive Members';
		}
		if($status_text != ''){
			$export_data[] = array($status_text);
		}
		$export_data[] = array(''); // Empty row
		
		// Add column headers
		$export_data[] = array(
			'SN #',
			'Concessionaires',
			'Cust Acct No.',
			'Meter Number',
			'Current',
			'30 Days',
			'60 Days',
			'90 Days',
			'120 Days',
			'150 Days Up',
			'Amount'
		);
		
		// Initialize grand totals
		$grand_total_current = 0;
		$grand_total_30days = 0;
		$grand_total_60days = 0;
		$grand_total_90days = 0;
		$grand_total_120days = 0;
		$grand_total_150daysup = 0;
		$grand_total_amount = 0;
		
		$index = 0;
		
		// Process each zone
		if(count($zones) > 0){
			foreach($zones as $key => $row){
				// Get aging AR records for this zone
				$get_dailytrans = $this->report_model->get_aging_ar_report_records($asofdate_mysql, $row['id'], $status_filter);
				
				// Only add zone header and process if there are records
				if(count($get_dailytrans) > 0){
					// Add zone header
					$export_data[] = array('', stripslashes($row['zone']), '', '', '', '', '', '', '', '', '');
					
					// Initialize zone totals
					$grand_total_current_zone = 0;
					$grand_total_30days_zone = 0;
					$grand_total_60days_zone = 0;
					$grand_total_90days_zone = 0;
					$grand_total_120days_zone = 0;
					$grand_total_150daysup_zone = 0;
					$grand_total_amount_zone = 0;
					
					// Process each transaction
					foreach($get_dailytrans as $key => $gdailytrans){
						$index++;
						
						// Ensure all values are numeric and handle null/empty values
						$current = isset($gdailytrans['current']) ? floatval($gdailytrans['current']) : 0;
						$days30 = isset($gdailytrans['30-days']) ? floatval($gdailytrans['30-days']) : 0;
						$days60 = isset($gdailytrans['60-days']) ? floatval($gdailytrans['60-days']) : 0;
						$days90 = isset($gdailytrans['90-days']) ? floatval($gdailytrans['90-days']) : 0;
						$days120 = isset($gdailytrans['120-days']) ? floatval($gdailytrans['120-days']) : 0;
						$days150 = isset($gdailytrans['150-DaysUp']) ? floatval($gdailytrans['150-DaysUp']) : 0;
						$total_balance = isset($gdailytrans['total_balance']) ? floatval($gdailytrans['total_balance']) : 0;
						
						// Add transaction row
						$export_data[] = array(
							$index,
							trim($gdailytrans['last_name'] . ', ' . $gdailytrans['first_name'] . ' ' . $gdailytrans['middle_name']),
							$gdailytrans['customer_id'],
							$gdailytrans['meter_number'],
							number_format($current, 2),
							number_format($days30, 2),
							number_format($days60, 2),
							number_format($days90, 2),
							number_format($days120, 2),
							number_format($days150, 2),
							number_format($total_balance, 2)
						);
						
						// Accumulate zone totals
						$grand_total_current_zone += $current;
						$grand_total_30days_zone += $days30;
						$grand_total_60days_zone += $days60;
						$grand_total_90days_zone += $days90;
						$grand_total_120days_zone += $days120;
						$grand_total_150daysup_zone += $days150;
						$grand_total_amount_zone += $total_balance;
					}
					
					// Add zone total row
					$export_data[] = array(
						'',
						'',
						'',
						'TOTAL',
						number_format($grand_total_current_zone, 2),
						number_format($grand_total_30days_zone, 2),
						number_format($grand_total_60days_zone, 2),
						number_format($grand_total_90days_zone, 2),
						number_format($grand_total_120days_zone, 2),
						number_format($grand_total_150daysup_zone, 2),
						number_format($grand_total_amount_zone, 2)
					);
					
					// Accumulate grand totals
					$grand_total_current += $grand_total_current_zone;
					$grand_total_30days += $grand_total_30days_zone;
					$grand_total_60days += $grand_total_60days_zone;
					$grand_total_90days += $grand_total_90days_zone;
					$grand_total_120days += $grand_total_120days_zone;
					$grand_total_150daysup += $grand_total_150daysup_zone;
					$grand_total_amount += $grand_total_amount_zone;
					
					// Add empty row between zones
					$export_data[] = array('');
				}
			}
		}
		
		// Add Grand Total row
		$export_data[] = array(
			'',
			'',
			'',
			'GRAND TOTAL',
			number_format($grand_total_current, 2),
			number_format($grand_total_30days, 2),
			number_format($grand_total_60days, 2),
			number_format($grand_total_90days, 2),
			number_format($grand_total_120days, 2),
			number_format($grand_total_150daysup, 2),
			number_format($grand_total_amount, 2)
		);
		
		// Add signature section
		$preparedby_data = $this->my_model->get_employee($preparedby);
		$verifiedby_data = $this->my_model->get_employee($verifiedby);
		$approvedby_data = $this->my_model->get_employee($approvedby);
		
		$export_data[] = array('');
		$export_data[] = array('Prepared by:', '', 'Verified by:');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($preparedby_data[0]) && isset($verifiedby_data[0])){
			$preparedby_name = strtoupper($preparedby_data[0]['first_name'] . ' ' . $preparedby_data[0]['middle_name'] . ' ' . $preparedby_data[0]['last_name']);
			$verifiedby_name = strtoupper($verifiedby_data[0]['first_name'] . ' ' . $verifiedby_data[0]['middle_name'] . ' ' . $verifiedby_data[0]['last_name']);
			$export_data[] = array($preparedby_name, '', $verifiedby_name);
			$export_data[] = array($preparedby_data[0]['jobtitle'], '', $verifiedby_data[0]['jobtitle']);
		}
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('Approved by:');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if(isset($approvedby_data[0])){
			$approvedby_name = strtoupper($approvedby_data[0]['first_name'] . ' ' . $approvedby_data[0]['middle_name'] . ' ' . $approvedby_data[0]['last_name']);
			$export_data[] = array($approvedby_name, '', 'Date/Time printed: ' . date('Y-m-d H:i:s'));
			$export_data[] = array($approvedby_data[0]['jobtitle']);
		}
		
		// Generate filename
		$filename = 'Aging_AR_Report_' . date('d-m-Y', strtotime($asofdate_mysql)) . '.xls';
		
		// Export to Excel (exit is handled in array_to_excel function)
		array_to_excel($export_data, $filename);
	}
	
	public function getmonthlyreportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			
			
            $zone = $this->input->post('zone');
            $billingperiod = $this->input->post('billingperiod');
            
            $status = $this->input->post('status');
            //if($status==3){
			//	$data['record'] = $this->report_model->get_monthly_billing_report_records_status3($zone,$billingperiod,$status);
			//}else{
				$data['record'] = $this->report_model->get_monthly_billing_report_records($zone,$billingperiod,$status);
			//}
            
            
            $this->load->view($this->monthlybillingreport_ajaxPage,$data);
				
	}
	public function getcustomerreportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			
			$zone = $this->input->post('zone');
			$status = $this->input->post('status');
			
			// Convert zone to integer, default to 0 if empty
			$zone = ($zone === '' || $zone === null) ? 0 : (int)$zone;
			
			// Ensure status is empty string if not set, handle '99' as 'All'
			if($status === '99' || $status === '' || $status === null){
				$status = '';
			}
			
			// Get records
			$data['record'] = $this->report_model->get_customer_report_records($zone,$status);
			
			// If no records, set empty array
			if(!isset($data['record']) || !is_array($data['record'])){
				$data['record'] = array();
			}
			
			// Load the view
			$this->load->view($this->customerreport_ajaxPage,$data);
	}	
	public function getagingARreportsearch()
	{		//*****  Add Search records  *****//
			$data['msg'] ='';
			
			
            $zone = $this->input->post('zone');
            $asofdate = $this->input->post('asofdate');
            $status = $this->input->post('status');

            //$status = $this->input->post('status');
            
            $data['record'] = $this->report_model->get_aging_ar_report_records($asofdate,$zone,$status);
            
            $this->load->view($this->agingARreport_ajaxPage,$data);
				
	}
	
}
?>