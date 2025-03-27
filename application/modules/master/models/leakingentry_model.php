<?php 
class leakingentry_model extends CI_Model {
	public $table_name = 'tbl_leaking_ledger';
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
	public $table_customer_meter_reading = 'tbl_addcustomer_reading';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    public function get_all_records() {
        $this->db->select($this->table_name.".*,".$this->table_customer.".*,".$this->table_customer_meter_reading.".month, ".$this->table_customer_meter_reading.".year, ".$this->table_customer_meter_reading.".amount ");
		$this->db->from($this->table_name);
		$this->db->join($this->table_customer, $this->table_name.".leaking_customer_id = ".$this->table_customer.".customer_id", 'left');
		$this->db->join($this->table_customer_meter_reading, $this->table_name.".leaking_refno = ".$this->table_customer_meter_reading.".refno", 'left');
		$this->db->order_by($this->table_name.'.leaking_id','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select("*");
		$this->db->from($this->table_name);
		if($id != ''){
			$this->db->where("id",$id);
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->row_array();
		}
		return $result;
    }
  	/** In Function Add Check Exits records for select table **/
	public function exit_details($exit_data) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where($exit_data);
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }
	
  	/** In Function Edit Check Exits records  for select table**/
	public function exit_id($exit_data,$local_id) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where($exit_data);
		$query = $this->db->get();
		$result = $query->num_rows();
		if($result > 0){
			$this->db->select("*");
			$this->db->from($this->table_name);
			$this->db->where($exit_data);
			$query = $this->db->get();
			$result = $query->row_array();
			if($result['id'] == $local_id){
				$result ='0';
			}
		}else{
			$result =1;
		}
		return $result;
    }
  	/** In Function Add records for select table **/
	public function add_record(){
		
		$paymentdate = date('Y-m-d',strtotime($this->input->post('payment_date')));

		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		
		$created_date = $dt_date->format("Y-m-d H:i:s");

		$set_data = array(
		                   
			'leaking_refno' => $this->input->post('refno'),
			'leaking_customer_id' => $this->input->post('customer_id'),
			'leaking_discount_percent' => $this->input->post('leaking_percent'),
			'leaking_discount_amount' => $this->input->post('leaking_amount'),
			'leaking_date' => $paymentdate,
			'leaking_total_amount' => $this->input->post('bill_amount'),
			'leaking_status' => $this->input->post('leaking_status'),
			'leaking_created_datetime' => $created_date
			
		);
		$result = $this->db->insert($this->table_name, $set_data); 
		return $result;
	}
  	/** In Function Update records for select table **/
	public function update_record($id){
		
		$set_data = array(
		                  
						'zone' => $this->input->post('zone'),
						'status' => $this->input->post('status'),
					  'create_date_time' => date('Y-m-d H:i:s'),
						
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
  	/** In Function Delete records for select table **/
	public function delete_record($id){
		$this->db->where('leaking_id',$id);
		$result = $this->db->delete($this->table_name); 
		return $result;
	}
	
  	/** In Function Status Update records for select table **/
	public function status_record($id,$status){
		//$sts = ($status == 1 ? 0 : 1);
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		
		$updated_date = $dt_date->format("Y-m-d H:i:s");
		$set_data = array(
			'leaking_status' => $status,
			'leaking_updated_datetime' => $updated_date
		);
		$this->db->where('leaking_id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
	public function get_income_metercustomer(){
		$this->db->select('SUM(pay_amount) as total1');
		$this->db->from($this->table_meter);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_income_monthlycustomer(){
		$this->db->select('SUM(paidamount) as total2');
		$this->db->from($this->table_monthly);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_outcome_expenses(){
		$this->db->select('SUM(total) as extotal1');
		$this->db->from($this->table_expenses);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_outcome_payroll(){
		$this->db->select('SUM(total) as extotal2');
		$this->db->from($this->table_payrol);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function total_customer(){
		$this->db->select('COUNT(id) as count_id');
		$this->db->from($this->table_customer);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
}


?>