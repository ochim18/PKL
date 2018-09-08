<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CRUD_Model {
	protected $table_name = "users";
	protected $table_view_name = 'user_view';
	protected $primary_key = "user_id";

	public $editable_column = array(
		'username',
		'name',
		'password'
	);

	private $allowed_session = array('user_id', 'username', 'name', 'capability');

	public function login($username , $password)
	{
		$user_data = parent::get(array('where' => array('username' => $username)));

		if ($user_data != null) {
			if ($user_data->password == $this->generate_password($password)) {
				$user_data = (array)$user_data;
				$user_data['capability'] = get_app_config('access_roles')[$user_data['capability']];

				$sess_array = array_input_filter($user_data, $this->allowed_session);
				$this->session->set_userdata('current_user', $sess_array);

				parent::update( current_user_data('user_id'), array( 
					'login_count' => $user_data['login_count'] + 1,
					'last_login' => date('Y-m-d H:i:s') 
				) );

				return array(
					'value' => true,
					'message' => "Success Login, Selamat datang {$user_data['name']}."
				);
			}else{
				return array(
					'value' => false,
					'message' => "Password Salah."
				);
			}
		}else{
			return array(
				'value' => false,
				'message' => "Username Tidak Ditemukan."
			);
		}
	}

	public function username_exists($username)
	{
		return parent::check_isset( 'username', $username );
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