 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dasbor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

		protected_page(array('admin', 'administrator'));

		$this->load->model('laporan/laporan_model');
		$this->load->model('databuku/keluarga_model');
		$this->load->model('kategori/kategori_model');
	}
	
	public function index()
	{
		$params = array(
			'title' 			=> "Dasbor", 
			'active_menu' 		=> "dasbor",
			'jumlah_buku' 		=> $this->keluarga_model->gets_view(),
			'jumlah_jenis' 		=> $this->kategori_model->gets_view(),
			'data' 				=> $this->laporan_model->gets_view(),
			'data_kolom'		=> array(
				'jumlah' 	=> "Jumlah Buku",
			),
		);

		$this->load->view('header', $params);
		$this->load->view('dasbor', $params);
		$this->load->view('footer', $params);
	}
}
