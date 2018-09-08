<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CRUD_Model {
	protected $table_view_name = 'laporan_tahun';

	public function view($view, $tahun)
	{
		return parent::gets(array('where' => array('tahun' => $tahun)), $view);
	}

	public function view1($view)
	{
		return parent::gets(array(), $view);
	}
}