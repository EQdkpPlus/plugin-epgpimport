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
 * $Id: lang_main.php 12431 2012-11-11 15:50:58Z wallenium $
 */
	$lang = array(
		'epgpimport' => 'EPGP-Import',
		'epgpimport_import' => 'Import EPGP-Log',
		'epgpimport_short_desc' => 'EPGP-Importer',
		'epgpimport_long_desc' => 'Import your EPGP-Logs from the ingame-Addon',
		'epgpimport_error_nolog' => 'No EPGP-Log given',
		'epgpimport_error_wrongformat' => 'EPGP-Log could not be imported, maybe it is damaged, has a wrong format or a newer one has already been imported.',
		'epgpimport_layoutwarning' => 'You don\'t use an EPGP-Layout. This can cause uncorrectly imports. Please go to the <a href="'.registry::get_const('root_path').'admin/manage_pagelayouts.php'.registry::get_const('SID').'">Pagelayout-Management</a> and select an EPGP-Layout.',
		'epgpimport_success' => 'EPGP-Log successfully imported.',
		'epgpimport_error_more_mdkp4event' => 'EPGP-Log could not be imported, because the selected event belongs to more than one MultiDKP-Pool.',
	);
	

?>