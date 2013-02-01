<?php
/*
* Project:     EQdkp-Plus Raidlogimport
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2008
* Date:        $Date: 2012-11-11 16:50:58 +0100 (So, 11. Nov 2012) $
* -----------------------------------------------------------------------
* @author      $Author: wallenium $
* @copyright   2008-2009 hoofy_leon
* @link        http://eqdkp-plus.com
* @package     raidlogimport
* @version     $Rev: 12431 $
*
* $Id: dkp.php 12431 2012-11-11 15:50:58Z wallenium $
*/

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
$eqdkp_root_path = './../../../';
include_once($eqdkp_root_path.'common.php');

class epgp_import extends page_generic {
	public static function __shortcuts() {
		$shortcuts = array('user', 'in', 'tpl', 'core', 'pm', 'config', 'jquery', 'html'
		);
		return array_merge(parent::$shortcuts, $shortcuts);
	}

	public function __construct() {
		$this->user->check_auth('a_epgpimport_import');
		
		$handler = array(
			'insert'	=> array('process' => 'insert_log')
		);
		parent::__construct(false, $handler);
		$this->process();
	}
	
	public function insert_log(){
		if ($this->in->get('log', '') != ''){
			include_once("../includes/epgp_parser.class.php");
			$parser = register('epgp_parser');
			
			//Check if event belongs to more than one MultiDKP-Pool
			$arrMDKPPools = $this->pdh->get('event', 'multidkppools', array($this->in->get('event', 0)));
			if (count($arrMDKPPools) > 1){
				$this->core->message($this->user->lang('epgpimport_error_more_mdkp4event'), $this->user->lang('error'), 'red');
				$this->display();
				return;
			}

			$mixedResult = $parser->parse($this->in->get('log', ''), $this->in->get('event', 0),  $this->in->get('itempool', 0));
			if (!$mixedResult){
				//error
				$this->core->message($this->user->lang('epgpimport_error_wrongformat'), $this->user->lang('error'), 'red');
			} else {
				//success
				$this->core->message($this->user->lang('epgpimport_success'), $this->user->lang('success'), 'green');
				
				$this->tpl->assign_vars(array(
					'RAID_ID'  => $mixedResult,
				));

				$this->core->set_vars(array(
					'page_title'        => $this->user->lang('epgpimport_import'),
					'template_path'     => $this->pm->get_data('epgpimport', 'template_path'),
					'template_file'     => 'admin/finished.html',
					'display'           => true,
					)
				);
				
			}
		} else {
			$this->core->message($this->user->lang('epgpimport_error_nolog'), $this->user->lang('error'), 'red');
		}
	}

	public function display($messages=array(), $blnImportFinished=false) {

		//fetch events
		$events = $this->pdh->aget('event', 'name', 0, array($this->pdh->get('event', 'id_list')));
		asort($events);

		//fetch itempools
		$itempools = $this->pdh->aget('itempool', 'name', 0, array($this->pdh->get('itempool', 'id_list')));
		asort($itempools);
		
		$this->tpl->assign_vars(array(
			'EVENTS'			=> $this->html->DropDown('event', $events, false),
			'ITEMPOOLS'			=> $this->html->DropDown('itempool', $itempools, false),
			'S_LAYOUT_WARNING'  => ($this->pdh->get_eqdkp_base_layout($this->config->get('eqdkp_layout')) != 'epgp') ? true : false,
		));

		$this->core->set_vars(array(
			'page_title'        => $this->user->lang('epgpimport_import'),
			'template_path'     => $this->pm->get_data('epgpimport', 'template_path'),
			'template_file'     => 'admin/insert.html',
			'display'           => true,
			)
		);
	}
}
if(version_compare(PHP_VERSION, '5.3.0', '<')) registry::add_const('short_epgp_import', epgp_import::__shortcuts());
registry::register('epgp_import');
?>