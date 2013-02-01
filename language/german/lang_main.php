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
		'epgpimport_import' => 'EPGP-Log importieren',
		'epgpimport_short_desc' => 'EPGP-Importer',
		'epgpimport_long_desc' => 'Importiere EPGP-Logs aus deinem Spiel',
		'epgpimport_error_nolog' => 'Es wurde kein EPGP-Log angegeben.',
		'epgpimport_error_wrongformat' => 'Der EPGP-Log konnte nicht importiert werden, da er fehlerhaft ist, ein falsches Format besitzt oder bereits ein neurer EPGP-Log importiert wurde.',
		'epgpimport_layoutwarning' => 'Du hast als Layout kein EPGP-Layout ausgewählt. Dadurch kann es zu fehlerhaften Importen kommen. Bitte gehe in die <a href="'.registry::get_const('root_path').'admin/manage_pagelayouts.php'.registry::get_const('SID').'">Layoutverwaltung</a> und wähle ein EPGP-Layout aus.',
		'epgpimport_success' => 'Der EPGP-Log wurde erfolgreich importiert.',
		'epgpimport_error_more_mdkp4event' => 'Der EPGP-Log konnte nicht importiert werden, da das ausgewählte Event mehr als einem MultiDKP-Pool zugeordnet ist.',
	);
	

?>