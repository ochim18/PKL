<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	protected $wilayah = array( 
		'bulan' 	=> 'Bulan',
		'kategori' 	=> 'Kategori',
	);
	

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

		protected_page(array('administrator', 'admin'));

		$this->load->model('laporan_model');
	}
	
	public function index()
	{
		$this->view();
	}

	private function get_data($wilayah, $tahun)
	{
		switch ($wilayah) {
			case 'bulan':
				if($tahun != "All"){
					$array = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
					$data1 = $this->laporan_model->view('laporan_bulan', $tahun);

					$check = array();
					foreach ($data1 as $key => $value1) {
						foreach ($array as $key => $value) {
							if ($value1->nama == $value) {
								$check[$value] = $value1;
							}
						}
					}
					foreach ($array as $key => $value) {
						if(!isset($check[$value])){
							$data[] = (object)array("nama" => "$value", "jumlah" => "0", "tahun" => "$tahun");
						}else{
							$data[] = (object)$check[$value];
						}
					}
				}else{
					$array = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
					$data1 = $this->laporan_model->gets_view();

					$check = array();
					foreach ($data1 as $key => $value1) {
						foreach ($array as $key => $value) {
							if ($value1->nama == $value) {
								$check[$value] = $value1;
							}
						}
					}
					foreach ($array as $key => $value) {
						if(!isset($check[$value])){
							$data[] = (object)array("nama" => "$value", "jumlah" => "0", "tahun" => "$tahun");
						}else{
							$data[] = (object)$check[$value];
						}
					}
				}
				break;
			case 'kategori':
				if($tahun != "All"){
					$variable = $this->laporan_model->view1('kategori_view');
					$array = array();
					foreach ($variable as $key => $value) {
						array_push($array, $value->kategori_nama);
					}
					$data1 = $this->laporan_model->view('laporan_kategori_tahun', $tahun);

					$check = array();
					foreach ($data1 as $key => $value1) {
						foreach ($array as $key => $value) {
							if ($value1->nama == $value) {
								$check[$value] = $value1;
							}
						}
					}
					foreach ($array as $key => $value) {
						if(!isset($check[$value])){
							$data[] = (object)array("nama" => "$value", "jumlah" => "0", "tahun" => "$tahun");
						}else{
							$data[] = (object)$check[$value];
						}
					}
				}else{
					$variable = $this->laporan_model->view1('kategori_view');
					$array = array();
					foreach ($variable as $key => $value) {
						array_push($array, $value->kategori_nama);
					}
					$data1 = $this->laporan_model->view1('laporan_kategori');

					$check = array();
					foreach ($data1 as $key => $value1) {
						foreach ($array as $key => $value) {
							if ($value1->nama == $value) {
								$check[$value] = $value1;
							}
						}
					}
					foreach ($array as $key => $value) {
						if(!isset($check[$value])){
							$data[] = (object)array("nama" => "$value", "jumlah" => "0");
						}else{
							$data[] = (object)$check[$value];
						}
					}
				}
				break;
			default:
				$data = $this->laporan_model->gets_view();
				break;
		}
		return $data;
	}


	public function view($wilayah = 'bulan', $tahun = '2018')
	{
		if( !in_array($wilayah, array_keys($this->wilayah))){
			set_message_flash('Data filter wilayah tidak ditemukan.');
			redirect('laporan');
		}

		if($tahun == false){
			$tahun = date('Y');
		}

		$params = array(
			'title' 		=> "Laporan Data Buku", 
			'active_menu' 	=> "laporan", 
			'wilayah' 		=> $this->wilayah, 
			'active_wilayah' => $wilayah,
			'data' 			=> $this->get_data($wilayah, $tahun),
			'tahun'			=> $tahun,
			'data_kolom' 	=> array('jumlah' 	=> "Jumlah Buku"), 
		);

		$this->load->view('header', $params);
		$this->load->view('laporan', $params);
		$this->load->view('footer', $params);
	}

    public function export($wilayah = 'desa', $filter = "keluarga")
	{
		if( !in_array($wilayah, array_keys($this->wilayah))){
			set_message_flash('Data filter wilayah tidak ditemukan.');
			redirect('laporan');
		}


		if( !in_array($filter, array_keys($this->filter))){
			set_message_flash('Data filter tidak ditemukan.');
			redirect('laporan');
		}

        $param = array(
            'page_title'    => 'Export Data',
			'data' 			=> $this->get_data($wilayah), 
			'data_kolom' 	=> $this->get_kolom($filter), 
        );

        $this->load->view('laporan-export', $param );
    }
}
