<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2002
 * -----------------------------------------------------------------------
 * @copyright   2006-2011 EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * 
 */
 
if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: French	
//Created by EQdkp Plus Translation Tool on  2013-01-27 08:28
//File: plugins/epgpimport/language/french/lang_main.php
//Source-Language: english

$lang = array( 
	"epgpimport" => 'Importer EPGP',
	"epgpimport_import" => 'Importer le log EPGP',
	"epgpimport_short_desc" => 'Importateur EPGP',
	"epgpimport_long_desc" => 'Importer votre log EPGP depuis l\'addon en jeu.',
	"epgpimport_error_nolog" => 'Aucun log EPGP fourni',
	"epgpimport_error_wrongformat" => 'Le log EPGP ne peut être importé. Il est peut-être endommagé, dans un mauvais format ou un log plus récent a déjà été importé.',
	"epgpimport_layoutwarning" => 'You don\'t use an EPGP-Layout. This can cause uncorrectly imports. Please go to the <a href="'.registry::get_const('root_path').'admin/manage_pagelayouts.php'.registry::get_const('SID').'">Pagelayout-Management</a> and select an EPGP-Layout.',
	"epgpimport_success" => 'Log EPGP importé avec succès',
	
);

?>