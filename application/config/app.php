<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['app_name'] = 'Pedataan App';

$config['access_roles'] = array(
	false,
	'admin',
	'administrator',
);

$config['main_menu_admin'] = array(
	array(
		'id'            => 'dasbor',
		'capability'    => array('admin'),
		'label'         => 'Dasbor',
		'icon'         => 'fa-dashboard',
		'url'           => 'dasbor',
	),
	'diveder',
	array(
		'id'            => 'databuku' ,
		'capability'    => array('admin'),
		'label'         => 'Data Pustaka',
		'icon'          => 'fa-list',
		'url'           => 'databuku',
	),array(
		'id'            => 'datapustaka' ,
		'capability'    => array('admin'),
		'label'         => 'Pustaka Saya',
		'icon'          => 'fa-book',
		'url'           => 'databuku/pustaka',
	),
	array(
		'id'            => 'laporan',
		'capability'    => array('admin'),
		'label'         => 'Laporan',
		'icon'         => 'fa-bar-chart',
		'url'           => 'laporan',
	),
	'diveder',
	array(
		'id'            => 'kategori',
		'capability'    => array('admin'),
		'label'         => 'Kategori',
		'icon'         => 'fa-file-text',
		'url'           => 'kategori',
	),
);

$config['main_menu_administrator'] = array(
	array(
		'id'            => 'dasbor',
		'capability'    => array('administrator'),
		'label'         => 'Dasbor',
		'icon'         => 'fa-dashboard',
		'url'           => 'dasbor',
	),
	'diveder',
	array(
		'id'            => 'databuku' ,
		'capability'    => array('administrator'),
		'label'         => 'Data Pustaka',
		'icon'          => 'fa-list',
		'url'           => 'databuku',
	),
	array(
		'id'            => 'laporan',
		'capability'    => array('administrator'),
		'label'         => 'Laporan',
		'icon'         => 'fa-bar-chart',
		'url'           => 'laporan',
	),
	'diveder',
	array(
		'id'            => 'kategori',
		'capability'    => array('administrator'),
		'label'         => 'Kategori',
		'icon'         => 'fa-file-text',
		'url'           => 'kategori',
	),
	array(
		'id'            => 'admin',
		'capability'    => array('administrator'),
		'label'         => 'Pustakawan',
		'icon'         => 'fa-male',
		'url'           => 'admin',
	),
	array(
		'id'            => 'administrator',
		'capability'    => array('administrator'),
		'label'         => 'Administrator',
		'icon'         => 'fa-user-secret',
		'url'           => 'admin/administrator',
	),
	array(
		'id'            => 'seting' ,
		'capability'    => array('administrator'),
		'label'         => 'Pengaturan',
		'icon'         => 'fa-cogs',
		'url'           => 'seting',
	),
);

$config['easter'] = array(
	'tahun' 			=> 'Tahun Terbit',
);
