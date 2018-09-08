<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CRUD_Model {
	protected $table_name = "kategori";
	protected $table_view_name = 'kategori_view';
	protected $primary_key = "kategori_id";

	public function name_exists($name)
	{
		return parent::check_isset( 'kategori_nama', $name );
	}
}