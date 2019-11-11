<?php
/*	Project:	EQdkp-Plus
 *	Package:	EPGPimport Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( !defined('EQDKP_INC') ) {
	die('You cannot access this file directly.');
}

class epgpimport extends plugin_generic {

	public $vstatus = 'Stable';
	public $version = '0.3.1';
	
	protected static $apiLevel = 23;
	
	public function __construct() {
		parent::__construct();

		$this->add_dependency(array(
			'plus_version' => '2.3',
			'games'	=> array('wow', 'wowclassic')
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
