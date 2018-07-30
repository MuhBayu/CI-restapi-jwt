<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class User_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function valid_login($username, $password) {
		$data = $this->db->get_where('tb_users', array('user_login' => $username))->row();
		if($data) {
			$hash = $data->user_pass;
			if(password_verify($password, $hash)) return $data;
		} return false;
	}
	public function is_valid_username($username) {
		return $this->db->from('tb_users')->where('user_login', $username)->get()->num_rows();
	}
}