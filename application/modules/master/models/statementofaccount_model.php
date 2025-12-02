<?php 
class statementofaccount_model extends CI_Model {
	public $table_customer = 'tbl_addcustomer';
	public $table_reading = 'tbl_addcustomer_reading';
	public $table_payment = 'tbl_addmetercustomer';
	public $table_months = 'tbl_months';
	public $table_zone = 'tbl_zone';
	public $table_classification = 'tbl_classification';
	public $table_customer_type = 'tbl_customer_type';
	public $table_billing_period = 'tbl_billing_period';
	
	// Autoloading a system library using constructor method
	public function __construct() {
        parent::__construct();
		ini_set('date.timezone', 'Asia/Manila');	
    }
	
	/** Get customer information **/
	public function get_customer_info($customer_id='') {
        $this->db->select($this->table_customer.".*,".$this->table_zone.".zone,".$this->table_classification.".class_name,".$this->table_customer_type.".cust_type_name");
		$this->db->from($this->table_customer);
		$this->db->join($this->table_zone, $this->table_customer.'.zone = '.$this->table_zone.'.id', 'left');
		$this->db->join($this->table_classification, $this->table_customer.'.classification = '.$this->table_classification.'.class_id', 'left');
		$this->db->join($this->table_customer_type, $this->table_customer.'.account_type = '.$this->table_customer_type.'.cust_type_id', 'left');
		if($customer_id != ''){
			$this->db->where($this->table_customer.".customer_id",$customer_id);
			$query = $this->db->get();
			$result = $query->row_array();
		}
		return $result;
    }
	
	/** Get all customer ledger entries (readings and payments) **/
	public function get_customer_ledger($customer_id='') {
		// Get billing entries (readings) - get unique entries per refno (latest one)
		// Include entries with amount > 0 even if reading is 0 or empty
		// Handle empty/null refno by grouping on month+year combination
		// Include all billing periods including 12-2024
		// Include customer_billing_id to link with payments
		$sql_readings = "SELECT t1.*, tm.month_name, bp.bp_due_date 
			FROM ".$this->table_reading." t1
			LEFT JOIN ".$this->table_months." tm ON t1.month = tm.month_id
			LEFT JOIN ".$this->table_billing_period." bp ON t1.bp_id = bp.bp_id
			INNER JOIN (
				SELECT 
					COALESCE(NULLIF(refno, ''), CONCAT('BP-', month, '-', year)) as group_key,
					MAX(id) as max_id 
				FROM ".$this->table_reading." 
				WHERE customer_id = ? 
					AND (
						(reading != '0' AND reading != '') 
						OR (amount > 0)
					)
				GROUP BY COALESCE(NULLIF(refno, ''), CONCAT('BP-', month, '-', year))
			) t2 ON COALESCE(NULLIF(t1.refno, ''), CONCAT('BP-', t1.month, '-', t1.year)) = t2.group_key 
				AND t1.id = t2.max_id
			WHERE t1.customer_id = ? 
				AND (
					(t1.reading != '0' AND t1.reading != '') 
					OR (t1.amount > 0)
				)
			ORDER BY t1.id DESC";
		$query_readings = $this->db->query($sql_readings, array($customer_id, $customer_id));
		$readings = $query_readings->result_array();
		
		// Get payment entries using the exact query structure from addpaymentcustomer/add
		// Use customer_billing_id relationship: tbl_addcustomer_reading.customer_billing_id = tbl_addmetercustomer.id
		// Also try month/year matching as fallback (same as get_metercustomer_add_all_records)
		$sql_payments = "SELECT 
			ac.id AS addcustomer_id, 
			ac.customer_id, 
			am.id AS addmetercustomer_id, 
			am.customer_id as payment_customer_id,
			am.date, 
			am.date as trans_date,
			am.month, 
			am.year, 
			am.amount, 
			am.total,
			am.pay_amount,
			am.per_unit, 
			am.or_number,
			am.invoice_id,
			ac.mobile1, 
			ac.mobile2, 
			ac.email_id,
			tm.month_name,
			COALESCE(ac2.reading, ac3.reading) as current_reading,
			COALESCE(ac2.previous_reading, ac3.previous_reading) as previous_reading,
			COALESCE(ac2.consumed, ac3.consumed) as consumed,
			COALESCE(ac2.amount, ac3.amount) as bill_amount,
			COALESCE(ac2.sc_discount, ac3.sc_discount) as sc_discount,
			COALESCE(ac2.unit_price, ac3.unit_price) as unit_price,
			COALESCE(ac2.bp_id, ac3.bp_id) as bp_id,
			COALESCE(ac2.penalty, ac3.penalty) as penalty,
			COALESCE(ac2.maintenance_fee, ac3.maintenance_fee) as maintenance_fee,
			COALESCE(ac2.refno, ac3.refno) as bill_refno,
			COALESCE(tm2.month_name, tm3.month_name) as bill_month_name,
			COALESCE(ac2.year, ac3.year) as bill_year,
			COALESCE(ac2.month, ac3.month) as bill_month,
			COALESCE(bp.bp_due_date, bp2.bp_due_date) as bp_due_date
			FROM ".$this->table_payment." am
			LEFT JOIN ".$this->table_customer." ac ON ac.customer_id = am.customer_id
			LEFT JOIN ".$this->table_months." tm ON am.month = tm.month_id
			LEFT JOIN ".$this->table_reading." ac2 ON ac2.customer_billing_id = am.id
			LEFT JOIN ".$this->table_months." tm2 ON ac2.month = tm2.month_id
			LEFT JOIN ".$this->table_billing_period." bp ON ac2.bp_id = bp.bp_id
			LEFT JOIN ".$this->table_reading." ac3 ON ac3.month = am.month AND ac3.year = am.year AND ac3.customer_id = am.customer_id
			LEFT JOIN ".$this->table_months." tm3 ON ac3.month = tm3.month_id
			LEFT JOIN ".$this->table_billing_period." bp2 ON ac3.bp_id = bp2.bp_id
			WHERE am.customer_id = ?
			ORDER BY am.id DESC";
		$query_payments = $this->db->query($sql_payments, array($customer_id));
		$all_payments_raw = $query_payments->result_array();
		
		// Group payments by OR number/invoice_id in PHP (same grouping logic as before)
		// Include ALL payments regardless of amount or OR number format
		$payments_grouped = array();
		foreach($all_payments_raw as $raw_payment) {
			// Skip if payment record is completely empty
			if(empty($raw_payment) || !isset($raw_payment['addmetercustomer_id'])) {
				continue;
			}
			
			// Determine the grouping key (OR number, invoice_id, or PAY-id)
			// Handle OR numbers with leading zeros (like 0001176)
			$group_key = '';
			if(isset($raw_payment['or_number']) && $raw_payment['or_number'] !== null && $raw_payment['or_number'] !== '') {
				// Preserve leading zeros by using strval without trim on numeric part
				$or_num = strval($raw_payment['or_number']);
				$or_num = trim($or_num); // Only trim whitespace, preserve leading zeros
				if($or_num !== '') {
					$group_key = $or_num;
				}
			}
			
			// If OR number not available, try invoice_id
			if(empty($group_key)) {
				if(isset($raw_payment['invoice_id']) && $raw_payment['invoice_id'] !== null && $raw_payment['invoice_id'] !== '') {
					$group_key = trim(strval($raw_payment['invoice_id']));
				}
			}
			
			// Last resort: use payment ID
			if(empty($group_key)) {
				$group_key = 'PAY-' . $raw_payment['addmetercustomer_id'];
			}
			
			// Initialize group if it doesn't exist
			if(!isset($payments_grouped[$group_key])) {
				$payments_grouped[$group_key] = array(
					'payment_id' => $raw_payment['addmetercustomer_id'],
					'ref_number' => $group_key,
					'or_number' => isset($raw_payment['or_number']) ? trim(strval($raw_payment['or_number'])) : '',
					'invoice_id' => isset($raw_payment['invoice_id']) ? trim(strval($raw_payment['invoice_id'])) : '',
					'customer_id' => $raw_payment['customer_id'],
					'date' => isset($raw_payment['trans_date']) ? $raw_payment['trans_date'] : $raw_payment['date'],
					'id' => $raw_payment['addmetercustomer_id'],
					'total_amount' => 0,
					'pay_amount' => 0, // Track pay_amount separately
					'payment_count' => 0,
					'billing_periods' => array(),
					'payment_ids' => array(),
					'earliest_date' => isset($raw_payment['trans_date']) ? $raw_payment['trans_date'] : $raw_payment['date'],
					'earliest_id' => $raw_payment['addmetercustomer_id'],
					'billing_details' => array() // Store billing details for each payment
				);
			}
			
			// Add billing details if available
			// Use bill_refno or check if we have any billing data
			$has_billing_data = false;
			if(isset($raw_payment['bill_refno']) && !empty($raw_payment['bill_refno'])) {
				$has_billing_data = true;
			} elseif(isset($raw_payment['bill_month_name']) && !empty($raw_payment['bill_month_name'])) {
				$has_billing_data = true;
			} elseif(isset($raw_payment['current_reading']) && $raw_payment['current_reading'] !== null && $raw_payment['current_reading'] !== '') {
				$has_billing_data = true;
			}
			
			if($has_billing_data) {
				$billing_detail = array(
					'refno' => isset($raw_payment['bill_refno']) && !empty($raw_payment['bill_refno']) ? $raw_payment['bill_refno'] : '',
					'billing_period' => isset($raw_payment['bill_month_name']) && isset($raw_payment['bill_year']) ? 
						$raw_payment['bill_month_name'] . ' ' . $raw_payment['bill_year'] : 
						(isset($raw_payment['month_name']) && isset($raw_payment['year']) ? $raw_payment['month_name'] . ' ' . $raw_payment['year'] : ''),
					'due_date' => isset($raw_payment['bp_due_date']) ? $raw_payment['bp_due_date'] : '',
					'previous_reading' => isset($raw_payment['previous_reading']) ? $raw_payment['previous_reading'] : '',
					'current_reading' => isset($raw_payment['current_reading']) ? $raw_payment['current_reading'] : '',
					'consumed' => isset($raw_payment['consumed']) ? $raw_payment['consumed'] : '',
					'bill_amount' => isset($raw_payment['bill_amount']) ? floatval($raw_payment['bill_amount']) : 0,
					'discount' => isset($raw_payment['sc_discount']) ? floatval($raw_payment['sc_discount']) : 0,
					'penalty' => isset($raw_payment['penalty']) ? floatval($raw_payment['penalty']) : 0,
					'maintenance_fee' => isset($raw_payment['maintenance_fee']) ? floatval($raw_payment['maintenance_fee']) : 0
				);
				
				// Check if this billing detail already exists (avoid duplicates)
				$exists = false;
				$check_key = $billing_detail['refno'] ? $billing_detail['refno'] : ($billing_detail['billing_period'] ? $billing_detail['billing_period'] : '');
				if(!empty($check_key)) {
					foreach($payments_grouped[$group_key]['billing_details'] as $existing) {
						if(($existing['refno'] && $existing['refno'] == $billing_detail['refno']) || 
						   (!$existing['refno'] && $existing['billing_period'] == $billing_detail['billing_period'])) {
							$exists = true;
							break;
						}
					}
				}
				if(!$exists) {
					$payments_grouped[$group_key]['billing_details'][] = $billing_detail;
				}
			}
			
			// Add this payment to the group - use amount, total, or pay_amount (same as addpaymentcustomer)
			// Include payments even if amount is 0 (to ensure all payments are shown)
			$payment_amount = 0;
			if(isset($raw_payment['total']) && $raw_payment['total'] !== null) {
				$payment_amount = floatval($raw_payment['total']);
			} elseif(isset($raw_payment['pay_amount']) && $raw_payment['pay_amount'] !== null) {
				$payment_amount = floatval($raw_payment['pay_amount']);
			} elseif(isset($raw_payment['amount']) && $raw_payment['amount'] !== null) {
				$payment_amount = floatval($raw_payment['amount']);
			}
			// Always add payment amount (even if 0) to ensure payment is included
			$payments_grouped[$group_key]['total_amount'] += $payment_amount;
			
			// Track pay_amount separately (use pay_amount from payment record if available)
			$pay_amount_value = 0;
			if(isset($raw_payment['pay_amount']) && $raw_payment['pay_amount'] !== null) {
				$pay_amount_value = floatval($raw_payment['pay_amount']);
			} else {
				$pay_amount_value = $payment_amount; // Fallback to payment_amount if pay_amount not available
			}
			$payments_grouped[$group_key]['pay_amount'] += $pay_amount_value;
			$payments_grouped[$group_key]['payment_count']++;
			
			// Track billing periods
			if(isset($raw_payment['month_name']) && isset($raw_payment['year']) && !empty($raw_payment['month_name'])) {
				$period = $raw_payment['month_name'] . ' ' . $raw_payment['year'];
				if(!in_array($period, $payments_grouped[$group_key]['billing_periods'])) {
					$payments_grouped[$group_key]['billing_periods'][] = $period;
				}
			}
			
			// Track payment IDs
			$payments_grouped[$group_key]['payment_ids'][] = $raw_payment['addmetercustomer_id'];
			
			// Update earliest date and ID
			$current_date = isset($raw_payment['trans_date']) ? $raw_payment['trans_date'] : $raw_payment['date'];
			$current_timestamp = strtotime($current_date);
			$earliest_timestamp = strtotime($payments_grouped[$group_key]['earliest_date']);
			if($current_timestamp !== false && ($earliest_timestamp === false || $current_timestamp < $earliest_timestamp)) {
				$payments_grouped[$group_key]['earliest_date'] = $current_date;
				$payments_grouped[$group_key]['earliest_id'] = $raw_payment['addmetercustomer_id'];
			}
			if($raw_payment['addmetercustomer_id'] < $payments_grouped[$group_key]['earliest_id']) {
				$payments_grouped[$group_key]['earliest_id'] = $raw_payment['addmetercustomer_id'];
			}
		}
		
		// Convert grouped array to format expected by rest of code
		// Expand payments to show one row per billing period if multiple billings paid
		// Always show payments even if no billing details are found
		$payments = array();
		foreach($payments_grouped as $group_key => $group_data) {
			// If payment has billing details, create one entry per billing
			if(!empty($group_data['billing_details'])) {
				foreach($group_data['billing_details'] as $billing_detail) {
					$payments[] = array(
						'payment_id' => $group_data['earliest_id'],
						'ref_number' => $group_data['ref_number'],
						'or_number' => $group_data['or_number'],
						'invoice_id' => $group_data['invoice_id'],
						'customer_id' => $group_data['customer_id'],
						'date' => $group_data['earliest_date'],
						'id' => $group_data['earliest_id'],
						'total_amount' => $group_data['total_amount'],
						'pay_amount' => $group_data['pay_amount'], // Use actual pay_amount
						'payment_count' => $group_data['payment_count'],
						'billing_periods' => implode(', ', $group_data['billing_periods']),
						'payment_ids' => implode(',', $group_data['payment_ids']),
						// Billing details
						'bill_refno' => $billing_detail['refno'],
						'billing_period' => $billing_detail['billing_period'],
						'due_date' => $billing_detail['due_date'],
						'previous_reading' => $billing_detail['previous_reading'],
						'current_reading' => $billing_detail['current_reading'],
						'consumed' => $billing_detail['consumed'],
						'bill_amount' => $billing_detail['bill_amount'],
						'discount' => $billing_detail['discount'],
						'penalty' => $billing_detail['penalty'],
						'maintenance_fee' => $billing_detail['maintenance_fee']
					);
				}
			} else {
				// If no billing details, still create entry with payment info
				// Use payment month/year as billing period if available
				$billing_period_display = '';
				if(!empty($group_data['billing_periods'])) {
					$billing_period_display = implode(', ', $group_data['billing_periods']);
				}
				
				$payments[] = array(
					'payment_id' => $group_data['earliest_id'],
					'ref_number' => $group_data['ref_number'],
					'or_number' => $group_data['or_number'],
					'invoice_id' => $group_data['invoice_id'],
					'customer_id' => $group_data['customer_id'],
					'date' => $group_data['earliest_date'],
					'id' => $group_data['earliest_id'],
					'total_amount' => $group_data['total_amount'],
					'pay_amount' => $group_data['pay_amount'], // Use actual pay_amount
					'payment_count' => $group_data['payment_count'],
					'billing_periods' => $billing_period_display,
					'payment_ids' => implode(',', $group_data['payment_ids']),
					// Empty billing details - payment exists but no linked billing found
					'bill_refno' => '',
					'billing_period' => $billing_period_display,
					'due_date' => '',
					'previous_reading' => '',
					'current_reading' => '',
					'consumed' => '',
					'bill_amount' => 0,
					'discount' => 0,
					'penalty' => 0,
					'maintenance_fee' => 0
				);
			}
		}
		
		// Sort by earliest ID descending (newest first)
		usort($payments, function($a, $b) {
			return $b['id'] - $a['id'];
		});
		
		// Get all individual payment records to map payment IDs to payment dates
		// This is needed to check if billing has payment via customer_billing_id
		// Include ALL payments for the customer
		$sql_payment_details = "SELECT id, date, total, pay_amount 
			FROM ".$this->table_payment." 
			WHERE customer_id = ?";
		$query_payment_details = $this->db->query($sql_payment_details, array($customer_id));
		$payment_details = $query_payment_details->result_array();
		
		// Combine and format entries
		$ledger_entries = array();
		
		// Create a map of payment IDs to payment dates
		// This is used to check if billing has payment via customer_billing_id = payment.id
		$payment_ids_map = array();
		foreach($payment_details as $payment_detail) {
			$payment_id = isset($payment_detail['id']) ? intval($payment_detail['id']) : 0;
			if($payment_id > 0) {
				// Store payment date for overdue check
				$payment_date = '';
				if(!empty($payment_detail['date'])){
					// Handle date format - convert d-m-Y to Y-m-d if needed
					if(strpos($payment_detail['date'], '-') !== false && strlen($payment_detail['date']) == 10){
						$date_parts = explode('-', $payment_detail['date']);
						if(count($date_parts) == 3 && strlen($date_parts[0]) == 2){
							// d-m-Y format, convert to Y-m-d
							$payment_date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
						} else {
							$payment_date = $payment_detail['date'];
						}
					} else {
						$payment_date = $payment_detail['date'];
					}
				}
				$payment_ids_map[$payment_id] = array(
					'exists' => true,
					'date' => $payment_date
				);
			}
		}
		
		// Add billing entries
		foreach($readings as $reading){
			// Use date from tbl_addcustomer_reading table
			// Handle date format - convert d-m-Y to Y-m-d if needed
			$entry_date = '';
			if(!empty($reading['date'])){
				// Date from tbl_addcustomer_reading.date field
				// Check if date is in d-m-Y format
				if(strpos($reading['date'], '-') !== false && strlen($reading['date']) == 10){
					$date_parts = explode('-', $reading['date']);
					if(count($date_parts) == 3 && strlen($date_parts[0]) == 2){
						// d-m-Y format, convert to Y-m-d
						$entry_date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
					} else {
						$entry_date = $reading['date'];
					}
				} else {
					$entry_date = $reading['date'];
				}
			} else {
				// Fallback: if date is empty, construct from year and month
				$entry_date = $reading['year'].'-'.str_pad($reading['month'], 2, '0', STR_PAD_LEFT).'-01';
			}
			
			// Check if this billing has a payment using customer_billing_id relationship
			// Relationship: tbl_addmetercustomer.id = tbl_addcustomer_reading.customer_billing_id
			$billing_refno = isset($reading['refno']) ? trim($reading['refno']) : '';
			$customer_billing_id = isset($reading['customer_billing_id']) ? intval($reading['customer_billing_id']) : 0;
			$has_payment = false;
			$payment_overdue = false;
			
			// Check if billing has payment via customer_billing_id
			if($customer_billing_id > 0 && isset($payment_ids_map[$customer_billing_id])) {
				$has_payment = true;
				
				// Get due date from billing_period table (bp_due_date)
				$due_date = isset($reading['bp_due_date']) ? $reading['bp_due_date'] : '';
				
				// Check if payment is overdue (payment date after due date from billing_period)
				if(!empty($due_date)) {
					$payment_date = isset($payment_ids_map[$customer_billing_id]['date']) ? $payment_ids_map[$customer_billing_id]['date'] : '';
					
					// Convert dates to timestamps for comparison
					if(!empty($payment_date)) {
						$payment_timestamp = strtotime($payment_date);
						$due_timestamp = strtotime($due_date);
						
						// If payment date is after due date from billing_period, it's overdue
						if($payment_timestamp !== false && $due_timestamp !== false && $payment_timestamp > $due_timestamp) {
							$payment_overdue = true;
						}
					}
				}
			}
			
			// Calculate debit amount based on payment status and due date
			$debit_amount = isset($reading['amount']) ? floatval($reading['amount']) : 0;
			$penalty_amount = isset($reading['penalty']) ? floatval($reading['penalty']) : 0;
			
			// If payment exists and is overdue (payment date > due date from billing_period), use penalty amount
			// If no payment exists, use penalty amount
			// Otherwise, use regular amount from tbl_addcustomer_reading
			if($payment_overdue && $penalty_amount > 0) {
				// Payment is overdue - use penalty amount
				// Use penalty amount if it's greater than amount (penalty includes base amount)
				// Otherwise, use amount + penalty
				if($penalty_amount > $debit_amount) {
					$debit_amount = $penalty_amount;
				} else {
					$debit_amount = $debit_amount + $penalty_amount;
				}
			} elseif(!$has_payment && $penalty_amount > 0) {
				// No payment exists - use penalty amount
				if($penalty_amount > $debit_amount) {
					$debit_amount = $penalty_amount;
				} else {
					$debit_amount = $debit_amount + $penalty_amount;
				}
			}
			// If payment exists and is NOT overdue, use regular amount (already set above)
			
			$ledger_entries[] = array(
				'date' => $entry_date,
				'type' => 'billing',
				'refno' => $billing_refno,
				'description' => 'Billing - '.$reading['month_name'].' '.$reading['year'],
				'debit' => $debit_amount,
				'credit' => 0,
				'reading' => isset($reading['reading']) ? $reading['reading'] : '',
				'previous_reading' => isset($reading['previous_reading']) ? $reading['previous_reading'] : '',
				'consumed' => isset($reading['consumed']) ? $reading['consumed'] : '',
				'unit_price' => isset($reading['unit_price']) ? $reading['unit_price'] : '',
				'penalty' => $penalty_amount,
				'sc_discount' => isset($reading['sc_discount']) ? $reading['sc_discount'] : '',
				'arrears' => isset($reading['arrears']) ? $reading['arrears'] : '',
				'maintenance_fee' => isset($reading['maintenance_fee']) ? $reading['maintenance_fee'] : '',
				'due_date' => isset($reading['bp_due_date']) ? $reading['bp_due_date'] : '',
				'has_payment' => $has_payment,
				'raw_data' => $reading
			);
		}
		
		// Add payment entries (already grouped by or_number)
		// Process ALL payments returned by query, regardless of payment_count or amount
		// Include ALL payments to ensure OR# 0001176 and others are shown
		foreach($payments as $payment){
			// Skip only if payment array is completely empty or missing critical fields
			if(empty($payment) || !isset($payment['id']) || !isset($payment['customer_id'])) {
				continue;
			}
			// Use date from tbl_addmetercustomer table
			// Handle date format - convert d-m-Y to Y-m-d if needed
			$entry_date = '';
			if(!empty($payment['date'])){
				// Date from tbl_addmetercustomer.date field (MIN(t1.date) from query)
				// Check if date is in d-m-Y format
				if(strpos($payment['date'], '-') !== false && strlen($payment['date']) == 10){
					$date_parts = explode('-', $payment['date']);
					if(count($date_parts) == 3 && strlen($date_parts[0]) == 2){
						// d-m-Y format, convert to Y-m-d
						$entry_date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
					} else {
						$entry_date = $payment['date'];
					}
				} else {
					$entry_date = $payment['date'];
				}
			}
			
			// If no date from tbl_addmetercustomer, use a default date to ensure entry is included
			if(empty($entry_date)) {
				$entry_date = isset($payment['year']) && isset($payment['month']) ? 
					$payment['year'].'-'.str_pad($payment['month'], 2, '0', STR_PAD_LEFT).'-01' : 
					date('Y-m-d');
			}
			
			// Get OR number - preserve leading zeros (like 0001176)
			// For single payments, ref_number should equal or_number (or invoice_id)
			$or_number = '';
			
			// Use ref_number first - it's already set from grouping and preserves leading zeros
			if(isset($payment['ref_number']) && $payment['ref_number'] !== null && $payment['ref_number'] !== '') {
				$temp_ref = strval($payment['ref_number']);
				$temp_ref = trim($temp_ref); // Only trim whitespace, preserve leading zeros in numbers
				// Only use ref_number if it's not just 'PAY-' (which means no or_number/invoice_id)
				if($temp_ref !== 'PAY-' && strpos($temp_ref, 'PAY-') !== 0) {
					$or_number = $temp_ref; // Preserve leading zeros
				}
			}
			
			// If ref_number wasn't usable, try or_number field directly
			if(empty($or_number)) {
				if(isset($payment['or_number']) && $payment['or_number'] !== null && $payment['or_number'] !== '') {
					$or_num = strval($payment['or_number']);
					$or_number = trim($or_num); // Preserve leading zeros
				}
			}
			
			// If still empty, try invoice_id
			if(empty($or_number)) {
				if(isset($payment['invoice_id']) && $payment['invoice_id'] !== null && $payment['invoice_id'] !== '') {
					$or_number = trim(strval($payment['invoice_id']));
				}
			}
			
			// Last resort - use ref_number even if it starts with PAY- (better than nothing)
			if(empty($or_number)) {
				if(isset($payment['ref_number']) && $payment['ref_number'] !== null && $payment['ref_number'] !== '') {
					$or_number = trim(strval($payment['ref_number']));
				}
			}
			
			// Absolute last resort - use payment ID
			if(empty($or_number)) {
				$payment_id = isset($payment['id']) ? $payment['id'] : (isset($payment['raw_data']['id']) ? $payment['raw_data']['id'] : uniqid());
				$or_number = 'PAY-' . $payment_id;
			}
			
			// Build description - show payment count if multiple billing periods
			$description = 'Payment - OR# '.$or_number;
			if(isset($payment['payment_count']) && $payment['payment_count'] > 1){
				$description .= ' ('.$payment['payment_count'].' periods)';
			}
			
			// Always add payment entry - never skip
			$ledger_entries[] = array(
				'date' => $entry_date,
				'type' => 'payment',
				'refno' => $or_number,
				'description' => $description,
				'debit' => 0,
				'credit' => isset($payment['total_amount']) ? floatval($payment['total_amount']) : 0,
				'payment_count' => isset($payment['payment_count']) ? intval($payment['payment_count']) : 1,
				'billing_periods' => isset($payment['billing_periods']) ? $payment['billing_periods'] : '',
				'raw_data' => $payment
			);
		}
		
		// Remove duplicate entries - ensure each refno+type appears only once (keep the most recent)
		// IMPORTANT: Billing and payment entries with same refno should BOTH appear (different types)
		$unique_entries = array();
		$seen_combinations = array();
		
		foreach($ledger_entries as $entry) {
			$refno = isset($entry['refno']) && !empty($entry['refno']) ? trim($entry['refno']) : '';
			$type = isset($entry['type']) ? $entry['type'] : '';
			$date = isset($entry['date']) ? strtotime($entry['date']) : false;
			if($date === false) $date = 0;
			
			// Skip entries with empty refno and type (shouldn't happen but safety check)
			if(empty($refno) && empty($type)) {
				continue;
			}
			
			// Create unique key: refno_type (billing and payment with same refno are different)
			$unique_key = $refno . '_' . $type;
			
			// If we haven't seen this refno+type combination, add it
			// If we have seen it, keep the one with the most recent date
			if(!isset($seen_combinations[$unique_key])) {
				$seen_combinations[$unique_key] = $entry;
			} else {
				$existing_date = isset($seen_combinations[$unique_key]['date']) ? strtotime($seen_combinations[$unique_key]['date']) : false;
				if($existing_date === false) $existing_date = 0;
				
				// Keep the entry with the more recent date
				if($date > $existing_date) {
					$seen_combinations[$unique_key] = $entry;
				}
			}
		}
		
		// Convert back to array - preserve all unique refno+type combinations
		$ledger_entries = array_values($seen_combinations);
		
		// Group by refno, then sort by date descending (newest first)
		// First, group entries by refno
		$grouped_entries = array();
		foreach($ledger_entries as $entry) {
			$refno = isset($entry['refno']) && !empty($entry['refno']) ? $entry['refno'] : 'no-refno';
			if(!isset($grouped_entries[$refno])) {
				$grouped_entries[$refno] = array();
			}
			$grouped_entries[$refno][] = $entry;
		}
		
		// Sort entries within each refno group by date descending
		foreach($grouped_entries as $refno => &$entries) {
			usort($entries, function($a, $b) {
				$dateA = strtotime($a['date']);
				$dateB = strtotime($b['date']);
				
				if($dateA === false) $dateA = 0;
				if($dateB === false) $dateB = 0;
				
				if ($dateA == $dateB) {
					// If same date, put payments after billings
					if($a['type'] == 'payment' && $b['type'] == 'billing') return 1;
					if($a['type'] == 'billing' && $b['type'] == 'payment') return -1;
					return 0;
				}
				return $dateB - $dateA; // Descending
			});
		}
		
		// Get the newest date for each refno group to sort groups
		$refno_dates = array();
		foreach($grouped_entries as $refno => $entries) {
			$max_date = 0;
			foreach($entries as $entry) {
				$date = strtotime($entry['date']);
				if($date !== false && $date > $max_date) {
					$max_date = $date;
				}
			}
			$refno_dates[$refno] = $max_date;
		}
		
		// Sort refno groups by their newest date (descending)
		uksort($grouped_entries, function($a, $b) use ($refno_dates) {
			$dateA = isset($refno_dates[$a]) ? $refno_dates[$a] : 0;
			$dateB = isset($refno_dates[$b]) ? $refno_dates[$b] : 0;
			return $dateB - $dateA; // Descending
		});
		
		// Flatten back to single array with final deduplication
		$ledger_entries = array();
		$final_seen = array();
		
		foreach($grouped_entries as $refno => $entries) {
			foreach($entries as $entry) {
				$refno_key = isset($entry['refno']) && !empty($entry['refno']) ? trim($entry['refno']) : '';
				$type = isset($entry['type']) ? $entry['type'] : '';
				$date = isset($entry['date']) ? $entry['date'] : '';
				
				// Skip entries with no refno and no type (shouldn't happen)
				if(empty($refno_key) && empty($type)) {
					continue;
				}
				
				// Create unique key: refno_type_date to allow same refno+type on different dates
				// But if same refno+type+date, keep only one (shouldn't happen after previous deduplication)
				$final_key = $refno_key . '_' . $type . '_' . $date;
				
				// If we haven't seen this exact combination, add it
				// This ensures billing and payment with same refno but different types both appear
				if(!isset($final_seen[$final_key])) {
					$final_seen[$final_key] = true;
					$ledger_entries[] = $entry;
				}
			}
		}
		
		return $ledger_entries;
	}
	
	/** Calculate running balance for ledger entries **/
	public function calculate_running_balance($ledger_entries) {
		// Sort entries by date ascending (oldest first) for correct balance calculation
		usort($ledger_entries, function($a, $b) {
			$dateA = strtotime($a['date']);
			$dateB = strtotime($b['date']);
			
			if($dateA === false) $dateA = 0;
			if($dateB === false) $dateB = 0;
			
			if ($dateA == $dateB) {
				// If same date, put billings before payments
				if($a['type'] == 'billing' && $b['type'] == 'payment') return -1;
				if($a['type'] == 'payment' && $b['type'] == 'billing') return 1;
				return 0;
			}
			return $dateA - $dateB; // Ascending (oldest first)
		});
		
		// Calculate running balance from oldest to newest
		$balance = 0;
		foreach($ledger_entries as &$entry){
			$balance = $balance + $entry['debit'] - $entry['credit'];
			$entry['balance'] = $balance;
		}
		
		// Reverse to show newest first (descending order)
		return array_reverse($ledger_entries);
	}
	
	/** Get current balance for customer **/
	public function get_current_balance($customer_id='') {
		// Get total billings
		$this->db->select_sum('amount');
		$this->db->from($this->table_reading);
		$this->db->where('customer_id', $customer_id);
		$this->db->where('reading !=', '0');
		$this->db->where('reading !=', '');
		$query_billings = $this->db->get();
		$total_billings = $query_billings->row()->amount ? $query_billings->row()->amount : 0;
		
		// Get total payments - use total column instead of pay_amount
		$this->db->select_sum('total');
		$this->db->from($this->table_payment);
		$this->db->where('customer_id', $customer_id);
		$query_payments = $this->db->get();
		$total_payments = $query_payments->row()->total ? $query_payments->row()->total : 0;
		
		$balance = $total_billings - $total_payments;
		return $balance;
	}
	
}
?>

