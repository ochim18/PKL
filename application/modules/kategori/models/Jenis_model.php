<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_model extends CRUD_Model {
	protected $table_name = "jenis";
	protected $table_view_name = 'jenis_view';
	protected $primary_key = "jenis_id";

	public function gets_jenis($kategori_id)
	{
		$args = array('where' => array('kategori_id' => $kategori_id));
		return parent::gets($args, true);
	}
}