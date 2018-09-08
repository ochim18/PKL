<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keluarga_model extends CRUD_Model {
	protected $table_name = "buku";
	protected $table_view_name = 'buku_view';
	protected $primary_key = "buku_id";

	public $editable_column = array(
		'no_issn',
		'judul',
		'pengarang',
		'jenis',
		'tahun_terbit',
		'abstract',
		'keyword',
		'data',
		'penerbit'
	);
	
	public function create( $args )
	{
		$args['created_at'] = date('Y-m-d H:i:s');
		return parent::create($args);
	}

	public function gets_buku($buku_id)
	{
		$args = array('where' => array('buku_id' => $buku_id));
		return parent::gets($args, true);
	}

	public function gets_in($buku_id)
	{
		$args = array('where_in' => array('buku_id' => $buku_id));
		return parent::gets($args, true);
	}

	public function gets_mydoc($username)
	{
		$args = array('where' => array('user_id' => $username));
		return parent::gets($args, true);
	}
}