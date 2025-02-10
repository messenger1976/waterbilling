<?php 
class reports_model extends CI_Model {
	public $table_name = 'tbl_addcustomer';
	public $table_billing = 'tbl_feesplaning';
	public $table_meter = 'tbl_addmetercustomer';
    public $table_meter_reading = 'tbl_addcustomer_reading';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_assets = 'tbl_addassets';
	public $table_users = 'tbl_responsibilities_user';
	public $table_zone = 'tbl_zone';
	public $table_employee = 'tbl_addemployee';
	public $table_jobtitle = 'tbl_jobtitle';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** In Function Get all records from select table **/
    
	 public function get_monthly_billing_report_records($zone,$billingperiod,$status=''){
		$this->db->select('tbl_addcustomer.customer_id, tbl_addcustomer.customer_type, tbl_addcustomer.first_name,tbl_addcustomer.last_name,tbl_addcustomer.middle_name,
		(SELECT zone FROM tbl_zone WHERE tbl_zone.id='.$this->table_name.'.zone) as zone, 
		tbl_addcustomer_reading.amount as reading_amount, 
		tbl_addcustomer_reading.sc_discount as sc_discount,
		tbl_addmetercustomer.*');
		$this->db->from($this->table_meter_reading);
		$this->db->join('tbl_addcustomer', $this->table_meter_reading.'tbl_addmetercustomer.customer_id = tbl_addcustomer.customer_id');
		$this->db->join('tbl_addcustomer_reading', 'tbl_addmetercustomer.customer_id = tbl_addcustomer_reading.customer_id and tbl_addmetercustomer.month=tbl_addcustomer_reading.month and tbl_addmetercustomer.year=tbl_addmetercustomer.year','left');
		$this->db->where('tbl_addmetercustomer.date',$from);
		//$this->db->where('tbl_addmetercustomer.date <=',$to);
		if($zone!=0){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}
		
		$this->db->order_by('last_name','asc');
		$this->db->order_by('first_name','asc');
		$this->db->group_by('invoice_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}


	
}
?>
	
