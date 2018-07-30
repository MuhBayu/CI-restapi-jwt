<?php

defined('BASEPATH') OR exit('No direct script access allowed');
       
require APPPATH . 'controllers/Rest.php';
class Api extends Rest {
	function __construct($config = 'rest') {
		parent::__construct($config);
		$this->load->model('item_model');
		$this->cekToken(); // pengecekan token tiap request
	}
	// *** Untuk Perbedaan Request Method 
	function index_get() {
		$output['success'] = true;
		$output['message'] = "Ini diakses melalui metode GET";
		return $this->response($output, REST::HTTP_OK);
	}
	function index_post() {
		$output['success'] = true;
		$output['message'] = "Ini diakses melalui metode POST";
		return $this->response($output, REST::HTTP_OK);
	}
	// *****************************************************************

	/**
	 * URL: http://localhost/foldername/api/get_item
	 * Method: POST
     * Headers required: Authorization (diisi dengan token)
     */
	function get_item_post() {
		$data = $this->item_model->get();
		$output['success'] = true;
		$output['count'] = $data->num_rows();
		$output['data'] = $data->result();
		return $this->response($output, REST::HTTP_OK);
	}
	/**
	 * URL: http://localhost/foldername/api/insert_item
	 * Method: POST
     * Headers required: Authorization (diisi dengan token)
     * Body required: item-name, item-price, item-status
     */
	function insert_item_post() {
		$data_insert = array(
			'item_name' => $this->post('item-name'),
			'item_price' => $this->post('item-price'),
			'item_status' => $this->post('item-status')
		);
		$insert = $this->item_model->insert($data_insert);
		if($insert) {
			$output['success'] = true;
			$output['message'] = "Data berhasil ditambahkan";
		} else {
			$output['success'] = false;
			$output['message'] = "Data gagal ditambahkan";
		}
		return $this->response($output, REST::HTTP_OK);
	}

	/**
	 * URL: http://localhost/foldername/api/insert_item
	 * Method: POST
     * Headers required: Authorization (diisi dengan token)
     * Body required: item-name, item-price, item-status
     */
	function update_item_post() {
		$id = $this->post('item-id');
		$data_update = array(
			'item_name' => $this->post('item-name'),
			'item_price' => $this->post('item-price'),
			'item_status' => $this->post('item-status')
		);
		$update = $this->item_model->update($data_update, ['item_id' => $id]);
		if($update) {
			$output['success'] = true;
			$output['message'] = "Data berhasil diperbarui";
		} else {
			$output['success'] = false;
			$output['message'] = "Data gagal diperbarui";
		}
		return $this->response($output, REST::HTTP_OK);
	}

	/**
	 * URL: http://localhost/foldername/api/delete_item
	 * Method: POST 
     * Headers required: Authorization (diisi dengan token)
     * Body required: item-id
     */
	function delete_item_post() {
		$id = $this->post('item-id');
		$delete = $this->item_model->delete(array('item_id' => $id));
		if($delete) {
			$output['success'] = true;
			$output['message'] = "Data berhasil dihapus";
		} else {
			$output['success'] = false;
			$output['message'] = "Data gagal dihapus";
		}
		return $this->response($output, REST::HTTP_OK);
	}

}

?>