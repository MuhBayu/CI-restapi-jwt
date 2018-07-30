<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Item_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function insert($data) {
		return $this->db->insert('tb_items', $data);
	}
	public function get($where=null, $limit=null, $offset=null) {
		$this->db->limit($limit, $offset);
		if($where) $this->db->where($where);
		return $this->db->get('tb_items');
	}
	public function update($data,$where=null) {
		if($where) $this->db->where($where);
    	return $this->db->update('tb_items', $data);
	}
	public function delete($where) {
		return $this->db->delete('tb_items', $where);
	}
}