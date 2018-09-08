<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Katadasar_model extends CRUD_Model {
	protected $table_name = "tb_katadasar";
	protected $table_view_name = 'katadasar_view';
	protected $primary_key = "id_katadasar";

	public $editable_column = array(
		'username',
		'name',
		'password'
	);
	
	public function gets_kata($kata)
	{
		$args = array(	'where' => array('katadasar' => $kata),
						'limit'	=> 1);
		return parent::gets($args, true);
	}

	public function katadasar_exists($kata)
	{
		return parent::check_isset( 'katadasar', $kata );
	}

	public function refresh_session()
	{
		$user_data = (array)parent::get(current_user_data('user_id'));
		$user_data['capability'] = get_app_config('access_roles')[$user_data['capability']];
		$sess_array = array_input_filter($user_data, $this->allowed_session);
		$this->session->set_userdata('current_user', $sess_array);
	}

	public function logout()
	{
		$this->session->unset_userdata('current_user');
	}

	public function create( $args )
	{
		$args['password'] = $this->generate_password($args['password']);
		$args['created_at'] = date('Y-m-d H:i:s');
		return parent::create($args);
	}

	public function update( $id, $args )
	{
		if (isset($args['password']))
			$args['password'] = $this->generate_password($args['password']);
		return parent::update( $id, $args );
	}

	private function generate_password($password){
		return substr(md5($password), -4) . sha1('a'.$password.'2') . substr(md5($password), 0, 6);
	}

	public function get_mine()
	{
		$args = array('where' => array('user_id' => current_user_data('user_id')));
		return parent::get($args);
	}

	public function check_last_pass($password)
	{
		$args = array(
			'where' => array(
				'user_id' 	=> current_user_data('user_id'),
				'password' 	=> $this->generate_password($password)
			)
		);
		return parent::get($args);
	}

	public function gets_capability($capability)
	{
		return parent::gets_view(array('where' => array('capability' => $capability)));
	}

	public function gets_administrator()
	{
		return $this->gets_capability(2);
	}

	public function gets_admin()
	{
		return $this->gets_capability(1);
	}

}