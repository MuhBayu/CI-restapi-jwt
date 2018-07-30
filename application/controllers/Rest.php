<?php defined('BASEPATH') OR exit('No direct script access allowed');
require $system_path.'JWT/autoload.php';

use Restserver\Libraries\REST_Controller;
use \Firebase\JWT\JWT;

class Rest extends REST_Controller {
    private $secretkey = 'secret_key'; //ubah dengan kode rahasia apapun

    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url');
    }
    public function index_get() {
        $this->load->view('rest_test');
    }
    /**
     * Generate Token bisa diakses dengan methode POST dengan URL http://localhost/foldername/rest/generate
     * Body required: username & password 
     */
    public function generate_post(){
        $date = new DateTime();
        $username = $this->post('username');
        $password = $this->post('password');

        if ($this->user_model->valid_login($username, $password)) { // Validasi akun
            $payload['id'] = $dataadmin->id_user;
            $payload['username'] = $dataadmin->username;
            $payload['iat'] = $date->getTimestamp(); //waktu di buat
            $payload['exp'] = $date->getTimestamp() + 3600; //expired dalam waktu satu jam

            $output['success'] = true;
            $output['expiry'] = date('Y/m/d H:i', $payload['exp']);
            $output['access_token'] = JWT::encode($payload,$this->secretkey);
            return $this->response($output, REST_Controller::HTTP_OK);
        } else {
            $this->viewtokenfail($username);
        }
    }

    public function viewtokenfail($username){
        $this->response([
            'status' => false,
            'username' => $username,
            'message' => 'Invalid Username Or Password'
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    // Pengecekan token setiap melakukan request
    public function cekToken(){
        $jwt = $this->input->get_request_header('Authorization');
        try {
            $decode = JWT::decode($jwt,$this->secretkey, array('HS256'));
            if ($this->user_model->is_valid_username($decode->username)) return true;
        } catch (Exception $e) {
            exit(json_encode(array('status' => false,'message' => 'Invalid Token or Token expired',)));
        }
    }

}
?>