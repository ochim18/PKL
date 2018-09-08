<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

		protected_page(array('administrator', 'admin'));
		
		$this->load->model('jenis_model');
		$this->load->model('kategori_model');
	}
	
	public function index()
	{
		$params = array(
			'title' 		=> 'Data Kategori', 
			'active_menu' 	=> 'kategori',
			'data' 			=> $this->jenis_model->gets_view(),
			'data1' 		=> $this->kategori_model->gets_view(),
		);

		$this->load->view('header', $params);
		$this->load->view('kategori-list', $params);
		$this->load->view('footer', $params);
	}

	public function input()
	{
		$params = array(
			'title' 		=> 'Input Data Provinsi', 
			'active_menu' 	=> 'kategori', 
			'mode'			=> 'add'
		);

		if ($post = $this->input->post()) {
			$errors = array();
			$form_valid = true;

			if( !isset($post['kategori_nama']) || !$post['kategori_nama'] ){
				$errors[] 	= "Nama kategori harus diisi.";
				$form_valid = false;
			}elseif( $this->kategori_model->name_exists($post['kategori_name']) ) {
				$errors[] 	= "Nama kategori sudah ada.";
				$form_valid = false;
			}
			
			if( !$form_valid ){
				$params['errors'] = $errors;
				$params['post'] = $post;
			} else {
				$new_data['kategori_nama'] = $post['kategori_nama'];
				$insert_id = $this->kategori_model->create( $new_data );

				set_message_flash('Data kategori telah berhasil ditambah.', 'success');
				redirect('kategori');
			}
		}

		$this->load->view('header', $params);
		$this->load->view('kategori-input', $params);
		$this->load->view('footer', $params);
	}

	public function edit($id = false)
	{
		$data = $this->kategori_model->get($id);
		if( !$data ){
			set_message_flash('Data kategori tidak ditemukan.');
			redirect('kategori');
		}

		$params = array(
			'title' 		=> 'Edit Kategori', 
			'active_menu' 	=> 'kategori', 
			'mode'			=> 'edit',
			'post' 			=> (array)$data,
		);

		if ($post = $this->input->post()) {
			$errors = array();
			$form_valid = true;

			if( !isset($post['kategori_nama']) || !$post['kategori_nama'] ){
				$errors[] 	= "Nama kategori harus diisi.";
				$form_valid = false;
			}

			if( !$form_valid ){
				$params['errors'] = $errors;
				$params['post'] = array_merge($params['post'], $post);
			} else {
				$new_data['kategori_nama'] = $post['kategori_nama'];
				$insert_id = $this->kategori_model->update( $id, $new_data );

				set_message_flash('Data kategori telah berhasil diubah.', 'success');
				redirect('kategori');
			}
		}

		$this->load->view('header', $params);
		$this->load->view('kategori-input', $params);
		$this->load->view('footer', $params);
	}

	public function delete($id = false)
	{
		$data = $this->kategori_model->get($id);
		if( !$data ){
			set_message_flash('Data kategori tidak ditemukan.');
			redirect('kategori');
		}
		
		$this->kategori_model->delete( $id );

		if ($error = $this->kategori_model->get_error()) {
			if (isset($error['code']) && $error['code'] == 1451) {
				set_message_flash('Data tidak dapat dihapus karena terpaut dengan data jenis. Silahkan hapus data jenis dibawahnya terlebih dahulu.');
				redirect('kategori');
			}
		}
		
		set_message_flash('Data kategori telah berhasil dihapus.', 'success');
		redirect('kategori');
	}
}
