<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Option_model extends CRUD_Model {
	protected $table_name = "options";
	protected $primary_key = "option_id";

	public $editable_column = array(
		'option_key',
		'option_value',
	);

	public function get_value($option_key)
	{
		$data = parent::get(array('where' => array('option_key' => $option_key)));
		return ($data) ? $data->option_value : "";
	}

	public function get_id($option_key, $option_value)
	{
		$data = parent::get(array(
			'where' => array(
				'option_key' 	=> $option_key,
				'option_value' => $option_value,
			)
		));
		return ($data) ? $data->option_id : false;
	}

	public function gets_data_id($option_id)
	{
		return parent::gets(array('where_in' => array('option_id' => $option_id)));
	}

	public function gets_value($option_key)
	{
		$data = parent::gets(array(
			'select' => array('option_id', 'option_value'),
			'where' => array('option_key' => $option_key),
		));
		return $data;
	}

	public function check_isset_key($option_key)
	{
		if (!parent::get(array('where' => array('option_key' => $option_key)))) {
			return false;
		}
		return true;
	}

	public function set_value($option_key, $option_value)
	{
		return parent::create(array('option_key' => $option_key, 'option_value' => $option_value));
	}

	public function change_value($option_key, $option_value)
	{
		return parent::update(array('option_key' => $option_key), array('option_value' => $option_value));
	}
}