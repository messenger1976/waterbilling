<?php 
class Common_model extends CI_Model {
	
	public $table_web_settings = 'tbl_websettings';
	public $table_zone = 'tbl_zone'; //zone table
	public $table_meter = 'tbl_addmetercustomer';
	public $table_monthly = 'tbl_monthlycustomer';
	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
    }
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record() {
        $this->db->select("*");
		$this->db->from($this->table_web_settings);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }   

	/* Global common get zone records*/
	public function get_zone_records() {
        //$this->db->select("*");
		//$this->db->from($this->table_zone);
		$this->db->order_by($this->table_zone.'.id','desc');
		$query = $this->db->get($this->table_zone);
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }

	public function get_income_metercustomer(){
		$this->db->select('SUM(grand_total) as total1');
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
 
}
?>