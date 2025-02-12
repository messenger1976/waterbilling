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
        $billingperiod = explode(' ',$billingperiod);

		$this->db->select($this->table_name.'.customer_type, '.$this->table_name.'.first_name,'.$this->table_name.'.last_name,'.$this->table_name.'.middle_name,'.$this->table_name.'.meter_number,
		(SELECT zone FROM tbl_zone WHERE tbl_zone.id='.$this->table_name.'.zone) as zone, 
        (SELECT bp_due_date FROM tbl_billing_period WHERE tbl_billing_period.bp_id='.$this->table_meter_reading.'.bp_id) as due_date, '.
        $this->table_meter.'.invoice_id,'.
        $this->table_meter.'.date as payment_date,'.
        $this->table_meter.'.amount as payment_amount,
        '.$this->table_meter_reading.'.*');
		$this->db->from($this->table_meter_reading);
		$this->db->join($this->table_name, $this->table_meter_reading.'.customer_id = '.$this->table_name.'.customer_id');

		$this->db->join($this->table_meter, $this->table_meter_reading.'.customer_id='.$this->table_meter.'.customer_id AND '.$this->table_meter_reading.'.month='.$this->table_meter.'.month AND '.$this->table_meter_reading.'.year='.$this->table_meter.'.year','left');

		$this->db->where($this->table_meter_reading.'.month',$billingperiod[0]);
        $this->db->where($this->table_meter_reading.'.year',$billingperiod[1]);
		//$this->db->where('tbl_addmetercustomer.date <=',$to);
		if($zone!=0){
			$this->db->where('tbl_addcustomer.zone',$zone);
		}
        if($status==='1'){
            $this->db->where($this->table_meter.'.invoice_id IS NOT NULL');
        }elseif($status==='0'){
            $this->db->where($this->table_meter.'.invoice_id IS NULL');
        }
		
		$this->db->order_by($this->table_name.'.last_name','asc');
		$this->db->order_by($this->table_name.'.first_name','asc');
		//$this->db->group_by('invoice_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}


	
}
?>
	
