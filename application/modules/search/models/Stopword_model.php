<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stopword_model extends CRUD_Model {
	protected $table_name = "stopword";
	protected $table_view_name = 'stopword_view';
	protected $primary_key = "id_stopword";

	public function katastop_exists($kata)
	{
		return parent::check_isset('kata', $kata );
	}
}