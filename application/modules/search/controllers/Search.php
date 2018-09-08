<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('databuku/keluarga_model');
	}

	public function index()
	{
		$this->search();
	}

	/*************************************************************
	 * Login App
	 *************************************************************/

	public function search()
	{
		
		$params = array(
			'title' 		=> 'Search',
		);

		$this->load->view('simple-header', $params );
		$this->load->view('search', $params );
		$this->load->view('simple-footer', $params );
	}

	public function doc_view($buku_id)
	{
		$data = $this->keluarga_model->get_view($buku_id);
		$params = array(
			'title' 		=> 'Search',
			'post' 			=> (array)$data,

		);

		$this->load->view('simple-header1', $params );
		$this->load->view('document_view', $params );
		$this->load->view('simple-footer', $params );
	}

	/*************************************************************
	 * Setting profile
	 *************************************************************/

	public function profil()
	{
		
		$data = $this->user_model->get_mine();

		$param = array(
			'title' 		=> 'Profil Member',
			'active_menu' 	=> 'seting-profile',
			'data' 			=> $data,
		);

		if ($post = $this->input->post()) {
			$new_data = array();
			$errors = array();
			$form_valid = true;

			if( !isset($post['name']) || !$post['name'] ){
				$errors[] 	= "Nama harus diisi.";
				$form_valid = false;
			}else{
				$new_data['name'] = $post['name'];
			}

			if (isset($post['change_password'])) {
				if( !isset($post['last_password']) || !$post['last_password'] ){
					$errors[] 	= "Kata Sandi Lama harus diisi.";
					$form_valid = false;
				}else if( !isset($post['new_password']) || !$post['new_password'] ){
					$errors[] 	= "Kata Sandi baru harus diisi.";
					$form_valid = false;
				}else if( !isset($post['repeat_password']) || !$post['repeat_password'] ){
					$errors[] 	= "Ulangi katasandi harus diisi.";
					$form_valid = false;
				}else if( $post['new_password'] != $post['repeat_password'] ) {
					$errors[] = "Pengulangan kata sandi tidak sama.";
					$form_valid = false;
				}

				if (!$this->user_model->check_last_pass($post['last_password'])) {
					$errors[] = "Password lama salah.";
					$form_valid = false;
				}else{
					$new_data['password'] = $post['new_password'];
				}
			}

			if( !$form_valid ){
				$param['errors'] = $errors;
			} else {
				$this->user_model->update( $data->user_id, $new_data);
				$this->user_model->refresh_session();

				set_message_flash('Perubahan data telah berhasil disimpan. ', 'success');
				redirect('user/profil');
			}
		}

		$this->load->view('header', $param );
		$this->load->view('profile', $param );
		$this->load->view('footer', $param );
	}

}
