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

	public $vstatus = 'Alpha';
	public $version = '0.2.0';
	
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
			'icon'				=> 'fa-fire',
			)
		);

		//permissions
		$this->add_permission('a', 'import', 'N', $this->user->lang('epgpimport_import'), array(2,3));

		//menu
		$this->add_menu('admin', $this->gen_admin_menu());
	}
	
	
	
	public function gen_admin_menu() {
		return array(array(
			'icon' => 'fa-fire',
			'name' => $this->user->lang('epgpimport'),
			1 => array(
				'link' => 'plugins/' . $this->code . '/admin/import.php'.$this->SID,
				'text' => $this->user->lang('epgpimport_import'),
				'check' => 'a_epgpimport_import',
				'icon' => 'fa-upload')
		));
	}

}
?>