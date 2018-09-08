<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

		protected_page(array('administrator'));
	}
	
	public function index()
	{
		$params = array(
			'title' 		=> 'Data Admin', 
			'active_menu' 	=> 'admin',
			'data' 			=> $this->user_model->gets_admin(),
		);

		$this->load->view('header', $params);
		$this->load->view('admin-list', $params);
		$this->load->view('footer', $params);
	}

	public function input()
	{
		$params = array(
			'title' 		=> 'Dashboard', 
			'active_menu' 	=> 'admin', 
			'mode'			=> 'add'
		);

		if ($post = $this->input->post()) {
			$errors = array();
			$form_valid = true;

			if( !isset($post['name']) || !$post['name'] ){
				$errors[] 	= "Nama lengkap harus diisi.";
				$form_valid = false;
			}
			if( !isset($post['username']) || !$post['username'] ){
				$errors[] 	= "Username harus diisi.";
				$form_valid = false;
			} elseif( $this->user_model->username_exists($post['username']) ) {
				$errors[] 	= "Username sudah ada yang menggunakan.";
				$form_valid = false;
			}

			if( !isset($post['password']) || !isset($post['repeat_password']) || !$post['password'] ) {
				$errors[] 	= "Kata sandi harus diisi.";
				$form_valid = false;
			} elseif( $post['password'] != $post['repeat_password'] ) {
				$errors[] 	= "Pengulangan password tidak sama.";
				$form_valid = false;
			}

			if( !$form_valid ){
				$params['errors'] = $errors;
				$params['post'] = $post;
			} else {
				$allowed_add = $this->user_model->editable_column;
				$data = array_input_filter($post, $allowed_add);
				$data['capability'] = 1; // admin
				$insert_id = $this->user_model->create( $data );

				set_message_flash('Data admin telah berhasil ditambah.', 'success');
				redirect('admin');
			}
		}

		$this->load->view('header', $params);
		$this->load->view('admin-input', $params);
		$this->load->view('footer', $params);
	}

	public function edit($id = false)
	{
		$data = $this->user_model->get($id);
		if( !$data || $data->capability != 1){
			set_message_flash('Data admin tidak ditemukan.');
			redirect('admin');
		}

		$params = array(
			'title' 		=> 'Dashboard', 
			'active_menu' 	=> 'admin', 
			'mode'			=> 'edit',
			'post' 			=> (array)$data,
		);

		if ($post = $this->input->post()) {
			$errors = array();
			$form_valid = true;

			if( !isset($post['name']) || !$post['name'] ){
				$errors[] 	= "Nama lengkap harus diisi.";
				$form_valid = false;
			}
			if( !isset($post['username']) || !$post['username'] ){
				$errors[] 	= "Username harus diisi.";
				$form_valid = false;
			} elseif( $data->username != $post['username'] && $this->user_model->username_exists($post['username']) ) {
				$errors[] 	= "Username sudah ada yang menggunakan.";
				$form_valid = false;
			}

			if (isset($post['change_password'])) {
				if( !isset($post['password']) || !isset($post['repeat_password']) || !$post['password'] ) {
					$errors[] 	= "Kata sandi harus diisi.";
					$form_valid = false;
				} elseif( $post['password'] != $post['repeat_password'] ) {
					$errors[] 	= "Pengulangan password tidak sama.";
					$form_valid = false;
				}
			}else{
				if (isset($post['password'])) {
					unset($post['password']);
				}
			}

			if( !$form_valid ){
				$params['errors'] = $errors;
				$params['post'] = array_merge($params['post'], $post);
			} else {
				$allowed_add = $this->user_model->editable_column;
				$data = array_input_filter($post, $allowed_add);
				$insert_id = $this->user_model->update( $id, $data );

				set_message_flash('Data admin telah berhasil ditambah.', 'success');
				redirect('admin');
			}
		}

		$this->load->view('header', $params);
		$this->load->view('admin-input', $params);
		$this->load->view('footer', $params);
	}

	public function delete($id = false)
	{
		$data = $this->user_model->get($id);
		
		if( !$data || $data->capability != 1){
			set_message_flash('Data admin tidak ditemukan.');
			redirect('admin');
		}

		$this->user_model->delete( $id );
		set_message_flash('Data admin telah berhasil dihapus.', 'success');
		redirect('admin');
	}
}
