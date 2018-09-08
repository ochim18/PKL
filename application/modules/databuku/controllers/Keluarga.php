<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keluarga extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");


		$this->load->model('keluarga_model');
	}
	
	public function view($id = false)
	{
		protected_page(array('admin', 'administrator'));
		
		$params = array(
			'title' 		=> "Data Pustaka", 
			'active_menu' 	=> "databuku",
			'data_buku'		=> $this->keluarga_model->gets_view(),
		);

		$this->load->view('header', $params);
		$this->load->view('databuku-view', $params);
		$this->load->view('footer', $params);
	}

	public function input()
	{
		protected_page(array('admin'));
		
		$this->load->model('kategori/kategori_model');
		
		if(current_user_data('capability') == "administrator"){
			$active_menu = "databuku";
		}else{
			$active_menu = "datapustaka";
		}
		$params = array(
			'title' 		=> "Input Buku", 
			'active_menu' 	=> $active_menu,
			'mode' 			=> "add",
			'data_kategori'	=> $this->kategori_model->gets_view(),
		);

		if ($post = $this->input->post()) {
			$errors = array();
			$form_valid = true;

			
			if ($post = $this->input->post()) {
				$required_post = array(
					'no_issn'		=> "NO ISSN",
					'judul'			=> "Judul",
					'pengarang'		=> "Pengarang",
					'jenis'			=> "Jenis",
					'tahun_terbit'	=> "Tahun Terbit",
					'abstract'		=> "Abstract",
					'keyword'		=> "Keyword",
					'penerbit'		=> "Penerbit"
				);

				$errors = array();
				$form_valid = true;

				foreach (array_keys($required_post) as $key) {
					if (!in_array($key, array_keys($post)) || $post[$key] == "") {
						$errors[] 	= $required_post[$key] . " harus diisi.";
						$form_valid = false;
					}
				}

				if(!is_numeric($post['no_issn'])){
					$errors[] 	= " no issn harus angka.";
					$form_valid = false;
				}

				if($post['jenis'] == 0){
					$errors[] 	= "jenis atau kategori harus diisi";
					$form_valid = false;
				}

				if($_FILES['data']['size'] == 0){
					$errors[] 	= " File harus diisi.";
					$form_valid = false;
				}

				if( !$form_valid ){
					$params['errors'] = $errors;
					unset($post['kategori_id']);
					$params['post'] = $post;
				} else {
					$allowed_add = $this->keluarga_model->editable_column;
					$data = array_input_filter($post, $allowed_add);
					if (isset($_FILES['data']) && $_FILES['data']['error']== 0) {
						$this->load->library('upload');
						
						$config['file_name']   = 'file';
						$config['upload_path']   = './uploads/';
						$config['allowed_types'] = 'pdf';

						$this->upload->initialize($config);

						if ( $this->upload->do_upload('data')){
							$file_data = $this->upload->data();
							$data['data'] = $file_data['file_name'];
						}
					}
					$data['user_id'] = current_user_data('user_id');
					$insert_id = $this->keluarga_model->create( $data );

					set_message_flash('Data Buku telah berhasil ditambah.', 'success');
					redirect('databuku');
				}
			}
		}

		$this->load->view('header', $params);
		$this->load->view('databuku-input', $params);
		$this->load->view('footer', $params);
	}

	public function edit($id = false)
	{
		protected_page(array('admin'));

		$this->load->model('kategori/kategori_model');
		$this->load->model('keluarga_model');
		$data = $this->keluarga_model->get_view($id);
		if( !$data ){
			set_message_flash('Data keluarga tidak ditemukan.');
			redirect('databuku');
		}


		if(current_user_data('capability') == "administrator"){
			$active_menu = "databuku";
		}else{
			$active_menu = "datapustaka";
		}

		$params = array(
			'title' 		=> "Edit Buku", 
			'active_menu' 	=> $active_menu,
			'mode' 			=> "edit",
			'data_kategori'	=> $this->kategori_model->gets_view(),
			'post' 			=> (array)$data,
		);

		if ($post = $this->input->post()) {
			$errors = array();
			$form_valid = true;

			
			if ($post = $this->input->post()) {
				$required_post = array(
					'no_issn'		=> "NO ISSN",
					'judul'			=> "Judul",
					'pengarang'		=> "Pengarang",
					'jenis'			=> "Jenis",
					'tahun_terbit'	=> "Tahun Terbit",
					'abstract'		=> "Abstract",
					'keyword'		=> "Keyword",
					'penerbit'		=> "Penerbit"
				);

				$errors = array();
				$form_valid = true;

				foreach (array_keys($required_post) as $key) {
					if (!in_array($key, array_keys($post)) || $post[$key] == "") {
						$errors[] 	= $required_post[$key] . " harus diisi.";
						$form_valid = false;
					}
				}

				if(!is_numeric($post['no_issn'])){
					$errors[] 	= " no issn harus angka.";
					$form_valid = false;
				}

				if($post['jenis'] == 0){
					$errors[] 	= "jenis atau kategori harus diisi";
					$form_valid = false;
				}


				if( !$form_valid ){
					$params['errors'] = $errors;
					unset($post['kategori_id']);
					$params['post'] = $post;
				} else {
					$allowed_add = $this->keluarga_model->editable_column;
					$new_data = array_input_filter($post, $allowed_add);
					if (isset($_FILES['data']) && $_FILES['data']['error']== 0) {
						$this->load->library('upload');
						
						$config['file_name']   = 'file';
						$config['upload_path']   = './uploads/';
						$config['allowed_types'] = 'pdf';

						$this->upload->initialize($config);

						if ( $this->upload->do_upload('data')){
							$file_data = $this->upload->data();
							$new_data['data'] = $file_data['file_name'];
						}
					}
					$insert_id = $this->keluarga_model->update($data->buku_id, $new_data );

					set_message_flash('Data Buku telah berhasil diedit.', 'success');
					redirect('databuku');
				}
			}
		}
		$this->load->view('header', $params);
		$this->load->view('databuku-input', $params);
		$this->load->view('footer', $params);
	}

public function view_pustaka($id = false)
	{
		protected_page(array('admin','administrator'));

		$this->load->model('keluarga_model');
		$data = $this->keluarga_model->get_view($id);
		if( !$data ){
			set_message_flash('Data keluarga tidak ditemukan.');
			redirect('databuku');
		}

		$params = array(
			'title' 		=> "Edit Buku", 
			'active_menu' 	=> "databuku",
			'post' 			=> (array)$data,
		);

		$this->load->view('header', $params);
		$this->load->view('pustaka-view', $params);
		$this->load->view('footer', $params);
	}

	public function view_pustaka_saya($id = false)
	{
		protected_page(array('admin'));

		$this->load->model('keluarga_model');
		$data = $this->keluarga_model->get_view($id);
		if( !$data ){
			set_message_flash('Data keluarga tidak ditemukan.');
			redirect('datapustaka');
		}

		$params = array(
			'title' 		=> "Edit Buku", 
			'active_menu' 	=> "datapustaka",
			'post' 			=> (array)$data,
		);

		$this->load->view('header', $params);
		$this->load->view('pustaka-view', $params);
		$this->load->view('footer', $params);
	}

	public function delete($id = false)
	{
		protected_page(array('admin', 'administrator'));

		$data = $this->keluarga_model->gets_buku($id);

		if( !$data ){
			set_message_flash('Data buku tidak ditemukan.');
			redirect('databuku');
		}

		$this->keluarga_model->delete( $data[0]->buku_id );

		set_message_flash('Data buku telah berhasil dihapus.', 'success');
		redirect('databuku');
	}

	public function take_data(){

		$kategori_id = $this->input->post('kategori_id');
		$jenis_id = $this->input->post('jenis_id');
		$this->load->model('kategori/jenis_model');
		$jenis = $this->jenis_model->gets_jenis($kategori_id);
		if(count($jenis) > 0){
			
			$select_box = '<option>Pilih Jenis';
			foreach ($jenis as $row) {
				if($row->jenis_id == $jenis_id)
					$select_box .= '<option value="'.$row->jenis_id.'" selected="">'.$row->jenis_nama;
				else 
					$select_box .= '<option value='.$row->jenis_id.'>'.$row->jenis_nama;
			}
			echo json_encode($select_box);
		}
	}
}


