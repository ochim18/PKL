<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Library extends CI_Controller {

	protected $wilayah = array(
		'desa' 		=> 'Kelurahan/Desa',
		'kecamatan' => 'Kecamatan',
	);
	
	protected $filter = array(
		'keluarga' 		=> 'Keluarga',
		'jiwa' 			=> 'Jiwa',
		'sex' 			=> 'Jenis Kelamin',
		'productive' 	=> 'Usia Produktif',
		'born' 			=> 'Kelahiran dan Kematian',
		'kb' 			=> 'Peserta KB',
		'participation_kb' => 'Kesertaan KB',
	);

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

		protected_page(array('administrator'));

		$this->load->model('laporan_model');
	}
	
	public function index()
	{
		$this->load->view('header', $params);
		$this->load->view('laporan', $params);
		$this->load->view('footer', $params);
	}

	private function get_data($wilayah)
	{
		switch ($wilayah) {
			case 'kecamatan':
				$data = $this->laporan_model->view('laporan_kecamatan');
				break;
			default:
				$data = $this->laporan_model->gets_view();
				break;
		}
		return $data;
	}

	private function get_kolom($filter)
	{
		switch ($filter) {
			case 'keluarga':
				$data = array(
					'jumlah_keluarga' 	=> "Jumlah Keluarga",
				);
				break;
			case 'jiwa':
				$data = array(
					'jumlah_jiwa' 	=> "Jumlah Jiwa",
				);
				break;
			case 'sex':
				$data = array(
					'jumlah_lk' 	=> "Laki-Laki",
					'jumlah_pr' 	=> "Perempuan"
				);
				break;
			case 'productive':
				$data = array(
					'jumlah_usia_dibawah_produktif' 	=> "Belum Produktif",
					'jumlah_usia_produktif' 			=> "Produktif",
					'jumlah_usia_diatas_produktif' 		=> "Lewat Produktif"
				);
				break;
			case 'born':
				$data = array(
					'jumlah_kelahiran' 	=> "Kelahiran",
					'jumlah_hidup' 		=> "Hidup",
					'jumlah_kematian' 	=> "Kematian"
				);
				break;
			case 'kb':
				$data = array(
					'jumlah_pus' 				=> "Jumlah PUS",
					'jumlah_peserta_kb' 		=> "Peserta KB",
					'jumlah_bukan_peserta_kb' 	=> "Bukan Peserta KB"
				);
				break;
			default:
				$data = array(
					'jumlah_kb_sedang' 			=> "Sedang",
					'jumlah_kb_pernah' 			=> "Pernah",
					'jumlah_kb_tidak_pernah' 	=> "Tidak Pernah"
				);
				break;
		}
		return $data;
	}

	public function view($wilayah = 'desa', $filter = "keluarga")
	{
		if( !in_array($wilayah, array_keys($this->wilayah))){
			set_message_flash('Data filter wilayah tidak ditemukan.');
			redirect('laporan');
		}

		if( !in_array($filter, array_keys($this->filter))){
			set_message_flash('Data filter tidak ditemukan.');
			redirect('laporan');
		}

		$params = array(
			'title' 		=> "Laporan Kependudukan", 
			'active_menu' 	=> "laporan", 
			'wilayah' 		=> $this->wilayah, 
			'filter' 		=> $this->filter,  
			'active_wilayah' => $wilayah, 
			'active_filter' => $filter, 
			'data' 			=> $this->get_data($wilayah), 
			'data_kolom' 	=> $this->get_kolom($filter), 
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
