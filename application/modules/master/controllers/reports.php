<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reports extends CI_Controller {
	// Declare globle variable here
	
	public $headerPage = '../../views/admin-includes/header'; 
	
	public $listPage = 'adddailyreport_add';
	public $agingARreportPage = 'aging_ar_report';
    public $leakingARreportPage = 'leaking_ar_report';
    public $monthlyBillingReportPage = 'monthly_billing_report';		   //*****  View page   *****//
	public $customerReportPage = 'customer_report';		   //*****  View page   *****//
    public $monthlyIncomeReportAnalyticPage = 'monthly_income_report_analytic';  //*****  View page   *****//
    public $monthlyIncomeReportPrintPage = 'monthly_income_report_printtopdf';
	public $searchPage ='adddaily_search _ajax';
    public $monthlybillingreport_ajaxPage ='monthly_billing_report_ajax';
    public $customerreport_ajaxPage ='customer_report_ajax';
	public $agingARreport_ajaxPage ='aging_ar_report_ajax';
	public $customerPaymentMonitoringPage = 'customer_payment_monitoring_report';
	public $customerpaymentmonitoring_ajaxPage = 'customer_payment_monitoring_report_ajax';
	public $printtopdfPage ='monthlybillingreport_printtopdf';
	public $customerprinttopdfPage ='customerreport_printtopdf';
	public $agingprinttopdfPage ='agingarreport_printtopdf';
	public $leakingarreport ='leakingarreport_printtopdf';
	public $arrearsMonitoringPage = 'arrears_monitoring_report';
	public $arrearsmonitoring_ajaxPage = 'arrears_monitoring_report_ajax';
	public $arrearsmonitoringprinttopdfPage = 'arrearsmonitoring_printtopdf';
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
		$this->load->model('statementofaccount_model', 'soa_model');
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

	public function arrears_monitoring_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->arrearsMonitoringPage,$data);
	}

	public function customer_payment_monitoring_report() {
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$this->load->view($this->headerPage, $header);
		$this->load->view($this->customerPaymentMonitoringPage, $data);
	}

	public function leaking_ar_report(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['zone'] = $this->customer_model->get_zone();
		$data['employee'] = $this->my_model->get_employee();
		//$header['record_info'] = $this->top_model->get_last_login_details(1);
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->leakingARreportPage,$data);
	}

	public function monthly_income_report_analytic(){ 		 //*****  View Loading  *****//
		$header['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['employee'] = $this->my_model->get_employee();
		$this->load->view($this->headerPage,$header);
		$this->load->view($this->monthlyIncomeReportAnalyticPage,$data);
	}

	public function getmonthlyincomereportanalytic(){
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		if (empty($month) || empty($year)) {
			$days_in_month = (int) date('t', mktime(0, 0, 0, (int)$month, 1, (int)$year));
			if ($days_in_month < 28) $days_in_month = 31;
			$this->_json_response(array('daily' => array(), 'total' => 0, 'days_in_month' => $days_in_month));
			return;
		}
		$result = $this->report_model->get_monthly_income_daily($month, $year);
		$this->_json_response($result);
	}

	private function _json_response($data) {
		while (ob_get_level()) { @ob_end_clean(); }
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data, JSON_NUMERIC_CHECK);
	}

	/** Print/PDF: Monthly Income Report Analytic - opens in new window for printing */
	public function monthly_income_report_printtopdf($month, $year) {
		$month = (int) $month;
		$year = (int) $year;
		if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
			show_error('Invalid month or year.');
			return;
		}
		$data = $this->report_model->get_monthly_income_daily($month, $year);
		$mn = getMonthName($month);
		$data['month_name'] = isset($mn[0]) ? $mn[0]->month_name : date('F', mktime(0,0,0,$month,1));
		$data['month'] = $month;
		$data['year'] = $year;
		$this->load->view($this->monthlyIncomeReportPrintPage, $data);
	}

	/** Export to Excel: Monthly Income Report Analytic */
	public function monthly_income_exporttoexcel($month, $year) {
		$month = (int) $month;
		$year = (int) $year;
		if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
			show_error('Invalid month or year.');
			return;
		}
		@ini_set('display_errors', 0);
		error_reporting(0);
		while (ob_get_level()) { @ob_end_clean(); }
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line.");
		}
		$this->load->helper('excel');
		$result = $this->report_model->get_monthly_income_daily($month, $year);
		$daily = $result['daily'];
		$total = (float) $result['total'];
		$days_in_month = (int) $result['days_in_month'];
		$mn = getMonthName($month);
		$month_name = isset($mn[0]) ? $mn[0]->month_name : date('F', mktime(0,0,0,$month,1));
		$export_data = array();
		$export_data[] = array('MONTHLY INCOME REPORT ANALYTIC');
		$export_data[] = array($month_name . ' ' . $year);
		$export_data[] = array('');
		$export_data[] = array('Day', 'Income');
		for ($d = 1; $d <= $days_in_month; $d++) {
			$amt = isset($daily[$d]) ? (float) $daily[$d] : 0;
			$export_data[] = array($d, number_format($amt, 2));
		}
		$export_data[] = array('');
		$export_data[] = array('TOTAL', number_format($total, 2));
		$filename = 'Monthly_Income_Report_' . $month_name . '_' . $year . '_' . date('d-m-Y') . '.xls';
		array_to_excel($export_data, $filename);
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

	public function customerprinttopdf($status,$zone='',$preparedby='',$verifiedby='',$approvedby='',$special_privilege=0){
		//$header['roleResponsible'] = $this->top_model->get_responsibilities();
		//$data['zone'] = $this->my_model->get_zone($zone);
		$data['zone'] = $this->my_model->get_zone($zone);
        $data['status'] = ($status=='99')?'':$status;
		$data['record'] = $this->report_model->get_customer_report_records($zone,$data['status'],$special_privilege);
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);

		//$this->load->view($this->headerPage,$header);
		$this->load->view($this->customerprinttopdfPage,$data);
	}

	public function exporttoexcel_customer($status,$zone='',$preparedby='',$verifiedby='',$approvedby='',$special_privilege=0){
		// Suppress error display to prevent output before headers
		@ini_set('display_errors', 0);
		error_reporting(0);
		
		// Increase execution time and memory limit for large exports
		set_time_limit(600); // 10 minutes
		ini_set('memory_limit', '512M');
		
		// Clean any previous output to prevent corruption
		while (ob_get_level()) {
			@ob_end_clean();
		}
		
		// Prevent any output before headers
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}
		
		$this->load->helper('excel');
		
		// Get zone data
		$zones = $this->my_model->get_zone($zone);
		$status_filter = ($status=='99')?'':$status;
		
		// Prepare export data array
		$export_data = array();
		
		// Add header rows
		$export_data[] = array('CUSTOMER REPORT');
		$export_data[] = array(''); // Empty row
		
		// Add column headers
		$export_data[] = array(
			'SN #',
			'Customer ID',
			'First Name',
			'Last Name',
			'Address',
			'Zone',
			'Classification',
			'Status'
		);
		
		$index = 0;
		
		// Get customer records
		$records = $this->report_model->get_customer_report_records($zone, $status_filter, $special_privilege);
		
		// Process each record
		if(count($records) > 0){
			foreach($records as $key => $customer){
				$index++;
				
				// Determine status message
				$status_msg = '';
				if($customer['status'] == '1'){ 
					$status_msg = "Active"; 
				} else if($customer['status'] == '0'){ 
					$status_msg = "Inactive"; 
				} else if($customer['status'] == '2'){ 
					$status_msg = "Disconnected"; 
				}
				
				// Add customer row
				$export_data[] = array(
					$index,
					stripslashes($customer['customer_id']),
					stripslashes($customer['first_name']),
					stripslashes($customer['last_name']),
					stripslashes($customer['address']),
					stripslashes($customer['zone_name']),
					stripslashes($customer['classification_name']),
					$status_msg
				);
			}
		} else {
			$export_data[] = array('No records found', '', '', '', '', '', '', '');
		}
		
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
		$filename = 'Customer_Report_' . date('Y-m-d') . '.xls';
		
		// Export to Excel (exit is handled in array_to_excel function)
		array_to_excel($export_data, $filename);
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
		// Suppress error display to prevent output before headers
		@ini_set('display_errors', 0);
		error_reporting(0);
		
		// Increase execution time and memory limit for large exports
		set_time_limit(600); // 10 minutes
		ini_set('memory_limit', '512M');
		
		// Clean any previous output to prevent corruption
		while (ob_get_level()) {
			@ob_end_clean();
		}
		
		// Prevent any output before headers
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}
		
		$this->load->helper('excel');
		
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
			$special_privilege = $this->input->post('special_privilege');
			
			// Convert zone to integer, default to 0 if empty
			$zone = ($zone === '' || $zone === null) ? 0 : (int)$zone;
			
			// Ensure status is empty string if not set, handle '99' as 'All'
			if($status === '99' || $status === '' || $status === null){
				$status = '';
			}
			
			// Convert special_privilege to integer, default to 0 if empty (no filter)
			$special_privilege = ($special_privilege === '' || $special_privilege === null) ? 0 : (int)$special_privilege;
			
			// Get records
			$data['record'] = $this->report_model->get_customer_report_records($zone,$status,$special_privilege);
			
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

	public function getarrearsmonitoringsearch()
	{
		$data['msg'] = '';
		$zone = $this->input->post('zone');
		$asofdate = $this->input->post('asofdate');
		$status = $this->input->post('status');
		$data['record'] = $this->report_model->get_arrears_monitoring_records($asofdate, $zone, $status);
		$this->load->view($this->arrearsmonitoring_ajaxPage, $data);
	}

	public function updatearrearsmonitoring()
	{
		while (ob_get_level()) { @ob_end_clean(); }
		header('Content-Type: application/json; charset=utf-8');
		$customer_id = $this->input->post('customer_id');
		$aging_amount = $this->input->post('aging_amount');
		$result = $this->report_model->update_current_period_arrears($customer_id, $aging_amount);
		echo json_encode($result, JSON_NUMERIC_CHECK);
	}

	/**
	 * AJAX: Customer Payment Monitoring search (neutral URL — paths containing "payment" are often blocked by extensions, yielding HTTP 0).
	 */
	public function getcpmonsearch() {
		$this->_customer_payment_monitoring_search_ajax();
	}

	/** Legacy alias; prefer getcpmonsearch for new clients. */
	public function getcustomerpaymentmonitoringsearch() {
		$this->_customer_payment_monitoring_search_ajax();
	}

	private function _customer_payment_monitoring_search_ajax() {
		try {
			$zone = $this->input->get_post('zone');
			$status = $this->input->get_post('status');
			$offset = $this->input->get_post('offset');
			$zone = ($zone === '' || $zone === null) ? 0 : (int) $zone;
			if ($status === '99' || $status === '' || $status === null) {
				$status = '';
			}
			$offset = ($offset === '' || $offset === null) ? 0 : (int) $offset;
			if ($offset < 0) {
				$offset = 0;
			}
			$limit = 100;
			$total = $this->report_model->count_customer_payment_monitoring_records($zone, $status);
			$rows = $this->report_model->get_customer_payment_monitoring_records($zone, $status, $limit, $offset);
			$arrears_cache = array();
			foreach ($rows as &$row) {
				$cid = isset($row['customer_id']) ? $row['customer_id'] : '';
				if ($cid === '') {
					$row['arrears'] = 0;
					continue;
				}
				if (!array_key_exists($cid, $arrears_cache)) {
					$arrears_cache[$cid] = (float) $this->soa_model->get_current_balance_excluding_active_billing_period($cid);
				}
				$row['arrears'] = $arrears_cache[$cid];
			}
			unset($row);
			$data['record'] = $rows;
			$data['total_count'] = $total;
			$data['offset'] = $offset;
			$data['limit'] = $limit;
			$data['has_more'] = ($offset + count($rows)) < $total;
			$this->load->view($this->customerpaymentmonitoring_ajaxPage, $data);
		} catch (Throwable $e) {
			log_message('error', 'customer_payment_monitoring_search_ajax: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
			$this->output->set_status_header(500);
			echo '<div class="alert alert-danger"><strong>Report could not load.</strong><br>'
				. htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
				. '<br><small>Details are in the application log.</small></div>';
		}
	}

	/**
	 * Export CPM report (neutral URL; delegates to export_customer_payment_monitoring_excel).
	 */
	public function export_cpmon_excel($zone = 0, $status = '99') {
		$this->export_customer_payment_monitoring_excel($zone, $status);
	}

	/**
	 * Export full Customer Payment Monitoring report (same filters as on-screen; batched DB reads of 100 rows).
	 * URL: reports/export_customer_payment_monitoring_excel/{zone}/{status} — use status 99 for All.
	 */
	public function export_customer_payment_monitoring_excel($zone = 0, $status = '99') {
		@ini_set('display_errors', 0);
		error_reporting(0);
		set_time_limit(600);
		ini_set('memory_limit', '512M');
		while (ob_get_level()) {
			@ob_end_clean();
		}
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}
		$this->load->helper('excel');
		$zone = (int) $zone;
		$status_filter = ($status === '99' || $status === '' || $status === null) ? '' : (string) $status;
		$zones = $this->my_model->get_zone($zone);
		$zone_name = 'All Zones';
		if ($zone > 0 && is_array($zones) && count($zones) > 0 && isset($zones[0]['zone'])) {
			$zone_name = $zones[0]['zone'];
		}
		$status_display = 'All statuses';
		if ($status_filter === '1') {
			$status_display = 'Active';
		} elseif ($status_filter === '0') {
			$status_display = 'Inactive';
		} elseif ($status_filter === '2') {
			$status_display = 'Disconnected';
		}
		$export_data = array();
		$export_data[] = array('CUSTOMER PAYMENT MONITORING REPORT');
		$export_data[] = array('Payments included: posted date in the calendar month immediately before the zone active billing period (when set).');
		$export_data[] = array('Zone: ' . $zone_name);
		$export_data[] = array('Customer status: ' . $status_display);
		$export_data[] = array('Exported: ' . date('Y-m-d H:i:s'));
		$export_data[] = array('');
		$export_data[] = array(
			'SN #',
			'Customer ID',
			'Customer Name',
			'Address',
			'Zone',
			'OR number',
			'# billing periods',
			'Billing Period Paid',
			'Total Amount Paid',
			'Arrears'
		);
		$arrears_cache = array();
		$index = 0;
		$offset = 0;
		$page_size = 100;
		while (true) {
			$batch = $this->report_model->get_customer_payment_monitoring_records($zone, $status_filter, $page_size, $offset);
			if (!is_array($batch) || count($batch) === 0) {
				break;
			}
			foreach ($batch as $row) {
				$index++;
				$cid = isset($row['customer_id']) ? $row['customer_id'] : '';
				if ($cid !== '' && !array_key_exists($cid, $arrears_cache)) {
					$arrears_cache[$cid] = (float) $this->soa_model->get_current_balance_excluding_active_billing_period($cid);
				}
				$ar = ($cid !== '' && array_key_exists($cid, $arrears_cache)) ? $arrears_cache[$cid] : 0;
				$name = trim(
					(isset($row['last_name']) ? stripslashes($row['last_name']) : '') . ', ' .
					(isset($row['first_name']) ? stripslashes($row['first_name']) : '') . ' ' .
					(isset($row['middle_name']) ? stripslashes($row['middle_name']) : '')
				);
				$export_data[] = array(
					$index,
					isset($row['customer_id']) ? stripslashes($row['customer_id']) : '',
					$name,
					isset($row['address']) ? stripslashes($row['address']) : '',
					isset($row['zone_name']) ? stripslashes($row['zone_name']) : '',
					isset($row['or_number']) ? stripslashes((string) $row['or_number']) : '',
					isset($row['period_count']) ? (int) $row['period_count'] : 0,
					isset($row['billing_periods_paid']) ? stripslashes($row['billing_periods_paid']) : '',
					isset($row['total_paid']) ? number_format((float) $row['total_paid'], 2, '.', '') : '0.00',
					number_format($ar, 2, '.', '')
				);
			}
			$offset += $page_size;
			if (count($batch) < $page_size) {
				break;
			}
		}
		if ($index === 0) {
			$export_data[] = array('No records found for the selected filters.');
		}
		$filename = 'Customer_Payment_Monitoring_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $zone_name) . '_' . date('Y-m-d') . '.xls';
		array_to_excel($export_data, $filename);
	}

	public function arrearsmonitoringprinttopdf($asofdate, $zone, $status, $preparedby = '', $verifiedby = '', $approvedby = ''){
		$data['zone'] = $this->my_model->get_zone($zone);
		$data['asofdate'] = $asofdate;
		$data['status'] = ($status == '99') ? '' : $status;
		$data['record'] = array();
		$data['preparedby'] = $this->my_model->get_employee($preparedby);
		$data['verifiedby'] = $this->my_model->get_employee($verifiedby);
		$data['approvedby'] = $this->my_model->get_employee($approvedby);
		$this->load->view($this->arrearsmonitoringprinttopdfPage, $data);
	}

	public function arrearsmonitoringexporttoexcel($asofdate = '', $zone = '', $status = '', $preparedby = '', $verifiedby = '', $approvedby = ''){
		@ini_set('display_errors', 0);
		error_reporting(0);
		set_time_limit(600);
		ini_set('memory_limit', '512M');
		while (ob_get_level()) { @ob_end_clean(); }
		if (headers_sent($file, $line)) {
			die("Headers already sent in $file on line $line. Cannot send Excel file.");
		}
		$this->load->helper('excel');

		$status = ($status == '99') ? '' : $status;
		$zones = $this->my_model->get_zone($zone);
		if (!is_array($zones)) {
			$zones = array();
		}

		$status_display = '';
		if ($status === '99' || $status === '') {
			$status_display = 'All Members';
		} elseif ($status === '1') {
			$status_display = 'Active Members';
		} elseif ($status === '2') {
			$status_display = 'Disconnected Members';
		} elseif ($status === '0') {
			$status_display = 'Inactive Members';
		}

		$export_data = array();
		$export_data[] = array('ARREARS MONITORING REPORT');
		$export_data[] = array('As of ' . $asofdate);
		$export_data[] = array($status_display);
		$export_data[] = array('');
		$export_data[] = array('SN #', 'Customer ID', 'Customer Name', 'Meter Number', 'Zone', 'Aging Amount', 'Current Billing Period Arrears');

		$grand_total_aging = 0;
		$grand_total_arrears = 0;
		$index = 0;
		if (count($zones) > 0) {
			foreach ($zones as $zone_row) {
				$export_data[] = array('', stripslashes($zone_row['zone']), '', '', '', '', '');
				$rows = $this->report_model->get_arrears_monitoring_records($asofdate, $zone_row['id'], $status);
				if (!is_array($rows)) {
					$rows = array();
				}
				$zone_total_aging = 0;
				$zone_total_arrears = 0;
				foreach ($rows as $row) {
					$index++;
					$aging_amount = isset($row['total_balance']) ? (float)$row['total_balance'] : 0;
					$current_arr = isset($row['current_arrears']) ? (float)$row['current_arrears'] : 0;
					$export_data[] = array(
						$index,
						stripslashes($row['customer_id']),
						stripslashes(trim($row['last_name']) . ', ' . trim($row['first_name']) . ' ' . trim($row['middle_name'])),
						stripslashes($row['meter_number']),
						stripslashes($row['zone']),
						number_format($aging_amount, 2),
						number_format($current_arr, 2)
					);
					$zone_total_aging += $aging_amount;
					$zone_total_arrears += $current_arr;
					$grand_total_aging += $aging_amount;
					$grand_total_arrears += $current_arr;
				}
				$export_data[] = array('', '', '', 'TOTAL', '', number_format($zone_total_aging, 2), number_format($zone_total_arrears, 2));
			}
		}
		$export_data[] = array('', '', '', 'GRAND TOTAL', '', number_format($grand_total_aging, 2), number_format($grand_total_arrears, 2));

		$preparedby_data = $this->my_model->get_employee($preparedby);
		$verifiedby_data = $this->my_model->get_employee($verifiedby);
		$approvedby_data = $this->my_model->get_employee($approvedby);
		$export_data[] = array('');
		$export_data[] = array('Prepared by:', '', 'Verified by:', '', '', '', '');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if (isset($preparedby_data[0]) && isset($verifiedby_data[0])) {
			$preparedby_name = $preparedby_data[0]['first_name'] . ' ' . $preparedby_data[0]['middle_name'] . ' ' . $preparedby_data[0]['last_name'];
			$verifiedby_name = $verifiedby_data[0]['first_name'] . ' ' . $verifiedby_data[0]['middle_name'] . ' ' . $verifiedby_data[0]['last_name'];
			$export_data[] = array(strtoupper($preparedby_name), '', strtoupper($verifiedby_name), '', '', '', '');
			$export_data[] = array($preparedby_data[0]['jobtitle'], '', $verifiedby_data[0]['jobtitle'], '', '', '', '');
		}
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('Approved by:', '', '', '', '', '', '');
		$export_data[] = array('');
		$export_data[] = array('');
		$export_data[] = array('');
		if (isset($approvedby_data[0])) {
			$approvedby_name = $approvedby_data[0]['first_name'] . ' ' . $approvedby_data[0]['middle_name'] . ' ' . $approvedby_data[0]['last_name'];
			$export_data[] = array(strtoupper($approvedby_name), '', 'Date/Time printed: ' . date('Y-m-d H:i:s'), '', '', '', '');
			$export_data[] = array($approvedby_data[0]['jobtitle'], '', '', '', '', '', '');
		}
		$export_data[] = array('');

		$filename = 'Arrears_Monitoring_Report_' . str_replace(array(' ', '/'), '_', $asofdate) . '_' . date('d-m-Y') . '.xls';
		array_to_excel($export_data, $filename);
	}
	
}
?>