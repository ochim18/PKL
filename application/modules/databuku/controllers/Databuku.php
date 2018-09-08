<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Databuku extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

		$this->load->model('keluarga_model');
	}
	
	public function index()
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

	public function pustaka()
	{
		protected_page(array('admin', 'administrator'));
		
		$params = array(
			'title' 		=> "Data Pustaka", 
			'active_menu' 	=> "datapustaka",
			'data_buku'		=> $this->keluarga_model->gets_mydoc(current_user_data('user_id')),
		);

		$this->load->view('header', $params);
		$this->load->view('databuku-view-pustaka', $params);
		$this->load->view('footer', $params);
	}
	public function phpinfo()
	{
		protected_page(array('administrator'));

		$this->load->view('phpinfo');	
	}

	public function import()
	{
		protected_page(array('admin', 'administrator'));

		$params = array(
			'title' 		=> "Import data", 
			'active_menu' 	=> "databuku",
		);

		if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
			$errors = array();
			$form_valid = true;

			$this->load->library('excel');
			$this->load->model('check_model');
			$reference 		= $this->check_model->get_data();
			$family_number 	= (array)json_decode($reference->family_number);
			$code 			= (array)json_decode($reference->code);
			$easter 		= (array)json_decode($reference->easter);
			try {
				$new_data = array();
				$objPHPExcel = PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
				$objPHPExcel->setActiveSheetIndex(0);
				$data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true); 


				$alfabet = range('A', 'Z');
				$read_data = false;
				$new_data = array();

				foreach ($data as $index => $row) {
					if ($row['A'] == "No") {
						$read_data = true;
					}else if($read_data && $row[$alfabet[0]] === null){
						break;
					}else if($read_data){
						for ($i=0; $i < 15; $i++) {
							if (!isset($row[$alfabet[$i]]) || $row[$alfabet[$i]] === null) {
								$form_valid = false;
								$errors[] = "Format keluarga tidak sesuai.";
								break;
							}
						}
						if (!$form_valid) {
							break;
						}elseif (in_array( $row['I'], $family_number)) {
							$form_valid = false;
							$errors[] = "KK sudah terdaftar pada nomor " . $row['A'];
							break;
						}elseif (!in_array( $row['B'] . '.' . $row['C'] . '.' . $row['D'] . '.' . $row['E'] , $code)) {
							$form_valid = false;
							$errors[] = "Kode wilayah pada nomor " . $row['A'] . " tidak terdaftar pada sistem.";
							break;
						}elseif ($row['K'] > $row['J']) {
							$form_valid = false;
							$errors[] = "Jumlah PUP lebih kecil dari peserta KB pada no " . $row['A'];
							break;
						}elseif (!in_array( $row['R'], (array)$easter['participation_kb'])) {
							$form_valid = false;
							$errors[] = "Keterangan Kesertaan Ber-KB pada nomor " . $row['A'] . " tidak terdaftar pada sistem.";
							break;
						}else{
							$new_data[$row['I']]['no'] = $row['A'];
							$new_data[$row['I']]['data'] = array(
								'village_id'		=> array_search($row['B'] . '.' . $row['C'] . '.' . $row['D'] . '.' . $row['E'] , $code),
								'house_number'		=> $row['H'],
								'family_number'		=> $row['I'],
								'rw'				=> $row['F'],
								'rt'				=> $row['G'],
							);
							$new_data[$row['I']]['meta'] = array(
								'number_of_pus'					=> $row['J'],
								'number_of_kb_members'			=> $row['K'],
								'married_age_of_husband'		=> $row['L'],
								'married_wife_age'				=> $row['M'],
								'number_of_children_born_lk'	=> $row['N'],
								'number_of_children_born_pr'	=> $row['O'],
								'number_of_children_lk'			=> $row['P'],
								'number_of_children_pr'			=> $row['Q'],
								'participation_kb_id'			=> array_search($row['R'], (array)$easter['participation_kb']),
							);
							$new_data[$row['I']]['member'] = array();
							$new_data[$row['I']]['kk'] = 0;
						}
					}
				}

				$read_data = false;
				if ($form_valid) {
					$objPHPExcel->setActiveSheetIndex(1);
					$data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true); 

					foreach ($data as $index => $row) {
						if ($row['A'] == "No") {
							$read_data = true;
						}else if($read_data && $row[$alfabet[0]] === null){
							break;
						}else if($read_data){
							for ($i=0; $i < 11; $i++) {
								if (!isset($row[$alfabet[$i]]) || $row[$alfabet[$i]] === null) {
									$form_valid = false;
									$errors[] = "Format anggota keluarga tidak sesuai.";
									break;
								}
							}
							if (!$form_valid) {
								break;
							}elseif (!in_array( $row['B'], array_keys($new_data))) {
								$form_valid = false;
								$errors[] = "No KK tidak ditemukan pada nomor " . $row['A'];
								break;
							}elseif (!in_array( $row['F'], (array)$easter['sex'])) {
								$form_valid = false;
								$errors[] = "Keterangan jenis kelamin pada nomor " . $row['A'] . " tidak terdaftar pada sistem.";
								break;
							}elseif (!in_array( $row['G'], (array)$easter['religion'])) {
								$form_valid = false;
								$errors[] = "Keterangan Agama pada nomor " . $row['A'] . " tidak terdaftar pada sistem.";
								break;
							}elseif (!in_array( $row['H'], (array)$easter['relationship'])) {
								$form_valid = false;
								$errors[] = "Keterangan Hubungan KK pada nomor " . $row['A'] . " tidak terdaftar pada sistem.";
								break;
							}elseif (!in_array( $row['I'], (array)$easter['education'])) {
								$form_valid = false;
								$errors[] = "Keterangan pendidikan pada nomor " . $row['A'] . " tidak terdaftar pada sistem.";
								break;
							}elseif (!in_array( $row['J'], (array)$easter['work'])) {
								$form_valid = false;
								$errors[] = "Keterangan pekerjaan pada nomor " . $row['A'] . " tidak terdaftar pada sistem.";
								break;
							}elseif (!in_array( $row['K'], (array)$easter['status'])) {
								$form_valid = false;
								$errors[] = "Keterangan status perkawinan pada nomor " . $row['A'] . " tidak terdaftar pada sistem.";
								break;
							}elseif (!in_array( $row['L'], (array)$easter['jkn'])) {
								$form_valid = false;
								$errors[] = "Keterangan JKN pada nomor " . $row['A'] . " tidak terdaftar pada sistem.";
								break;
							}else{
								$new_data[$row['B']]['member'][] = array(
									'nik'				=> $row['C'],
									'name'				=> $row['D'],
									'dob'				=> date('Y-m-d', strtotime($row['E'])),
									'relationship_id'	=> array_search($row['H'], (array)$easter['relationship']),
									'sex_id'			=> array_search($row['F'], (array)$easter['sex']),
									'religion_id'		=> array_search($row['G'], (array)$easter['religion']),
									'education_id'		=> array_search($row['I'], (array)$easter['education']),
									'work_id'			=> array_search($row['J'], (array)$easter['work']),
									'status_id'			=> array_search($row['K'], (array)$easter['status']),
									'jkn_id'			=> array_search($row['L'], (array)$easter['jkn']),
								);
								if ($row['H'] == "Kepala Keluarga") {
									$new_data[$row['B']]['kk'] += 1;
								}
							}
						}
					}
				}
			} catch (Exception $e) {
				$errors = "Error import data.";
			}
			foreach ($new_data as $key => $value) {
				if ($value['kk'] < 1) {
					$form_valid = false;
					$errors[] = "Data keluarga tidak memiliki kepala keuarga pada nomor " . $value['no'];
					break;
				}elseif ($value['kk'] > 1) {
					$form_valid = false;
					$errors[] = "Data keluarga memiliki lebih dari satu kepala keuarga pada nomor " . $value['no'];
					break;
				}
			}

			if (!$form_valid) {
				$params['errors'] = $errors;
			}else{
				foreach ($new_data as $item) {
					$data_insert = $item['data'];
					$insert_id = $this->keluarga_model->create( $data_insert );

					$data_insert = $item['meta'];
					$data_insert['family_id'] 		= $insert_id;
					$this->family_meta_model->create( $data_insert );

					foreach ($item['member'] as $member) {
						$data_insert = $member;
						$data_insert['family_id'] = $insert_id;
						$this->family_member_model->create( $data_insert );
					}
				}
				set_message_flash("Success import data.", 'success');
				redirect('databuku');
			}
		}

		$this->load->view('header', $params);
		$this->load->view('databuku-import', $params);
		$this->load->view('footer', $params);
	}

    public function export()
    {
		protected_page(array('administrator'));

        $param = array(
            'page_title'    => 'Export Data',
            'data'    		=> $this->keluarga_model->gets_view(),
            'data_anggota'	=> $this->family_member_model->gets_view(),
        );

        $this->load->view('databuku-export', $param );
    }
}
