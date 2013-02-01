<?php
/*
* Project:     EQdkp-Plus epgpimport
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2008
* Date:        $Date: 2012-11-11 16:50:58 +0100 (So, 11. Nov 2012) $
* -----------------------------------------------------------------------
* @author      $Author: wallenium $
* @copyright   2008-2009 hoofy_leon
* @link        http://eqdkp-plus.com
* @package     epgpimport
* @version     $Rev: 12431 $
*
* $Id: epgpimport_plugin_class.php 12431 2012-11-11 15:50:58Z wallenium $
*/

if ( !defined('EQDKP_INC') ) {
	die('You cannot access this file directly.');
}

class epgpimport extends plugin_generic {
	public static function __shortcuts() {
		$shortcuts = array('core', 'user', 'db', 'pdh', 'config');
		return array_merge(parent::$shortcuts, $shortcuts);
	}

	public $vstatus = 'Alpha';
	public $version = '0.1.1';
	
	public function __construct() {
		parent::__construct();

		$this->add_dependency(array(
			'plus_version' => '1.0',
			'games'	=> array('wow')
		));

		$this->add_data(array(
			'name'				=> 'EPGP-Import',
			'code'				=> 'epgpimport',
			'path'				=> 'epgpimport',
			'contact'			=> 'http://eqdkp-plus.eu',
			'template_path' 	=> 'plugins/epgpimport/templates/',
			'version'			=> $this->version,
			'author'			=> 'GodMod',
			'description'		=> $this->user->lang('epgpimport_short_desc'),
			'long_description'	=> $this->user->lang('epgpimport_long_desc'),
			'homepage'			=> EQDKP_PROJECT_URL,
			'manuallink'		=> 'http://eqdkp-plus.eu/wiki/',
			'icon'				=> $this->root_path.'plugins/epgpimport/images/epgp.png',
			)
		);

		//permissions
		//$this->add_permission('a', 'config', 'N', $this->user->lang('configuration'), array(2,3));
		$this->add_permission('a', 'import', 'N', $this->user->lang('epgpimport_import'), array(2,3));
		
		//pdh-modules
		/*
		$this->add_pdh_read_module('rli_zone');
		$this->add_pdh_read_module('rli_boss');
		$this->add_pdh_read_module('rli_item');
		$this->add_pdh_write_module('rli_zone');
		$this->add_pdh_write_module('rli_boss');
		$this->add_pdh_write_module('rli_item');
		*/

		//menu
		$this->add_menu('admin_menu', $this->gen_admin_menu());
	}
	
	
	
	public function gen_admin_menu() {
		return array(array(
			'icon' => './../../plugins/epgpimport/images/epgp.png',
			'name' => $this->user->lang('epgpimport'),
			/*
			1 => array(
				'link' => 'plugins/' . $this->code . '/admin/settings.php'.$this->SID,
				'text' => $this->user->lang('settings'),
				'check' => 'a_epgpimport_config',
				'icon' => 'manage_settings.png'),

			2 => array(
				'link' => 'plugins/' . $this->code . '/admin/bz.php'.$this->SID,
				'text' => $this->user->lang('epgpimport_bz'),
				'check' => 'a_epgpimport_bz',
				'icon' => './../../plugins/epgpimport/images/report_edit.png'),
			*/
			3 => array(
				'link' => 'plugins/' . $this->code . '/admin/import.php'.$this->SID,
				'text' => $this->user->lang('epgpimport_import'),
				'check' => 'a_epgpimport_import',
				'icon' => './../../plugins/epgpimport/images/report_add.png')
		));
	}

}
if(version_compare(PHP_VERSION, '5.3.0', '<')) registry::add_const('short_epgpimport', epgpimport::__shortcuts());
?>