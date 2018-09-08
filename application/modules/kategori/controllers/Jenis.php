<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

		protected_page(array('administrator'));

		$this->load->model('kategori_model');
		$this->load->model('jenis_model');
	}
	
	public function input($id = false)
	{
		$params = array(
			'title' 		=> 'Input Jenis', 
			'active_menu' 	=> 'kategori', 
			'mode'			=> 'add',
			'data_kategori'	=> $this->kategori_model->gets(),
			'id'			=> $id,
		);

		if ($post = $this->input->post()) {
			$errors = array();
			$form_valid = true;

			if( !isset($post['kategori_id']) || !$post['kategori_id'] || 
				!($this->kategori_model->get($post['kategori_id'])) ){
				$errors[] 	= "kategori tidak ditemukan.";
				$form_valid = false;
			}
			
			if( !isset($post['jenis_nama']) || !$post['jenis_nama'] ){
				$errors[] 	= "Nama jenis harus diisi.";
				$form_valid = false;
			}
			
			if( !$form_valid ){
				$params['errors'] = $errors;
				$params['post'] = $post;
			} else {
				$new_data['kategori_id'] = $post['kategori_id'];
				$new_data['jenis_nama'] = $post['jenis_nama'];
				$insert_id = $this->jenis_model->create( $new_data );

				set_message_flash('Data jenis telah berhasil ditambah.', 'success');
				redirect('kategori');
			}
		}

		$this->load->view('header', $params);
		$this->load->view('jenis-input', $params);
		$this->load->view('footer', $params);
	}

	public function edit($id = false)
	{
		$data = $this->jenis_model->get($id);
		if( !$data ){
			set_message_flash('Data jenis tidak ditemukan.');
			redirect('kategori');
		}

		$params = array(
			'title' 		=> 'Edit Jenis', 
			'active_menu' 	=> 'kategori', 
			'mode'			=> 'edit',
			'data_kategori'	=> $this->kategori_model->gets(),
			'post' 			=> (array)$data,
		);

		if ($post = $this->input->post()) {
			$errors = array();
			$form_valid = true;

			if( !isset($post['kategori_id']) || !$post['kategori_id'] || 
				!($this->kategori_model->get($post['kategori_id'])) ){
				$errors[] 	= "Kategori tidak ditemukan.";
				$form_valid = false;
			}
			
			if( !isset($post['jenis_nama']) || !$post['jenis_nama'] ){
				$errors[] 	= "Nama Jenis harus diisi.";
				$form_valid = false;
			}

			if( !$form_valid ){
				$params['errors'] = $errors;
				$params['post'] = array_merge($params['post'], $post);
			} else {
				$new_data['kategori_id'] = $post['kategori_id'];
				$new_data['jenis_nama'] = $post['jenis_nama'];
				$insert_id = $this->jenis_model->update( $id, $new_data );

				set_message_flash('Data jenis telah berhasil ditambah.', 'success');
				redirect('kategori');
			}
		}

		$this->load->view('header', $params);
		$this->load->view('jenis-input', $params);
		$this->load->view('footer', $params);
	}

	public function delete($id = false)
	{
		$data = $this->jenis_model->get($id);
		if( !$data ){
			set_message_flash('Data jenis tidak ditemukan.');
			redirect('kategori');
		}
		
		$this->jenis_model->delete( $id );
		
		set_message_flash('Data jenis telah berhasil dihapus.', 'success');
		redirect('kategori');
	}
}
