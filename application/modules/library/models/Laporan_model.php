<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CRUD_Model {
	protected $table_view_name = 'laporan_desa';

	public function view($view)
	{
		return parent::gets(array(), $view);
	}
}