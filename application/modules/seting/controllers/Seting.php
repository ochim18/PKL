<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seting extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

		protected_page(array('administrator'));
		
		$this->load->model('option_model');
	}

	public function index()
	{
		$param = array(
			'title' 		=> 'Seting App',
			'active_menu' 	=> 'seting',
		);

		if ($post = $this->input->post()) {
			if (isset($post['option']) && isAssoc( $post['option'] )) {
				foreach ($post['option'] as $key => $value) {
					if ($this->option_model->check_isset_key($key)) {
						$this->option_model->change_value($key, $value);
					}else{
						$this->option_model->set_value($key, $value);
					}
				}
			}

			if (isset($_FILES['logo']) && $_FILES['logo']['error']== 0) {
				$this->load->library('upload');
				
				$config['file_name'] 	 = 'logo';
				$config['upload_path']   = './uploads/';
				$config['allowed_types'] = 'jpg|jpeg|gif|png';

				$this->upload->initialize($config);

				if ( $this->upload->do_upload('logo')){
					$file_data = $this->upload->data();
					if ($this->option_model->check_isset_key('logo')) {
						$img_last = $this->option_model->get_value('logo');
						if ($img_last != "") {
							unlink('uploads/'.$img_last);
						}
						
						$this->option_model->change_value('logo', $file_data['file_name']);
					}else{
						$this->option_model->set_value('logo', $file_data['file_name']);
					}
				}
			}

			set_message_flash("Data telah berhasil diubah.", 'success');
			redirect('seting');
		}

		$this->load->view('header', $param );
		$this->load->view('seting', $param );
		$this->load->view('footer', $param );
	}
}
